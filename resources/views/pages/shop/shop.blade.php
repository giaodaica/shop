@extends('layouts.layout')
@section('content')
    <section class="top-space-margin half-section bg-gradient-very-light-gray">
        <div class="container">
            <div class="row align-items-center justify-content-center"
                data-anime='{ "el": "childs", "translateY": [-15, 0], "opacity": [0,1], "duration": 300, "delay": 0, "staggervalue": 100, "easing": "easeOutQuad" }'>
                <div class="col-12 col-xl-8 col-lg-10 text-center position-relative page-title-extra-large">
                    <h1 class="alt-font fw-600 text-dark-gray mb-10px">Cửa hàng</h1>
                </div>
                <div class="col-12 breadcrumb breadcrumb-style-01 d-flex justify-content-center">
                    <ul>
                        <li><a href="{{route('home')}}">Trang chủ</a></li>
                        <li>Cửa hàng</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- end section -->
    <!-- start section -->
    <section class="pt-0 ps-6 pe-6 lg-ps-2 lg-pe-2 sm-ps-0 sm-pe-0">
        <div class="container-fluid">
            <div class="row flex-row-reverse">
                <div class="col-xxl-10 col-lg-9 ps-5 md-ps-15px md-mb-60px">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div id="search-summary" class="text-muted fs-15 mb-0"></div>
                        </div>


                    </div>

                    @if ($products->isEmpty())
                        <div class="text-center py-5">
                            <h6 class="alt-font fw-500 text-dark-gray mb-3">Rất tiếc, chúng tôi không tìm thấy sản phẩm nào
                                phù hợp.</h6>

                        </div>
                    @else
                        <ul
                            class="shop-modern shop-wrapper grid-loading grid grid-5col lg-grid-4col md-grid-3col sm-grid-2col xs-grid-1col gutter-extra-large text-center">
                            <li class="grid-sizer"></li>
                            @if (!request('q'))
                                @if ($products->isEmpty())
                                    <div class="no-products-message">
                                        <i class="fas fa-search"></i>
                                        <h3>Không tìm thấy sản phẩm</h3>
                                        <p>Không có sản phẩm nào phù hợp với bộ lọc của bạn. Vui lòng thử lại với các tiêu
                                            chí khác.</p>
                                    </div>
                                @else
                                    @foreach ($products as $product)
                                        <!-- start shop item -->
                                        <li class="grid-item">
                                            <div class="shop-box mb-10px">
                                                <div class="shop-image mb-20px">
                                                    @php
                                                        $variant = $product->variants->first();
                                                    @endphp
                                                    <a href="{{ route('home.show', $product->slug) }}">
                                                        <img src="{{ asset(optional($product)->image_url) }}"
                                                            alt="{{ $product->name }}">
                                                        <div class="shop-overlay bg-gradient-gray-light-dark-transparent">
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="shop-footer text-start">
                                                    <a href="{{ route('home.show', $product->slug) }}"
                                                        class="alt-font text-dark-gray fs-19 fw-500 product-name-truncate">{{ $product->name }}</a>
                                                    <div class="price lh-22 fs-16">

                                                        @if ($variant && $variant->sale_price < $variant->listed_price)
                                                            <div class="product-price">
                                                                {{ number_format($variant->sale_price) }} ₫
                                                                <span
                                                                    class="product-old-price">{{ number_format($variant->listed_price) }}
                                                                    ₫</span>
                                                            </div>
                                                        @elseif($variant)
                                                            <span
                                                                class="product-price">{{ number_format($variant->listed_price) }}
                                                                ₫</span>
                                                        @endif
                                                    </div>

                                                </div>
                                            </div>
                                        </li>
                                        <!-- end shop item -->
                                    @endforeach
                                @endif
                            @endif
                        </ul>
                        @if ($products->lastPage() > 1)
                        <div class="w-100 d-flex mt-4 justify-content-center md-mt-30px">
                            <ul class="pagination pagination-style-01 fs-13 fw-500 mb-0">
                                <!-- Nút Previous -->
                                <li class="page-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $products->previousPageUrl() }}"
                                        {{ $products->onFirstPage() ? 'aria-disabled="true"' : '' }}>
                                        <i class="feather icon-feather-arrow-left fs-18 d-xs-none"></i>
                                    </a>
                                </li>

                                <!-- Các trang -->
                                @for ($i = 1; $i <= $products->lastPage(); $i++)
                                    <li class="page-item {{ $products->currentPage() == $i ? 'active' : '' }}">
                                        <a class="page-link"
                                            href="{{ $products->url($i) }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</a>
                                    </li>
                                @endfor

                                <!-- Nút Next -->
                                <li class="page-item {{ $products->hasMorePages() ? '' : 'disabled' }}">
                                    <a class="page-link" href="{{ $products->nextPageUrl() }}"
                                        {{ $products->hasMorePages() ? '' : 'aria-disabled="true"' }}>
                                        <i class="feather icon-feather-arrow-right fs-18 d-xs-none"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endif

                    @endif
                </div>
                <div class="col-xxl-2 col-lg-3 shop-sidebar"
                    data-anime='{ "el": "childs", "translateY": [-15, 0], "opacity": [0,1], "duration": 300, "delay": 0, "staggervalue": 300, "easing": "easeOutQuad" }'>
                    <form action="{{ route('home.shop') }}" method="GET" id="filterForm">
                        <div class="mb-30px">
                            <div class="d-flex align-items-center mb-10px">
                                <span class="alt-font fw-500 fs-19 text-dark-gray d-block">Danh Mục</span>
                                <i class="fa-solid fa-chevron-down ms-auto toggle-filter" style="cursor: pointer;"></i>
                            </div>
                            <ul class="shop-filter category-filter fs-16">
                                @php
                                    $totalCategories = count($categories);
                                    $initialShow = 4;
                                @endphp
                                @foreach ($categories as $index => $category)
                                    @php
                                        $selected =
                                            is_array(request('categories')) &&
                                            in_array($category->id, request('categories'));
                                    @endphp
                                    <li class="{{ $index >= $initialShow ? 'hidden-category' : '' }}">
                                        <label class="checkbox-container">
                                            <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                                {{ $selected ? 'checked' : '' }}>
                                            <span class="custom-checkbox {{ $selected ? 'checked' : '' }}"></span>
                                            {{ $category->name }}
                                        </label>
                                        <span class="item-qty">{{ $category->products_count }}</span>
                                    </li>
                                @endforeach
                                @if ($totalCategories > $initialShow)
                                    <li class="show-more-categories">
                                        <button type="button" class="btn btn-link p-0 text-decoration-none">Xem
                                            thêm</button>
                                    </li>
                                    <li class="collapse-categories" style="display: none;">
                                        <button type="button" class="btn btn-link p-0 text-decoration-none">Thu
                                            gọn</button>
                                    </li>
                                @endif
                            </ul>

                        </div>
                        <div class="mb-30px">
                            <div class="d-flex align-items-center mb-10px">
                                <span class="alt-font fw-500 fs-19 text-dark-gray d-block">Mức Giá</span>
                                <i class="fa-solid fa-chevron-down ms-auto toggle-filter" style="cursor: pointer;"></i>
                            </div>

                            {{-- Bộ lọc radio sẵn có --}}
                            <ul class="shop-filter price-filter fs-16">
                                @php
                                    $priceRanges = [
                                        '' => 'Tất cả',
                                        '0-100000' => 'Dưới 100k',
                                        '100000-300000' => '100K - 300k',
                                        '300000-500000' => '300k - 500k',
                                        '500000-1000000' => '500k - 1 triệu',
                                        '1000000-999999999' => 'Trên 1 triệu',
                                    ];
                                    $selectedPriceRange = request('price_range', ''); // Default to empty string
                                @endphp
                                @foreach ($priceRanges as $range => $label)
                                    <li>
                                        <label class="checkbox-container">
                                            <input type="radio" name="price_range" value="{{ $range }}"
                                                {{ $selectedPriceRange === $range ? 'checked' : '' }}>
                                            <span
                                                class="custom-checkbox {{ $selectedPriceRange === $range ? 'checked' : '' }}"></span>
                                            {{ $label }}
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="mb-30px">
                            <div class="d-flex align-items-center mb-10px">
                                <span class="alt-font fw-500 fs-19 text-dark-gray d-block">Màu Sắc</span>
                                <i class="fa-solid fa-chevron-down ms-auto toggle-filter" style="cursor: pointer;"></i>
                            </div>
                            <ul class="shop-filter color-filter fs-16">
                                @foreach ($colors as $color)
                                    @php
                                        $selected =
                                            is_array(request('colors')) && in_array($color->id, request('colors'));
                                    @endphp
                                    <li>
                                        <label class="checkbox-container">
                                            <input type="checkbox" name="colors[]" value="{{ $color->id }}"
                                                {{ $selected ? 'checked' : '' }}>
                                            <span class="custom-checkbox {{ $selected ? 'checked' : '' }}"></span>
                                            {{ $color->color_name }}
                                        </label>
                                        <span class="item-qty">{{ $color->product_variants_count }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="mb-30px">
                            <div class="d-flex align-items-center mb-10px">
                                <span class="alt-font fw-500 fs-19 text-dark-gray d-block">Kích Cỡ</span>
                                <i class="fa-solid fa-chevron-down ms-auto toggle-filter" style="cursor: pointer;"></i>
                            </div>
                            <ul class="shop-filter size-filter fs-16">
                                @foreach ($sizes as $size)
                                    @php
                                        $selected = is_array(request('sizes')) && in_array($size->id, request('sizes'));
                                    @endphp
                                    <li>
                                        <label class="checkbox-container">
                                            <input type="checkbox" name="sizes[]" value="{{ $size->id }}"
                                                {{ $selected ? 'checked' : '' }}>
                                            <span class="custom-checkbox {{ $selected ? 'checked' : '' }}"></span>
                                            {{ $size->size_name }}
                                        </label>
                                        <span class="item-qty">{{ $size->product_variants_count }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <link href="{{ asset('assets/css/shop.css') . '?v=' . filemtime(public_path('assets/css/shop.css')) }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/shop/shop.js') . '?v=' . filemtime(public_path('assets/js/shop/shop.js')) }}" defer></script>

@endpush
