<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index()
    {
        return view('dashboard.categories.index');
    }

    // Trang form thêm sản phẩm
    public function create()
    {
        return view('dashboard.categories.create');
    }

    // Trang hiển thị chi tiết sản phẩm
    public function show($id)
    {
        return view('dashboard.categories.show');
    }

    // Trang form chỉnh sửa sản phẩm
    public function edit($id)
    {
        return view('dashboard.categories.edit');
    }

}
