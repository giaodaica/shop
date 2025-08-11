<div class="tab-pane fade show active" id="current-sale" role="tabpanel">
    <div class="row">
        @forelse($sale as $item)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="product-card">
                    <a href="{{ route('home.show', ['slug' => $item->product->slug, 'flash_item_id' => $item->id]) }}"
                        class="text-decoration-none text-dark">
                        <div class="product-image">
                            <img src="{{ $item->product->image_url }}" alt="{{ $item->name }}"
                                 style="width:100%;height:150px;object-fit:contain;">
                        </div>
                        <div class="product-name-truncate">
                            {{ $item->name }}
                        </div>
                        <div class="product-price">
                            <span class="current-price">{{ number_format($item->price_at_flash_sale) }}₫</span>
                            <span class="original-price">{{ number_format($item->sale_price) }}₫</span>
                            <span class="discount-badge">
                                -{{ $item->flashSale->discount ?? ($item->flash_sale_discount ?? 0) }}%
                            </span>
                        </div>
                    </a>

                    @if ($item->is_active)
                        <div class="stock-info">
                            ⚡ Còn {{ $item->max_quantity }}/{{ $item->stock_quantity }} suất
                        </div>

                        @if ($item->max_quantity - $item->sold_quantity > 0)
                            {{-- <form action="{{ route('cart.add', ['id' => $item->id]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $item->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button class="btn buy-btn" name="flash_sale" value="1">Mua ngay</button>
                            </form> --}}
                        @else
                            <button class="btn buy-btn disabled" disabled>Hết hàng</button>
                        @endif
                    @else
                        <button class="btn btn-secondary" disabled>🕐 Sắp diễn ra</button>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-12 text-center">Không có flash sale đang diễn ra.</div>
        @endforelse
    </div>
</div>
