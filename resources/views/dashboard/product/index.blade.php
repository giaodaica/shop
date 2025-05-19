@extends('dashboard.layouts.layout')

@section('main-content')
<div class="container mt-4">
    <h2>Danh sách sản phẩm</h2>
    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Thêm sản phẩm</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tên sản phẩm</th>
                <th>Mô tả</th>
                <th>Giá</th>
                <th>Danh mục</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Áo trắng</td>
                <td>Đẹp và thanh lịch</td>
                <td>100000</td>
                <td>Thời trang</td>
                <td>
                    <a href="{{ route('products.show', 1) }}" class="btn btn-info btn-sm">Xem</a>
                    <a href="{{ route('products.edit', 1) }}" class="btn btn-warning btn-sm">Sửa</a>
                    <form action="{{ route('products.destroy', 1) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Xoá</button>
                    </form>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
