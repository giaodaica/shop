<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product_variants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RevenueController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission.hierarchy:Quản lý Doanh thu')->only(['index']);
        $this->middleware('permission:Lọc dữ liệu doanh thu')->only(['index']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // dd($_GET);
        $request->validate(
            [
                'type' => 'in:day,month,year,hour',
                'date_from' => 'nullable|date',
                'date_to' => 'nullable|date|after_or_equal:date_from',
                'datetime_from' => 'nullable|date',
                'datetime_to' => 'nullable|date|after_or_equal:datetime_from',
                'month_from' => 'nullable|date_format:Y-m',
                'month_to' => 'nullable|date_format:Y-m|after_or_equal:month_from',
                'year_from' => 'nullable|integer|min:2000|max:2100',
                'year_to' => 'nullable|integer|min:2000|max:2100|after_or_equal:year_from',
            ],
            [
                'type.in' => 'Loại thống kê không hợp lệ',
                'date_from.date' => 'Ngày bắt đầu không hợp lệ',
                'date_to.date' => 'Ngày kết thúc không hợp lệ',
                'date_to.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu',
                'datetime_from.date' => 'Ngày giờ bắt đầu không hợp lệ',
                'datetime_to.date' => 'Ngày giờ kết thúc không hợp lệ',
                'datetime_to.after_or_equal' => 'Ngày giờ kết thúc phải sau hoặc bằng ngày giờ bắt đầu',
                'month_from.date_format' => 'Tháng bắt đầu không hợp lệ (hình thức: YYYY-MM)',
                'month_to.date_format' => 'Tháng kết thúc không hợp lệ (hình thức: YYYY-MM)',
                'month_to.after_or_equal' => 'Tháng kết thúc phải sau hoặc bằng tháng bắt đầu',
                'year_from.integer' => 'Năm bắt đầu phải là một số nguyên',
                'year_from.min' => 'Năm bắt đầu phải lớn hơn hoặc bằng 2000',
                'year_from.max' => 'Năm bắt đầu phải nhỏ hơn hoặc bằng 2100',
                'year_to.integer' => 'Năm kết thúc phải là một số nguyên',
                'year_to.min' => 'Năm kết thúc phải lớn hơn hoặc bằng 2000',
                'year_to.max' => 'Năm kết thúc phải nhỏ hơn hoặc bằng 2100',
                'year_to.after_or_equal' => 'Năm kết thúc phải sau hoặc bằng năm bắt đầu',
            ]
        );
        if ($request->type == 'year' && !$request->year_from || !$request->year_to) {
            $request->merge([
                'year_from' => now()->year,
                'year_to' => now()->year,
            ]);
        }
        if ($request->type == 'month' && (!$request->month_from || !$request->month_to)) {
            $currentMonth = now()->format('Y-m'); // lấy tháng hiện tại theo định dạng 2025-08

            $request->merge([
                'month_from' => $currentMonth,
                'month_to' => $currentMonth,
            ]);
        }

        if ($request->type == 'hour' && !$request->datetime_from || !$request->datetime_to) {
            $request->merge([
                'datetime_from' => now(),
                'datetime_to' => now(),
            ]);
        }
        if (!$request->type) {
            $request->merge([
                'type' => 'day',
                'date_from' => now()->toDateString(),
                'date_to' => now()->toDateString(),
            ]);
        }
        $revenue_order = Order::selectRaw('
        COUNT(orders.id) as sodonhang,
        COUNT(CASE WHEN orders.status = "pending" THEN 1 END) as donhang_dangcho,
       COUNT(CASE WHEN orders.status IN ("success", "delivered") THEN 1 END) as donhang_thanhcong,
        COUNT(CASE WHEN orders.status = "cancelled" THEN 1 END) as donhang_huy,
        COUNT(CASE WHEN orders.status = "failed" THEN 1 END) as donhang_thatbai,
        COUNT(CASE WHEN orders.status = "shipping" THEN 1 END) as donhang_dangvanchuyen');

        // thống kê doanh thu
        $revenue_doanhthu = Order::selectRaw('
        COUNT(*) as sodonhang,
        SUM(final_amount) as doanhthu,
        SUM(discount_amount) as tong_giam_gia')
            ->whereIn('orders.status', ['success', 'delivered']);

        // thống kê số lượng sản phẩm đã bán và loinhuan
        $revenue_loinhan = OrderItem::selectRaw('
        SUM(quantity) as tongsanpham,
        SUM((sale_price - import_price) * quantity) as loinhuan')
            ->join('orders', 'orders.id', 'order_items.order_id')
            ->whereIn('orders.status', ['success', 'delivered']);
        // dd($revenue_loinhan->first());

        // thống kê sản phẩm bán chạy
        $revenue_top_product = OrderItem::selectRaw(
            'SUM(quantity) as soluong_ban,
            product_name as ten_san_pham,
            SUM((sale_price - import_price) * quantity) as doanhthu'
        )->join('orders', 'orders.id', 'order_items.order_id')
            ->whereIn('orders.status', ['success', 'delivered'])
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('soluong_ban')
            ->take(5);
        // thống kê sản phẩm bán ế
        $least_sold_variants = Product_variants::select(
            'id',
            'product_id',
            'name as variant_name',
            'initial_stock',
            'sold_quantity',
            DB::raw('CASE WHEN initial_stock > 0 THEN ROUND((sold_quantity / initial_stock) * 100, 2) ELSE 0 END as sold_percentage')
        )
            ->orderBy('sold_percentage', 'asc')
            ->take(5)
            ->get();
        // dd($least_sold_variants);
        $revenue_top_users = Order::selectRaw('
        SUM(final_amount) as tong_tien_mua,
        SUM(user_id) as so_don_hang,
        user_id as user_id,
        users.name as user_name
        ')->join('users', 'users.id', 'orders.user_id')
            ->whereIn('orders.status', ['success', 'delivered'])
            ->groupBy('user_id', 'user_name')
            ->orderByDesc('tong_tien_mua')
            ->take(5);
        $start = null;
        $end = null;
        $timeFilter = function ($q) use ($request, &$start, &$end) {
            switch ($request->type) {
                case 'hour':
                    $q->whereDate('orders.created_at', '>=', $request->datetime_from)
                        ->whereDate('orders.created_at', '<=', $request->datetime_to);
                    $start = $request->datetime_from;
                    $end = $request->datetime_to;
                    break;
                case 'day':
                    $q->whereDate('orders.created_at', '>=', $request->date_from)
                        ->whereDate('orders.created_at', '<=', $request->date_to);
                    $start = $request->date_from;
                    $end = $request->date_to;
                    break;
                case 'month':
                    [$year_s, $month_s] = explode('-', $request->month_from);
                    [$year_z, $month_z] = explode('-', $request->month_to);

                    $q->where(function ($q2) use ($year_s, $month_s, $year_z, $month_z) {
                        $q2->whereYear('orders.created_at', '>=', $year_s)
                            ->whereYear('orders.created_at', '<=', $year_z)
                            ->whereMonth('orders.created_at', '>=', $month_s)
                            ->whereMonth('orders.created_at', '<=', $month_z);
                    });

                    // dd($q->toSql(), $q->getBindings());
                    $start = $request->month_from;
                    $end = $request->month_to;
                    break;
                case 'year':
                    $q->whereYear('orders.created_at', '>=', $request->year_from)
                        ->whereYear('orders.created_at', '<=', $request->year_to);
                    $start = '1-1-' . $request->year_from;
                    if ($request->year_to < now()->year) {
                        $end = '31-12-' . $request->year_to;
                    } else {
                        $end = now()->format('d-m-Y');
                    }
                    break;
            }
        };

        $data_order = $revenue_order->where($timeFilter)->first();
        $data_top_5 = $revenue_top_product->where($timeFilter)->get();
        $data_doanhthu = $revenue_doanhthu->where($timeFilter)->first();
        $data_loinhan = $revenue_loinhan->where($timeFilter)->first();
        $data_top_5_users = $revenue_top_users->where($timeFilter)->get();
        $sodonhang = $data_order->sodonhang ?? 0;
        $doanhthu = $data_doanhthu->doanhthu ?? 0;
        $sodonhang_done = $revenue_doanhthu->first()->sodonhang ?? 0;
        $dtb = $sodonhang > 0 ? $doanhthu / $sodonhang_done : 0;

        return view('dashboard.pages.revenue.index', compact('dtb', 'start', 'end', 'data_order', 'data_top_5', 'data_doanhthu', 'sodonhang', 'doanhthu', 'data_top_5_users', 'data_loinhan', 'least_sold_variants'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
