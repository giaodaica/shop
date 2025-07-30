<!-- Tối ưu hóa CSS - Gộp các style trùng lặp và sử dụng CSS variables -->

<!-- HTML tối ưu hóa -->
@if ($flashSales->count() > 0)
<link rel="stylesheet" href="{{ asset('assets/css/flash_sale.css') }}">
    <div class="container mt-4">
        <div class="flash-sale-container">
            <!-- Header -->
            <div class="flash-sale-header">
                <div class="sale-badges">
                    <div class="flash-badge">Flash Sale</div>
                    <div class="flash-badge">Giá Sốc</div>
                    <div class="flash-badge">Online Only</div>
                </div>
            </div>

            <!-- Flash Sale Cards -->
            <div class="container py-4">
                <div class="flash-sale-wrapper">
                    <div class="d-flex flex-wrap gap-3 mb-3 justify-content-center">
                        @foreach ($flashSales as $index => $sale)
                            <button class="btn btn-show-products {{ $index === 0 ? 'default-tab' : '' }}"
                                data-id="{{ $sale->id }}">
                                <div class="flash-sale-card {{ $sale->is_active ? 'active' : '' }}">
                                    <div class="sale-header">
                                        <div class="sale-label {{ $sale->is_active ? 'active-label' : '' }}">
                                            <div class="sale-icon {{ $sale->is_active ? 'active-icon' : 'upcoming-icon' }}">
                                                {{ $sale->is_active ? '⚡' : '🕐' }}
                                            </div>
                                        </div>
                                        <div class="status-badge {{ $sale->is_active ? 'active' : 'upcoming' }}">
                                            {{ $sale->is_active ? 'Đang diễn ra' : 'Sắp diễn ra' }}
                                        </div>
                                    </div>

                                    @if ($sale->is_active)
                                        <div class="flash-countdown" data-time="{{ $sale->target_time }}">
                                            <!-- Countdown sẽ được JavaScript điền vào -->
                                        </div>
                                    @else
                                        <div class="upcoming-time">
                                            <div class="time-display">{{ $sale->target_time }}</div>
                                        </div>
                                    @endif
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Products Container -->
                @foreach ($flashSales as $sale1)
                    <div class="flash-sale-products mb-4" id="products-{{ $sale1->id }}"></div>
                    {{-- @php
                    dd($sale1);
                    @endphp --}}
                @endforeach
            </div>
        </div>
    </div>
@endif

<!-- JavaScript tối ưu hóa -->
<script src="{{ asset('assets/js/shop/flash_sale.js') }}"></script>