@extends('dashboard.layouts.layout')

@section('main-content')

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Category Detail</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Ecommerce</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Category</a></li>
                            <li class="breadcrumb-item active">Detail</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Chi tiết danh mục</h5>
                    </div>
                    <div class="card-body">
                        <h4 class="card-title">Thời trang</h4>
                        <p class="card-text">Đây là mô tả chi tiết cho danh mục Thời trang.</p>
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Quay lại</a>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- container-fluid -->
</div><!-- End Page-content -->

@endsection


