<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Vouchers;
use App\Models\Product_variants;
use App\Models\VouchersUsers;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    //
public function index()
{
    $userId = Auth::id();

   
    $cartItems = Cart::with('productVariant.color')->where('user_id', $userId)->get();
    $cartItems = Cart::with('productVariant.color', 'productVariant.size', 'productVariant.product')->where('user_id', $userId)->get();

   
    $selectedIds = session('cart_selected_ids', []);

   
    $selectedItems = $cartItems->whereIn('id', $selectedIds);

    $subtotal = $selectedItems->sum(fn($item) => $item->quantity * $item->price_at_time);
    // CHỈ lấy voucher nếu thực sự có session mã
    $voucherDiscount = session()->has('voucher_code') && session()->has('voucher_discount')
        ? session('voucher_discount')
        : 0;
    
    $total = $subtotal - $voucherDiscount;

    $voucherDiscount = 0;

    
    if (count($selectedItems) > 0 && session()->has('voucher_code')) {
        $voucherCode = session('voucher_code');
        $voucher = DB::table('vouchers')
            ->where('code', $voucherCode)
            ->where('status', 'active')
            ->first();

        if ($voucher && $subtotal >= ($voucher->min_order_value ?? 0)) {
            if ($voucher->type_discount === 'percent') {
                $voucherDiscount = round($subtotal * ($voucher->value / 100));
                if ($voucher->max_discount && $voucherDiscount > $voucher->max_discount) {
                    $voucherDiscount = $voucher->max_discount;
                }
            } else {
                $voucherDiscount = $voucher->value;
            }
        }
    }

    $total = $subtotal - $voucherDiscount;

    
    $availableVouchers = DB::table('vouchers')
        ->join('vouchers_users', 'vouchers.id', '=', 'vouchers_users.voucher_id')
        ->where('vouchers_users.user_id', $userId)
        ->where('vouchers_users.status', 'available')
        ->select('vouchers.*')
        ->get();

    return view('pages.shop.cart', compact(
        'cartItems',
        'selectedIds', 
        'subtotal',
        'voucherDiscount',
        'total',
        'availableVouchers'
    ));
}


public function ajaxUpdateSelected(Request $request)
{
    $userId = Auth::id();
    $items = $request->input('items', []);

    $selectedIds = [];
    $subtotal = 0;

    foreach ($items as $item) {
        $cartItem = Cart::where('user_id', $userId)
            ->where('id', $item['id'])
            ->first();

        if ($cartItem) {
            // Cập nhật số lượng
            $cartItem->quantity = (int) $item['qty'];
            $cartItem->save();

            $selectedIds[] = $cartItem->id;

            $subtotal += $cartItem->quantity * $cartItem->price_at_time;
        }
    }

    // Lưu lại danh sách được chọn
    session(['cart_selected_ids' => $selectedIds]);

    // Xử lý voucher
    $voucherDiscount = 0;
    $voucherRemoved = false;

    if (session()->has('voucher_code')) {
        $voucher = DB::table('vouchers')
            ->where('code', session('voucher_code'))
            ->where('status', 'active')
            ->first();

        if ($voucher) {
            if ($voucher->type_discount === 'percent') {
                $voucherDiscount = round($subtotal * ($voucher->value / 100));

                if ($voucher->max_discount && $voucherDiscount > $voucher->max_discount) {
                    $voucherDiscount = $voucher->max_discount;
                }
            } else {
                $voucherDiscount = $voucher->value;
            }

            if ($voucher->min_order_value && $subtotal < $voucher->min_order_value) {
                session()->forget(['voucher_code', 'voucher_discount']);
                $voucherDiscount = 0;
                $voucherRemoved = true;
            } else {
                session(['voucher_discount' => $voucherDiscount]);
            }
        } else {
            session()->forget(['voucher_code', 'voucher_discount']);
            $voucherDiscount = 0;
            $voucherRemoved = true;
        }
    }

    $total = $subtotal - $voucherDiscount;

    return response()->json([
        'success' => true,
        'subtotal' => number_format($subtotal, 0, ',', '.'),
        'voucher_discount' => number_format($voucherDiscount, 0, ',', '.'),
        'total' => number_format($total, 0, ',', '.'),
        'voucher_removed' => $voucherRemoved,
    ]);
}






public function deleteSelected(Request $request)
{
    $ids = $request->input('ids', []);
    $userId = auth()->id();

    // Xóa cart item
    Cart::where('user_id', $userId)->whereIn('id', $ids)->delete();

    // Lấy lại các cart item còn lại sau khi xóa
    $remainingItems = Cart::where('user_id', $userId)->get();

    $subtotal = $remainingItems->sum(function ($item) {
        return $item->quantity * $item->price_at_time;
    });

    $voucherRemoved = false;

    // Kiểm tra lại điều kiện voucher
    if (session()->has('voucher_code')) {
        $voucher = DB::table('vouchers')
            ->where('code', session('voucher_code'))
            ->where('status', 'active')
            ->first();

        if (!$voucher || ($voucher->min_order_value && $subtotal < $voucher->min_order_value)) {
            session()->forget(['voucher_code', 'voucher_discount']);
            $voucherRemoved = true;
        }
    }

    return response()->json([
        'success' => true,
        'voucher_removed' => $voucherRemoved,
    ]);
}


public function updateQuantity(Request $request)
{
    try {
        $cartItem = Cart::findOrFail($request->id);
        $variant = $cartItem->productVariant;

        if ($request->action === 'increase') {
            if ($cartItem->quantity >= $variant->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể tăng vượt quá tồn kho.'
                ]);
            }
            $cartItem->quantity += 1;
        } elseif ($request->action === 'decrease') {
            if ($cartItem->quantity > 1) {
                $cartItem->quantity -= 1;
            }
        }

        $cartItem->save();

        // Tính lại
        $subtotal = Cart::where('user_id', Auth::id())->sum(DB::raw('quantity * price_at_time'));
        $voucherDiscount = session('voucher_discount', 0);
        $total = $subtotal - $voucherDiscount;

        return response()->json([
            'success' => true,
            'quantity' => $cartItem->quantity,
            'item_total' => number_format($cartItem->quantity * $cartItem->price_at_time, 0, ',', '.') . ' đ',
            'subtotal' => number_format($subtotal, 0, ',', '.') . ' đ',
            'total' => number_format($total, 0, ',', '.') . ' đ',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Lỗi hệ thống: ' . $e->getMessage(),
        ], 500);
    }
}

public function calculateTotal(Request $request)
{
    $cartItems = Cart::where('user_id', Auth::id())->get();
    $subtotal = $cartItems->sum(function ($item) {
        return $item->quantity * $item->price_at_time;
    });

    $voucherDiscount = session('voucher_discount', 0);
    $total = $subtotal - $voucherDiscount;

    return response()->json([
        'subtotal' => number_format($subtotal, 0, ',', '.') . ' đ',
        'total' => number_format($total, 0, ',', '.') . ' đ'
    ]);
}

public function getUserVouchers()
{
    $userId = Auth::id();

    $vouchers = DB::table('vouchers_users')
        ->join('vouchers', 'vouchers_users.voucher_id', '=', 'vouchers.id')
        ->where('vouchers_users.user_id', $userId)
        ->where('vouchers_users.status', 'available')
        ->select('vouchers.*', 'voucher_user.status', 'vouchers_users.is_used')
        ->get();

    return response()->json($vouchers);
}

public function applyVoucher(Request $request)
{
    $request->validate([
        'code' => 'required|string'
    ]);

    $userId = Auth::id();
    $now = now();

    $voucher = DB::table('vouchers')
        ->where('code', $request->code)
        ->where('status', 'active')
        ->first();

    if (!$voucher) {
        return redirect()->back()->with('error', 'Mã giảm giá không hợp lệ hoặc không hoạt động.');
    }

    if (($voucher->start_date && $now->lt($voucher->start_date)) ||
        ($voucher->end_date && $now->gt($voucher->end_date))) {
        return redirect()->back()->with('error', 'Mã giảm giá đã hết hạn hoặc chưa đến thời gian sử dụng.');
    }

    if ($voucher->max_used !== null && $voucher->used >= $voucher->max_used) {
        return redirect()->back()->with('error', 'Mã giảm giá đã được sử dụng hết lượt.');
    }

    $userHasVoucher = DB::table('vouchers_users')
        ->where('user_id', $userId)
        ->where('voucher_id', $voucher->id)
        ->where('status', 'available')
        ->exists();

    if (!$userHasVoucher) {
        return redirect()->back()->with('error', 'Bạn chưa nhận được mã giảm giá này.');
    }


    $selectedIds = session('cart_selected_ids', []);

    if (empty($selectedIds)) {
        return redirect()->back()->with('error', 'Vui lòng chọn sản phẩm để áp dụng mã giảm giá.');
    }

    $cartItems = Cart::where('user_id', $userId)
        ->whereIn('id', $selectedIds)
        ->get();

    if ($cartItems->isEmpty()) {
        return redirect()->back()->with('error', 'Không thể áp dụng mã vì không có sản phẩm hợp lệ được chọn.');
    }

    
    $subtotal = $cartItems->sum(function ($item) {
        return $item->quantity * $item->price_at_time;
    });

   
    if ($voucher->min_order_value && $subtotal < $voucher->min_order_value) {
        return redirect()->back()->with('error', 'Đơn hàng phải tối thiểu ' . number_format($voucher->min_order_value, 0, ',', '.') . ' đ để sử dụng mã này.');
    }

   
    $discount = 0;
    if ($voucher->type_discount === 'percent') {
        $discount = round($subtotal * ($voucher->value / 100));
        if ($voucher->max_discount && $discount > $voucher->max_discount) {
            $discount = $voucher->max_discount;
        }
    } else {
        $discount = $voucher->value;
    }

   
    session([
        'voucher_code' => $voucher->code,
        'voucher_discount' => $discount
    ]);

    return redirect()->back()->with('success', 'Áp dụng mã giảm giá thành công!');
}



public function removeVoucher()
{
    session()->forget(['voucher_code', 'voucher_discount']);
    return redirect()->back()->with('info', 'Đã huỷ mã giảm giá');
}

    public function add_to_cart($id,request $request){

        $request->validate([
            'color' => 'required|exists:colors,id',
            'size' => 'required|exists:sizes,id',
            'quantity' => 'required|integer|min:1',
        ],[
            'color.required' => 'Bạn chưa chọn màu',
            'color.exists' => 'Không có màu này',
            'size.required' => 'Bạn chưa chọn size',
            'size.exists' => 'Không có size này',
            'quantity.required' => 'Bạn phải chọn số lượng',
            'quantity.integer' => 'Số lượng không hợp lệ',
            'quantity.min' => 'Số lượng sản phẩm tối thiểu phải là 1',

        ]);
        $product = Products::find($id);
        if(!$product){
            return redirect()->back()->with('error', 'Sản phẩm không tồn tại');
        }
        $variants = Product_variants::where('product_id',$id)->
        where('color_id',$request->color)->
        where('size_id',$request->size)->first();
        // dd($variants->price_atti);
        if(!$variants){
            return redirect()->back()->with('error','Sản phẩm này đã hết hàng hoặc không có xin vui lòng thao tác lại');
        }
        if($variants->stock < $request->quantity){
            return redirect()->back()->with('error',"Số lượng sản phẩm tồn kho chỉ còn $variants->stock");
        }
        $user_id = auth::user()->id;
        $items_cart = Cart::where('user_id',$user_id)->where('product_variants_id',$variants->id)->first();
        $quantity_in_db = $items_cart->quantity ?? 0;
        $new_quantity = $request->quantity+$quantity_in_db;
        // dd($new_quantity);

        if($new_quantity > $variants->stock){
            return redirect()->back()->with('error','Số lượng sản phẩm tồn kho không đủ vui lòng kiểm tra lại'); // kiểm tra xem hàng tồn kho có đủ khi khách hàng thêm 1 sản phẩm mới vào giỏ khi sản phẩm đó đã có sẵn
        }

        if(auth::user()){
            if(!$items_cart){
                Cart::create([
                    'user_id' => $user_id,
                    'product_variants_id' => $variants->id,
                    'quantity' => $request->quantity,
                    'price_at_time' => $variants->sale_price
                ]);
            return redirect()->back()->with('success','Thêm thành công');
            }else{
                $items_cart->update([
                     'quantity' => $items_cart->quantity + $request->quantity
                ]);
             return redirect()->back()->with('success','Thêm thành công');

            }
        }

    }
}
