@extends('dashboard.layouts.layout')

@section('main-content')
<div class="container mt-4">
    <h2>Thêm danh mục</h2>
    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Tên danh mục</label>
            <input type="text" name="name" class="form-control" placeholder="Nhập tên danh mục">
        </div>
        <button type="submit" class="btn btn-success">Thêm</button>
    </form>
</div>
@endsection
