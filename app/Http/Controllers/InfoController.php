<?php

namespace App\Http\Controllers;

use App\Models\AddressBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\OrderHistories;
use App\Models\OrderItem;
use App\Models\Provinces;
use App\Models\RefundMoney;
use App\Models\Vouchers;
use App\Models\VouchersLog;
use App\Models\VouchersUsers;

class InfoController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'default_phone' => 'nullable|string|max:20',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        DB::table('users')->where('id', $user->id)->update($validatedData);

        return redirect()->back()->with('success', 'Thông tin đã được cập nhật thành công!');
    }

    public function account(Request $request)
    {
        $query = Order::where('user_id', Auth::id());

        // Lọc theo ngày nếu có
        if ($request->has('from') && $request->has('to')) {
            $from = $request->input('from');
            $to = $request->input('to');
            $query->whereDate('created_at', '>=', $from)
                ->whereDate('created_at', '<=', $to);
        }

        $orders = $query->latest()->get();

        $user = Auth::user();

        $addresses = AddressBook::where('user_id', $user->id)->with(['province', 'ward'])->limit(2)->get();
        $pendingOrders = $orders->where('status', 'pending')->values();
        $confirmedOrders = $orders->where('status', 'confirmed')->values();
        $shippingOrders = $orders->where('status', 'shipping')->values();
        $successOrders = $orders->where('status', 'success')->values();
        $failedOrders = $orders->where('status', 'failed')->values();
        $cancelledOrders = $orders->where('status', 'cancelled')->values();
        // dd($cancelledOrders);
        // dd($shippingOrders, $orders, $confirmedOrders,$successOrders,$pendingOrders,  $failedOrders,   $cancelledOrders);

        // Lấy voucher của user: chỉ lấy voucher chưa dùng và còn hạn
        $now = now();
        $userVouchers = $user->vouchers()->with('cate_vouchers')
            ->wherePivot('is_used', 'unused')
            ->where('vouchers_users.end_date', '>=', $now)
            ->get();
        $vouchers = $userVouchers->map(function ($voucher) {
            return [
                'code' => $voucher->code,
                'name' => $voucher->cate_vouchers->name ?? '---',
                'type' => $voucher->type_discount,
                'value' => $voucher->value,
                'end_date' => $voucher->end_date,
            ];
        });
        // dd($vouchers);
        return view('pages.shop.account', compact('orders', 'addresses', 'pendingOrders', 'confirmedOrders', 'shippingOrders', 'successOrders', 'cancelledOrders', 'vouchers'));
    }
    public function orderDetail($id)
    {
        $order = Order::with([
            'orderItems.productVariant.color',
            'orderItems.productVariant.size',
            'orderItems.productVariant.product'
        ])->leftJoin('provinces', 'provinces.province_code', 'orders.province_code')
            ->leftJoin('wards', 'wards.ward_code', 'orders.ward_code')
            ->select('orders.*', 'provinces.name as province_name', 'wards.name as ward_name', 'wards.name as ward_name')
            ->where('orders.id', $id)->first();
        //  dd($order);

        if (!$order) {
            abort(404, 'Order not found');
        }

        $shippingAddress = $order->addressBook; // đúng với quan hệ trong model
        $subtotal = $order->orderItems->sum(function ($item) {
            return $item->sale_price * $item->quantity;
        });
        $discount = $order->discount_amount ?? 0; // hoặc trường discount nếu có
        // dd($discount);
        $shipping = $order->shipping_fee ?? 0;
        $total = $subtotal - $discount + $shipping;

        $refund = RefundMoney::where('order_id', $id)
            ->where('user_id', Auth::user()->id)
            ->first();

        $provinces = Provinces::orderBy('name')->get();
        return view('pages.shop.partials.order-detail', compact(
            'order',
            'shippingAddress',
            'subtotal',
            'discount',
            'shipping',
            'total',
            'refund',

            'provinces'
        ));
    }
    public function cancel(Request $request, $id)
    {
        $request->validate([
            'cancel_reason' => 'required|string|max:255',
            // 'cancel_note' => 'nullable|string|max:500',
        ]);
        $order = Order::findOrFail($id);
        // Chỉ cho phép hủy nếu trạng thái phù hợp
        if ($order->status !== 'pending') {
            return back()->with('error', 'Chỉ có thể hủy đơn hàng ở trạng thái chờ xác nhận.');
        }
        $order->status = 'cancelled';
        if ($order->status == 'cancelled') {
            OrderItem::where('order_id', $id)->get()->each(function ($item) {
                $item->productVariant->increment('stock', $item->quantity);
            });
            $voucher = Vouchers::find($order->voucher_id);
            if ($order->voucher_id && $voucher->end_date < now()) {
                VouchersUsers::updateOrCreate(
                    [
                        'user_id' => $order->user_id,
                        'voucher_id' => $order->voucher_id,
                    ],
                    [
                        'is_used'    => 'unused',
                        'start_date' => now(),
                        'end_date'   => now()->addDays(7),
                    ]
                );
                VouchersLog::create([
                    'user_id' => $order->user_id,
                    'voucher_id' => $order->voucher_id,
                    'order_id' => $id,
                    'type' => 'refund_new',
                    'content' => 'Voucher đã được tạo lại do đơn hàng bị hủy',
                ]);
            } else if ($order->voucher_id) {
                VouchersUsers::where('user_id', $order->user_id)
                    ->where('voucher_id', $order->voucher_id)
                    ->update([
                        'is_used' => 'unused',
                    ]);
                VouchersLog::create([
                    'user_id' => $order->user_id,
                    'voucher_id' => $order->voucher_id,
                    'order_id' => $id,
                    'type' => 'refund_reuse',
                    'content' => 'Voucher đã được đánh dấu là chưa sử dụng do đơn hàng bị hủy',
                ]);
            }
        }


        $order->save();

        // Lưu lịch sử hủy đơn nếu cần
        OrderHistories::create([
            'users' => Auth::id(),
            'order_id' => $order->id,
            'from_status' => 'pending',
            'to_status' => 'cancelled',
            'note' => $request->cancel_reason,
            'content' => $request->cancel_note,
        ]);

        return redirect()->back()->with('success', 'Đã hủy đơn hàng thành công!');
    }

    public function filterOrders(Request $request)
    {
        $query = Order::where('user_id', Auth::id());
        if ($request->has('from') && $request->has('to')) {
            $query->whereDate('created_at', '>=', $request->from)
                ->whereDate('created_at', '<=', $request->to);
        }
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        $orders = $query->latest()->get();

        // Trả về HTML của partial order-list
        return view('pages.shop.partials.order-list', ['orders' => $orders])->render();
    }
    /**
     * Gửi ảnh và comment từ user, cập nhật user_confirm thành true
     */
    public function submitUserConfirmation(Request $request, $id)
    {
        // Kiểm tra đơn hàng tồn tại và thuộc về user hiện tại
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Kiểm tra trạng thái đơn hàng phải là 'success'
        if ($order->status !== 'success') {
            return redirect()->back()->with('error', 'Chỉ có thể xác nhận nhận hàng khi đơn hàng ở trạng thái thành công.');
        }

        // Kiểm tra xem đã xác nhận chưa
        if ($order->user_confirm) {
            return redirect()->back()->with('error', 'Đơn hàng này đã được xác nhận nhận hàng.');
        }

        // Validate request
        $request->validate([
            'user_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'user_comment' => 'nullable|string|max:500',
        ], [
            'user_image.required' => 'Vui lòng chọn ảnh xác nhận',
            'user_image.image' => 'File phải là hình ảnh',
            'user_image.mimes' => 'Chỉ chấp nhận định dạng: jpeg, png, jpg, gif, svg, webp',
            'user_image.max' => 'Kích thước ảnh không được vượt quá 2MB',
            'user_comment.max' => 'Ghi chú không được vượt quá 500 ký tự',
        ]);

        try {
            // Upload ảnh
            if ($request->hasFile('user_image')) {
                $file = $request->file('user_image');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/orders/'), $filename);

                // Cập nhật thông tin vào database
                $order->update([
                    'image_user' => 'uploads/orders/' . $filename,
                    'user_comment' => $request->user_comment,
                    'user_confirm' => true,
                ]);

                // Tạo lịch sử đơn hàng
                OrderHistories::create([
                    'users' => Auth::id(),
                    'order_id' => $order->id,
                    'from_status' => $order->status,
                    'to_status' => $order->status,
                    'note' => 'Khách hàng đã xác nhận nhận hàng',
                    'content' => $request->user_comment ?? '',
                ]);

                return redirect()->back()->with('success', 'Xác nhận nhận hàng thành công!');
            }

            return redirect()->back()->with('error', 'Có lỗi xảy ra khi upload ảnh');
        } catch (\Exception $e) {
            Log::error('Error submitting user confirmation: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        // if (!$request->ward_code ) {
        //     $request->ward_code = $request->ward_code_hidden;
        // }
        // dd($request->ward_code_hidden);
        
        $data_order = Order::find($id);
        if (!$data_order) {
            return abort(403, "Không tìm thấy đơn này");
        }
        if ($data_order->status == 'cancelled' || $data_order->status == 'success' || $data_order->status == 'shipping') {
            return redirect()->back()->with('error', 'Bạn không thể sửa địa chỉ của đơn hàng');
        }
        $request->merge([
    'ward_code' => $request->ward_code ?: $request->ward_code_hidden
]);

        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'province_code' => ['required', 'exists:provinces,province_code'],
            'ward_code' => ['required', 'exists:wards,ward_code'],
            'address' => 'required|string',
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'province_code.required' => 'Vui lòng chọn tỉnh/thành phố.',
            'province_code.exists' => 'Tỉnh/thành phố không hợp lệ.',
            'ward_code.required' => 'Vui lòng chọn xã/phường.',
            'ward_code.exists' => 'Xã/phường không hợp lệ.',
            'address.required' => 'Vui lòng nhập địa chỉ chi tiết.',
        ]);
        $data_order->update([
            'address_books_id' => null,
            'province_code' => $request->province_code,
            'ward_code' => $request->ward_code ?? $request->ward_code_hidden,
            'address' => $request->address,
            'phone' => $request->phone,
            'name' => $request->name,
        ]);
        OrderHistories::create([
            'from_status' => 'Địa chỉ cũ',
            'to_status' => 'Địa chỉ mới',
            'order_id' => $id,
            'note' => 'Khách hàng thay đổi địa chỉ giao hàng',
            'users' => Auth::user()->id,
        ]);
        return redirect()->back()->with('success', 'Sửa địa chỉ thành công');
    }
}
