@extends('dashboard.layouts.layout')

@section('main-content')
<div class="container mt-4">
    <h2>Chỉnh sửa sản phẩm</h2>
    <form action="{{ route('products.update', 1) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Tên sản phẩm</label>
            <input type="text" name="name" class="form-control" value="Áo trắng">
        </div>
        <div class="mb-3">
            <label>Mô tả</label>
            <input type="text" name="dsc" class="form-control" value="Đẹp và thanh lịch">
        </div>
        <div class="mb-3">
            <label>Giá</label>
            <input type="number" name="price" class="form-control" value="100000">
        </div>
        <div class="mb-3">
            <label>Danh mục</label>
            <select name="category_id" class="form-control">
                <option value="1" selected>Thời trang</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>
@endsection
