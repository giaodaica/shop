<section class="ps-7 pe-7 pb-3 lg-ps-3 lg-pe-3 sm-pb-6 xs-px-0">
    <div class="container">
        <div class="row mb-5 xs-mb-8">
            <div class="col-12 text-center">
                <h2 class="alt-font text-dark-gray mb-0 ls-minus-2px">Sản phẩm bán <span class="text-highlight fw-600">chạy nhất<span class="bg-base-color h-5px bottom-2px"></span></span></h2>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <ul class="shop-modern shop-wrapper grid-loading grid grid-5col lg-grid-4col md-grid-3col sm-grid-2col xs-grid-1col gutter-extra-large text-center" data-anime='{ "el": "childs", "translateY": [-15, 0], "opacity": [0,1], "duration": 300, "delay": 0, "staggervalue": 100, "easing": "easeOutQuad" }'>
                    <li class="grid-sizer"></li>
                    @foreach($bestSellers as $product)
                    <!-- start shop item -->
                    <li class="grid-item">
                        <div class="shop-box mb-10px">
                            <div class="shop-image mb-20px">
                                <a href="{{ route('home.show', $product->slug) }}">
                                    <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}">
                                    <div class="shop-overlay bg-gradient-gray-light-dark-transparent"></div>
                                </a>
                            </div>
                            <div class="shop-footer text-start">
                                <a href="{{ route('home.show', $product->slug) }}" class="alt-font text-dark-gray fs-19 fw-500 product-name-truncate">{{ $product->name }}</a>
                                <div class="price lh-22 fs-16">
                                    @php
                                        $variant = $product->variants->first();
                                        $rating = $product->rating ?? 0; // Giả sử có trường rating
                                        $reviewCount = $product->review_count ?? 0; // Giả sử có trường review_count
                                    @endphp
                                    @if ($variant && $variant->sale_price < $variant->listed_price)
                                        {{-- <del class="text-muted">{{ number_format($variant->listed_price, 3) }}đ</del>
                                        <span class="text-danger">{{ number_format($variant->sale_price, 3) }}đ</span> --}}
                                        <div class="product-price">
                                            {{ number_format($variant->sale_price) }} ₫
                                            <span class="product-old-price">{{ number_format($variant->listed_price) }} ₫</span>
                                        </div>
                                    @elseif($variant)
                                        <span>{{ number_format($variant->listed_price) }}₫</span>
                                    @endif
                                </div>
                                <div class="rating">
                                    <span class="text-warning">★★★★★</span> {{ number_format($rating, 1) }}
                                    {{-- <div> ( đánh giá)</div> --}}
                                    {{-- <div class="rating-text"> ({{ $reviewCount }} đánh giá)</div> --}}
                                </div>
                            </div>
                        </div>
                    </li>
                    <!-- end shop item -->
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>
<style>
    .product-price {
    color: #ff6b35;
    font-size: 17px;
    font-weight: 700;
    margin-bottom: 5px;
}

.product-old-price {
    color: #999;
    text-decoration: line-through;
    font-size: 13px;
    margin-right: 10px;
}
.rating-text {
    color: #666;
    font-size: 14px;
}
</style>
