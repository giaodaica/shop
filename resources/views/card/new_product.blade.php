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
                    <button class="tab-btn" data-tab="upcoming">
                        <div class="upcoming-section">
                            <div class="upcoming-item">
                                <span class="upcoming-label">Bắt đầu lúc</span>
                                <span
                                    class="upcoming-time">{{ $upcomingFlashSales->first()->start_date->format('H:i') }}</span>
                            </div>
                        </div>
                    </button>
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
                                    @include('pages.shop.partials.flash_sale', [
                                        'flashSales' => $activeFlashSales,
                                        'type' => 'active',
                                    ])
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <!-- Upcoming Flash Sale Tab -->
            <div class="tab-content" id="upcoming-tab">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div id="upcoming-loading" style="display:none;text-align:center;padding:40px 0;">
                                <div class="loading-spinner"><i class="fas fa-spinner fa-spin fa-2x"></i>
                                    <p>Đang tải sản phẩm...</p>
                                </div>
                            </div>
                            <ul id="upcoming-list"
                                class="shop-modern shop-wrapper grid-loading grid grid-5col lg-grid-4col md-grid-3col sm-grid-2col xs-grid-1col gutter-extra-large text-center"
                                data-anime='{ "el": "childs", "translateY": [-15, 0], "opacity": [0,1], "duration": 300, "delay": 0, "staggervalue": 100, "easing": "easeOutQuad" }'
                                style="display:none;">
                                @if ($upcomingFlashSales->count() > 0)
                                    @include('pages.shop.partials.flash_sale', [
                                        'flashSales' => $upcomingFlashSales,
                                        'type' => 'upcoming',
                                    ])
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
<link rel="stylesheet" href="{{ asset('assets/css/flash_sale.css') }}">

<script>
    // Countdown Timer chỉ cho flash sale đầu tiên (active)
    @if ($activeFlashSales->count() > 0)
        @php
            $firstActiveFlashSale = $activeFlashSales->first();
            $endTime = $firstActiveFlashSale->end_date;
        @endphp
  var flashSaleEndTime = "{{ $activeFlashSales->count() > 0 ? $activeFlashSales->first()->end_date : '' }}";
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

    // Tab Switching đơn giản
    document.addEventListener('DOMContentLoaded', function() {
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');
        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const targetTab = this.getAttribute('data-tab');
                tabBtns.forEach(b => b.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));
                this.classList.add('active');
                document.getElementById(targetTab + '-tab').classList.add('active');

                // Hiệu ứng loading cho upcoming
                if (targetTab === 'upcoming') {
                    const loading = document.getElementById('upcoming-loading');
                    const list = document.getElementById('upcoming-list');
                    if (loading && list) {
                        loading.style.display = 'block';
                        list.style.display = 'none';
                        setTimeout(() => {
                            loading.style.display = 'none';
                            list.style.display = 'block';
                            if (window.imagesLoaded) {
                                imagesLoaded(list, function() {
                                    if (window.Masonry && window.Masonry.data &&
                                        window.Masonry.data(list)) {
                                        window.Masonry.data(list).layout();
                                    } else if (window.Isotope && window.Isotope
                                        .data && window.Isotope.data(list)) {
                                        window.Isotope.data(list).layout();
                                    }
                                });
                            } else {
                                window.dispatchEvent(new Event('resize'));
                            }
                        }, 800);
                    }
                } else if (targetTab === 'flash-sale') {
                    // Khi chuyển về tab flash-sale, trigger reflow grid luôn
                    setTimeout(() => {
                        window.dispatchEvent(new Event('resize'));
                    }, 100);
                }
            });
        });
        // Khi load trang, hiển thị luôn list upcoming nếu tab active
        if (document.querySelector('.tab-btn.active[data-tab="upcoming"]')) {
            const loading = document.getElementById('upcoming-loading');
            const list = document.getElementById('upcoming-list');
            if (loading && list) {
                loading.style.display = 'block';
                list.style.display = 'none';
                setTimeout(() => {
                    loading.style.display = 'none';
                    list.style.display = 'block';
                    // Trigger reflow grid (fix lỗi layout khi chuyển tab)
                    window.dispatchEvent(new Event('resize'));
                }, 800);
            }
        }

    });
</script>
