@extends('dashboard.layouts.layout')

@section('main-content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- Tiêu đề -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Chi Tiết Sản Phẩm</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="#">Quản lý sản phẩm</a></li>
                                <li class="breadcrumb-item active">Chi Tiết Sản Phẩm</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin sản phẩm -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mt-0">
                                <div class="d-flex flex-wrap">
                                    {{-- Ảnh --}}
                                    <div class="me-4 mb-3">
                                        @if ($product->image_url)
                                            <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}"
                                                class="img-thumbnail"
                                                style="max-height: 250px; max-width: 250px; object-fit: contain;">
                                        @else
                                            <p class="text-muted">Không có hình ảnh.</p>
                                        @endif
                                    </div>

                                    {{-- Thông tin --}}
                                    <div class="flex-grow-1 mb-3">
                                        <h4 class="text-break">{{ $product->name }}</h4>
                                        <div class="d-flex flex-wrap gap-2 mb-3">
                                            <div>
                                                <a href="#" class="text-primary">
                                                    {{ $product->category->name ?? 'Chưa có danh mục' }}
                                                </a>
                                            </div>
                                            <div class="vr"></div>
                                            <div class="text-muted">Ngày tạo:
                                                <span class="text-body fw-medium">
                                                    {{ $product->created_at?->format('d/m/Y') ?? 'N/A' }}
                                                </span>
                                            </div>
                                        </div>

                                        {{-- Mô tả --}}
                                        <h5>Mô tả sản phẩm:</h5>
                                        @if ($product->description)
                                            <div class="border p-3 rounded bg-light text-break">
                                                {!! $product->description !!}
                                            </div>
                                        @else
                                            <p class="text-muted">Chưa có mô tả cho sản phẩm này.</p>
                                        @endif
                                    </div>

                                    {{-- Nút sửa --}}
                                    <div class="ms-auto">
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-light"
                                            title="Chỉnh sửa">
                                            <i class="ri-pencil-fill align-bottom"></i>
                                        </a>
                                    </div>
                                </div>

                                {{-- Chi tiết khác --}}
                                <div class="row mt-4">
                                    <div class="col-lg-4 col-sm-6">
                                        <div class="p-2 border border-dashed rounded d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-title rounded bg-transparent text-success fs-24">
                                                    <i class="ri-hashtag"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="text-muted mb-1">Mã sản phẩm:</p>
                                                <h5 class="mb-0">{{ $product->id }}</h5>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-sm-6">
                                        <div class="p-2 border border-dashed rounded d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-title rounded bg-transparent text-success fs-24">
                                                    <i class="ri-price-tag-3-fill"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="text-muted mb-1">Slug:</p>
                                                <h5 class="mb-0 text-break">{{ $product->slug }}</h5>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-sm-6">
                                        <div class="p-2 border border-dashed rounded d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-title rounded bg-transparent text-success fs-24">
                                                    <i class="ri-calendar-todo-fill"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="text-muted mb-1">Ngày cập nhật:</p>
                                                <h5 class="mb-0">
                                                    {{ $product->updated_at?->format('d/m/Y') ?? 'N/A' }}
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- DANH SÁCH BIẾN THỂ --}}
                                @if ($product->variants->isNotEmpty())
                                    <hr class="my-4">
                                    <h4 class="mb-4">Biến thể sản phẩm</h4>

                                    @foreach ($product->variants as $variant)
                                        <div class="row mb-5 border-bottom pb-4">
                                            <div class="col-xl-4 col-md-6">
                                                <div class="bg-light p-2 rounded">
                                                    @if ($variant->variant_image_url)
                                                        <img src="{{ asset($variant->variant_image_url) }}"
                                                            alt="{{ $variant->name }}" class="img-fluid d-block mx-auto"
                                                            style="max-height: 250px;" />
                                                    @else
                                                        <img src="{{ asset('storage/no-image.png') }}" alt="Không có hình"
                                                            class="img-fluid d-block mx-auto" style="max-height: 250px;" />
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-xl-8">
                                                <h5>{{ $variant->name }}</h5>
                                                <p><strong>Sản phẩm cha:</strong> {{ $product->name }}</p>

                                                <div class="row mb-2">
                                                    <div class="col-md-4">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <p class="mb-0"><strong>Màu sắc:</strong>
                                                                {{ $variant->color->color_name ?? 'N/A' }}</p>
                                                            @if ($variant->color?->color_code)
                                                                <div class="border"
                                                                    style="width: 25px; height: 25px; background-color: {{ $variant->color->color_code }};">
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p><strong>Size:</strong> {{ $variant->size->size_name ?? 'N/A' }}
                                                        </p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p><strong>Trạng thái:</strong>
                                                            @if ($variant->is_show)
                                                                <span class="badge bg-success">Hiển thị</span>
                                                            @else
                                                                <span class="badge bg-danger">Ẩn</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="row mb-2">
                                                    <div class="col-md-4">
                                                        <p><strong>Giá nhập:</strong>
                                                            {{ number_format($variant->import_price, 0, ',', '.') }} VNĐ
                                                        </p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p><strong>Giá niêm yết:</strong>
                                                            {{ number_format($variant->listed_price, 0, ',', '.') }} VNĐ
                                                        </p>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <p><strong>Giá bán:</strong>
                                                            {{ number_format($variant->sale_price, 0, ',', '.') }} VNĐ</p>
                                                    </div>
                                                </div>

                                                <p><strong>Kho hàng:</strong> {{ $variant->stock }}</p>

                                                <div class="mt-2 d-flex align-items-center">
                                                    <a href="{{ route('variants.edit', $variant->id) }}"
                                                        class="btn btn-sm btn-primary me-2">Chỉnh sửa</a>
                                                    <a href="{{ route('variants.show', $variant->id) }}"
                                                        class="btn btn-sm btn-secondary me-2">Xem chi tiết</a>
                                                    @if ($variant->use_flash_sale == 0)
                                                        <form action="{{ route('addflashsale', $variant->id) }}"
                                                            method="POST" class="m-0">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success">Thêm vào
                                                                flash
                                                                sale</button>
                                                        </form>
                                                    @else
                                                    <div class="text-danger">Sản phẩm đã có trong flash sale</div>
                                                    @endif
                                                </div>

                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
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

        </div>
    </div>
@endsection
