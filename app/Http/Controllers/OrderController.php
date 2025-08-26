<?php

namespace App\Http\Controllers;

use App\Mail\OrderCancelledMail;
use App\Models\Order;
use App\Models\OrderHistories;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\AddressBook;
use App\Models\FlashSaleItems;
use App\Models\Provinces;
use App\Models\RefundMoney;
use App\Models\User;
use App\Models\Vouchers;
use App\Models\VouchersLog;
use App\Models\VouchersUsers;
use App\Models\Wards;
use App\Models\Product_variants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
// GHN removed

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


        if (empty($selectedIds)) {
            return redirect()->route('home.cart')->with('error', 'Vui lòng chọn sản phẩm để thanh toán!');
        }

        // Lấy giỏ hàng (kèm sản phẩm bị xóa mềm)
        $cartItems = Cart::with([
            'productVariant' => function ($query) {
                $query->withTrashed()
                    ->with(['product' => function ($q) {
                        $q->withTrashed();
                    }, 'color', 'size']);
            }
        ])
            ->where('user_id', $userId)
            ->whereIn('id', $selectedIds)
            ->get();


        if ($cartItems->isEmpty()) {
            return redirect()->route('home.cart')->with('error', 'Giỏ hàng trống!');
        }

        // Kiểm tra sản phẩm bị xóa mềm
        $deletedItems = $cartItems->filter(function ($item) {
            return optional($item->productVariant)->deleted_at
                || optional(optional($item->productVariant)->product)->deleted_at;
        });

        if ($deletedItems->isNotEmpty()) {
            return redirect()->route('home.cart')
                ->with('error', 'Có sản phẩm đã bị xóa hoặc ngừng kinh doanh, vui lòng bỏ chọn.');
        }


        // Tính toán giá
        $subtotal = $cartItems->sum(fn($item) => $item->quantity * $item->price_at_time);
        $voucherDiscount = session('voucher_discount', 0);
        $shippingType = session('shipping_type', 'basic');
        $addresses = AddressBook::where('user_id', $userId)->with(['province', 'ward'])->get();
        $defaultAddress = $addresses->first();
        $shippingFee = $this->calculateShippingFee($subtotal, $shippingType, $defaultAddress);
        $total = $subtotal - $voucherDiscount + $shippingFee;

        // Lấy địa chỉ giao hàng
        // $addresses đã lấy ở trên

        // Lấy voucher đã áp dụng
        $appliedVoucher = null;
        if (session('voucher_code')) {
            $appliedVoucher = Vouchers::where('code', session('voucher_code'))->first();
        }
        if ($appliedVoucher && $appliedVoucher->status == 'disabled') {
            return back()->with('error', 'Voucher đã bị vô hiệu hóa');
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

    // Tính phí vận chuyển nội bộ dựa trên tỉnh/thành (province) và quận/huyện (ward) của bạn
    private function calculateShippingFee($subtotal, $shippingType = 'basic', ?AddressBook $address = null)
    {
        try {
            if (!$address) {
                return 0;
            }

            $provinceName = mb_strtolower(trim(optional($address->province)->name ?? ''), 'UTF-8');
            $districtName = mb_strtolower(trim(optional($address->ward)->name ?? ''), 'UTF-8');
            // Nếu subtotal ≥ 200.000đ: cơ bản = 0đ; nhanh +20k.
            // Nếu Hà Nội:
            // Quận nội thành: cơ bản = 20k.
            // Huyện ngoại thành: cơ bản = 25k.
            // Tỉnh khác: cơ bản = 30k.
            // Nhanh: +30k nếu subtotal < 200k; +20k nếu subtotal ≥ 200k.

            $isHanoi = str_contains($provinceName, 'hà nội') || str_contains($provinceName, 'ha noi');
            $isUrbanDistrict = false;
            if ($isHanoi) {
                $urbanList = [
                    'ba đình',
                    'ngọc hà',
                    'giảng võ',
                    'hoàn kiếm',
                    'cửa nam',
                    'phú thượng',
                    'hồng hà',
                    'tây hồ',
                    'bồ đề',
                    'việt hưng',
                    'phúc lợi',
                    'long biên',
                    'nghĩa đô',
                    'cầu giấy',
                    'yên hòa',
                    'ô chợ dừa',
                    'láng',
                    'văn miếu - quốc tử giám',
                    'kim liên',
                    'đống đa',
                    'hai bà trưng',
                    'vĩnh tuy',
                    'bạch mai',
                    'vĩnh hưng',
                    'định công',
                    'tương mai',
                    'lĩnh nam',
                    'hoàng mai',
                    'hoàng liệt',
                    'yên sở',
                    'phương liệt',
                    'khương đình',
                    'thanh xuân',
                    'từ liêm',
                    'thượng cát',
                    'đông ngạc',
                    'xuân đỉnh',
                    'tây tựu',
                    'phú diễn',
                    'xuân phương',
                    'tây mỗ',
                    'đại mỗ',
                    'thanh liệt',
                    'kiến hưng',
                    'hà đông',
                    'yên nghĩa',
                    'phú lương',
                    'sơn tây',
                    'tùng thiện',
                    'dương nội',
                    'chương mỹ'
                ];
                foreach ($urbanList as $q) {
                    if (str_contains($districtName, $q)) {
                        $isUrbanDistrict = true;
                        break;
                    }
                }
            }

            $base = 0;
            if ($subtotal >= 200000) {
                $base = 0;
            } else {
                if ($isHanoi) {
                    $base = $isUrbanDistrict ? 20000 : 25000;
                } else {
                    $base = 30000;
                }
            }

            if ($shippingType === 'express') {
                // Chỉ cho phép ship nhanh nội thành Hà Nội
                if (!($isHanoi && $isUrbanDistrict)) {
                    // Nếu không đủ điều kiện, coi như chuyển về basic
                    return $base;
                }
                $base += ($subtotal >= 200000 ? 20000 : 30000);
            }

            return $base;
        } catch (\Throwable $e) {
            Log::warning('calculateShippingFee local error: ' . $e->getMessage());
            return 0;
        }
    }

    public function processCheckout(Request $request)
    {
        try {
            // 1) Validate input như hiện tại
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

            // 2) Xác định danh sách item được chọn
            $selectedIds = session('cart_selected_ids', []);
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

            // 3) Lấy giỏ hàng chỉ các sản phẩm được chọn (kèm đủ quan hệ để render lỗi đẹp)
            $cartItems = Cart::with([
                'productVariant.color',
                'productVariant.size',
                'productVariant.product'
            ])
                ->where('user_id', $userId)
                ->whereIn('id', $selectedIds)
                ->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('home.cart')->with('error', 'Giỏ hàng trống!');
            }

            // 4) Lấy địa chỉ (đúng user) để tính phí & lưu snapshot
            $address = AddressBook::where('id', $request->address_id)
                ->where('user_id', $userId)
                ->with(['province', 'ward'])
                ->firstOrFail();

            // 5) Bắt đầu transaction để CHỐT kho an toàn (chống 2 tab)
            DB::beginTransaction();

            // 5.1) KHÓA & TRỪ TỒN KHO NGAY LÚC ĐẶT HÀNG
            $subtotal = 0;
            $outOfStockItems = [];

            foreach ($cartItems as $item) {
                // Kiểm tra biến thể & sản phẩm còn tồn tại
                if (!$item->productVariant || !$item->productVariant->product) {
                    throw new \Exception('Có sản phẩm đã ngừng kinh doanh hoặc không còn tồn tại.');
                }

                if ($item->flash_sale_items_id) {
                    // Lock flash sale item + kèm flashSale để check end_date
                    $flashSaleItem = FlashSaleItems::with('flashSale')
                        ->where('id', $item->flash_sale_items_id)
                        ->lockForUpdate()
                        ->first();

                    if (!$flashSaleItem) {
                        throw new \Exception("Sản phẩm Flash Sale không tồn tại.");
                    }

                    // Kiểm tra chương trình còn hiệu lực
                    $fs = $flashSaleItem->flashSale;
                    if (!$fs || !$fs->end_date || now()->greaterThan(\Carbon\Carbon::parse($fs->end_date))) {
                        throw new \Exception("Chương trình Flash Sale đã kết thúc.");
                    }

                    // max_quantity lúc này là số còn lại (remaining)
                    $remaining = (int) $flashSaleItem->max_quantity;
                    if ($item->quantity > $remaining) {
                        $name = $item->productVariant->product->name ?? 'Sản phẩm';
                        $outOfStockItems[] = [
                            'name' => $name,
                            'requested' => $item->quantity,
                            'available' => $remaining
                        ];
                        continue;
                    }

                    // Trừ ngay trong transaction
                    $flashSaleItem->decrement('max_quantity', $item->quantity);
                    $flashSaleItem->increment('sold_quantity', $item->quantity);
                } else {
                    // Lock biến thể hàng thường
                    $variant = Product_variants::where('id', $item->product_variants_id)
                        ->lockForUpdate()
                        ->first();

                    if (!$variant) {
                        throw new \Exception("Biến thể sản phẩm không tồn tại.");
                    }

                    if ($item->quantity > (int)$variant->stock) {
                        $name = $item->productVariant->product->name ?? 'Sản phẩm';
                        $available = (int)$variant->stock;
                        $outOfStockItems[] = [
                            'name' => $name,
                            'requested' => $item->quantity,
                            'available' => $available
                        ];
                        continue;
                    }

                    $variant->decrement('stock', $item->quantity);
                    $variant->increment('sold_quantity', $item->quantity);
                }

                // Tính subtotal theo giá chốt trong cart
                $subtotal += ($item->quantity * $item->price_at_time);
            }

            // Kiểm tra nếu có sản phẩm hết hàng
            if (!empty($outOfStockItems)) {
                DB::rollback();
                $errorMessage = 'Một số sản phẩm không đủ tồn kho:';
                foreach ($outOfStockItems as $item) {
                    $errorMessage .= "\n- {$item['name']}: Yêu cầu {$item['requested']}, có sẵn {$item['available']}";
                }
                return redirect()->back()->with('error', $errorMessage);
            }

            // 5.2) TÍNH VOUCHER (re-calc để chắc chắn, rồi lưu snapshot)
            $voucherDiscount = 0;
            $voucherData = null;
            $voucherCode = session('voucher_code');

            if ($voucherCode) {
                $voucherData = Vouchers::where('code', $voucherCode)->first();

                if ($voucherData && $voucherData->status === 'active') {
                    // Kiểm tra thời gian hiệu lực (nếu có)
                    $now = now();
                    $validTime = true;
                    if (!empty($voucherData->start_date) && $now->lt(\Carbon\Carbon::parse($voucherData->start_date))) {
                        $validTime = false;
                    }
                    if (!empty($voucherData->end_date) && $now->gt(\Carbon\Carbon::parse($voucherData->end_date))) {
                        $validTime = false;
                    }

                    if ($validTime && $subtotal >= ($voucherData->min_order_value ?? 0)) {
                        if ($voucherData->type_discount === 'percent') {
                            $voucherDiscount = round($subtotal * ($voucherData->value / 100));
                            if (!empty($voucherData->max_discount) && $voucherDiscount > $voucherData->max_discount) {
                                $voucherDiscount = $voucherData->max_discount;
                            }
                        } else {
                            $voucherDiscount = min($voucherData->value, $subtotal);
                        }
                    }
                }
            }

            // 5.3) TÍNH PHÍ VẬN CHUYỂN & FINAL AMOUNT
            $shippingFee = $this->calculateShippingFee($subtotal, $request->shipping_type, $address);
            $finalAmount = $subtotal - $voucherDiscount + $shippingFee;

            // 5.4) TẠO MÃ ĐƠN
            $orderCode = 'ORD' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

            // 5.5) LƯU ORDER (kèm snapshot voucher)
            $orderData = [
                'user_id' => $userId,

                'voucher_code_snapshot' => $voucherData->code ?? null,
                'voucher_type_discount_snapshot' => $voucherData->type_discount ?? null,
                'voucher_value_snapshot' => $voucherData->value ?? null,
                'voucher_max_discount_snapshot' => $voucherData->max_discount ?? null,
                'voucher_min_order_value_snapshot' => $voucherData->min_order_value ?? null,
                'voucher_start_date_snapshot' => $voucherData->start_date ?? null,
                'voucher_end_date_snapshot' => $voucherData->end_date ?? null,

                'address_books_id' => $address->id,
                'voucher_id' => $voucherCode ? ($voucherData->id ?? null) : null,

                'name' => $address->name,
                'phone' => $address->phone,
                'address' => $address->address,
                'province_code' => $address->province_code,
                'ward_code' => $address->ward_code,

                'total_amount' => $subtotal,
                'final_amount' => $finalAmount,
                'discount_amount' => $voucherDiscount,

                'status' => 'pending',
                'code_order' => $orderCode,

                'pay_method' => $request->payment_method,
                'status_pay' => $request->payment_method === 'VNPAY' ? 'unpaid' : 'cod_paid',

                'notes' => $request->notes ?? null,
                'shipping_fee' => $shippingFee,
                'shipping_method' => $request->shipping_type,
            ];

            $order = Order::create($orderData);

            // 5.6) LƯU ORDER ITEMS (dùng price_at_time + thông tin màu/size như bạn)
            foreach ($cartItems as $item) {
                $orderItemData = [
                    'order_id' => $order->id,
                    'product_variant_id' => $item->product_variants_id,
                    'flash_sale_items_id' => $item->flash_sale_items_id ?? null,

                    'product_id' => $item->productVariant->product_id,
                    'product_name' => $item->productVariant->product->name,
                    'product_image_url' => $item->productVariant->variant_image_url
                        ?? $item->productVariant->product->image_url
                        ?? '',

                    'import_price' => $item->productVariant->import_price,
                    'listed_price' => $item->productVariant->listed_price,
                    'sale_price' => $item->price_at_time, // GIÁ CHỐT

                    'quantity' => $item->quantity,
                    'promotion_type' => $item->promotion_type ?? '0',

                    'color_name' => $item->productVariant->color->color_name ?? '',
                    'size_name' => $item->productVariant->size->size_name ?? '',
                ];

                OrderItem::create($orderItemData);
            }

            // 5.7) XOÁ CHỈ NHỮNG ITEM ĐÃ CHỌN KHỎI GIỎ
            Cart::where('user_id', $userId)
                ->whereIn('id', $selectedIds)
                ->delete();

            // 5.8) ĐÁNH DẤU VOUCHER ĐÃ DÙNG (nếu có) — làm trong transaction để an toàn
            if (!empty($voucherCode) && $voucherData) {
                DB::table('vouchers_users')
                    ->where('user_id', $userId)
                    ->where('voucher_id', $voucherData->id)
                    ->update([
                        'is_used' => 'used',
                        'status' => 'used',
                        'updated_at' => now()
                    ]);

                DB::table('vouchers')
                    ->where('id', $voucherData->id)
                    ->increment('used');
            }

            // 5.9) XOÁ SESSION LIÊN QUAN
            session()->forget(['voucher_code', 'voucher_discount', 'shipping_fee', 'cart_selected_ids']);

            // 5.10) LƯU THÔNG TIN HIỂN THỊ SAU THANH TOÁN
            session([
                'payment_method' => $request->payment_method,
                'shipping_type' => $request->shipping_type,
                'order_code' => $orderCode
            ]);

            // 5.11) LƯU THÔNG TIN ĐƠN HÀNG VÀO SESSION ĐỂ KHÔI PHỤC KHI THANH TOÁN THẤT BẠI (chỉ cho VNPAY)
            if ($request->payment_method === 'VNPAY') {
                $cartItemsData = [];
                foreach ($cartItems as $item) {
                    $cartItemsData[] = [
                        'product_variant_id' => $item->product_variants_id,
                        'quantity' => $item->quantity,
                        'price_at_time' => $item->price_at_time,
                        'flash_sale_items_id' => $item->flash_sale_items_id ?? null
                    ];
                }

                // Lấy thông tin voucher trước khi xóa session
                $voucherInfo = null;
                if ($voucherCode && $voucherData) {
                    $voucherInfo = [
                        'id' => $voucherData->id,
                        'code' => $voucherData->code,
                        'discount' => $voucherDiscount
                    ];
                }

                session([
                    'cancelled_order_data' => [
                        'cart_items' => $cartItemsData,
                        'voucher_id' => $voucherInfo ? $voucherInfo['id'] : null,
                        'voucher_code' => $voucherInfo ? $voucherInfo['code'] : null,
                        'voucher_discount' => $voucherInfo ? $voucherInfo['discount'] : 0,
                        'order_id' => $order->id
                    ]
                ]);
            }

            // Commit — từ đây tab khác mới có thể tiếp tục và sẽ thấy kho đã bị trừ
            DB::commit();

            // 6) Điều hướng theo phương thức thanh toán
            if ($request->payment_method === 'VNPAY') {
                $paymentUrl = $this->createVnpayPaymentUrl($order, $finalAmount);
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

    /**
     * Hủy thanh toán online đang chờ (VNPAY) và khôi phục giỏ hàng/voucher.
     * Dùng khi người dùng back/đóng tab trang thanh toán mà không có callback.
     */
    public function cancelPendingPayment(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $cancelledOrderData = session('cancelled_order_data');
        if (empty($cancelledOrderData) || empty($cancelledOrderData['order_id'])) {
            return response()->json(['success' => true, 'message' => 'No pending payment to cancel']);
        }

        try {
            // Khôi phục cart/voucher/stock từ session
            $this->restoreProductsFromSession();

            // Xóa order pending tương ứng
            $orderId = (int) $cancelledOrderData['order_id'];
            DB::beginTransaction();
            OrderItem::where('order_id', $orderId)->delete();
            Order::where('id', $orderId)->delete();
            DB::commit();

            // Xóa session lưu tạm dữ liệu khôi phục
            session()->forget('cancelled_order_data');

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('cancelPendingPayment error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Internal error'], 500);
        }
    }

    public function db_order(Request $request)
    {
        // Validate input filter
        // $request->validate([
        //     'everything' => 'nullable|string|max:30',
        //     'status' => 'nullable|in:pending,success,failed,shipping,all,cancelled,confirmed',
        //     'pay_method' => 'nullable|in:all,VNPAY,COD',
        //     'status_pay' => 'nullable|in:unpaid,paid,failed,cod_paid'
        // ]);

        // Khởi tạo query
        $query = Order::query();

        // Nếu có nhập "everything", tìm theo code_order hoặc name
        if (!empty($request->everything)) {
            $search = $request->everything;
            $query->where(function ($q) use ($search) {
                $q->where('code_order', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        // Xử lý status
        $valid_status = ['pending', 'success', 'failed', 'shipping', 'cancelled', 'confirmed', 'delivered'];
        // Nếu có filter status và khác 'all'
        if (!empty($request->status) && $request->status !== 'all' && in_array($request->status, $valid_status)) {
            $query->where('status', $request->status);
        }

        // Xử lý pay_method
        $valid_pay_methods = ['VNPAY', 'COD'];
        if (!empty($request->pay_method) && $request->pay_method !== 'all' && in_array($request->pay_method, $valid_pay_methods)) {
            $query->where('pay_method', $request->pay_method);
        }

        // Xử lý status_pay
        $valid_status_pay = ['unpaid', 'paid', 'failed', 'cod_paid'];
        if (!empty($request->status_pay) && in_array($request->status_pay, $valid_status_pay)) {
            $query->where('status_pay', $request->status_pay);
        }

        // Xử lý param type từ query string, ưu tiên hơn filter status (nếu có)
        $action = ['pending', 'confirmed', 'shipping', 'success', 'cancelled', 'failed', 'delivered'];
        $type = $request->query('type');

        if ($type && !in_array($type, $action)) {
            return abort(403, 'Không có hành động này');
        }
        if ($type) {
            $query->where('status', $type);
        }

        // Sắp xếp mới nhất trước
        $query->orderBy('created_at', 'desc');

        // Phân trang 10 item / trang
        $data_order = $query->paginate(10)->withQueryString();

        // Đếm số order failed (nếu cần)
        $count_failed = OrderHistories::where('from_status', 'failed')->count();

        // Trả về view, truyền dữ liệu
        // Đếm số đơn theo từng trạng thái
        $statusCounts = Order::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');
        return view('dashboard.pages.order.index', [
            'data_order' => $data_order,
            'count_failed' => $count_failed,
            'statusCounts' => $statusCounts,
            // Nếu bạn cần truyền thêm filter đã chọn để view dễ hiển thị lại
            'filters' => $request->only(['everything', 'status', 'pay_method', 'status_pay', 'type']),
        ]);
    }

    public function refund($present, $id, $refund)
    {
        $items = OrderItem::where('order_id', $id)->get();

        // Hoàn lại stock cho sản phẩm thường
        $items->whereNull('flash_sale_items_id')->each(function ($item) {
            $variant = Product_variants::withTrashed()->find($item->product_variant_id);

            if ($variant) { // tồn tại cả soft delete
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


        $voucher = Vouchers::find($present->voucher_id);
        if ($present->voucher_id && $voucher->end_date < now()) {
            VouchersUsers::updateOrCreate(
                [
                    'user_id' => $present->user_id,
                    'voucher_id' => $present->voucher_id,
                ],
                [
                    'is_used'    => 'unused',
                    'status' => 'available',
                    'start_date' => now(),
                    'end_date'   => now()->addDays(7),
                ]
            );
            VouchersLog::create([
                'user_id' => $present->user_id,
                'voucher_id' => $present->voucher_id,
                'order_id' => $id,
                'actor' => Auth::id(),
                'type' => 'refund_new',
                'content' => 'Voucher đã được tạo lại do đơn hàng bị hủy',
            ]);
        } else if ($present->voucher_id) {
            VouchersUsers::where('user_id', $present->user_id)
                ->where('voucher_id', $present->voucher_id)
                ->update([
                    'status' => 'available',
                    'is_used' => 'unused'
                ]);
            VouchersLog::create([
                'user_id' => $present->user_id,
                'voucher_id' => $present->voucher_id,
                'order_id' => $id,
                'type' => 'refund_reuse',
                'actor' => Auth::id(),
                'content' => 'Voucher đã được đánh dấu là chưa sử dụng do đơn hàng bị hủy',
            ]);
        }
        // dd($refund);
        $data_product = OrderItem::where('order_id', $id)->get();
        if ($present->status == 'confirmed' || (isset($refund) && $refund == 1)) {
            $final_amount = $present->final_amount;
        } else {
            $final_amount = $present->final_amount - $present->shipping_fee;
        }
        if ($present->status_pay == 'paid' && $present->pay_method == 'VNPAY' || $present->pay_method == 'QR') {
            // dd($final_amount);

            RefundMoney::create([
                'user_id' => $present->user_id,
                'order_id' => $id,
                'amount' => $final_amount,
                'status' => 'admin',
            ]);
            $voucher = VouchersUsers::where('voucher_id', $present->voucher_id)->first();
            // dd($voucher);
            if (!$voucher) {
                $voucher = null;
            }
            $type = VouchersLog::where('voucher_id', $present->voucher_id)->first();
            Mail::to($present->user->email)->send(new OrderCancelledMail($present, $voucher, $type, $final_amount, $data_product));
        }
        if ($present->status_pay == 'cod_paid' && $present->pay_method == 'COD') {
            $type = VouchersLog::where('voucher_id', $present->voucher_id)->first();

            Mail::to($present->user->email)->send(new OrderCancelledMail($present, $voucher, $type, null, $data_product));
        }
    }
    public function db_order_change(Request $request, $id)
    {
        // dd($_POST);
        $before = $request->change;

        $request->validate(
            [
                'content' => 'nullable|string|max:255',
                'notes' => 'nullable|string|max:255',
                'image_ship' => 'required_if:change,shipping|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ],
            [
                'content.max' => 'Nội dung không được quá 255 ký tự',
                'content.string' => 'Nội dung phải là chuỗi ký tự',
                'notes.max' => 'Ghi chú không được quá 255 ký tự',
                'notes.string' => 'Ghi chú phải là chuỗi ký tự',
                'image_ship.image' => 'Ảnh giao hàng phải là một tệp hình ảnh',
                'image_ship.mimes' => 'Ảnh giao hàng phải có định dạng jpeg, png, jpg, gif hoặc svg',
                'image_ship.max' => 'Ảnh giao hàng không được vượt quá 2MB',
                'image_ship.required_if' => 'Ảnh giao hàng là bắt buộc khi cập nhật trạng thái giao hàng thành công',
            ]
        );
        if (!$request->input('content')) {
            $refund = 1;
            $content = $request->input('content1');
        } else {
            $refund = 0;
        }
        $data_change = ['pending', 'confirmed', 'shipping', 'cancelled', 'failed', 'return'];
        if ($before && !in_array($before, $data_change)) {
            return  abort(403, "Hành động không hợp lệ");
        }
        // dd($id);
        $old_status = Order::find($id);
        $present = Order::find($id);

        $count = OrderHistories::where('from_status', 'failed')->where('order_id', $id)->count();

        if (!$present || !$old_status) {
            return abort(403, 'Không thấy đơn hàng này vui lòng kiểm tra lại');
        }
        switch ($before) {
            case 'pending':
                if ($present->status != 'pending') {
                    return abort(403, "Bạn không thể đổi sang trạng thái đã xác nhận khi đơn hàng không ở trạng thái chưa xác nhận ");
                } else {
                    $present->status = 'confirmed';
                    $present->updated_at = now();
                    $note = 'Đơn hàng đã được xác nhận';
                }
                break;
            case 'confirmed':
                if ($present->status != 'confirmed') {
                    return abort(403, 'Bạn không thể đổi sang trạng thái giao hàng khi đơn hàng không ở trạng thái đã xác nhận ');
                } else {
                    $present->status = 'shipping';
                    $present->updated_at = now();
                    $note = 'Đơn vị vận chuyển đã lấy hàng, chuẩn bị giao hàng';
                }
                break;
            case 'shipping':
                if ($present->status != 'shipping') {
                    return abort(403, 'Bạn không thể đổi sang trạng thái đã giao hàng khi đơn hàng không ở trạng thái đang giao hàng ');
                } else {
                    if ($present->pay_method == 'COD') {
                        $present->status_pay = 'paid';
                    }
                    $present->status = 'success';
                    $present->updated_at = now();
                    $note = $request->notes ?? 'Đơn hàng đã được giao thành công';
                    if ($request->hasFile('image_ship')) {
                        $image = $request->file('image_ship');

                        $filename = time() . '_' . $image->getClientOriginalName();

                        $image->move(public_path('uploads/orders'), $filename);

                        $present->image_ship = 'uploads/orders/' . $filename;
                    }
                }
                break;
            case 'failed':
                if ($present->status != 'shipping') {
                    return abort(403, 'Bạn không thể đổi sang trạng thái giao hàng thất bại khi đơn hàng không ở trạng thái đang giao hàng ');
                } else {
                    $present->status = 'failed';
                    $present->updated_at = now();
                    $note = 'Giao hàng thất bại';
                }
                break;
            case 'return':
                if ($present->status != 'failed') {
                    return abort(403, 'Bạn không thể đổi sang trạng thái giao lại khi đơn hàng không ở trạng thái giao hàng thất bại ');
                } else {
                    $present->status = 'shipping';
                    $present->updated_at = now();
                    $note = 'Đơn vị vận chuyển đã lấy hàng , chuẩn bị giao hàng';
                }
                break;
            case 'cancelled':
                if ($present->status == 'failed' || $present->status == 'pending' || $present->status == 'confirmed' || $count == 2) {
                    $present->status = 'cancelled';
                    $present->updated_at = now();
                    $note = 'Đơn hàng đã được hủy theo yêu cầu của khách hàng';
                } else {
                    return abort(403, 'Đơn chỉ được hủy khi ở trạng thái chưa xác nhận , đã xác nhận hoặc đơn giao thất bại');
                }
                break;
        }
        // dd($present);
        if ($present->status == 'cancelled') {

            $this->refund($present, $id, $refund);
            // dd($present);

        }

        $present->save();
        OrderHistories::create([
            'users' => Auth::user()->id,
            'order_id' => $id,
            'from_status' => $old_status->status,
            'to_status' => $present->status,
            'note' => $note,
            'content' => $content ?? $request->input('content', ''),
        ]);

        if ($count >= 2 && $present->status == 'failed') {
            $refund = 0;
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
            $this->refund($present, $id, $refund);
        }


        return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }

    public function db_order_show($id)
    {

        $data_order = Order::leftJoin('vouchers', 'vouchers.id', 'orders.voucher_id')
            ->leftJoin('address_books', 'address_books.id', 'orders.address_books_id')
            ->leftJoin('users', 'users.id', 'orders.user_id')
            ->leftJoin('provinces as province_order', 'orders.province_code', 'province_order.province_code')
            ->leftJoin('wards as ward_order', 'orders.ward_code', 'ward_order.ward_code')
            ->leftJoin('provinces as province_book', 'address_books.province_code', 'province_book.province_code')
            ->leftJoin('wards as ward_book', 'address_books.ward_code', 'ward_book.ward_code')->select(
                'orders.*',
                'vouchers.code',
                'address_books.name as ad_name',
                'address_books.address as ad_address',
                'address_books.phone as ad_phone',
                'users.email',
                'province_order.name as province_o',
                'ward_order.name as ward_o',
                'province_book.name as province_b',
                'ward_book.name as ward_b',
            )->where('orders.id', $id)
            ->first();
        // dd($data_order);
        $data_order_items = OrderItem::join('orders', 'orders.id', 'order_items.order_id')->leftJoin('product_variants', 'product_variants.id', 'order_items.product_variant_id')->leftJoin('sizes', 'sizes.id', 'product_variants.size_id')->leftJoin('colors', 'colors.id', 'product_variants.color_id')->where('order_id', $id)->select(
            'order_items.*',
            'sizes.size_name as p_size',
            'colors.color_name as p_color',
        )->get();
        $histoty_order = OrderHistories::join('users', 'users.id', 'order_histories.users')->where('order_id', $id)->select(
            'order_histories.*',
            'users.name as user_name',
            'users.id as user_id'
        )->orderBy('created_at', 'desc')->get();
        $data_refund = RefundMoney::where('order_id', $id)->where('status', 'approved')->first();
        // dd($data_order);
        // dd($histoty_order);
        // $historyItems = OrderHistories::where('order_id', $id)->get()->keyBy('from_status');
        // dd($historyItems);
        // dd($data_order);
        // dd($data_order_items);
        $data_provinces = Provinces::orderBy('place_type', 'ASC')->get();
        if (!$data_order) {
            return abort(403, "không có đơn này");
        }
        return view('dashboard.pages.order.detail', compact('data_order', 'data_order_items', 'histoty_order', 'data_provinces', 'data_refund'));
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
        // Lấy địa chỉ đang chọn từ client (nếu gửi) hoặc địa chỉ đầu tiên của user
        $addressId = $request->input('address_id');
        $address = null;
        if ($addressId) {
            $address = AddressBook::where('id', $addressId)->where('user_id', $userId)->with(['province', 'ward'])->first();
        }
        if (!$address) {
            $address = AddressBook::where('user_id', $userId)->with(['province', 'ward'])->first();
        }
        $shippingFee = $this->calculateShippingFee($subtotal, $request->shipping_type, $address);
        $total = $subtotal - $voucherDiscount + $shippingFee;

        return response()->json([
            'success' => true,
            'shipping_type' => $request->shipping_type,
            'shipping_fee' => number_format($shippingFee, 0, ',', '.') . ' đ',
            'shipping_fee_raw' => (int) $shippingFee,
            'total' => number_format($total, 0, ',', '.') . ' đ',
            'total_raw' => (int) $total
        ]);
    }

    /**
     * Xử lý callback từ VNPAY tại route /checkout
     */
    private function handleVnpayCallback(Request $request)
    {
        try {
            Log::info('VNPAY Callback received', [
                'all_params' => $request->all(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
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
                // Xóa session data vì thanh toán đã thành công
                session()->forget('cancelled_order_data');

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
                // Thanh toán thất bại - khôi phục sản phẩm về giỏ hàng
                // Lưu thông tin voucher trước khi xóa đơn hàng
                $voucherToRestore = null;
                if ($order->voucher_id) {
                    $voucherToRestore = Vouchers::find($order->voucher_id);
                }

                // Khôi phục sản phẩm từ session

                $this->restoreProductsFromSession();


                // Xóa đơn hàng thất bại

                OrderItem::where('order_id', $order->id)->delete();
                $order->delete();


                // Tạo thông báo lỗi chi tiết
                $errorMsg = 'Thanh toán thất bại! Các sản phẩm đã được khôi phục về giỏ hàng.';

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
                            $errorMsg = 'Bạn đã hủy thanh toán! Các sản phẩm đã được khôi phục về giỏ hàng. Bạn có thể thử lại hoặc chọn phương thức thanh toán khác.';
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

                // Khôi phục voucher session nếu có
                if ($voucherToRestore) {
                    // Tính toán lại discount dựa trên tổng tiền sản phẩm từ session
                    $cancelledOrderData = session('cancelled_order_data', []);
                    $totalAmount = 0;
                    if (isset($cancelledOrderData['cart_items'])) {
                        $totalAmount = collect($cancelledOrderData['cart_items'])->sum(function ($item) {
                            return $item['quantity'] * $item['price_at_time'];
                        });
                    }



                    // Sử dụng thông tin voucher từ session nếu có
                    if (isset($cancelledOrderData['voucher_code']) && isset($cancelledOrderData['voucher_discount'])) {
                        session([
                            'voucher_code' => $cancelledOrderData['voucher_code'],
                            'voucher_discount' => $cancelledOrderData['voucher_discount']
                        ]);
                    } else {
                        // Fallback: tính toán lại discount
                        session([
                            'voucher_code' => $voucherToRestore->code,
                            'voucher_discount' => $this->calculateVoucherDiscount($voucherToRestore, $totalAmount)
                        ]);

                        Log::info('Restored voucher session with recalculated discount', [
                            'voucher_code' => $voucherToRestore->code,
                            'voucher_discount' => session('voucher_discount'),
                            'total_amount' => $totalAmount
                        ]);
                    }
                }

                // Thêm thông báo về việc có thể thanh toán lại
                $errorMsg .= ' Bạn có thể thử thanh toán lại hoặc chọn phương thức thanh toán khác.';

                // Xóa session data sau khi đã hoàn tất khôi phục
                session()->forget('cancelled_order_data');

                return redirect()->route('home.checkout')->with('success', $errorMsg);
            }
        } catch (\Exception $e) {

            return redirect()->route('home.done')->with('error', 'Có lỗi xảy ra khi xử lý thanh toán! Vui lòng liên hệ hỗ trợ.');
        }
    }

    /**
     * Khôi phục sản phẩm từ session khi hủy thanh toán VNPAY
     */
    private function restoreProductsFromSession()
    {
        try {
            $userId = Auth::id();
            $cancelledOrderData = session('cancelled_order_data', []);

            if (empty($cancelledOrderData)) {
                return;
            }

            DB::beginTransaction();

            // Khôi phục sản phẩm về giỏ hàng
            if (isset($cancelledOrderData['cart_items'])) {
                $restoredCartIds = [];

                foreach ($cancelledOrderData['cart_items'] as $item) {
                    $existingCartItem = Cart::where('user_id', $userId)
                        ->where('product_variants_id', $item['product_variant_id'])
                        ->first();

                    if ($existingCartItem) {
                        $existingCartItem->increment('quantity', $item['quantity']);
                        $restoredCartIds[] = $existingCartItem->id;
                    } else {
                        $newCartItem = Cart::create([
                            'user_id' => $userId,
                            'product_variants_id' => $item['product_variant_id'],
                            'quantity' => $item['quantity'],
                            'price_at_time' => $item['price_at_time'],
                            'flash_sale_items_id' => $item['flash_sale_items_id'] ?? null
                        ]);
                        $restoredCartIds[] = $newCartItem->id;
                    }

                    // Khôi phục tồn kho và sold_quantity
                    $productVariant = Product_variants::find($item['product_variant_id']);
                    if ($productVariant) {
                        $productVariant->increment('stock', $item['quantity']);
                        $productVariant->decrement('sold_quantity', $item['quantity']);
                    }

                    // Khôi phục flash sale nếu có
                    if (isset($item['flash_sale_items_id']) && $item['flash_sale_items_id']) {
                        $flashSaleItem = FlashSaleItems::find($item['flash_sale_items_id']);
                        if ($flashSaleItem) {
                            $flashSaleItem->increment('max_quantity', $item['quantity']);
                            $flashSaleItem->decrement('sold_quantity', $item['quantity']);
                        }
                    }
                }

                // Khôi phục session cart_selected_ids để có thể thanh toán lại
                session(['cart_selected_ids' => $restoredCartIds]);
            }

            // Khôi phục voucher nếu có
            if (isset($cancelledOrderData['voucher_id']) && $cancelledOrderData['voucher_id']) {
                // Khôi phục trạng thái voucher trong bảng vouchers_users
                VouchersUsers::where('user_id', $userId)
                    ->where('voucher_id', $cancelledOrderData['voucher_id'])
                    ->update([
                        'is_used' => 'unused',
                        'status' => 'available',
                        'updated_at' => now()
                    ]);

                // Giảm lượt đã dùng trong bảng vouchers
                Vouchers::where('id', $cancelledOrderData['voucher_id'])->decrement('used');

                // Khôi phục session voucher từ dữ liệu session nếu có
                if (isset($cancelledOrderData['voucher_code']) && isset($cancelledOrderData['voucher_discount'])) {
                    session([
                        'voucher_code' => $cancelledOrderData['voucher_code'],
                        'voucher_discount' => $cancelledOrderData['voucher_discount']
                    ]);
                } else {
                    // Fallback: tính toán lại discount
                    $voucher = Vouchers::find($cancelledOrderData['voucher_id']);
                    if ($voucher) {
                        $totalAmount = collect($cancelledOrderData['cart_items'])->sum(function ($item) {
                            return $item['quantity'] * $item['price_at_time'];
                        });

                        session([
                            'voucher_code' => $voucher->code,
                            'voucher_discount' => $this->calculateVoucherDiscount($voucher, $totalAmount)
                        ]);
                    }
                }
            }

            DB::commit();

            // KHÔNG xóa session data ngay lập tức - để handleVnpayCallback có thể sử dụng
            // session()->forget('cancelled_order_data');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error restoring products from session: ' . $e->getMessage());
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
        // Xử lý IPN từ VNPAY
        Log::info('VNPAY IPN received', $request->all());

        // Thực hiện xác minh và xử lý IPN
        // Code xử lý IPN sẽ được thêm ở đây

        return response()->json(['RspCode' => '00', 'Message' => 'Confirmed']);
    }

    /**
     * Upload ảnh xác nhận từ user
     */
    public function uploadUserImage(Request $request, $id)
    {
        // Kiểm tra đơn hàng tồn tại và thuộc về user hiện tại
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Validate request
        $request->validate([
            'user_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'note' => 'nullable|string|max:500',
        ], [
            'user_image.required' => 'Vui lòng chọn ảnh xác nhận',
            'user_image.image' => 'File phải là hình ảnh',
            'user_image.mimes' => 'Chỉ chấp nhận định dạng: jpeg, png, jpg, gif, svg',
            'user_image.max' => 'Kích thước ảnh không được vượt quá 2MB',
            'note.max' => 'Ghi chú không được vượt quá 500 ký tự',
        ]);

        try {
            // Upload ảnh
            if ($request->hasFile('user_image')) {
                $file = $request->file('user_image');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/orders/user_images'), $filename);

                // Cập nhật ảnh vào database
                $order->update([
                    'image_user' => 'uploads/orders/user_images/' . $filename,
                    'notes' => $request->note ?? $order->notes,
                ]);

                // Tạo lịch sử đơn hàng
                OrderHistories::create([
                    'users' => Auth::id(),
                    'order_id' => $order->id,
                    'from_status' => $order->status,
                    'to_status' => $order->status,
                    'note' => 'Khách hàng đã gửi ảnh xác nhận nhận hàng',
                    'content' => $request->note ?? '',
                ]);

                return redirect()->back()->with('success', 'Gửi ảnh xác nhận thành công!');
            }

            return redirect()->back()->with('error', 'Có lỗi xảy ra khi upload ảnh');
        } catch (\Exception $e) {
            Log::error('Error uploading user image: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi upload ảnh: ' . $e->getMessage());
        }
    }

    public function getWards(Request $request)
    {
        return response()->json(
            Wards::where('province_code', $request->province_id)->get(['ward_code', 'name'])
        );
    }
    public function change_address(Request $request, $id)
    {
        if ($request->_form != "change_address") {
            return abort(403, "Hành Động Không Hợp Lệ");
        }
        $data_order = Order::find($id);
        if (!$data_order) {
            return abort(403, "Không tìm thấy đơn này");
        }
        if ($data_order->status != 'pending') {
            return redirect()->back()->with('error', 'Bạn không thể sửa địa chỉ của đơn hàng');
        }
        $request->validate([
            'ad_name' => 'required|string',
            'ad_phone' => 'required|string',
            'province_id' => ['required', 'exists:provinces,province_code'],
            'ward_id' => ['required', 'exists:wards,ward_code'],
            'ad_address' => 'required|string',
        ], [
            'ad_name.required' => 'Vui lòng nhập họ tên.',
            'ad_phone.required' => 'Vui lòng nhập số điện thoại.',
            'province_id.required' => 'Vui lòng chọn tỉnh/thành phố.',
            'province_id.exists' => 'Tỉnh/thành phố không hợp lệ.',
            'ward_id.required' => 'Vui lòng chọn xã/phường.',
            'ward_id.exists' => 'Xã/phường không hợp lệ.',
            'ad_address.required' => 'Vui lòng nhập địa chỉ chi tiết.',
        ]);
        $data_order->update([
            'address_books_id' => null,
            'province_code' => $request->province_id,
            'ward_code' => $request->ward_id,
            'address' => $request->ad_address,
            'phone' => $request->ad_phone,
            'name' => $request->ad_name,
        ]);
        OrderHistories::create([
            'from_status' => 'Địa chỉ cũ',
            'to_status' => 'Địa chỉ mới',
            'order_id' => $id,
            'note' => 'Khách hàng yêu cầu đổi địa chỉ giao hàng',
            'users' => Auth::user()->id,
        ]);
        return redirect()->back()->with('success', 'Sửa địa chỉ thành công');
    }

    /**
     * Tính toán discount voucher
     */
    private function calculateVoucherDiscount($voucher, $subtotal)
    {
        if (!$voucher) {
            return 0;
        }

        if ($voucher->type_discount === 'percent') {
            $discount = round($subtotal * ($voucher->value / 100));
            if ($voucher->max_discount && $discount > $voucher->max_discount) {
                $discount = $voucher->max_discount;
            }
        } else {
            $discount = $voucher->value;
        }

        return $discount;
    }
}
