@extends('dashboard.layouts.layout')

@section('main-content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- Tiêu đề trang -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Tạo màu</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('colors.index') }}">Màu</a></li>
                                <li class="breadcrumb-item active">Tạo màu</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Kết thúc tiêu đề -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <form action="{{ route('colors.store') }}" method="POST">
                @csrf
                <div class="row">

                    {{-- Form nhập --}}
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">

                                {{-- Tên màu --}}
                                <div class="mb-3">
                                    <label for="color_name" class="form-label">Tên màu</label>
                                    <input type="text" name="color_name"
                                        class="form-control @error('color_name') is-invalid @enderror"
                                        value="{{ old('color_name') }}" id="color_name" placeholder="Nhập tên màu">
                                    @error('color_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Color Picker -->
                                <div>
                                    <label for="colorPicker" class="form-label">Chọn mã màu</label>
                                    <input type="color" name="color_code" class="form-control form-control-color w-25"
                                        id="colorPicker" value="{{ old('color_code') }}">
                                </div>
                                @error('color_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <button type="submit" class="btn btn-primary mt-3 w-100">Tạo màu</button>
                                <a href="{{ route('colors.index') }}" class="btn btn-secondary mt-2 w-100">Quay lại</a>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection
