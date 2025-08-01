<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\FlashSale;
use App\Models\FlashSaleItems;
use App\Models\Vouchers;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use Illuminate\Support\Carbon;

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
    public function index(Request $request)
    {
        $voucher_block_3 = Vouchers::where('status', 'active')
            ->where('block', 3)
            ->where('max_used', '>=', 1)
            ->first();

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

        // Lấy thời gian hiện tại theo múi giờ Việt Nam (Asia/Ho_Chi_Minh)
        $now = now('Asia/Ho_Chi_Minh');

        // Tạo mốc thời gian bắt đầu và kết thúc trong ngày hiện tại (0:00 - 23:59:59)
        $todayStart = $now->copy()->startOfDay();
        $todayEnd = $now->copy()->endOfDay();

        // Lấy các flash sale trong ngày có trạng thái là 'active' hoặc 'upcoming'
        $flashSales = FlashSale::whereIn('status', ['active', 'upcoming'])
            ->whereBetween('start_date', [$todayStart, $todayEnd])
            ->whereHas('items') // chỉ lấy flash sale có ít nhất 1 sản phẩm
            ->get()
            ->map(function ($sale) use ($now) {
                // Kiểm tra xem flash sale này đang diễn ra hay chưa
                $isActive = $sale->start_date <= $now && $sale->end_date > $now;

                // Gắn nhãn hiển thị: nếu đang diễn ra thì là 'Còn lại', nếu chưa thì 'Sắp diễn ra'
                $sale->label = $isActive ? 'Còn lại' : 'Sắp diễn ra';

                // Gắn trạng thái có đang hoạt động hay không để dùng bên view
                $sale->is_active = $isActive;

                // Gắn thời gian đích:
                // - Nếu đang diễn ra: gắn thời gian kết thúc dạng ISO (để dùng countdown JavaScript)
                // - Nếu chưa diễn ra: chỉ hiển thị giờ bắt đầu (dạng H:i, ví dụ 12:00)
                $sale->target_time = $isActive
                    ? $sale->end_date->timezone('Asia/Ho_Chi_Minh')->toIso8601String()
                    : $sale->start_date->timezone('Asia/Ho_Chi_Minh')->format('H:i');

                // Trả về đối tượng flash sale đã được xử lý
                return $sale;
            });


        // dd($flashSales);
            $category_product = Categories::take(4)->get();

           
        // Mặc định: trả về giao diện
        return view('pages.shop.index', compact(
            'voucher_block_3',
            'bestSellers',
            'featured',
            'flashSales','category_product'
        ));
    }

    public function getProducts($id)
    {
        // Lấy flash sale theo ID để kiểm tra trạng thái (active hoặc upcoming)
        $flashSale = FlashSale::findOrFail($id);
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $isActive = $flashSale->start_date <= $now && $flashSale->end_date > $now;
        // Lấy các sản phẩm trong flash sale
        $sale = FlashSaleItems::where('flash_sale_id', $id)
            ->join('colors', 'flash_sale_items.color_id', 'colors.id')
            ->join('sizes', 'flash_sale_items.size_id', 'sizes.id')
            ->select('flash_sale_items.*', 'colors.color_name as color_name', 'sizes.size_name as size_name')
            ->get()
            // Sử dụng map() để thêm thông tin trạng thái đang diễn ra hay chưa
            ->map(function ($item) use ($isActive) {
                $item->is_active = $isActive;
                return $item;
            });
        // $sale = FlashSaleItems::where('flash_sale_id', $id)->get();

        return view('pages.flashshow', compact('sale'));
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
}
