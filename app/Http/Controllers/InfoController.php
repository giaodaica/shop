<?php

namespace App\Http\Controllers;

use App\Mail\OrderCancelledMail;
use App\Models\AddressBook;
use App\Models\FlashSaleItems;
use App\Models\Product_variants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\OrderHistories;
use App\Models\OrderItem;
use App\Models\Provinces;
use App\Models\RefundMoney;
use App\Models\Review;
use App\Models\Vouchers;
use App\Models\VouchersLog;
use App\Models\VouchersUsers;
use Mail;

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
        $deliveredOrders = $orders->where('status', 'delivered')->values();
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
        return view('pages.shop.account', compact('orders', 'addresses', 'pendingOrders', 'confirmedOrders', 'shippingOrders', 'successOrders', 'cancelledOrders', 'vouchers','deliveredOrders'));
    }
    public function orderDetail($id)
    {
        $order = Order::with([
            'orderItems.productVariant.color',
            'orderItems.productVariant.size',
            'orderItems.productVariant.product',

        ])->leftJoin('provinces', 'provinces.province_code', 'orders.province_code')
            ->leftJoin('wards', 'wards.ward_code', 'orders.ward_code')
            ->select('orders.*', 'provinces.name as province_name', 'wards.name as ward_name', 'wards.name as ward_name')
            ->where('orders.id', $id)->first();
        // $data_item = Review::where('order_id',$id)->where('product_variant_id',$order->productVariant->id)->first();
        // dd($data_item);
        if (!$order) {
            abort(404, 'Order not found');
        }
        // $data_reviews = Review::where('order_id',$id)->where('product_variant_id', $order->orderItems)->first();
        // dd($data_reviews);
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
        ->whereIn('status', ['pending', 'admin','approved']) // chỉ tính những request đang được xử lý
        ->first();
    
    // if ($existingRefund) {
    //     return response()->json(['error' => 'Bạn đã gửi yêu cầu hoàn tiền trước đó']);
    // }
    
    
    

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
              // Lấy toàn bộ item của đơn
        $items = OrderItem::where('order_id', $id)->get();

        // Hoàn lại stock cho sản phẩm thường
        $items->whereNull('flash_sale_items_id')->each(function ($item) {
            $variant = Product_variants::withTrashed()->find($item->product_variant_id);
            if ($variant) {
                $variant->increment('stock', $item->quantity);
                $variant->decrement('sold_quantity', $item->quantity);
            }
        });

        // Hoàn lại số lượng cho sản phẩm flash sale
        $items->whereNotNull('flash_sale_items_id')->each(function ($item) {
            $flashSaleItem = FlashSaleItems::where('product_variant_id', $item->product_variant_id)
                ->where('id', $item->flash_sale_items_id)
                ->first();

            if ($flashSaleItem) {
                $flashSaleItem->increment('max_quantity', $item->quantity);
                $flashSaleItem->decrement('sold_quantity', $item->quantity);
            }
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
                        'status' => 'available',
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
                        'status' => 'available',
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
        if ($order->status != 'confirmed') {
            $final_amount = $order->final_amount - $order->shipping_fee;
        } else {
            $final_amount = $order->final_amount;
        }
        if ($order->status_pay == 'paid' && $order->pay_method == 'VNPAY' || $order->pay_method == 'QR') {
            // dd($final_amount);

            RefundMoney::create([
                'user_id' => $order->user_id,
                'order_id' => $id,
                'amount' => $final_amount,
                'status' => 'admin',
            ]);
            $voucher = VouchersUsers::where('voucher_id', $order->voucher_id)->first();
            // dd($voucher);
            if (!$voucher) {
                $voucher = null;
            }

            $type = VouchersLog::where('voucher_id', $order->voucher_id)->first();
            Mail::to($order->user->email)->send(new OrderCancelledMail($order, $voucher, $type, $final_amount));
        }
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
            'user_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'user_comment' => 'nullable|string|max:500',
        ], [
            'user_image.image' => 'File phải là hình ảnh',
            'user_image.mimes' => 'Chỉ chấp nhận định dạng: jpeg, png, jpg, gif, svg, webp',
            'user_image.max' => 'Kích thước ảnh không được vượt quá 2MB',
            'user_comment.max' => 'Ghi chú không được vượt quá 500 ký tự',
        ]);
        

        try {
            // Upload ảnh
            $dataUpdate = [
                'user_comment' => $request->user_comment,
                'user_confirm' => true,
                'status' => 'delivered'
            ];
            
            if ($request->hasFile('user_image')) {
                $file = $request->file('user_image');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/orders/'), $filename);
                $dataUpdate['image_user'] = 'uploads/orders/' . $filename;
            }
            
            // Cập nhật thông tin order
            $order->update($dataUpdate);
            
            // Tạo lịch sử
            OrderHistories::create([
                'users' => Auth::id(),
                'order_id' => $order->id,
                'from_status' => $order->status,
                'to_status' => 'delivered',
                'note' => 'Khách hàng đã xác nhận nhận hàng',
                'content' => '',
            ]);
            
            return redirect()->back()->with('success', 'Xác nhận nhận hàng thành công!');
            
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
        if ($data_order->status == 'cancelled' || $data_order->status == 'success' || $data_order->status == 'confirmed') {
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
    // comment
    public function store(Request $request)
    {
        // dd($_POST);
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|min:3',
            'order_id' => 'required|exists:orders,id',
            'product_variant_id' => 'required|exists:product_variants,id',
            'color' => 'required|string|max:50',
            'size' => 'required|string|max:50',
        ], [
            'content.required' => 'Nội dung đánh giá không được để trống!',
            'content.min' => 'Nội dung đánh giá phải tối thiểu :min ký tự!',
            'color.required' => 'Vui lòng chọn màu sản phẩm!',
            'size.required' => 'Vui lòng chọn kích thước sản phẩm!',
        ]);

        // Check nếu đã đánh giá
        $data_item = Review::where('order_id', $request->order_id)
            ->where('product_variant_id', $request->product_variant_id)
            ->first();

        if ($data_item) {
            return back()->with('error', 'Bạn đã đánh giá, không thể đánh giá lại!');
        }

        Review::create([
            'product_id' => $request->product_id,
            'order_id' => $request->order_id,
            'product_variant_id' => $request->product_variant_id,
            'color' => $request->color ?? null,
            'size' => $request->size ?? null,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'content' => $request->content,
            'is_show' => 1, // chờ admin duyệt
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'alert' => 'alert-success',
                'message' => 'Đánh giá của bạn đã được gửi'
            ]);
        }

        return back()->with('success', 'Đánh giá của bạn đã được gửi');
    }
}
