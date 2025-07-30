@extends('dashboard.layouts.layout')

@section('css-content')
    <style>
        .flash-sale-table th,
        .flash-sale-table td {
            text-align: center;
            vertical-align: middle;
        }

        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
    </style>
@endsection

@section('main-content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Sản phẩm trong Flash Sale</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="#">Hệ Thống</a></li>
                                <li class="breadcrumb-item active">Sản phẩm Flash Sale</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <!-- Dữ liệu giả -->


            <!-- Flash Sale Product Table -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Danh sách sản phẩm áp dụng Flash Sale</h5>
                            <a href="{{ route('flash-sales-items.create', $flash_sale_id) }}" class="btn btn-primary">Thêm
                                sản phẩm</a>
                        </div>
                        <hr class="my-4">
                        @foreach ($variants as $variant)
                            <div class="row mb-5 border-bottom pb-4">
                                <div class="col-xl-4 col-md-6">
                                    <div class="bg-light p-2 rounded">
                                        @if ($variant->variant_image_url)
                                            <img src="{{ asset($variant->variant_image_url) }}" alt="{{ $variant->name }}"
                                                class="img-fluid d-block mx-auto" style="max-height: 250px;" />
                                        @else
                                            <img src="{{ asset('storage/no-image.png') }}" alt="Không có hình"
                                                class="img-fluid d-block mx-auto" style="max-height: 250px;" />
                                        @endif
                                    </div>
                                </div>

                                <div class="col-xl-8">

                                    <div class="row mb-2">
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center gap-2">
                                                <p class="mb-0"><strong>Màu sắc:</strong>
                                                    {{ $variant->color_name ?? 'N/A' }}</p>
                                                @if ($variant->color?->color_code)
                                                    <div class="border"
                                                        style="width: 25px; height: 25px; background-color: {{ $variant->color->color_code }};">
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <p><strong>Size:</strong> {{ $variant->size_name ?? 'N/A' }}</p>
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-4">
                                            <p><strong>Giá nhập:</strong>
                                                {{ number_format($variant->import_price, 0, ',', '.') }} VNĐ</p>
                                        </div>
                                        <div class="col-md-4">
                                            <p><strong>Giá niêm yết:</strong>
                                                {{ number_format($variant->listed_price, 0, ',', '.') }} VNĐ</p>
                                        </div>
                                        <div class="col-md-4">
                                            <p><strong>Giá bán:</strong>
                                                {{ number_format($variant->sale_price, 0, ',', '.') }} VNĐ</p>
                                        </div>
                                        <div class="col-md-4">
                                            <p><strong>Giá trong flash sale</strong>
                                                {{ number_format($variant->price_at_flash_sale, 0, ',', '.') }} VNĐ</p>
                                        </div>
                                    </div>

                                    <p><strong>Số sản phẩm bán trong flash sale:</strong> {{ $variant->max_quantity }}</p>
                                    <p><strong>Số sản phẩm đã bán:</strong> {{ $variant->sold_quantity }}</p>

                                    <div class="d-flex gap-2 justify-content">
                                        <a href="{{ route('variants.edit', $variant->product_variant_id) }}"
                                            class="btn btn-sm btn-primary me-1">Chỉnh sửa</a>
                                       <form action="{{route('remove-items-flashsale',$variant->id)}}" method="post">
                                        @csrf
                                        <input type="hidden" name="flash_sale" value="{{$flash_sale_id}}" id="">
                                        <button class="btn btn-sm btn-secondary">Xóa</button>
                                       </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content gap-2">
                <a href="{{ route('flash-sale') }}" class="btn btn-success">Quay Lại</a>
                @switch($data_flash_sale->status)
                    @case('upcoming')
                        <form action="{{ route('active-flash-sale', $flash_sale_id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="key" value="upcoming">
                            <button class="btn btn-success">Khởi động</button>
                        </form>
                    @break

                    @case('active')
                        <form action="{{ route('active-flash-sale', $flash_sale_id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="key" value="active">
                            <button class="btn btn-danger">Kết Thúc</button>
                        </form>
                    @break

                    @default
                @endswitch
            </div>
        </div>
    </div>
@endsection

@section('js-content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: "{{ session('success') }}",
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                html: `{!! session('error') !!}`, // Dùng `html:` thay vì `text:`
            });
        @endif
    </script>
@endsection
