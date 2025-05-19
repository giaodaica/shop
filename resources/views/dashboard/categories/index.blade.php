@extends('dashboard.layouts.layout')

@section('main-content')
<div class="container mt-4">
    <h2>Danh sách danh mục</h2>
    <a href="{{ route('categories.create') }}" class="btn btn-primary mb-3">Thêm danh mục</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tên danh mục</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Thời trang</td>
                <td>
                    <a href="{{ route('categories.show', 1) }}" class="btn btn-info btn-sm">Xem</a>
                    <a href="{{ route('categories.edit', 1) }}" class="btn btn-warning btn-sm">Sửa</a>
                    <form action="{{ route('categories.destroy', 1) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Xoá</button>
                    </form>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
