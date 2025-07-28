<li class="grid-sizer"></li>
@foreach ($flashSales as $flashSale)
    @foreach ($flashSale->itemsWithProduct as $item)
        <!-- start shop item -->
        <li class="grid-item">
            <div class="shop-box mb-10px">
                <div class="product-image mb-20px">
                    <a href="">
                        <img src="{{ asset($item->variant_image_url) }}" alt="{{ $item->name }}">
                        <div class="shop-overlay bg-gradient-gray-light-dark-transparent"></div>
                    </a>
                </div>
                <div class="shop-footer text-start">
                    <a href=""
                       class="alt-font text-dark-gray fs-19 fw-500 product-name-truncate">{{ $item->name }}</a>
                    <div class="price lh-22 fs-16">
                        <div class="product-price">
                            {{ number_format($item->price_at_flash_sale) }}₫
                            <span class="product-old-price">{{ number_format($item->sale_price) }}₫</span>
                        </div>
                        
                        <div class="product-status flash-sale-progress">
                            <span class="fire-icon">🔥</span>
                            @if (isset($type) && $type === 'upcoming')
                                <span class="progress-label text-warning">Sắp diễn ra</span>
                            @else
                            <div class="product-badge flash-sale-badge">-{{ $flashSale->discount }}%</div>
                                @if ($item->sold_quantity == 0)
                                    <span class="progress-label">Vừa mở bán</span>
                                @else
                                    <span class="progress-label">
                                        Còn {{ $item->max_quantity - $item->sold_quantity }}/{{ $item->max_quantity }} suất
                                    </span>
                                @endif
                            @endif
                        </div>
                        @if (isset($type) && $type === 'upcoming')
                            <button class="buy-now-btn" disabled style="opacity:0.6;cursor:not-allowed;">
                                Sắp mở bán
                            </button>
                        @else
                            <button class="buy-now-btn"
                                    data-product-id="{{ $item->product_variant_id }}"
                                    data-flash-sale-id="{{ $flashSale->id }}">
                                Mua ngay
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </li>
        <!-- end shop item -->
    @endforeach
@endforeach
