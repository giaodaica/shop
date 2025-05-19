<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Products;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    // Hiển thị danh sách sản phẩm// Trang danh sách sản phẩm
    public function index()
    {
        return view('dashboard.product.index');
    }

    // Trang form thêm sản phẩm
    public function create()
    {
        return view('dashboard.product.create');
    }

    // Trang hiển thị chi tiết sản phẩm
    public function show($id)
    {
        return view('dashboard.product.show');
    }

    // Trang form chỉnh sửa sản phẩm
    public function edit($id)
    {
        return view('dashboard.product.edit');
    }
}
