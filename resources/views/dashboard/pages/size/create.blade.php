@extends('dashboard.layouts.layout')

@section('main-content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- Tiêu đề trang -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Tạo size</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('sizes.index') }}">Size</a></li>
                                <li class="breadcrumb-item active">Tạo size</li>
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
            <form action="{{ route('sizes.store') }}" method="POST">
                @csrf
                <div class="row">

                    {{-- Form nhập --}}
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">

                                {{-- Tên size --}}
                                <div class="mb-3">
                                    <label for="size_name" class="form-label">Tên size</label>
                                    <input type="text" name="size_name"
                                        class="form-control @error('size_name') is-invalid @enderror"
                                        value="{{ old('size_name') }}" id="size_name" placeholder="Nhập tên size">
                                    @error('size_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>



                                <button type="submit" class="btn btn-primary mt-3 w-100">Tạo size</button>
                                <a href="{{ route('sizes.index') }}" class="btn btn-secondary mt-2 w-100">Quay lại</a>
                            </div>
                        </div>
                    </div>

                    {{-- Nếu size không cần ảnh thì bỏ phần upload --}}
                    {{-- Nếu cần, có thể thêm --}}
                </div>
            </form>
        </div>
    </div>
@endsection
