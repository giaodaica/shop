@extends('dashboard.layouts.layout')

@section('main-content')
<div class="container mt-4">
    <h2>Thêm sản phẩm</h2>
    <form action="{{ route('products.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Tên sản phẩm</label>
            <input type="text" name="name" class="form-control" placeholder="Nhập tên sản phẩm">
        </div>
        <div class="mb-3">
            <label>Mô tả</label>
            <input type="text" name="dsc" class="form-control" placeholder="Nhập mô tả">
        </div>
        <div class="mb-3">
            <label>Giá</label>
            <input type="number" name="price" class="form-control" placeholder="Nhập giá">
        </div>
        <div class="mb-3">
            <label>Danh mục</label>
            <select name="category_id" class="form-control">
                <option value="1">Thời trang</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Thêm</button>
    </form>
</div>
@endsection
