@extends('dashboard.layouts.layout')

@section('main-content')
<div class="container mt-4">
    <h2>Chi tiết sản phẩm</h2>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Áo trắng</h4>
            <p class="card-text"><strong>Mô tả:</strong> Đẹp và thanh lịch</p>
            <p class="card-text"><strong>Giá:</strong> 100000</p>
            <p class="card-text"><strong>Danh mục:</strong> Thời trang</p>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </div>
</div>
@endsection
