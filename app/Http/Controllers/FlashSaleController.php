<?php

namespace App\Http\Controllers;

use App\Models\FlashSale;
use App\Models\FlashSaleItems;
use App\Models\Product_variants;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission.hierarchy:Quản lý Flash Sale')->only(['index']);
        $this->middleware('permission:Tạo flash sale')->only(['create']);
        $this->middleware('permission:Sửa flash sale')->only(['edit', 'update']);
        $this->middleware('permission:Xóa flash sale')->only(['destroy']);
        $this->middleware('permission:Kích hoạt flash sale')->only(['change_active']);
    }

    public function index()
    {
        $flashSales  = FlashSale::join('users', 'flash_sales.user_id', 'users.id')->select(
            'flash_sales.*',
            'users.name as name',
            'users.id as user_id'
        )->orderBy('slot_time','asc')->get();
        // dd($flashSales);
        return view('dashboard.pages.flashsale.index', compact('flashSales'));
    }
    public function show($id)
    {
        $variants = FlashSaleItems::where('flash_sale_id', $id)
            ->join('colors', 'flash_sale_items.color_id', 'colors.id')
            ->join('sizes', 'flash_sale_items.size_id', 'sizes.id')
            ->select('flash_sale_items.*','colors.color_name as color_name','sizes.size_name as size_name')
            ->get();
        // dd($variants);
        $flash_sale_id = $id;
        $data_flash_sale = FlashSale::findOrFail($flash_sale_id);
        return view('dashboard.pages.flashsale.show', compact('variants', 'flash_sale_id', 'data_flash_sale'));
    }
    public function create(Request $request)
    {
        // dd($_POST);

        $request->validate(
            [
                'discount' => 'required|numeric|max:100|min:0',
                'check_date' => 'required|date_format:Y-m-d|after_or_equal:today',
                'slot_time' => 'required|max:12|min:1|numeric'
            ],
            [
                'discount.required' => 'Giá trị giảm giá không được bỏ trống',
                'discount.numeric' => 'Giá trị giảm giá phải là số từ(0-100)',
                'discount.max' => 'Giá trị giảm giá không được lớn hơn 100',
                'discount.min' => 'Giá trị giảm giá không được nhỏ hơn 0',
                'check_date.required' => 'Không được bỏ trống ngày',
                'check_date.date_format' => 'Giá trị ngày không hợp lệ',
                'check_date.after_or_equal' => 'Phải = hoặc sau ngày hôm nay',
                'slot_time.required' => 'Khung giờ không được bỏ trống',
                'slot_time.numeric' => 'Khung giờ không hợp lệ',
                'slot_time.max' => 'Khung giờ không hợp lệ',
                'slot_time.min' => 'Khung giờ không hợp lệ',
            ]

        );
        $slots = [
            1  => ['start' => '00:00:00', 'end' => '02:00:00'],
            2  => ['start' => '02:00:00', 'end' => '04:00:00'],
            3  => ['start' => '04:00:00', 'end' => '06:00:00'],
            4  => ['start' => '06:00:00', 'end' => '08:00:00'],
            5  => ['start' => '08:00:00', 'end' => '10:00:00'],
            6  => ['start' => '10:00:00', 'end' => '12:00:00'],
            7  => ['start' => '12:00:00', 'end' => '14:00:00'],
            8  => ['start' => '14:00:00', 'end' => '16:00:00'],
            9  => ['start' => '16:00:00', 'end' => '18:00:00'],
            10 => ['start' => '18:00:00', 'end' => '20:00:00'],
            11 => ['start' => '20:00:00', 'end' => '22:00:00'],
            12 => ['start' => '22:00:00', 'end' => '23:59:59'],
        ];
        if (!isset($slots[$request->slot_time])) {
            return back()->with('error', 'Khung giờ không hợp lệ');
        }
        if ($request->check_date == now()->toDateString()) {
            $slotStart = Carbon::parse($request->check_date . ' ' . $slots[$request->slot_time]['start']);

            if (now()->greaterThanOrEqualTo($slotStart)) {
                return back()->with('error', 'Không thể tạo flash sale vì khung giờ này đã bắt đầu.');
            }
        }
        $check_flash_sale = FlashSale::whereDate('start_date', '=', $request->check_date)
            ->where('slot_time', $request->slot_time)
            ->first();

        if ($check_flash_sale) {
            return redirect()->back()->with('error', 'Khung giờ này đã có flash sale đang hoạt động');
        }

        FlashSale::create([
            'discount' => $request->discount,
            'start_date' => $request->check_date . $slots[$request->slot_time]['start'],
            'end_date' => $request->check_date . $slots[$request->slot_time]['end'],
            'status' => "upcoming",
            'slot_time' => $request->slot_time,
            'user_id' => Auth::user()->id
        ]);
        return redirect()->back()->with('success', 'Thành công');
    }
    public function change_active($id)
    {
        // dd($_POST);
        $key = $_POST['key'];
        if ($key != 'upcoming' && $key != 'active') {
            return redirect()->back()->with('error', 'Thao tác không hợp lệ');
        }
        $data_flash_sale = FlashSale::findOrFail($id);
        $data_items_flash_sale = FlashSaleItems::where('flash_sale_id', $id)->first();
        if (!$data_items_flash_sale) {
            return redirect()->back()->with('error', 'Không thể khởi động chương trình vì chưa có sản phẩm');
        }
        switch ($key) {
            case 'upcoming':
                if ($data_flash_sale->status != 'upcoming') {
                    return redirect()->back()->with('error', 'Thao tác không hợp lệ');
                } else {
                    $data_flash_sale->status = 'active';
                }
                break;
            case 'active':
                if ($data_flash_sale->status != 'active') {
                    return redirect()->back()->with('error', 'Thao tác không hợp lệ');
                } else {
                    $data_flash_sale->status = 'ended';
                    FlashSaleItems::where('flash_sale_id', $id)->get()->each(function ($item) {
                        Product_variants::where('id', $item->product_variant_id)->increment('stock', $item->max_quantity);
                    });
                }
                break;
        }
        $data_flash_sale->update();
        return redirect()->back()->with('success', 'Thành công');
    }
    public function destroy($id)
    {
        $data_flash_sale = FlashSale::findOrFail($id);
        if ($data_flash_sale->status != 'upcoming') {
            return redirect()->back()->with('error', 'Bạn chỉ được xóa hoặc thay đổi khi chương trình ở trạng thái đang chờ');
        }
        $data_items_flash_sale = FlashSaleItems::where('flash_sale_id', $id)->first();
        if ($data_items_flash_sale) {
            FlashSaleItems::where('flash_sale_id', $id)->get()->each(function ($item) {
                Product_variants::where('id', $item->product_variant_id)->increment('stock', $item->max_quantity);
            });
        }
        $data_flash_sale->delete();
        return redirect()->back()->with('success', 'Xóa thành công');
    }
    public function edit($id)
    {
        $flashSale = FlashSale::where('id', $id)->first();
        if (!$flashSale) {
            return redirect()->route('flash-sale')->with('error', 'Không có chương trình này');
        }
        return view('dashboard.pages.flashsale.edit', compact('flashSale'));
    }
    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'discount' => 'required|numeric|max:100|min:0',
                'check_date' => 'required|date_format:Y-m-d|after_or_equal:today',
                'slot_time' => 'required|max:12|min:1|numeric'
            ],
            [
                'discount.required' => 'Giá trị giảm giá không được bỏ trống',
                'discount.numeric' => 'Giá trị giảm giá phải là số từ(0-100)',
                'discount.max' => 'Giá trị giảm giá không được lớn hơn 100',
                'discount.min' => 'Giá trị giảm giá không được nhỏ hơn 0',
                'check_date.required' => 'Không được bỏ trống ngày',
                'check_date.date_format' => 'Giá trị ngày không hợp lệ',
                'check_date.after_or_equal' => 'Phải = hoặc sau ngày hôm nay',
                'slot_time.required' => 'Khung giờ không được bỏ trống',
                'slot_time.numeric' => 'Khung giờ không hợp lệ',
                'slot_time.max' => 'Khung giờ không hợp lệ',
                'slot_time.min' => 'Khung giờ không hợp lệ',
            ]

        );
        $slots = [
            1  => ['start' => '00:00:00', 'end' => '02:00:00'],
            2  => ['start' => '02:00:00', 'end' => '04:00:00'],
            3  => ['start' => '04:00:00', 'end' => '06:00:00'],
            4  => ['start' => '06:00:00', 'end' => '08:00:00'],
            5  => ['start' => '08:00:00', 'end' => '10:00:00'],
            6  => ['start' => '10:00:00', 'end' => '12:00:00'],
            7  => ['start' => '12:00:00', 'end' => '14:00:00'],
            8  => ['start' => '14:00:00', 'end' => '16:00:00'],
            9  => ['start' => '16:00:00', 'end' => '18:00:00'],
            10 => ['start' => '18:00:00', 'end' => '20:00:00'],
            11 => ['start' => '20:00:00', 'end' => '22:00:00'],
            12 => ['start' => '22:00:00', 'end' => '23:59:59'],
        ];
        $check_status = FlashSale::findOrFail($id);
        if ($check_status->status != 'upcoming') {
            return back()->with('error', 'Không thể sửa flash sale.');
        }
        if (!isset($slots[$request->slot_time])) {
            return back()->with('error', 'Khung giờ không hợp lệ');
        }
        if ($request->check_date == now()->toDateString()) {
            $slotStart = Carbon::parse($request->check_date . ' ' . $slots[$request->slot_time]['start']);

            if (now()->greaterThanOrEqualTo($slotStart)) {
                return back()->with('error', 'Không thể sửa flash sale vì khung giờ này đã bắt đầu.');
            }
        }
        $check_flash_sale = FlashSale::whereDate('start_date', '=', $request->check_date)
            ->where('slot_time', $request->slot_time)
            ->first();

        if ($check_flash_sale && $check_flash_sale->slot_time != $request->slot_time) {
            return redirect()->back()->with('error', 'Khung giờ này đã có flash sale đang hoạt động');
        }
        $data_flash_sale_items = FlashSaleItems::where('flash_sale_id', $id)->first();
        if ($data_flash_sale_items) {
            $discount = $request->discount;
            FlashSaleItems::where('flash_sale_id', $id)->get()->each(function ($item) use ($discount) {
                $item->update([
                    'price_at_flash_sale' => round($item->sale_price * (1 - $discount / 100), 0)
                ]);
            });
        }
        $flash_sale = FlashSale::findOrFail($id);
        $flash_sale->update([
            'discount' => $request->discount,
            'start_date' => $request->check_date . $slots[$request->slot_time]['start'],
            'end_date' => $request->check_date . $slots[$request->slot_time]['end'],
            'slot_time' => $request->slot_time,
            'user_id' => Auth::user()->id
        ]);
        return redirect()->back()->with('success', 'Thành công');
    }
}
