<!-- Flash Sale Banner -->
@if ($activeFlashSales->count() > 0 || $upcomingFlashSales->count() > 0)
    <div class="flash-sale-banner">
        <div class="container">
            <div class="flash-sale-content">
                <div class="flash-sale-text">
                    <h1>FLASH SALE</h1>
                    <p>GIÁ SỐC - GIẢM ĐẾN 50%</p>
                </div>
            </div>
        </div>
    </div>


    <!-- Product Tabs Menu -->
    <div class="product-tabs-container">
        <div class="container">
            <div class="tabs-menu">
                <button class="tab-btn active" data-tab="flash-sale">
                    @if ($activeFlashSales->count() > 0)
                        <div class="countdown-section">
                            <div class="countdown-label">Chỉ còn:</div>
                            <div class="countdown-timer" id="countdown">
                                <div class="time-block">
                                    <span class="time-number" id="hours">00</span>

                                </div>
                                <div class="time-separator">:</div>
                                <div class="time-block">
                                    <span class="time-number" id="minutes">00</span>

                                </div>
                                <div class="time-separator">:</div>
                                <div class="time-block">
                                    <span class="time-number" id="seconds">00</span>

                                </div>
                            </div>
                        </div>
                    @endif
                </button>
                @if ($upcomingFlashSales->count() > 0)
                    @foreach ($upcomingFlashSales as $upcoming)
                        @if ($upcoming->itemsWithProduct->count() > 0)
                            <button class="tab-btn" data-tab="upcoming">
                                <div class="upcoming-section">
                                    <div class="upcoming-item">
                                        <span class="upcoming-label">Bắt đầu lúc</span>
                                        <span class="upcoming-time">{{ $upcoming->start_date->format('H:i') }}</span>
                                    </div>
                                </div>
                            </button>
                        @endif
                    @endforeach
                @endif

            </div>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="tab-content-container">
        <div class="container">
            <!-- Flash Sale Tab -->
            <div class="tab-content active" id="flash-sale-tab">
                @if ($activeFlashSales->count() > 0)
                  
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <ul class="shop-modern shop-wrapper grid-loading grid grid-5col lg-grid-4col md-grid-3col sm-grid-2col xs-grid-1col gutter-extra-large text-center"
                                    data-anime='{ "el": "childs", "translateY": [-15, 0], "opacity": [0,1], "duration": 300, "delay": 0, "staggervalue": 100, "easing": "easeOutQuad" }'>
                                    <li class="grid-sizer" id="flashSaleGrid"></li>
                                    @foreach ($activeFlashSales as $flashSale)
                                        @foreach ($flashSale->itemsWithProduct as $item)
                                            <!-- start shop item -->
                                            <li class="grid-item">
                                                <div class="shop-box mb-10px">
                                                    <div class="product-image mb-20px">
                                                        <a href="">
                                                            <img src="{{ asset($item->variant_image_url) }}"
                                                                alt="{{ $item->name }}">
                                                            <div
                                                                class="shop-overlay bg-gradient-gray-light-dark-transparent">
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="shop-footer text-start">
                                                        <a href=""
                                                            class="alt-font text-dark-gray fs-19 fw-500 product-name-truncate">{{ $item->name }}</a>
                                                        <div class="price lh-22 fs-16">

                                                            <div class="product-price">
                                                                {{ number_format($item->price_at_flash_sale) }}₫
                                                                <span
                                                                    class="product-old-price">{{ number_format($item->sale_price) }}₫</span>
                                                            </div>
                                                            <div class="product-badge flash-sale-badge">-{{ $flashSale->discount }}%</div>
                                                            <div class="product-status flash-sale-progress">
                                                                <span class="fire-icon">🔥</span>
                                                                @if ($item->sold_quantity == 0)
                                                                    <span class="progress-label">Vừa mở bán</span>
                                                                   
                                                                @else
                                                                    <span class="progress-label">Còn {{ $item->max_quantity - $item->sold_quantity }}/{{ $item->max_quantity }} suất</span>
                                                                    
                                                                @endif
                                                            </div>
                                                            <button class="buy-now-btn"
                                                                data-product-id="{{ $item->product_variant_id }}"
                                                                data-flash-sale-id="{{ $flashSale->id }}">Mua
                                                                ngay</button>

                                                        </div>

                                                    </div>
                                                </div>
                                            </li>
                                            <!-- end shop item -->
                                        @endforeach
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                @endif
            </div>

            <!-- Upcoming Flash Sale Tab -->
            @if ($upcomingFlashSales->count() > 0)
                <div class="tab-content" id="upcoming-tab">
                    <div class="product-grid" id="upcomingGrid">
                        @foreach ($upcomingFlashSales as $upcoming)
                            @if ($upcoming->itemsWithProduct->count() > 0)
                                @foreach ($upcoming->itemsWithProduct as $item)
                                    <div class="product-card">
                                        <div class="product-image">
                                            <img src="{{ asset($item->variant_image_url) }}"
                                                alt="{{ $item->name }}">
                                            <div class="product-badge upcoming-badge">Sắp diễn ra</div>
                                        </div>
                                        <div class="product-info">
                                            <h3 class="product-name">{{ $item->name }}</h3>
                                            <div class="product-price">
                                                <span
                                                    class="current-price">{{ number_format($item->price_at_flash_sale) }}₫</span>
                                                <span
                                                    class="original-price">{{ number_format($item->sale_price) }}₫</span>
                                            </div>
                                            <div class="product-status">Bắt đầu lúc
                                                {{ $upcoming->start_date->format('H:i') }}</div>
                                            <button class="buy-now-btn upcoming-btn" disabled>Sắp diễn ra</button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        @endforeach
                    </div>
                    {{-- <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <ul class="shop-modern shop-wrapper grid-loading grid grid-5col lg-grid-4col md-grid-3col sm-grid-2col xs-grid-1col gutter-extra-large text-center"
                                    data-anime='{ "el": "childs", "translateY": [-15, 0], "opacity": [0,1], "duration": 300, "delay": 0, "staggervalue": 100, "easing": "easeOutQuad" }'>
                                    <li class="grid-sizer" id="upcomingGrid"></li>
                                    @foreach ($upcomingFlashSales as $upcoming)
                                 
                                        @foreach ($upcoming->itemsWithProduct as $item)
                                            <!-- start shop item -->
                                            <li class="grid-item">
                                                <div class="shop-box mb-10px">
                                                    <div class="product-image mb-20px">
                                                        <a href="">
                                                            <img src="{{ asset($item->variant_image_url) }}"
                                                                alt="{{ $item->name }}">
                                                            <div
                                                                class="shop-overlay bg-gradient-gray-light-dark-transparent">
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="shop-footer text-start">
                                                        <a href=""
                                                            class="alt-font text-dark-gray fs-19 fw-500 product-name-truncate">{{ $item->name }}</a>
                                                        <div class="price lh-22 fs-16">

                                                            <div class="product-price">
                                                                {{ number_format($item->price_at_flash_sale) }}₫
                                                                <span
                                                                    class="product-old-price">{{ number_format($item->sale_price) }}₫</span>
                                                            </div>
                                                            <div class="product-badge upcoming-badge">Sắp diễn ra</div>
                                                            <div class="product-status">Bắt đầu lúc
                                                                {{ $upcoming->start_date->format('H:i') }}</div>
                                                            <button class="buy-now-btn upcoming-btn" disabled>Sắp diễn ra</button>

                                                        </div>

                                                    </div>
                                                </div>
                                            </li>
                                            <!-- end shop item -->
                                        @endforeach
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div> --}}
                </div>
            @endif
        </div>
    </div>
@endif
<link rel="stylesheet" href="{{ asset('assets/css/flash_sale.css') }}">

<script>
    // Countdown Timer
    @if ($activeFlashSales->count() > 0)
        @php
            $firstActiveFlashSale = $activeFlashSales->first();
            $endTime = $firstActiveFlashSale->end_date;
        @endphp

        function updateCountdown() {
            const now = new Date().getTime();
            const endTime = new Date('{{ $endTime }}').getTime();
            const distance = endTime - now;

            if (distance > 0) {
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
                document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
                document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
            } else {
                clearInterval(countdownInterval);
                document.getElementById('countdown').innerHTML =
                    '<div class="time-block"><span class="time-number">00</span><span class="time-label">Giờ</span></div><div class="time-separator">:</div><div class="time-block"><span class="time-number">00</span><span class="time-label">Phút</span></div><div class="time-separator">:</div><div class="time-block"><span class="time-number">00</span><span class="time-label">Giây</span></div>';
            }
        }

        const countdownInterval = setInterval(updateCountdown, 1000);
        updateCountdown();
    @endif

    // Tab Switching
    document.addEventListener('DOMContentLoaded', function() {
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const targetTab = this.getAttribute('data-tab');

                // Remove active class from all buttons and contents
                tabBtns.forEach(b => b.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));

                // Add active class to clicked button and corresponding content
                this.classList.add('active');
                document.getElementById(targetTab + '-tab').classList.add('active');

                // Load content for the selected tab
                loadTabContent(targetTab);
            });
        });

        // Load initial content for active tab
        loadTabContent('flash-sale');

        // Handle upcoming tab if it exists
        const upcomingTab = document.querySelector('[data-tab="upcoming"]');
        if (upcomingTab) {
            upcomingTab.addEventListener('click', function() {
                // Tab content is already loaded in HTML
            });
        }
    });

    // // Load Tab Content
    function loadTabContent(tabName) {
        // Nếu là upcoming hoặc new-products, chỉ load động cho new-products
        if (tabName === 'new-products') {
            const tabContent = document.getElementById(tabName + '-tab');
            const grid = tabContent.querySelector('.product-grid');
            grid.innerHTML =
                '<div class="loading-spinner"><i class="fas fa-spinner fa-spin"></i><p>Đang tải sản phẩm...</p></div>';
            setTimeout(() => {
                grid.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-box"></i>
                    <h3>Chưa có sản phẩm</h3>
                    <p>Nội dung cho tab ${tabName} sẽ được cập nhật sau!</p>
                </div>
            `;
            }, 1000);
        }
        // Nếu là flash-sale hoặc upcoming thì không làm gì (vì đã render sẵn)
    }
</script>
