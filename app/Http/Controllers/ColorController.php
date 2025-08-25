<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission.hierarchy:Quản lý Màu sắc')->only(['index']);
        $this->middleware('permission:Tạo màu sắc')->only(['create', 'store']);
        $this->middleware('permission:Sửa màu sắc')->only(['edit', 'update']);
        $this->middleware('permission:Xóa màu sắc')->only(['destroy']);
    }

    // Hiển thị danh sách màu
    public function index()
    {
        $colors = Color::orderBy('id', 'desc')->paginate(10);
        return view('dashboard.pages.color.index', compact('colors'));
    }

    // Hiển thị form tạo mới màu
    public function create()
    {
        return view('dashboard.pages.color.create');
    }

    // Lưu màu mới
    public function store(Request $request)
    {
        // dd($_POST)
        $request->validate([
            'color_name' => ['required', 'unique:colors,color_name', 'max:50', 'regex:/^[\pL\s]+$/u'],
            'color_code' => ['required', 'regex:/^#([A-Fa-f0-9]{6})$/'],
        ], [
            'color_name.required' => 'Tên màu không được để trống.',
            'color_name.unique' => 'Tên màu đã tồn tại, vui lòng chọn tên khác.',
            'color_name.max' => 'Tên màu không được dài quá 50 ký tự.',
            'color_name.regex' => 'Tên màu chỉ được chứa chữ và khoảng trắng.',
            'color_code.required' => 'Vui lòng chọn màu',
            'color_code.regex' => 'Mã màu không hợp lệ',
        ]);

        Color::create([
            'color_name' => $request->color_name,
            'color_code' => $request->color_code
        ]);

        return redirect()->route('colors.create')->with('success', 'Thêm màu thành công!');
    }

    // Hiển thị form sửa màu
    public function edit($id)
    {
        $color = Color::findOrFail($id);
        return view('dashboard.pages.color.edit', compact('color'));
    }

    // Cập nhật màu
    public function update(Request $request, $id)
    {
        $color = Color::findOrFail($id);

        $request->validate([
            'color_name' => ['required', 'unique:colors,color_name,' . $id, 'max:50', 'regex:/^[\pL\s]+$/u'],
        ], [
            'color_name.required' => 'Tên màu không được để trống.',
            'color_name.unique' => 'Tên màu đã tồn tại, vui lòng chọn tên khác.',
            'color_name.max' => 'Tên màu không được dài quá 50 ký tự.',
            'color_name.regex' => 'Tên màu chỉ được chứa chữ và khoảng trắng.',
        ]);

        $color->update([
            'color_name' => $request->color_name,
            'color_code' => $request->color_code
        ]);

        return redirect()->route('colors.index')->with('success', 'Cập nhật màu thành công!');
    }

    // Xóa màu
    public function destroy($id)
    {
        $color = Color::findOrFail($id);
        if ($color->productVariants()->exists()) {
            return redirect()->route('colors.index')->with('error', 'Không thể xóa màu vì đang được sử dụng trong sản phẩm.');
        }
        $color->delete();

        return redirect()->route('colors.index')->with('success', 'Xóa màu thành công!');
    }
    public function deleteMultiple(Request $request)
    {
        $ids = $request->ids;

        if (empty($ids) || !is_array($ids)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không có màu nào được chọn.'
            ], 400);
        }

        // Kiểm tra màu nào đang được sử dụng trong product_variants
        $colorsInUse = Color::whereIn('id', $ids)
            ->whereHas('productVariants')
            ->pluck('color_name')
            ->toArray();

        if (!empty($colorsInUse)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không thể xóa các màu đang được sản phẩm sử dụng: ' . implode(', ', $colorsInUse)
            ], 400);
        }

        // Nếu tất cả đều không bị dùng thì xóa
        Color::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Đã xóa ' . count($ids) . ' màu.'
        ]);
    }
}
