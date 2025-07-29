<div class="tab-pane fade show active" id="current-sale" role="tabpanel">
    <div class="row">
        @forelse($sale as $item)
    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
        <a href="{{ route('home.show',  ['slug' => $item->product->slug, 'flash_item_id' => $item->id]) }}" class="text-decoration-none text-dark">
            <div class="product-card">
                <div class="product-image">
                    <img src="{{ $item->variant_image_url }}" alt="{{ $item->name }}"
                        style="width:100%;height:150px;object-fit:contain;">
                </div>
                
                <div class="product-name-truncate">
                    {{ $item->name }}
                </div>
                <div class="product-price">
                    <span class="current-price">{{ number_format($item->price_at_flash_sale) }}₫</span>
                    <span class="original-price">{{ number_format($item->sale_price) }}₫</span>
                    <span class="discount-badge">-{{ $item->flashSale->discount ?? ($item->flash_sale_discount ?? 0) }}%</span>
                </div>

                @if($item->is_active)
                    <div class="stock-info">
                        ⚡ Còn
                        {{ $item->max_quantity - $item->sold_quantity }}/{{ $item->max_quantity }} suất
                    </div>
                    <button class="btn buy-btn">Mua ngay</button>
                @else
                    <button class="btn btn-secondary" disabled>🕐 Sắp diễn ra</button>
                @endif
            </div>
        </a>
    </div>
@empty
    <div class="col-12 text-center">Không có flash sale đang diễn ra.</div>
@endforelse


    </div>
</div>
