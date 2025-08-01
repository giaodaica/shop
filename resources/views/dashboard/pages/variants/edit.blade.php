@extends('dashboard.layouts.layout')

@section('main-content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Chỉnh sửa biến thể sản phẩm</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('variants.index') }}">Danh sách biến thể</a>
                                </li>
                                <li class="breadcrumb-item active">Chỉnh sửa biến thể</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <!-- Hiển thị lỗi chung -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('variants.update', $variant->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @if ($isProductDeleted)
                    <div class="alert alert-warning mb-3">
                        Sản phẩm liên quan đã bị xóa, bạn không thể chỉnh sửa biến thể này.
                    </div>
                @endif

                <div class="row g-4">

                    <!-- Sản phẩm (không sửa được) -->
                    <div class="col-lg-12">
                        <h6 class="fw-semibold">Sản phẩm</h6>
                        <select class="form-select" disabled>
                            @if ($product)
                                <option value="{{ $product->id }}" selected>{{ $product->name }}</option>
                            @else
                                <option value="" selected>Sản phẩm đã bị xóa</option>
                            @endif
                        </select>
                    </div>

                    <!-- Chọn Size -->
                    <div class="col-lg-6">
                        <h6 class="fw-semibold">Size</h6>
                        <select class="form-select" name="size_id" {{ $isProductDeleted ? 'disabled' : 'required' }}>
                            @foreach ($sizes as $size)
                                <option value="{{ $size->id }}"
                                    {{ old('size_id', $variant->size_id) == $size->id ? 'selected' : '' }}>
                                    {{ $size->size_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('size_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Chọn Color -->
                    <div class="col-lg-6">
                        <h6 class="fw-semibold">Màu</h6>
                        <select class="form-select" name="color_id" {{ $isProductDeleted ? 'disabled' : 'required' }}>
                            @foreach ($colors as $color)
                                <option value="{{ $color->id }}"
                                    {{ old('color_id', $variant->color_id) == $color->id ? 'selected' : '' }}>
                                    {{ $color->color_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('color_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Các trường khác -->
                    <div class="col-lg-4">
                        <h6 class="fw-semibold">Tên biến thể</h6>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $variant->name) }}"
                            {{ $isProductDeleted ? 'disabled' : '' }}>
                        @error('name')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-4">
                        <h6 class="fw-semibold">Giá nhập</h6>
                        <input type="number" name="import_price" class="form-control" min="0"
                            value="{{ old('import_price', intval($variant->import_price)) }}"
                            {{ $isProductDeleted ? 'disabled' : '' }}>
                        @error('import_price')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-4">
                        <h6 class="fw-semibold">Giá niêm yết</h6>
                        <input type="number" name="listed_price" class="form-control" min="0"
                            value="{{ old('listed_price', intval($variant->listed_price)) }}"
                            {{ $isProductDeleted ? 'disabled' : '' }}>
                        @error('listed_price')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-4">
                        <h6 class="fw-semibold">Giá bán</h6>
                        <input type="number" name="sale_price" class="form-control" min="0"
                            value="{{ old('sale_price', intval($variant->sale_price)) }}"
                            {{ $isProductDeleted ? 'disabled' : '' }}>
                        @error('sale_price')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-4">
                        <h6 class="fw-semibold">Số lượng kho</h6>
                        <input type="number" name="stock" class="form-control" min="0"
                            value="{{ old('stock', $variant->stock) }}" {{ $isProductDeleted ? 'disabled' : '' }}>
                        @error('stock')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-8">
                        <h6 class="fw-semibold">Ảnh biến thể (bạn có thể chọn ảnh mới để thay thế)</h6>
                        <input type="file" id="variant-image-input" name="variant_image_url"
                            class="form-control @error('variant_image_url') is-invalid @enderror" accept="image/*"
                            {{ $isProductDeleted ? 'disabled' : '' }}>
                        @error('variant_image_url')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror

                        @if ($variant->variant_image_url)
                            <img id="variant-image-preview" src="{{ asset($variant->variant_image_url) }}"
                                alt="Ảnh biến thể" style="max-width: 300px; margin-top: 10px; display: block;">
                        @else
                            <img id="variant-image-preview" src="" alt="Ảnh biến thể"
                                style="max-width: 300px; margin-top: 10px; display: none;">
                        @endif
                    </div>

                    <div class="col-lg-12 mt-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_show" name="is_show" value="1"
                                {{ old('is_show', $variant->is_show) ? 'checked' : '' }}
                                {{ $isProductDeleted ? 'disabled' : '' }}>
                            <label class="form-check-label" for="is_show">Hiển thị biến thể</label>
                        </div>
                        @error('is_show')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    @if (!$isProductDeleted)
                        <div class="col-lg-12 mt-3">
                            <button type="submit" class="btn btn-primary">Cập nhật biến thể</button>
                            <a href="{{ route('variants.index') }}" class="btn btn-secondary">Hủy</a>
                        </div>
                    @else
                        <div class="col-lg-12 mt-3">
                            <a href="{{ route('variants.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
                        </div>
                    @endif

                </div>
            </form>


        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <script>
                        document.write(new Date().getFullYear())
                    </script> © Velzon.
                </div>
                <div class="col-sm-6">
                    <div class="text-sm-end d-none d-sm-block">
                        Design & Develop by Themesbrand
                    </div>
                </div>
            </div>
        </div>
    </footer>
@endsection

@section('js-content')
    <script src="{{ asset('admin/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('admin/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('admin/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('admin/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <script src="{{ asset('admin/js/plugins.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script src="{{ asset('admin/js/app.js') }}"></script>
    <script>
        document.getElementById('variant-image-input')
            .addEventListener('change', function(event) {
                const preview = document.getElementById('variant-image-preview');
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            });
    </script>
@endsection
