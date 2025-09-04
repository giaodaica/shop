@extends('dashboard.layouts.layout')

@section('main-content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- Page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Thêm mới sản phẩm</h4>
                    </div>
                </div>
            </div>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <!-- Form -->
            <form id="createproduct-form" autocomplete="off" class="needs-validation" novalidate method="POST"
                action="{{ route('products.store') }}">
                @csrf


                <div class="row">

                    <!-- Left column: Product Info -->
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">

                                <!-- Tên sản phẩm -->
                                <div class="mb-3">
                                    <label class="form-label">Tên sản phẩm</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" placeholder="Nhập tên sản phẩm" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Slug -->
                                <div class="mb-3">
                                    <label class="form-label">Slug</label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                        name="slug" placeholder="Slug sẽ được tự động tạo" value="{{ old('slug') }}"
                                        readonly>
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Mô tả -->
                                <div class="mb-3">
                                    <label class="form-label">Mô tả sản phẩm</label>
                                    <textarea id="description-editor" class="form-control @error('description') is-invalid @enderror" name="description"
                                        rows="3" placeholder="Nhập mô tả sản phẩm">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Danh mục -->
                                <div class="mb-3">
                                    <label class="form-label">Danh mục sản phẩm</label>
                                    <select class="form-select @error('category_id') is-invalid @enderror"
                                        name="category_id" required>
                                        <option value="">-- Chọn danh mục --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Ảnh đại diện -->
                                <div class="mb-3">
                                    <label class="form-label">Hình ảnh</label>
                                    <div class="input-group">
                                        <input type="file" class="form-control" id="product-image">
                                        <label class="input-group-text" for="product-image">Thêm ảnh</label>
                                    </div>
                                    <input type="hidden" name="temp_image_url" id="temp_image_url"
                                        value="{{ old('temp_image_url') }}">
                                    <small id="product-image-name" class="form-text text-muted"></small>
                                    <div id="product-image-preview" class="mt-2">
                                        @if (old('temp_image_url'))
                                            <img src="{{ old('temp_image_url') }}" alt="Preview" width="150">
                                        @endif
                                    </div>
                                    @error('temp_image_url')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>


                            </div>
                        </div>
                    </div>

                    <!-- Biến thể -->
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Sản phẩm biến thể</h5>
                            </div>
                            <div class="card-body">

                                <div id="variant-container">
                                    @php $variants = old('variants', []); @endphp
                                    @foreach ($variants as $index => $variant)
                                        @include('dashboard.pages.product.partials.variant', [
                                            'index' => $index,
                                            'variant' => $variant,
                                        ])
                                    @endforeach

                                    @if (count($variants) == 0)
                                        @include('dashboard.pages.product.partials.variant', [
                                            'index' => 0,
                                        ])
                                    @endif
                                </div>

                                <p class="text-muted fst-italic">(*) Mỗi tổ hợp màu + size sẽ tạo ra một biến thể tự động
                                    với tên dạng: <strong>Tên sản phẩm + Màu + Size</strong></p>

                                <div class="text-center">
                                    <button type="button" id="add-variant" class="btn btn-success btn-sm">+ Thêm biến
                                        thể</button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Nút submit -->
                    <div class="d-flex justify-content-start mb-5">
                        <button type="submit" class="btn btn-primary">THÊM MỚI</button>
                        <a href="{{ route('products.index') }}" class="btn btn-danger ms-2">QUAY LẠI</a>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection

@section('js-content')
    <style>
        .invalid-feedback {
            min-height: 18px;
            font-size: 13px;
        }

        .variant-item {
            border-bottom: 1px dashed #ccc;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }

        .variant-separator {
            border-top: 1px dashed #bbb;
            margin-top: 25px;
            margin-bottom: 25px;
        }

        .variant-error-message {
            font-size: 0.875em;
            color: #dc3545;
            display: block;
            margin-top: 4px;
        }

        .alert-danger {
            background-color: #fff4f4;
            border: 1px solid #f8d7da;
            color: #842029;
            font-size: 0.9rem;
            border-radius: 6px;
        }

        .alert-danger ul {
            padding-left: 1rem;
            margin: 0;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/35.3.2/classic/ckeditor.js"></script>
    <script>
        let index = {{ count(old('variants', [])) > 0 ? count(old('variants', [])) : 1 }};

        ClassicEditor
            .create(document.querySelector('#description-editor'))
            .catch(error => {
                console.error(error);
            });

        // Hàm loại dấu tiếng Việt
        function removeVietnameseTones(str) {
            str = str.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
            str = str.replace(/đ/g, 'd').replace(/Đ/g, 'D');
            return str;
        }

        // Tự động tạo slug khi nhập tên sản phẩm
        $(document).on('input', 'input[name="name"]', function() {
            let name = $(this).val();
            let slug = removeVietnameseTones(name).toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/[\s-]+/g, '-')
                .replace(/^-+|-+$/g, '');

            $('input[name="slug"]').val(slug);
        });

        // Thêm biến thể
        $('#add-variant').on('click', function() {
            $.ajax({
                url: '{{ route('products.create') }}',
                method: 'GET',
                success: function(res) {
                    let newVariantHtml = `@include('dashboard.pages.product.partials.variant', ['index' => '__INDEX__'])`;
                    newVariantHtml = newVariantHtml.replace(/__INDEX__/g, index);
                    $('#variant-container').append(newVariantHtml);
                    index++;
                }
            });
        });

        // Upload ảnh sản phẩm
        $(document).on('change', '#product-image', function() {
            let formData = new FormData();
            formData.append('image', this.files[0]);
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: '{{ route('products.uploadTempImage') }}',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#temp_image_url').val(response.url);
                    $('#product-image-preview').html(
                        `<img src="${response.url}" alt="Preview" width="150">`);
                }
            });
        });

        // Upload ảnh biến thể
        $(document).on('change', '.variant-image-input', function() {
            let variantIndex = $(this).data('index');
            let formData = new FormData();
            formData.append('variant_image', this.files[0]);
            formData.append('_token', '{{ csrf_token() }}');

            let previewSelector = `#variant-image-preview-${variantIndex}`;

            $.ajax({
                url: '{{ route('products.uploadTempVariantImage') }}',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $(`input[name="variants[${variantIndex}][temp_variant_image_url]"]`).val(response
                        .url);
                    $(previewSelector).html(`<img src="${response.url}" alt="Preview" width="100">`);
                }
            });
        });
        // Xóa biến thể
        $(document).on('click', '.remove-variant', function() {
            $(this).closest('.variant-item').remove();
        });

        $(document).on('change', '.color-select', function() {
            let selectedOption = $(this).find('option:selected');
            let colorCode = selectedOption.data('color-code') || '#fff';
            let previewBoxId = $(this).data('color-preview');
            $('#' + previewBoxId).css('background-color', colorCode);
        });
    </script>
@endsection
