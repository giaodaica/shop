<?php

namespace App\Http\Controllers;

use App\Mail\OrderCancelledMail;
use App\Models\Order;
use App\Models\OrderHistories;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\AddressBook;
use App\Models\RefundMoney;
use App\Models\User;
use App\Models\Vouchers;
use App\Models\VouchersLog;
use App\Models\VouchersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index()
    {
        // Kiểm tra nếu có callback từ VNPAY
        if (request()->has('vnp_ResponseCode')) {
            return $this->handleVnpayCallback(request());
        }

        // Kiểm tra đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::id();

        // Lấy các sản phẩm được chọn từ giỏ hàng
        $selectedIds = session('cart_selected_ids', []);

        // Nếu chưa có sản phẩm nào được chọn, tự động chọn tất cả
        if (empty($selectedIds)) {
            $allCartItems = Cart::where('user_id', $userId)->pluck('id')->toArray();
            if (!empty($allCartItems)) {
                session(['cart_selected_ids' => $allCartItems]);
                $selectedIds = $allCartItems;
            }
        }

        if (empty($selectedIds)) {
            return redirect()->route('home.cart')->with('error', 'Vui lòng chọn sản phẩm để thanh toán!');
        }

        // Lấy giỏ hàng chỉ các sản phẩm được chọn
        $cartItems = Cart::with(['productVariant.color', 'productVariant.size', 'productVariant.product'])
            ->where('user_id', $userId)
            ->whereIn('id', $selectedIds)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('home.cart')->with('error', 'Giỏ hàng trống!');
        }

        // Tính toán giá
        $subtotal = $cartItems->sum(fn($item) => $item->quantity * $item->price_at_time);
        $voucherDiscount = session('voucher_discount', 0);
        $shippingType = session('shipping_type', 'basic');
        $shippingFee = $this->calculateShippingFee($subtotal, $shippingType);
        $total = $subtotal - $voucherDiscount + $shippingFee;

        // Lấy địa chỉ giao hàng
        $addresses = AddressBook::where('user_id', $userId)->get();

        // Lấy voucher đã áp dụng
        $appliedVoucher = null;
        if (session('voucher_code')) {
            $appliedVoucher = Vouchers::where('code', session('voucher_code'))->first();
        }
        // dd($voucherDiscount);
        return view('pages.shop.checkout', compact(
            'cartItems',
            'subtotal',
            'voucherDiscount',
            'shippingFee',
            'shippingType',
            'total',
            'addresses',
            'appliedVoucher'
        ));
    }

    // Thêm method tính phí vận chuyển
    private function calculateShippingFee($subtotal, $shippingType = 'basic')
    {
        $baseShippingFee = 0;

        // Tính phí cơ bản
        if ($subtotal >= 200000) {
            $baseShippingFee = 0; // Free shipping cho đơn hàng >= 200k
        } else {
            $baseShippingFee = 20000; // 20k cho đơn hàng < 200k
        }

        // Thêm phí vận chuyển nhanh nếu chọn
        if ($shippingType === 'express') {
            $baseShippingFee += 30000; // Thêm 30k cho vận chuyển nhanh
        }

        return $baseShippingFee;
    }

    public function processCheckout(Request $request)
    {
        try {
            $request->validate([
                'address_id' => 'required|exists:address_books,id',
                'payment_method' => 'required|in:COD,VNPAY',
                'shipping_type' => 'required|in:basic,express',
                'notes' => 'nullable|string|max:500',
                'terms_condition' => 'required|accepted'
            ], [
                'address_id.required' => 'Vui lòng chọn địa chỉ giao hàng',
                'address_id.exists' => 'Địa chỉ không hợp lệ',
                'payment_method.required' => 'Vui lòng chọn phương thức thanh toán',
                'payment_method.in' => 'Phương thức thanh toán không hợp lệ',
                'shipping_type.required' => 'Vui lòng chọn loại vận chuyển',
                'shipping_type.in' => 'Loại vận chuyển không hợp lệ',
                'terms_condition.required' => 'Vui lòng đồng ý với điều khoản',
                'terms_condition.accepted' => 'Vui lòng đồng ý với điều khoản'
            ]);

            $userId = Auth::id();

            // Lấy các sản phẩm được chọn từ giỏ hàng
            $selectedIds = session('cart_selected_ids', []);

            // Nếu chưa có sản phẩm nào được chọn, tự động chọn tất cả
            if (empty($selectedIds)) {
                $allCartItems = Cart::where('user_id', $userId)->pluck('id')->toArray();
                if (!empty($allCartItems)) {
                    session(['cart_selected_ids' => $allCartItems]);
                    $selectedIds = $allCartItems;
                }
            }

            if (empty($selectedIds)) {
                return redirect()->route('home.cart')->with('error', 'Vui lòng chọn sản phẩm để thanh toán!');
            }

            // Lấy giỏ hàng chỉ các sản phẩm được chọn
            $cartItems = Cart::with(['productVariant.color', 'productVariant.size', 'productVariant.product'])
                ->where('user_id', $userId)
                ->whereIn('id', $selectedIds)
                ->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('home.cart')->with('error', 'Giỏ hàng trống!');
            }

            // Kiểm tra tồn kho
            $outOfStockItems = [];
            foreach ($cartItems as $item) {
                if ($item->quantity > $item->productVariant->stock) {
                    $outOfStockItems[] = [
                        'name' => $item->productVariant->product->name,
                        'requested' => $item->quantity,
                        'available' => $item->productVariant->stock
                    ];
                }
            }

            if (!empty($outOfStockItems)) {
                $errorMessage = 'Một số sản phẩm không đủ tồn kho:';
                foreach ($outOfStockItems as $item) {
                    $errorMessage .= "\n- {$item['name']}: Yêu cầu {$item['requested']}, có sẵn {$item['available']}";
                }
                return redirect()->back()->with('error', $errorMessage);
            }

            DB::beginTransaction();

            // Tính toán giá
            $subtotal = $cartItems->sum(fn($item) => $item->quantity * $item->price_at_time);
            $voucherDiscount = session('voucher_discount', 0);
            $shippingFee = $this->calculateShippingFee($subtotal, $request->shipping_type);
            $finalAmount = $subtotal - $voucherDiscount + $shippingFee;

            // Lấy địa chỉ
            $address = AddressBook::where('id', $request->address_id)
                ->where('user_id', $userId)
                ->firstOrFail();

            // Tạo mã đơn hàng
            $orderCode = 'ORD' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

            // Tạo đơn hàng
            $orderData = [
                'user_id' => $userId,
                'address_books_id' => $address->id,
                'voucher_id' => session('voucher_code') ? Vouchers::where('code', session('voucher_code'))->first()?->id : null,
                'name' => $address->name,
                'phone' => $address->phone,
                'address' => $address->address,
                'total_amount' => $subtotal,
                'final_amount' => $finalAmount,
                'discount_amount' => $voucherDiscount,
                'status' => 'pending',
                'code_order' => $orderCode,
                'pay_method' => $request->payment_method,
                'status_pay' => $request->payment_method === 'VNPAY' ? 'unpaid' : 'cod_paid',
                'notes' => $request->notes ?? null,
                'shipping_fee' => $shippingFee,
                'shipping_method' => $request->shipping_type
            ];

            $order = Order::create($orderData);
            // dd($order);
            // Tạo chi tiết đơn hàng
            foreach ($cartItems as $item) {
                $orderItemData = [
                    'order_id' => $order->id,
                    'product_variant_id' => $item->product_variants_id,
                    'flash_sale_items_id' => $item->flash_sale_items_id ?? null,
                    'product_id' => $item->productVariant->product_id,
                    'product_name' => $item->productVariant->product->name,
                    'product_image_url' => $item->productVariant->variant_image_url ?? $item->productVariant->product->image_url ?? '',
                    'import_price' => $item->productVariant->import_price,
                    'listed_price' => $item->productVariant->listed_price,
                    'sale_price' => $item->price_at_time,
                    'quantity' => $item->quantity,
                    'promotion_type' => $item->promotion_type ?? '0',
                    'color_name' => $item->productVariant->color->color_name ?? '',
                    'size_name' => $item->productVariant->size->size_name ?? ''
                ];

                OrderItem::create($orderItemData);

                // Cập nhật tồn kho
                $item->productVariant->decrement('stock', $item->quantity);
            }

            // Xóa các sản phẩm đã được chọn khỏi giỏ hàng
            Cart::whereIn('id', $selectedIds)->delete();

            // Cập nhật trạng thái voucher đã sử dụng
            if (session('voucher_code')) {
                $voucher = Vouchers::where('code', session('voucher_code'))->first();
                if ($voucher) {
                    DB::table('vouchers_users')
                        ->where('user_id', $userId)
                        ->where('voucher_id', $voucher->id)
                        ->update(['is_used' => 'used']);
                }
            }

            // Xóa session voucher và cart_selected_ids
            session()->forget(['voucher_code', 'voucher_discount', 'shipping_fee', 'cart_selected_ids']);

            // Lưu thông tin thanh toán vào session để hiển thị ở trang thành công
            session([
                'payment_method' => $request->payment_method,
                'shipping_type' => $request->shipping_type,
                'order_code' => $orderCode
            ]);

            DB::commit();

            // Xử lý thanh toán theo phương thức
            if ($request->payment_method === 'VNPAY') {
                // Tạo URL thanh toán VNPAY
                $paymentUrl = $this->createVnpayPaymentUrl($order, $finalAmount);

                // Redirect trực tiếp đến VNPAY
                return redirect($paymentUrl);
            } else {
                return redirect()->route('home.done')->with('success', 'Đặt hàng thành công! Mã đơn hàng: ' . $orderCode);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi đặt hàng: ' . $e->getMessage());
        }
    }

    public function done()
    {
        return view('pages.shop.success_checkout');
    }

    public function db_order(Request $request)
    {
        $action = ['pending', 'confirmed', 'shipping', 'success', 'cancelled'];
        $type = $request->query('type');
        $count = OrderHistories::where('from_status', 'failed')->count();

        if ($type && !in_array($type, $action)) {
            return abort(403, 'Không có hành động này');
        }
        if ($type) {
            $data_order = Order::where('status', $type)->orderBy('created_at', 'desc')->paginate(10);
        } else {
            $data_order = Order::orderBy('created_at', 'desc')->paginate(10);
        }
        // dd($data_order);
        return view('dashboard.pages.order.index', compact('data_order', 'count'));
    }
    public function db_order_change(Request $request, $id)
    {
        $before = $request->change;
        // dd($before);
        $request->validate(
            [
                'content' => 'nullable|string|max:255',
            ],
            [
                'content.max' => 'Nội dung không được quá 255 ký tự',
                'content.string' => 'Nội dung phải là chuỗi ký tự',
            ]
        );
        if (!$request->content) {
            $content = $request->content1;
        }
        $data_change = ['pending', 'confirmed', 'shipping', 'cancelled', 'failed', 'return'];
        if ($before && !in_array($before, $data_change)) {
            return  abort(403, "Hành động không hợp lệ");
        }
        if ($before == 'failed' || $before == 'cancelled') {
            $id = $request->order_id;
        }
        // dd($id);
        $old_status = Order::find($id);
        $present = Order::find($id);

        $count = OrderHistories::where('from_status', 'failed')->count();

        if (!$present || !$old_status) {
            return abort(403, 'Không thấy đơn hàng này vui lòng kiểm tra lại');
        }
        switch ($before) {
            case 'pending':
                if ($present->status != 'pending') {
                    return abort(403, "Bạn không thể đổi sang trạng thái đã xác nhận khi đơn hàng không ở trạng thái chưa xác nhận ");
                } else {
                    $present->status = 'confirmed';
                    $note = 'Đơn hàng đã được xác nhận';
                }
                break;
            case 'confirmed':
                if ($present->status != 'confirmed') {
                    return abort(403, 'Bạn không thể đổi sang trạng thái giao hàng khi đơn hàng không ở trạng thái đã xác nhận ');
                } else {
                    $present->status = 'shipping';
                    $note = 'Đơn vị vận chuyển đã lấy hàng, chuẩn bị giao hàng';
                }
                break;
            case 'shipping':
                if ($present->status != 'shipping') {
                    return abort(403, 'Bạn không thể đổi sang trạng thái đã giao hàng khi đơn hàng không ở trạng thái đang giao hàng ');
                } else {
                    $present->status = 'success';
                    $note = 'Đơn hàng đã được giao thành công';
                }
                break;
            case 'failed':
                if ($present->status != 'shipping') {
                    return abort(403, 'Bạn không thể đổi sang trạng thái giao hàng thất bại khi đơn hàng không ở trạng thái đang giao hàng ');
                } else {
                    $present->status = 'failed';
                    $note = 'Giao hàng thất bại';
                }
                break;
            case 'return':
                if ($present->status != 'failed') {
                    return abort(403, 'Bạn không thể đổi sang trạng thái giao lại khi đơn hàng không ở trạng thái giao hàng thất bại ');
                } else {
                    $present->status = 'shipping';
                    $note = 'Đơn vị vận chuyển đã lấy hàng , chuẩn bị giao hàng';
                }
                break;
            case 'cancelled':
                if ($present->status == 'failed' || $present->status == 'pending' || $present->status == 'confirmed' || $count == 2) {
                    $present->status = 'cancelled';
                    $note = 'Đơn hàng đã được hủy theo yêu cầu của khách hàng';
                } else {
                    return abort(403, 'Đơn chỉ được hủy khi ở trạng thái chưa xác nhận , đã xác nhận hoặc đơn giao thất bại');
                }
                break;
        }
        // dd($present);
        if ($present->status == 'cancelled') {
            OrderItem::where('order_id', $id)->get()->each(function ($item) {
                $item->productVariant->increment('stock', $item->quantity);
            });
            $voucher = Vouchers::find($present->voucher_id);
            if ($present->voucher_id && $voucher->end_date < now()) {
                VouchersUsers::updateOrCreate(
                    [
                        'user_id' => $present->user_id,
                        'voucher_id' => $present->voucher_id,
                    ],
                    [
                        'is_used'    => 'unused',
                        'start_date' => now(),
                        'end_date'   => now()->addDays(7),
                    ]
                );
                VouchersLog::create([
                    'user_id' => $present->user_id,
                    'voucher_id' => $present->voucher_id,
                    'order_id' => $id,
                    'type' => 'refund_new',
                    'content' => 'Voucher đã được tạo lại do đơn hàng bị hủy',
                ]);
            } else if ($present->voucher_id) {
                VouchersUsers::where('user_id', $present->user_id)
                    ->where('voucher_id', $present->voucher_id)
                    ->update([
                        'is_used' => 'unused',
                    ]);
                VouchersLog::create([
                    'user_id' => $present->user_id,
                    'voucher_id' => $present->voucher_id,
                    'order_id' => $id,
                    'type' => 'refund_reuse',
                    'content' => 'Voucher đã được đánh dấu là chưa sử dụng do đơn hàng bị hủy',
                ]);
            }
            if ($present->status_pay == 'paid' && $present->pay_method == 'VNPAY' || $present->pay_method == 'QR') {
                RefundMoney::create([
                    'user_id' => $present->user_id,
                    'order_id' => $id,
                    'amount' => $present->final_amount,
                    'status' => 'admin',
                ]);
                $voucher = VouchersUsers::find($present->voucher_id);
                if (!$voucher) {
                    $voucher = null;
                }
                $type = VouchersLog::where('voucher_id', $present->voucher_id)->first();
                Mail::to($present->user->email)->send(new OrderCancelledMail($present, $voucher, $type));
            }
            // dd($present);

        }

        $present->save();
        OrderHistories::create([
            'users' => Auth::user()->id,
            'order_id' => $id,
            'from_status' => $old_status->status,
            'to_status' => $present->status,
            'note' => $note,
            'content' => $request->content ?? '',
        ]);

        if ($count >= 2 && $present->status == 'failed') {
            $present->status = 'cancelled';
            $present->save();
            OrderHistories::create([
                'users' => Auth::user()->id,
                'order_id' => $id,
                'from_status' => 'failed',
                'to_status' => 'cancelled',
                'note' => 'Đơn hàng đã tự động hủy do giao thất bại 3 lần',
                'content' => "",
            ]);
        }


        return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }
    public function db_order_show($id)
    {
        $data_order = Order::leftJoin('vouchers', 'vouchers.id', 'orders.voucher_id')->leftJoin('address_books', 'address_books.id', 'orders.address_books_id')->join('users', 'users.id', 'orders.user_id')->select(
            'orders.*',
            'vouchers.code',
            'address_books.name as ad_name',
            'address_books.address as ad_address',
            'address_books.phone as ad_phone',
            'users.email',
        )->where('orders.id', $id)
            ->first();
        $data_order_items = OrderItem::join('orders', 'orders.id', 'order_items.order_id')->join('product_variants', 'product_variants.id', 'order_items.product_variant_id')->join('sizes', 'sizes.id', 'product_variants.size_id')->join('colors', 'colors.id', 'product_variants.color_id')->where('order_id', $id)->select(
            'order_items.*',
            'sizes.size_name',
            'colors.color_name',
        )->get();
        $histoty_order = OrderHistories::join('users', 'users.id', 'order_histories.users')->where('order_id', $id)->select(
            'order_histories.*',
            'users.name as user_name'
        )->orderBy('created_at', 'desc')->get();
        // dd($data_order);
        // dd($histoty_order);
        // $historyItems = OrderHistories::where('order_id', $id)->get()->keyBy('from_status');
        // dd($historyItems);
        // dd($data_order);
        // dd($data_order_items);

        return view('dashboard.pages.order.detail', compact('data_order', 'data_order_items', 'histoty_order'));
    }

    // Method để cập nhật loại vận chuyển trong checkout
    public function updateShippingType(Request $request)
    {
        // Kiểm tra đăng nhập
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'shipping_type' => 'required|in:basic,express'
        ]);

        session(['shipping_type' => $request->shipping_type]);

        // Tính toán lại phí vận chuyển và tổng tiền
        $userId = Auth::id();
        $selectedIds = session('cart_selected_ids', []);

        if (empty($selectedIds)) {
            return response()->json(['error' => 'Không có sản phẩm nào được chọn'], 400);
        }

        $cartItems = Cart::with(['productVariant.color', 'productVariant.size', 'productVariant.product'])
            ->where('user_id', $userId)
            ->whereIn('id', $selectedIds)
            ->get();

        $subtotal = $cartItems->sum(fn($item) => $item->quantity * $item->price_at_time);
        $voucherDiscount = session('voucher_discount', 0);
        $shippingFee = $this->calculateShippingFee($subtotal, $request->shipping_type);
        $total = $subtotal - $voucherDiscount + $shippingFee;

        return response()->json([
            'success' => true,
            'shipping_type' => $request->shipping_type,
            'shipping_fee' => number_format($shippingFee, 0, ',', '.') . ' đ',
            'total' => number_format($total, 0, ',', '.') . ' đ'
        ]);
    }

    /**
     * Xử lý callback từ VNPAY tại route /checkout
     */
    private function handleVnpayCallback(Request $request)
    {
        try {
            $request->validate([
                'vnp_ResponseCode' => 'required|string',
                'vnp_TransactionStatus' => 'required|string',
                'vnp_TxnRef' => 'required|string',
                'vnp_Amount' => 'required|numeric',
                'vnp_BankTranNo' => 'nullable|string',
                'vnp_TransactionNo' => 'required|string',
                'vnp_OrderInfo' => 'required|string',
                'vnp_PayDate' => 'required|string',
                'vnp_BankCode' => 'nullable|string',
                'vnp_CardType' => 'nullable|string',
                'vnp_SecureHash' => 'required|string'
            ], [
                'vnp_ResponseCode.required' => 'Mã phản hồi không được để trống',
                'vnp_TransactionStatus.required' => 'Trạng thái giao dịch không được để trống',
                'vnp_TxnRef.required' => 'Mã đơn hàng không được để trống',
                'vnp_Amount.required' => 'Số tiền không được để trống',
                'vnp_TransactionNo.required' => 'Mã giao dịch không được để trống',
                'vnp_OrderInfo.required' => 'Thông tin đơn hàng không được để trống',
                'vnp_PayDate.required' => 'Ngày thanh toán không được để trống',
                'vnp_SecureHash.required' => 'Chữ ký bảo mật không được để trống'

            ]);
            $responseCode = $request->get('vnp_ResponseCode');
            $transactionStatus = $request->get('vnp_TransactionStatus');
            $txnRef = $request->get('vnp_TxnRef');
            $amount = $request->get('vnp_Amount');
            $bankTranNo = $request->get('vnp_BankTranNo');
            $transactionNo = $request->get('vnp_TransactionNo');
            $orderInfo = $request->get('vnp_OrderInfo');
            $payDate = $request->get('vnp_PayDate');
            $bankCode = $request->get('vnp_BankCode');
            $cardType = $request->get('vnp_CardType');
            $secureHash = $request->get('vnp_SecureHash');

            // Tìm đơn hàng theo TxnRef
            $order = Order::where('code_order', $txnRef)->first();
            if (!$order) {
                return redirect()->route('home.done')->with('error', 'Không tìm thấy đơn hàng! Mã đơn hàng: ' . $txnRef);
            }

            // Kiểm tra chữ ký bảo mật
            $verification = $this->verifyVnpayPayment($request);
            if (!$verification['is_valid_signature']) {
                return redirect()->route('home.done')->with('error', 'Chữ ký không hợp lệ! Có thể có lỗi bảo mật.');
            }

            // Kiểm tra trạng thái giao dịch
            if ($responseCode === '00' && $transactionStatus === '00') {
                // Thanh toán thành công
                $order->update([
                    'status_pay' => 'paid',
                    'status' => 'confirmed',
                    'payment_date' => now()
                ]);

                // Tạo lịch sử đơn hàng
                OrderHistories::create([
                    'users' => $order->user_id,
                    'order_id' => $order->id,
                    'from_status' => 'pending',
                    'to_status' => 'confirmed',
                    'note' => 'Thanh toán VNPAY thành công - Mã GD: ' . $transactionNo . ' - Ngân hàng: ' . $bankCode
                ]);

                return redirect()->route('home.done')->with('success', 'Thanh toán thành công! Mã đơn hàng: ' . $txnRef);
            } else {
                // Thanh toán thất bại
                $order->update([
                    'status_pay' => 'failed',
                    'status' => 'cancelled'
                ]);

                // Tạo thông báo lỗi chi tiết
                $errorMsg = 'Thanh toán thất bại! Mã đơn hàng: ' . $txnRef;

                // Thêm thông tin lỗi cụ thể
                if ($responseCode) {
                    $errorMsg .= ' (Mã lỗi: ' . $responseCode . ')';

                    // Giải thích mã lỗi
                    switch ($responseCode) {
                        case '01':
                            $errorMsg .= ' - Giao dịch chưa hoàn tất';
                            break;
                        case '02':
                            $errorMsg .= ' - Giao dịch bị lỗi';
                            break;
                        case '04':
                            $errorMsg .= ' - Giao dịch đảo (Khách hàng đã bị trừ tiền tại Ngân hàng nhưng GD chưa thành công ở VNPAY)';
                            break;
                        case '05':
                            $errorMsg .= ' - VNPAY đang xử lý giao dịch này (GD hoàn tiền sang tiền mặt)';
                            break;
                        case '06':
                            $errorMsg .= ' - Giao dịch bị hủy';
                            break;
                        case '07':
                            $errorMsg .= ' - Giao dịch bị từ chối bởi VNPAY';
                            break;
                        case '09':
                            $errorMsg .= ' - Giao dịch không thành công do: Thẻ/Tài khoản bị khóa';
                            break;
                        case '13':
                            $errorMsg .= ' - Giao dịch không thành công do Quý khách nhập sai mật khẩu xác thực giao dịch (OTP)';
                            break;
                        case '65':
                            $errorMsg .= ' - Giao dịch không thành công do tài khoản của Quý khách không đủ số dư';
                            break;
                        case '75':
                            $errorMsg .= ' - Ngân hàng thanh toán đang bảo trì';
                            break;
                        case '79':
                            $errorMsg .= ' - Giao dịch không thành công do Quý khách nhập sai mật khẩu thanh toán quốc tế';
                            break;
                        case '99':
                            $errorMsg .= ' - Các lỗi khác (lỗi còn lại, không có trong danh sách mã lỗi đã liệt kê)';
                            break;
                        default:
                            $errorMsg .= ' - Lỗi không xác định';
                            break;
                    }
                }

                // Thêm thông tin ngân hàng nếu có
                if ($bankCode) {
                    $errorMsg .= ' - Ngân hàng: ' . $bankCode;
                }

                return redirect()->route('home.done')->with('error', $errorMsg);
            }
        } catch (\Exception $e) {
            return redirect()->route('home.done')->with('error', 'Có lỗi xảy ra khi xử lý thanh toán! Vui lòng liên hệ hỗ trợ.');
        }
    }

    /**
     * Tạo URL thanh toán VNPAY
     */
    private function createVnpayPaymentUrl($order, $amount)
    {
        $environment = Config::get('vnpay.environment', 'test');
        $config = Config::get("vnpay.{$environment}");

        $vnp_TmnCode = $config['tmn_code'];
        $vnp_HashSecret = $config['hash_secret'];
        $vnp_Url = $config['url'];

        // Sử dụng domain thực tế thay vì localhost
        $vnp_Returnurl = url('/checkout');

        // Nếu đang ở localhost, thử sử dụng IP thực tế
        if (strpos($vnp_Returnurl, 'localhost') !== false || strpos($vnp_Returnurl, '127.0.0.1') !== false) {
            // Thử lấy IP thực tế
            $serverIP = $_SERVER['SERVER_ADDR'] ?? $_SERVER['LOCAL_ADDR'] ?? '127.0.0.1';
            $serverPort = $_SERVER['SERVER_PORT'] ?? '80';
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';

            if ($serverIP !== '127.0.0.1' && $serverIP !== '::1') {
                $vnp_Returnurl = $protocol . '://' . $serverIP . ':' . $serverPort . '/checkout';
            }
        }

        $vnp_TxnRef = $order->code_order;
        $vnp_OrderInfo = 'Thanh toán đơn hàng ' . $order->code_order;
        $vnp_OrderType = 'other'; // Thay đổi về 'other' thay vì 'billpayment'
        $vnp_Amount = $amount * 100; // VNPAY yêu cầu amount * 100
        $vnp_Locale = 'vn';
        $vnp_IpAddr = request()->ip();
        $vnp_CreateDate = date('YmdHis');
        $vnp_ExpireDate = date('YmdHis', strtotime('+15 minutes')); // Thời gian hết hạn

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => $vnp_CreateDate,
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate" => $vnp_ExpireDate,
        );

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return $vnp_Url;
    }

    /**
     * Xác thực callback từ VNPAY
     */
    private function verifyVnpayPayment($request)
    {
        $environment = Config::get('vnpay.environment', 'test');
        $config = Config::get("vnpay.{$environment}");
        $vnp_HashSecret = $config['hash_secret'];

        $inputData = array();
        $data = $request->all();

        foreach ($data as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        $isValidSignature = ($vnp_SecureHash == $secureHash);
        $isSuccess = ($inputData['vnp_ResponseCode'] ?? '') == '00';

        return [
            'success' => $isValidSignature && $isSuccess,
            'data' => $inputData,
            'is_valid_signature' => $isValidSignature,
            'is_success' => $isSuccess,
            'calculated_hash' => $secureHash
        ];
    }

    /**
     * Truy vấn trạng thái giao dịch VNPAY
     */
    private function queryVnpayTransaction($orderCode)
    {
        $environment = Config::get('vnpay.environment', 'test');
        $config = Config::get("vnpay.{$environment}");

        $vnp_TmnCode = $config['tmn_code'];
        $vnp_HashSecret = $config['hash_secret'];
        $vnp_apiUrl = $config['api_url'];

        $vnp_RequestId = time() . "";
        $vnp_Version = Config::get('vnpay.version', '2.1.0');
        $vnp_Command = "querydr";
        $vnp_TxnRef = $orderCode;
        $vnp_OrderInfo = "Truy van GD:" . $orderCode;
        $vnp_TxnDate = date('YmdHis');

        $inputData = array(
            "vnp_RequestId" => $vnp_RequestId,
            "vnp_Version" => $vnp_Version,
            "vnp_Command" => $vnp_Command,
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_TxnDate" => $vnp_TxnDate,
        );

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_apiUrl . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $vnp_Url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        return [
            'response' => $response,
            'error' => $error,
            'url' => $vnp_Url
        ];
    }

    /**
     * Xử lý IPN (Instant Payment Notification) từ VNPAY
     */
    public function vnpayIpn(Request $request)
    {
        try {
            $responseCode = $request->get('vnp_ResponseCode');
            $transactionStatus = $request->get('vnp_TransactionStatus');
            $txnRef = $request->get('vnp_TxnRef');
            $transactionNo = $request->get('vnp_TransactionNo');

            // Tìm đơn hàng theo TxnRef
            $order = Order::where('code_order', $txnRef)->first();
            if (!$order) {
                return response()->json(['error' => 'Order not found'], 404);
            }

            // Kiểm tra trạng thái giao dịch
            if ($responseCode === '00' && $transactionStatus === '00') {
                $order->update([
                    'status_pay' => 'paid',
                    'status' => 'confirmed',
                    'payment_date' => now()
                ]);

                return response()->json(['success' => true]);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}
