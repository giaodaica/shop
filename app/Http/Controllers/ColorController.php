<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
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
        $color->delete();

        return redirect()->route('colors.index')->with('success', 'Xóa màu thành công!');
    }
}
