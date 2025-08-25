<!-- start filter section -->
<div class="col-12 col-lg-3 col-md-4 filter-sidebar">
    <form action="{{ route('search.filter') }}" method="GET" id="searchFilterForm">
        <input type="hidden" name="q" value="{{ request('q') }}">
        <div class="row">
            <div class="col-12 col-sm-6 col-md-12">
                <!-- start category filter -->
                <div class="widget mb-40px">
                    <h5 class="widget-title alt-font fw-500">Danh mục</h5>
                    <div class="widget-body">
                        <ul class="category-filter list-unstyled mb-0">
                            @foreach($categories as $category)
                                <li>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $category->id }}" id="category-{{ $category->id }}"
                                            {{ is_array(request('categories')) && in_array($category->id, request('categories')) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="category-{{ $category->id }}">
                                            {{ $category->name }}
                                            <span class="item-qty">{{ $category->products_count }}</span>
                                        </label>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <!-- end category filter -->

                <!-- start size filter -->
                <div class="widget mb-40px">
                    <h5 class="widget-title alt-font fw-500">Kích thước</h5>
                    <div class="widget-body">
                        <ul class="size-filter list-unstyled mb-0">
                            @foreach($sizes as $size)
                                <li>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="sizes[]" value="{{ $size->id }}" id="size-{{ $size->id }}"
                                            {{ is_array(request('sizes')) && in_array($size->id, request('sizes')) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="size-{{ $size->id }}">
                                            {{ $size->name }}
                                            <span class="item-qty">({{ $size->products_count }})</span>
                                        </label>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <!-- end size filter -->

                <!-- start color filter -->
                <div class="widget mb-40px">
                    <h5 class="widget-title alt-font fw-500">Màu sắc</h5>
                    <div class="widget-body">
                        <ul class="color-filter list-unstyled mb-0">
                            @foreach($colors as $color)
                                <li>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="colors[]" value="{{ $color->id }}" id="color-{{ $color->id }}"
                                            {{ is_array(request('colors')) && in_array($color->id, request('colors')) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="color-{{ $color->id }}">
                                            {{ $color->name }}
                                            <span class="item-qty">({{ $color->products_count }})</span>
                                        </label>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <!-- end color filter -->

                <!-- start price filter -->
                <div class="widget mb-40px">
                    <h5 class="widget-title alt-font fw-500">Mức giá</h5>
                    <div class="widget-body">
                        <ul class="price-filter list-unstyled mb-0">
                            @php
                                $priceRanges = [
                                    '' => 'Tất cả',
                                    '0-100000' => 'Dưới 100k',
                                    '100000-300000' => '100K - 300k',
                                    '300000-500000' => '300k - 500k',
                                    '500000-1000000' => '500k - 1 triệu',
                                    '1000000-999999999' => 'Trên 1 triệu',
                                ];
                                $selectedPriceRange = request('price_range', '');
                            @endphp
                            @foreach ($priceRanges as $range => $label)
                                <li>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="price_range" value="{{ $range }}" id="price-{{ $range }}"
                                            {{ $selectedPriceRange === $range ? 'checked' : '' }}>
                                        <label class="form-check-label" for="price-{{ $range }}">
                                            {{ $label }}
                                        </label>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <!-- end price filter -->

               
            </div>
        </div>
    </form>
</div>
<!-- end filter section -->

<!-- start products section -->
<div class="col-12 col-lg-9 col-md-8">
    <div class="row">
        <div class="col-md-6">
            @if(request('q'))
                <p class="text-muted fs-15 mb-0">
                    Tìm thấy 
                    <span class="fw-bold text-dark">{{ $products->total() }}</span> 
                    kết quả với từ khoá 
                    "<span class="fw-bold text-dark">{{ Str::limit(request('q'), 30, '...') }}</span>"
                </p>
            @endif
        </div>
        <div class="col-md-6 text-md-end">
            <form action="{{ route('search.filter') }}" method="GET" class="d-flex justify-content-md-end align-items-center gap-2">
                {{-- Preserve all existing filters --}}
                @foreach(request()->except('sort') as $key => $value)
                    @if(is_array($value))
                        @foreach($value as $v)
                            <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                        @endforeach
                    @else
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
    
                
                <select name="sort" id="sort" class="form-select form-select-sm w-auto border-0 bg-light">
                    <option value="">Sắp xếp</option>
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                </select>
            </form>
        </div>
    </div>

    @if($products->isEmpty())
        <div class="text-center py-5">
            <h6 class="alt-font fw-500 text-dark-gray mb-3">Rất tiếc, chúng tôi không tìm thấy sản phẩm nào phù hợp.</h6>
        </div>
    @else
        <ul class="shop-modern shop-wrapper grid-loading grid grid-5col lg-grid-4col md-grid-3col sm-grid-2col xs-grid-1col gutter-extra-large text-center">
            <li class="grid-sizer"></li>
            
            @foreach ($products as $product)
                <!-- start shop item -->
                <li class="grid-item" data-category="{{ $product->category_id }}">
                    <div class="shop-box mb-10px">
                        @php
                        $variant = $product->variants->first();
                        @endphp
                        <div class="shop-image mb-20px">
                            <a href="{{ route('home.show', $product->slug) }}">
                                <img src="{{ asset(optional($variant)->variant_image_url) }}" alt="{{ $product->name }}">
                                <div class="shop-overlay bg-gradient-gray-light-dark-transparent"></div>
                            </a>
                        </div>
                        <div class="shop-footer text-start">
                            <a href="{{ route('home.show', $product->slug) }}" class="alt-font text-dark-gray fs-19 fw-500 product-name-truncate">{{ $product->name }}</a>
                            <div class="price lh-22 fs-16">
                                @php
                                    
                                    $rating = $product->rating ?? 0;
                                    $reviewCount = $product->review_count ?? 0;
                                @endphp
                                @if ($variant && $variant->sale_price < $variant->listed_price)
                                    <div class="product-price">
                                        {{ number_format($variant->sale_price) }} ₫
                                        <span class="product-old-price">{{ number_format($variant->listed_price) }} ₫</span>
                                    </div>
                                @elseif($variant)
                                    <span class="product-price">{{ number_format($variant->listed_price) }}₫</span>
                                @endif
                            </div>
                           
                            <div class="product-sizes" style="display: none;">
                                @foreach($product->variants as $variant)
                                    @if($variant->size && !$variant->is_out_of_stock)
                                        <span class="size" data-size="{{ $variant->size->id }}"></span>
                                    @endif
                                @endforeach
                            </div>
                            <div class="product-colors" style="display: none;">
                                @foreach($product->variants as $variant)
                                    @if($variant->color && !$variant->is_out_of_stock)
                                        <span class="color" data-color="{{ $variant->color->id }}"></span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </li>
                <!-- end shop item -->
            @endforeach
        </ul>
        
        <div class="w-100 d-flex mt-4 justify-content-center md-mt-30px">
            <ul class="pagination pagination-style-01 fs-13 fw-500 mb-0">
                <!-- Nút Previous -->
                <li class="page-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $products->previousPageUrl() }}" {{ $products->onFirstPage() ? 'aria-disabled="true"' : '' }}>
                        <i class="feather icon-feather-arrow-left fs-18 d-xs-none"></i>
                    </a>
                </li>
        
                <!-- Các trang -->
                @for ($i = 1; $i <= $products->lastPage(); $i++)
                    <li class="page-item {{ $products->currentPage() == $i ? 'active' : '' }}">
                        <a class="page-link" href="{{ $products->url($i) }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</a>
                    </li>
                @endfor
        
                <!-- Nút Next -->
                <li class="page-item {{ $products->hasMorePages() ? '' : 'disabled' }}">
                    <a class="page-link" href="{{ $products->nextPageUrl() }}" {{ $products->hasMorePages() ? '' : 'aria-disabled="true"' }}>
                        <i class="feather icon-feather-arrow-right fs-18 d-xs-none"></i>
                    </a>
                </li>
            </ul>
        </div>
    @endif
</div>
<!-- end products section -->

