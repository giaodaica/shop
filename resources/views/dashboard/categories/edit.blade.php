@extends('dashboard.layouts.layout')

@section('main-content')
<div class="container mt-4">
    <h2>Chỉnh sửa danh mục</h2>
    <form action="{{ route('categories.update', 1) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Tên danh mục</label>
            <input type="text" name="name" class="form-control" value="Thời trang">
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>
@endsection
