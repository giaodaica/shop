@extends('dashboard.layouts.layout')

@section('main-content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- Page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Chỉnh sửa sản phẩm</h4>
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
            <form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data"
                class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <!-- Tên sản phẩm -->
                                <div class="mb-3">
                                    <label class="form-label">Tên sản phẩm</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="product-name" name="name"
                                        value="{{ old('name') !== null ? old('name') : $product->name }}" maxlength="255">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Slug -->
                                <div class="mb-3">
                                    <label class="form-label">Slug</label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                        id="product-slug" name="slug"
                                        value="{{ old('slug') !== null ? old('slug') : $product->slug }}" readonly>
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Mô tả -->
                                <div class="mb-3">
                                    <label class="form-label">Mô tả sản phẩm</label>
                                    <textarea id="description-editor" class="form-control @error('description') is-invalid @enderror" name="description"
                                        rows="3">{{ old('description', $product->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Danh mục -->
                                <div class="mb-3">
                                    <label class="form-label">Danh mục</label>
                                    <select class="form-select @error('category_id') is-invalid @enderror"
                                        name="category_id">
                                        <option value="">-- Chọn danh mục --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Ảnh hiện tại -->
                                <div id="product-image-preview" class="mb-3">
                                    @if ($product->image_url)
                                        <img src="{{ asset($product->image_url) }}" alt="preview" class="img-thumbnail"
                                            style="max-height: 300px;">
                                        <input type="hidden" name="temp_image_url"
                                            value="{{ asset($product->image_url) }}">
                                    @endif
                                </div>

                                <!-- Ảnh mới -->
                                <div class="mb-3">
                                    <label class="form-label">Ảnh mới</label>
                                    <div class="input-group">
                                        <input type="file" class="form-control" id="product-image" accept="image/*">
                                        <label class="input-group-text" for="product-image">Thêm ảnh</label>
                                    </div>
                                    <input type="hidden" name="temp_image_url" id="temp_image_url"
                                        value="{{ old('temp_image_url') }}">
                                </div>

                                <!-- Biến thể -->
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Biến thể sản phẩm</h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="variant-container">
                                            @foreach ($product->variants as $index => $variant)
                                                @include('dashboard.pages.product.partials.variant', [
                                                    'index' => $index,
                                                    'variant' => $variant,
                                                    'editMode' => true,
                                                    'sizes' => $sizes,
                                                    'colors' => $colors,
                                                ])
                                            @endforeach
                                        </div>
                                        <div id="variant-message"
                                            class="alert alert-danger alert-dismissible fade show d-none" role="alert">
                                            <span id="variant-text"></span>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                        <p class="text-muted fst-italic">(*) Mỗi tổ hợp Màu + Size tạo ra biến thể với tên:
                                            <strong>Tên + Màu + Size</strong>
                                        </p>
                                        <div class="text-center">
                                            <button type="button" id="add-variant" class="btn btn-success btn-sm">+ Thêm
                                                biến thể</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit -->
                                <div class="d-flex justify-content-start my-4">
                                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                                    <a href="{{ route('products.index') }}" class="btn btn-danger ms-2">Quay lại</a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js-content')
    <template id="variant-template">
        @include('dashboard.pages.product.partials.variant', [
            'index' => '__INDEX__',
            'variant' => [],
            'sizes' => $sizes,
            'colors' => $colors,
        ])
    </template>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/35.3.2/classic/ckeditor.js"></script>
    <script>
        let index = {{ count($product->variants ?? []) }};

        $('#add-variant').on('click', function() {
            let template = $('#variant-template').html().replace(/__INDEX__/g, index);
            $('#variant-container').append(template);
            index++;
        });

        $(document).on('change', '#product-image', function() {
            let file = this.files[0];
            let formData = new FormData();
            formData.append('image', file);
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: '{{ route('products.uploadTempImage') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#product-image-preview').html(
                        `<img src="${response.url}" class="img-thumbnail" style="max-height: 300px;">`
                    );
                    $('input[name="temp_image_url"]').val(response.url);
                },
                error: function() {
                    alert('Lỗi khi tải ảnh sản phẩm.');
                }
            });
        });

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

        $(document).on('click', '.remove-variant', function() {
            if ($('.variant-item').length > 1) {
                $(this).closest('.variant-item').remove();
                $('#variant-message').addClass('d-none'); // ẩn alert
            } else {
                $('#variant-text').text('Sản phẩm phải có ít nhất một biến thể.');
                $('#variant-message').removeClass('d-none'); // hiện alert lại
            }
        });




        ClassicEditor.create(document.querySelector('#description-editor')).catch(error => console.error(error));

        function removeVietnameseTones(str) {
            return str.normalize('NFD').replace(/\u0300-\u036f/g, '').replace(/đ/g, 'd').replace(/Đ/g, 'D');
        }

        $(document).on('input', 'input[name="name"]', function() {
            let name = $(this).val();
            let slug = removeVietnameseTones(name).toLowerCase().trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/[\s-]+/g, '-')
                .replace(/^-+|-+$/g, '');
            $('input[name="slug"]').val(slug);
        });

        // Validate form before submit
    </script>
@endsection
