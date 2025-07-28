<?php

namespace App\Http\Controllers;

use App\Models\FlashSale;
use App\Models\Vouchers;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $voucher_block_3 = Vouchers::where('status', 'active')->where('block', 3)->where('max_used', '>=', 1)->first();
        // Sản Phẩm Bán Chạy Nhất
        $bestSellers = Products::with(['category', 'variants.color', 'variants.size'])
            ->whereHas('category', function ($query) {
                $query->where('status', '1');
            })
            ->whereHas('variants', function ($query) {
                $query->where('is_show', 1)->whereNull('deleted_at');
            })
            ->whereNull('deleted_at')
            ->orderBy('views_page', 'desc')
            ->paginate(10);
        // Sản Phẩm Nổi Bật
        $featured = Products::with(['category', 'variants.color', 'variants.size'])
            ->whereHas('category', function ($query) {
                $query->where('status', '1');
            })
            ->whereHas('variants', function ($query) {
                $query->where('is_show', 1)->whereNull('deleted_at');
            })
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->paginate(5);
        // Lấy flash sale active
        $activeFlashSales = FlashSale::getActiveFlashSales();

        // Lấy flash sale upcoming  
        $upcomingFlashSales = FlashSale::getUpcomingFlashSales();

        return view('pages.shop.index', compact('voucher_block_3', 'bestSellers', 'featured', 'activeFlashSales','upcomingFlashSales'));
    }

    public function show($id)
    {
        return view('pages.shop.show');
    }
    public function admin()
    {
        return view('dashboard.index');
    }

    public function shop()
    {
        return view('pages.shop.index');
    }

    /**
     * Lấy tất cả sản phẩm flash sale có status là active
     */
    public function getActiveFlashSales()
    {
        $activeFlashSales = FlashSale::getActiveFlashSales();
        return response()->json([
            'success' => true,
            'data' => $activeFlashSales
        ]);
    }

    /**
     * Lấy tất cả sản phẩm flash sale có status là upcoming
     */
    public function getUpcomingFlashSales()
    {
        $upcomingFlashSales = FlashSale::getUpcomingFlashSales();
        return response()->json([
            'success' => true,
            'data' => $upcomingFlashSales
        ]);
    }

    /**
     * Hiển thị trang flash sale
     */
   
}
