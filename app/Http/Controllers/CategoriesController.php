<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission.hierarchy:Quản lý Danh mục')->only(['index']);
        $this->middleware('permission:Tạo danh mục')->only(['create', 'store']);
        $this->middleware('permission:Sửa danh mục')->only(['edit', 'update']);
        $this->middleware('permission:Xóa danh mục')->only(['destroy']);
        $this->middleware('permission:Khôi phục danh mục')->only(['restore']);
    }

    public function index(Request $request)
    {
        $status = $request->get('status', 'active'); // mặc định là active

        if ($status === 'trashed') {
            $categories = Categories::onlyTrashed()->paginate(10);
        } elseif ($status === 'all') {
            $categories = Categories::withTrashed()->paginate(10);
        } else {
            $categories = Categories::paginate(10); // chỉ lấy active
        }

        // Đếm số lượng
        $countActive = Categories::count();
        $countTrashed = Categories::onlyTrashed()->count();
        $countAll = $countActive + $countTrashed;

        return view('dashboard.pages.categories.index', compact('categories', 'status', 'countActive', 'countTrashed', 'countAll'));
    }
    public function restore($id)
    {
        $category = Categories::onlyTrashed()->findOrFail($id);
        $category->restore();

        return redirect()->route('categories.index', ['status' => 'active'])
            ->with('success', 'Khôi phục danh mục thành công.');
    }
    public function create()
    {
        return view('dashboard.pages.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name',
            'image' => 'required|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:2048',
        ], [
            'name.required' => 'Tên danh mục không được để trống.',
            'name.unique' => 'Tên danh mục đã tồn tại.',
            'image.required' => 'Ảnh không được để trống.',
            'image.mimes' => 'Ảnh chỉ được chấp nhận định dạng jpeg, png, jpg, gif, svg, webp, avif.',
            'image.max' => 'Kích thước ảnh không được vượt quá 2MB.',
        ]);

        $data = $request->only('name', 'status');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = public_path('uploads/categories/' . $filename);

            // Resize ảnh về 600x450
            $img = imagecreatefromstring(file_get_contents($file->getRealPath()));
            $resized = imagescale($img, 600, 450);
            imagejpeg($resized, $path, 90);
            imagedestroy($img);
            imagedestroy($resized);

            $data['image'] = 'uploads/categories/' . $filename;
        }

        Categories::create($data);

        return redirect()->route('categories.index')->with('success', 'Thêm danh mục thành công!');
    }

    public function update(Request $request, $id)
    {
        $category = Categories::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:categories,name,' . $id,
            'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:2048',
            'status' => 'required|in:0,1',
        ], [
            'name.required' => 'Tên danh mục không được để trống.',
            'name.unique' => 'Tên danh mục đã tồn tại.',
            'image.mimes' => 'Ảnh chỉ được chấp nhận định dạng jpeg, png, jpg, gif, svg, webp, avif.',
            'image.max' => 'Kích thước ảnh không được vượt quá 2MB.',
            'status.required' => 'Trạng thái không được để trống.',
            'status.in' => 'Trạng thái không hợp lệ. Chỉ được chọn 0 hoặc 1.',
        ]);

        $data = $request->only('name', 'status');

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu tồn tại
            if ($category->image && file_exists(public_path($category->image))) {
                unlink(public_path($category->image));
            }

            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = public_path('uploads/categories/' . $filename);

            // Resize ảnh về 600x450
            $img = imagecreatefromstring(file_get_contents($file->getRealPath()));
            $resized = imagescale($img, 600, 450);
            imagejpeg($resized, $path, 90);
            imagedestroy($img);
            imagedestroy($resized);

            $data['image'] = 'uploads/categories/' . $filename;
        }

        $category->update($data);

        return redirect()->route('categories.index')->with('success', 'Cập nhật danh mục thành công!');
    }


    // {
    //     $category = Categories::findOrFail($id);

    //     $request->validate([
    //         'name' => 'required|unique:categories,name,' . $id,
    //         'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //         'status' => 'required|in:0,1',
    //     ]);

    //     $data = $request->all();

    //     if ($request->hasFile('image')) {
    //         // Xóa ảnh cũ nếu có
    //         if ($category->image && \Storage::disk('public')->exists($category->image)) {
    //             \Storage::disk('public')->delete($category->image);
    //         }

    //         // Lưu ảnh mới
    //         $path = $request->file('image')->store('categories', 'public');
    //         $data['image'] = $path;
    //     }

    //     $category->update($data);

    //     return redirect()->route('categories.index')->with('success', 'Cập nhật danh mục thành công!');
    // }


    public function show($id)
    {
        $category = Categories::findOrFail($id);
        return view('dashboard.pages.categories.show', compact('category'));
    }

    public function edit($id)
    {
        $category = Categories::findOrFail($id);
        return view('dashboard.pages.categories.edit', compact('category'));
    }



    public function destroy($id)
    {
        $category = Categories::findOrFail($id);
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Xóa danh mục thành công!');
    }
}
