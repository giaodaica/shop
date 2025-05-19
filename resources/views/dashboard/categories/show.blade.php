@extends('dashboard.layouts.layout')

@section('main-content')
<div class="container mt-4">
    <h2>Chi tiết danh mục</h2>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Thời trang</h4>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </div>
</div>
@endsection

