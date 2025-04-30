@extends('layouts.layout')
@section('cdn-custom')
<link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
@endsection
@section('js-page-custom')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    // Lấy ngày hiện tại và định dạng
    const currentDate = new Date();
    const currentDateString = currentDate.toLocaleDateString('en-GB'); // Định dạng dd/mm/yyyy

    // Cập nhật placeholder với khoảng thời gian
    document.getElementById('daterange').placeholder = `01/01/2022-${currentDateString}`;

    // Khởi tạo flatpickr
    flatpickr("#daterange", {
        mode: "range",  // Cho phép chọn một khoảng thời gian
        dateFormat: "Y-m-d",  // Định dạng ngày
        minDate: "2022-01-01",  // Giới hạn ngày bắt đầu là 1/1/2022
        maxDate: currentDate,  // Giới hạn ngày kết thúc là ngày hiện tại
        locale: {
            firstDayOfWeek: 1, // Đặt ngày đầu tuần là thứ 2
        },
        onClose: function(selectedDates, dateStr, instance) {
            if (selectedDates.length === 2) {
                instance.input.value = selectedDates[0].toLocaleDateString() + " - " + selectedDates[1].toLocaleDateString();
            }
        }
    });

    document.addEventListener("DOMContentLoaded", function () {
        const selectedTab = localStorage.getItem("selectedTab");
        const selectedSubTab = localStorage.getItem("selectedSubTab");

        // Bật tab cha
        if (selectedTab) {
            const tabToActivate = document.querySelector(`[href="${selectedTab}"]`);
            const tabContentToShow = document.querySelector(selectedTab);

            if (tabToActivate && tabContentToShow) {
                document.querySelectorAll('.nav-link').forEach(tab => tab.classList.remove('active'));
                document.querySelectorAll('.tab-pane').forEach(content => content.classList.remove('show', 'active'));

                tabToActivate.classList.add('active');
                tabContentToShow.classList.add('show', 'active');

                // Nếu là tab_seven2 → xử lý tab con
                if (selectedTab === "#tab_seven2") {
                    let subTabToActivate, subTabContentToShow;

                    if (selectedSubTab) {
                        subTabToActivate = document.querySelector(`[href="${selectedSubTab}"]`);
                        subTabContentToShow = document.querySelector(selectedSubTab);
                    } else {
                        // Không có localStorage → bật tab_third1
                        subTabToActivate = document.querySelector('#tab_seven2 .nav-link[href="#tab_third1"]');
                        subTabContentToShow = document.querySelector('#tab_third1');
                    }

                    if (subTabToActivate && subTabContentToShow) {
                        document.querySelectorAll('#tab_seven2 .nav-link').forEach(tab => tab.classList.remove('active'));
                        document.querySelectorAll('#tab_seven2 .tab-pane').forEach(content => content.classList.remove('show', 'active'));

                        subTabToActivate.classList.add('active');
                        subTabContentToShow.classList.add('show', 'active');
                    }
                }
            }
        }

        // Lưu tab cha
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function () {
                const href = link.getAttribute('href');
                localStorage.setItem("selectedTab", href);

                if (href !== '#tab_seven2') {
                    localStorage.removeItem("selectedSubTab");
                } else {
                    // Khi click tab_seven2 thì bật luôn tab_third1 và nội dung
                    const subTabToActivate = document.querySelector('#tab_seven2 .nav-link[href="#tab_third1"]');
                    const subTabContentToShow = document.querySelector('#tab_third1');

                    if (subTabToActivate && subTabContentToShow) {
                        document.querySelectorAll('#tab_seven2 .nav-link').forEach(tab => tab.classList.remove('active'));
                        document.querySelectorAll('#tab_seven2 .tab-pane').forEach(content => content.classList.remove('show', 'active'));

                        subTabToActivate.classList.add('active');
                        subTabContentToShow.classList.add('show', 'active');
                    }
                }
            });
        });

        // Lưu tab con
        document.querySelectorAll('#tab_seven2 .nav-link').forEach(link => {
            link.addEventListener('click', function () {
                const href = this.getAttribute('href');
                localStorage.setItem("selectedSubTab", href);
                localStorage.setItem("selectedTab", "#tab_seven2");
            });
        });
    });

</script>
@endsection
<style>
    .nav-item-date{
        width: 230px;
        justify-content: center;
    }
    input#daterange {
    text-align: center;
}
.order-status.success {
    background-color: rgba(0, 171, 85, .078);
    color: #007b55;
}
.order-status.pending {
    background-color: rgba(255, 193, 7, .13); /* vàng nhạt */
    color: #b78103;
}
.order-status.confirmed {
    background-color: rgba(24, 144, 255, .10); /* xanh dương nhạt */
    color: #1769aa;
}
.order-status.shipping {
    background-color: rgba(0, 184, 217, .10); /* xanh cyan nhạt */
    color: #006c9c;
}
.order-status.cancelled {
    background-color: rgba(255, 72, 66, .10); /* đỏ nhạt */
    color: #b72136;
}
</style>
@section('content')
        <!-- start page title -->
        <section class="page-title-center-alignment cover-background top-space-padding" style="background-image: url({{asset('assets/images/demo-decor-store-title-bg.jpg')}})">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center position-relative page-title-extra-large">
                    </div>
                    <div class="col-12 breadcrumb breadcrumb-style-01 d-flex justify-content-center">
                        <ul>

                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <!-- end page title -->
        <!-- start section -->
        <section class="position-relative">
            <div class="container">
                <div class="row">
                    <div class="col-xl-3 col-lg-4 tab-style-07 md-mb-50px sm-mb-35px" data-anime='{ "translate": [50, 0], "opacity": [0,1], "duration": 600, "delay":100, "staggervalue": 150, "easing": "easeOutQuad" }'>
                        <div class="position-sticky top-50px">
                            <ul class="nav nav-tabs justify-content-center border-0 fw-500 text-left alt-font bg-very-light-gray border-radius-6px overflow-hidden">
                                <li class="nav-item">
                                    <a data-bs-toggle="tab" href="#tab_seven1" class="nav-link active">
                                        <span>
                                            <span class="me-5px"><i class="bi bi-file-text"></i></span>
                                            <span>Trang chủ</span>
                                        </span>
                                        <span class="bg-hover bg-base-color"></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tab_seven2">
                                        <span>
                                            <span class="me-5px"><i class="bi bi-bag-plus"></i></span>
                                            <span>Lịch sử mua hàng</span>
                                        </span>
                                        <span class="bg-hover bg-base-color"></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tab_seven3">
                                        <span>
                                            <span class="me-5px"><i class="bi bi-credit-card-2-back"></i></span>
                                            <span>Payment information</span>
                                        </span>
                                        <span class="bg-hover bg-base-color"></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tab_seven4">
                                        <span>
                                            <span class="me-5px"><i class="bi bi-box"></i></span>
                                            <span>Orders and returns</span>
                                        </span>
                                        <span class="bg-hover bg-base-color"></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tab_seven5">
                                        <span>
                                            <span class="me-5px"><i class="bi bi-cart"></i></span>
                                            <span>Ordering from crafto</span>
                                        </span>
                                        <span class="bg-hover bg-base-color"></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tab_seven6">
                                        <span>
                                            <span class="me-5px"><i class="bi bi-info-circle"></i></span>
                                            <span>Help and support</span>
                                        </span>
                                        <span class="bg-hover bg-base-color"></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-8 offset-xl-1 lg-ps-50px md-ps-15px" data-anime='{ "translateX": [0, 0], "opacity": [0,1], "duration": 600, "delay":150, "staggervalue": 150, "easing": "easeOutQuad" }'>
                        <div class="tab-content h-100">
                            <!-- start tab content -->
                            <div class="tab-pane fade in active show" id="tab_seven1">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="accordion accordion-style-02" id="accordion-style-01" data-active-icon="icon-feather-minus" data-inactive-icon="icon-feather-plus">
                                            <!-- start accordion item -->
                                            <div class="accordion-item active-accordion">
                                                <div class="accordion-header border-bottom border-color-extra-medium-gray pt-0">
                                                    <a href="#" data-bs-toggle="collapse" data-bs-target="#accordion-style-01-01" aria-expanded="true" data-bs-parent="#accordion-style-01">
                                                        <div class="accordion-title mb-0 position-relative text-dark-gray">
                                                            <i class="feather icon-feather-minus"></i><span class="fw-500 fs-18">Can i order over the phone?</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div id="accordion-style-01-01" class="accordion-collapse collapse show" data-bs-parent="#accordion-style-01">
                                                    <div class="accordion-body last-paragraph-no-margin border-bottom border-color-light-medium-gray">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took galley of type and scrambled to make type.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end accordion item -->
                                            <!-- start accordion item -->
                                            <div class="accordion-item">
                                                <div class="accordion-header border-bottom border-color-extra-medium-gray">
                                                    <a href="#" data-bs-toggle="collapse" data-bs-target="#accordion-style-01-02" aria-expanded="false" data-bs-parent="#accordion-style-01">
                                                        <div class="accordion-title mb-0 position-relative text-dark-gray">
                                                            <i class="feather icon-feather-plus"></i><span class="fw-500 fs-17">I am having difficulty placing an order?</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div id="accordion-style-01-02" class="accordion-collapse collapse" data-bs-parent="#accordion-style-01">
                                                    <div class="accordion-body last-paragraph-no-margin border-bottom border-color-light-medium-gray">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took galley of type and scrambled to make type.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end accordion item -->
                                            <!-- start accordion item -->
                                            <div class="accordion-item">
                                                <div class="accordion-header border-bottom border-color-light-medium-gray">
                                                    <a href="#" data-bs-toggle="collapse" data-bs-target="#accordion-style-01-03" aria-expanded="false" data-bs-parent="#accordion-style-01">
                                                        <div class="accordion-title mb-0 position-relative text-dark-gray">
                                                            <i class="feather icon-feather-plus"></i><span class="fw-500 fs-17">What payment methods does accept?</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div id="accordion-style-01-03" class="accordion-collapse collapse" data-bs-parent="#accordion-style-01">
                                                    <div class="accordion-body last-paragraph-no-margin border-bottom border-color-light-medium-gray">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took galley of type and scrambled to make type.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end accordion item -->
                                            <!-- start accordion item -->
                                            <div class="accordion-item">
                                                <div class="accordion-header border-bottom border-color-light-medium-gray">
                                                    <a href="#" data-bs-toggle="collapse" data-bs-target="#accordion-style-01-04" aria-expanded="false" data-bs-parent="#accordion-style-01">
                                                        <div class="accordion-title mb-0 position-relative text-dark-gray">
                                                            <i class="feather icon-feather-plus"></i><span class="fw-500 fs-17">Can i amend my order once placed?</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div id="accordion-style-01-04" class="accordion-collapse collapse" data-bs-parent="#accordion-style-01">
                                                    <div class="accordion-body last-paragraph-no-margin border-bottom border-color-light-medium-gray">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took galley of type and scrambled to make type.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end accordion item -->
                                            <!-- start accordion item -->
                                            <div class="accordion-item">
                                                <div class="accordion-header border-bottom border-color-light-medium-gray">
                                                    <a href="#" data-bs-toggle="collapse" data-bs-target="#accordion-style-01-05" aria-expanded="false" data-bs-parent="#accordion-style-01">
                                                        <div class="accordion-title mb-0 position-relative text-dark-gray">
                                                            <i class="feather icon-feather-plus"></i><span class="fw-500 fs-17">How do i know if my order was successful?</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div id="accordion-style-01-05" class="accordion-collapse collapse" data-bs-parent="#accordion-style-01">
                                                    <div class="accordion-body last-paragraph-no-margin border-bottom border-color-light-medium-gray">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took galley of type and scrambled to make type.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end accordion item -->
                                            <!-- start accordion item -->
                                            <div class="accordion-item">
                                                <div class="accordion-header border-bottom border-color-transparent">
                                                    <a href="#" data-bs-toggle="collapse" data-bs-target="#accordion-style-01-06" aria-expanded="false" data-bs-parent="#accordion-style-01">
                                                        <div class="accordion-title mb-0 position-relative text-dark-gray">
                                                            <i class="feather icon-feather-plus"></i><span class="fw-500 fs-17">What if my order is incorrect?</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div id="accordion-style-01-06" class="accordion-collapse collapse" data-bs-parent="#accordion-style-01">
                                                    <div class="accordion-body last-paragraph-no-margin border-bottom border-color-transparent">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took galley of type and scrambled to make type.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end accordion item -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end tab content -->
                            <!-- start tab content -->
                            <div class="tab-pane fade in h-100" id="tab_seven2">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="col tab-style-03">
                                            <ul class="nav justify-content-center text-center fw-500 border-color-light-medium-gray mb-7 gap-2">
                                                <li class="nav-item-date">
                                                    Chọn khoảng thời gian<input type="text" name="daterange" id="daterange"/>
                                                </li>
                                            </ul>
                                            <ul class="nav justify-content-center text-center fw-500 border-color-light-medium-gray mb-7 gap-2">
                                                <li class="nav-item"><a class="nav-link active border text-black rounded" data-bs-toggle="tab" href="#tab_third1">Tất cả</a></li>
                                                <li class="nav-item"><a class="nav-link border text-black rounded" data-bs-toggle="tab" href="#tab_third2">Chờ xác nhận</a></li>
                                                <li class="nav-item"><a class="nav-link border text-black rounded" data-bs-toggle="tab" href="#tab_third3">Đã xác nhận</a></li>
                                                <li class="nav-item"><a class="nav-link border text-black rounded" data-bs-toggle="tab" href="#tab_third4">Đang vận chuyển</a></li>
                                                <li class="nav-item"><a class="nav-link border text-black rounded" data-bs-toggle="tab" href="#tab_third5">Đã giao hàng</a></li>
                                                <li class="nav-item"><a class="nav-link border text-black rounded" data-bs-toggle="tab" href="#tab_third6">Đã hủy</a></li>
                                            </ul>
                                            <div class="tab-content">
                                                <!-- start tab content -->
                                                <div class="tab-pane fade in active show" id="tab_third1">
                                                    <div class="bg-white border border-dark p-35px border-radius-6px mt-9 position-relative">
                                                        <!-- Thời gian góc trên phải -->
                                                        <span class="position-absolute top-0 end-0 mt-2 me-3 text-secondary small">01/05/2025</span>
                                                        <div class="row align-items-center justify-content-center justify-content-lg-start">
                                                            <div class="col-5 col-sm-3 sm-mb-20px text-center">
                                                                <img src="{{asset('assets/images/shop/product1.png')}}" alt=""/>
                                                            </div>
                                                            <div class="col-md-9 text-center text-md-start ps-3">
                                                                <div class="mb-2">
                                                                    <span class="fw-bold fs-5 d-block">Tên sản phẩm: Áo thun nam basic</span>
                                                                    <span class="order-status pending px-3 py-2 rounded">Chờ xác nhận</span>
                                                                    <span class="text-danger fw-bold fs-5 d-block">Giá: 350.000₫</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Nút chi tiết góc dưới phải -->
                                                        <a href="#" class="btn btn-sm border border-danger text-danger bg-white position-absolute bottom-0 end-0 mb-3 me-3">Chi tiết</a>
                                                    </div>
                                                    <div class="bg-white border border-dark p-35px border-radius-6px mt-9 position-relative">
                                                        <!-- Thời gian góc trên phải -->
                                                        <span class="position-absolute top-0 end-0 mt-2 me-3 text-secondary small">01/05/2025</span>
                                                        <div class="row align-items-center justify-content-center justify-content-lg-start">
                                                            <div class="col-5 col-sm-3 sm-mb-20px text-center">
                                                                <img src="https://placehold.co/233x237" alt=""/>
                                                            </div>
                                                            <div class="col-md-9 text-center text-md-start ps-3">
                                                                <div class="mb-2">
                                                                    <span class="fw-bold fs-5 d-block">Tên sản phẩm: Áo thun nam basic</span>
                                                                    <span class="order-status confirmed px-3 py-2 rounded">Đã xác nhận</span>
                                                                    <span class="text-danger fw-bold fs-5 d-block">Giá: 350.000₫</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Nút chi tiết góc dưới phải -->
                                                        <a href="#" class="btn btn-sm border border-danger text-danger bg-white position-absolute bottom-0 end-0 mb-3 me-3">Chi tiết</a>
                                                    </div>
                                                    <div class="bg-white border border-dark p-35px border-radius-6px mt-9 position-relative">
                                                        <!-- Thời gian góc trên phải -->
                                                        <span class="position-absolute top-0 end-0 mt-2 me-3 text-secondary small">01/05/2025</span>
                                                        <div class="row align-items-center justify-content-center justify-content-lg-start">
                                                            <div class="col-5 col-sm-3 sm-mb-20px text-center">
                                                                <img src="https://placehold.co/233x237" alt=""/>
                                                            </div>
                                                            <div class="col-md-9 text-center text-md-start ps-3">
                                                                <div class="mb-2">
                                                                    <span class="fw-bold fs-5 d-block">Áo thun nam basic</span>
                                                                    <span class="order-status shipping px-3 py-2 rounded">Đang giao hàng</span>
                                                                    <span class="text-danger fw-bold fs-5 d-block">Giá: 350.000₫</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Nút chi tiết góc dưới phải -->
                                                        <a href="#" class="btn btn-sm border border-danger text-danger bg-white position-absolute bottom-0 end-0 mb-3 me-3">Chi tiết</a>
                                                    </div>
                                                    <div class="bg-white border border-dark p-35px border-radius-6px mt-9 position-relative">
                                                        <!-- Thời gian góc trên phải -->
                                                        <span class="position-absolute top-0 end-0 mt-2 me-3 text-secondary small">01/05/2025</span>
                                                        <div class="row align-items-center justify-content-center justify-content-lg-start">
                                                            <div class="col-5 col-sm-3 sm-mb-20px text-center">
                                                                <img src="https://placehold.co/233x237" alt=""/>
                                                            </div>
                                                            <div class="col-md-9 text-center text-md-start ps-3">
                                                                <div class="mb-2">
                                                                    <span class="fw-bold fs-5 d-block">Tên sản phẩm: Áo thun nam basic</span>
                                                                    <span class="order-status success px-3 py-2 rounded">Đã giao hàng</span>
                                                                    <span class="text-danger fw-bold fs-5 d-block">Giá: 350.000₫</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Nút chi tiết góc dưới phải -->
                                                        <a href="#" class="btn btn-sm border border-danger text-danger bg-white position-absolute bottom-0 end-0 mb-3 me-3">Chi tiết</a>
                                                    </div>
                                                    <div class="bg-white border border-dark p-35px border-radius-6px mt-9 position-relative">
                                                        <!-- Thời gian góc trên phải -->
                                                        <span class="position-absolute top-0 end-0 mt-2 me-3 text-secondary small">01/05/2025</span>
                                                        <div class="row align-items-center justify-content-center justify-content-lg-start">
                                                            <div class="col-5 col-sm-3 sm-mb-20px text-center">
                                                                <img src="https://placehold.co/233x237" alt=""/>
                                                            </div>
                                                            <div class="col-md-9 text-center text-md-start ps-3">
                                                                <div class="mb-2">
                                                                    <span class="fw-bold fs-5 d-block">Tên sản phẩm: Áo thun nam basic</span>
                                                                    <span class="order-status cancelled px-3 py-2 rounded">Đã hủy đơn</span>
                                                                    <span class="text-danger fw-bold fs-5 d-block">Giá: 350.000₫</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Nút chi tiết góc dưới phải -->
                                                        <a href="#" class="btn btn-sm border border-danger text-danger bg-white position-absolute bottom-0 end-0 mb-3 me-3">Chi tiết</a>
                                                    </div>
                                                </div>
                                                <!-- end tab content -->
                                                <!-- start tab content -->
                                                <div class="tab-pane fade in" id="tab_third2">
                                                    <div class="row align-items-center justify-content-center g-0">
                                                        <div class="col-lg-6 col-md-11 position-relative md-mb-30px">
                                                            <figure class="mb-0">
                                                                <img src="https://placehold.co/580x475" alt="" class="w-95 border-radius-6px">
                                                                <figcaption class="position-absolute bottom-90px right-minus-50px xs-bottom-10px xs-right-minus-15px xs-w-140px">
                                                                    <img src="{{asset('assets/images/demo-spa-salon-facility-bg.png')}}" class="animation-float" alt="">
                                                                </figcaption>
                                                            </figure>
                                                        </div>
                                                        <div class="col-lg-5 col-md-11 offset-lg-1">
                                                            <span class="fs-15 mb-15px text-gradient-fast-pink-light-yellow fw-700 d-inline-block text-uppercase ls-1px">Swimming pool</span>
                                                            <h3 class="ls-minus-1px text-dark-gray w-100 fw-600">The best place with a good swimming pool.</h3>
                                                            <p class="mb-35px w-95 sm-w-100">A design-led approach guides the team, implementing practices, products and services that are thoughtful and environmentally sound. family of professionals that creates intelligent designs that help the face of hospitality.</p>
                                                            <a href="#" class="btn btn-medium btn-switch-text btn-round-edge btn-transparent-light-gray">
                                                                <span>
                                                                    <span class="btn-double-text" data-text="Explore more">Explore more</span>
                                                                </span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end tab content -->
                                                <!-- start tab content -->
                                                <div class="tab-pane fade in" id="tab_third3">
                                                    <div class="row align-items-center justify-content-center g-0">
                                                        <div class="col-lg-6 col-md-11 position-relative md-mb-30px">
                                                            <figure class="mb-0">
                                                                <img src="https://placehold.co/580x475" alt="" class="w-95 border-radius-6px">
                                                                <figcaption class="position-absolute bottom-90px right-minus-50px xs-bottom-10px xs-right-minus-15px xs-w-140px">
                                                                    <img src="{{asset('assets/images/demo-spa-salon-facility-bg.png')}}" class="animation-float" alt="">
                                                                </figcaption>
                                                            </figure>
                                                        </div>
                                                        <div class="col-lg-5 col-md-11 offset-lg-1">
                                                            <span class="fs-15 mb-15px text-gradient-fast-pink-light-yellow fw-700 d-inline-block text-uppercase ls-1px">Private beach</span>
                                                            <h3 class="ls-minus-1px text-dark-gray w-100 fw-600">The best luxury beach for spa massage.</h3>
                                                            <p class="mb-35px w-95 sm-w-100">A design-led approach guides the team, implementing practices, products and services that are thoughtful and environmentally sound. family of professionals that creates intelligent designs that help the face of hospitality.</p>
                                                            <a href="#" class="btn btn-medium btn-switch-text btn-round-edge btn-transparent-light-gray">
                                                                <span>
                                                                    <span class="btn-double-text" data-text="Explore more">Explore more</span>
                                                                </span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end tab content -->
                                                <!-- start tab content -->
                                                <div class="tab-pane fade in" id="tab_third4">
                                                    <div class="row align-items-center justify-content-center g-0">
                                                        <div class="col-lg-6 col-md-11 position-relative md-mb-30px">
                                                            <figure class="mb-0">
                                                                <img src="https://placehold.co/580x475" alt="" class="w-95 border-radius-6px">
                                                                <figcaption class="position-absolute bottom-90px right-minus-50px xs-bottom-10px sm-right-minus-20px xs-right-minus-10px xs-w-140px">
                                                                    <img src="{{asset('assets/images/demo-spa-salon-facility-bg.png')}}" class="animation-float" alt="">
                                                                </figcaption>
                                                            </figure>
                                                        </div>
                                                        <div class="col-lg-5 col-md-11 offset-lg-1">
                                                            <span class="fs-15 mb-15px text-gradient-fast-pink-light-yellow fw-700 d-inline-block text-uppercase ls-1px">Sauna bath</span>
                                                            <h3 class="ls-minus-1px text-dark-gray w-100 fw-600">Saunas improve your health and wellness.</h3>
                                                            <p class="mb-35px w-95 sm-w-100">A design-led approach guides the team, implementing practices, products and services that are thoughtful and environmentally sound. family of professionals that creates intelligent designs that help the face of hospitality.</p>
                                                            <a href="#" class="btn btn-medium btn-switch-text btn-round-edge btn-transparent-light-gray">
                                                                <span>
                                                                    <span class="btn-double-text" data-text="Explore more">Explore more</span>
                                                                </span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end tab content -->
                                                     <!-- start tab content -->
                                                     <div class="tab-pane fade in" id="tab_third5">
                                                        <div class="row align-items-center justify-content-center g-0">
                                                            <div class="col-lg-6 col-md-11 position-relative md-mb-30px">
                                                                <figure class="mb-0">
                                                                    <img src="https://placehold.co/580x475" alt="" class="w-95 border-radius-6px">
                                                                    <figcaption class="position-absolute bottom-90px right-minus-50px xs-bottom-10px sm-right-minus-20px xs-right-minus-10px xs-w-140px">
                                                                        <img src="{{asset('assets/images/demo-spa-salon-facility-bg.png')}}" class="animation-float" alt="">
                                                                    </figcaption>
                                                                </figure>
                                                            </div>
                                                            <div class="col-lg-5 col-md-11 offset-lg-1">
                                                                <span class="fs-15 mb-15px text-gradient-fast-pink-light-yellow fw-700 d-inline-block text-uppercase ls-1px">Sauna bath</span>
                                                                <h3 class="ls-minus-1px text-dark-gray w-100 fw-600">Saunas improve your health and wellness.</h3>
                                                                <p class="mb-35px w-95 sm-w-100">A design-led approach guides the team, implementing practices, products and services that are thoughtful and environmentally sound. family of professionals that creates intelligent designs that help the face of hospitality.</p>
                                                                <a href="#" class="btn btn-medium btn-switch-text btn-round-edge btn-transparent-light-gray">
                                                                    <span>
                                                                        <span class="btn-double-text" data-text="Explore more">Explore more</span>
                                                                    </span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- end tab content -->
                                                         <!-- start tab content -->
                                                <div class="tab-pane fade in" id="tab_third6">
                                                    <div class="row align-items-center justify-content-center g-0">
                                                        <div class="col-lg-6 col-md-11 position-relative md-mb-30px">
                                                            <figure class="mb-0">
                                                                <img src="https://placehold.co/580x475" alt="" class="w-95 border-radius-6px">
                                                                <figcaption class="position-absolute bottom-90px right-minus-50px xs-bottom-10px sm-right-minus-20px xs-right-minus-10px xs-w-140px">
                                                                    <img src="{{asset('assets/images/demo-spa-salon-facility-bg.png')}}" class="animation-float" alt="">
                                                                </figcaption>
                                                            </figure>
                                                        </div>
                                                        <div class="col-lg-5 col-md-11 offset-lg-1">
                                                            <span class="fs-15 mb-15px text-gradient-fast-pink-light-yellow fw-700 d-inline-block text-uppercase ls-1px">Sauna bath</span>
                                                            <h3 class="ls-minus-1px text-dark-gray w-100 fw-600">Saunas improve your health and wellness.</h3>
                                                            <p class="mb-35px w-95 sm-w-100">A design-led approach guides the team, implementing practices, products and services that are thoughtful and environmentally sound. family of professionals that creates intelligent designs that help the face of hospitality.</p>
                                                            <a href="#" class="btn btn-medium btn-switch-text btn-round-edge btn-transparent-light-gray">
                                                                <span>
                                                                    <span class="btn-double-text" data-text="Explore more">Explore more</span>
                                                                </span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end tab content -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end tab content -->
                            <!-- start tab content -->
                            <div class="tab-pane fade in h-100" id="tab_seven3">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="accordion accordion-style-02" id="accordion-style-03" data-active-icon="icon-feather-minus" data-inactive-icon="icon-feather-plus">
                                            <!-- start accordion item -->
                                            <div class="accordion-item active-accordion">
                                                <div class="accordion-header border-bottom border-color-extra-medium-gray pt-0">
                                                    <a href="#" data-bs-toggle="collapse" data-bs-target="#accordion-style-03-01" aria-expanded="true" data-bs-parent="#accordion-style-03">
                                                        <div class="accordion-title mb-0 position-relative text-dark-gray">
                                                            <i class="feather icon-feather-minus"></i><span class="fw-500 fs-17">Can I return my order?</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div id="accordion-style-03-01" class="accordion-collapse collapse show" data-bs-parent="#accordion-style-03">
                                                    <div class="accordion-body last-paragraph-no-margin border-bottom border-color-light-medium-gray">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took galley of type and scrambled to make type.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end accordion item -->
                                            <!-- start accordion item -->
                                            <div class="accordion-item">
                                                <div class="accordion-header border-bottom border-color-extra-medium-gray">
                                                    <a href="#" data-bs-toggle="collapse" data-bs-target="#accordion-style-03-02" aria-expanded="false" data-bs-parent="#accordion-style-03">
                                                        <div class="accordion-title mb-0 position-relative text-dark-gray">
                                                            <i class="feather icon-feather-plus"></i><span class="fw-500 fs-17">What if my item is damaged or faulty?</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div id="accordion-style-03-02" class="accordion-collapse collapse" data-bs-parent="#accordion-style-03">
                                                    <div class="accordion-body last-paragraph-no-margin border-bottom border-color-light-medium-gray">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took galley of type and scrambled to make type.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end accordion item -->
                                            <!-- start accordion item -->
                                            <div class="accordion-item">
                                                <div class="accordion-header border-bottom border-color-light-medium-gray">
                                                    <a href="#" data-bs-toggle="collapse" data-bs-target="#accordion-style-03-03" aria-expanded="false" data-bs-parent="#accordion-style-03">
                                                        <div class="accordion-title mb-0 position-relative text-dark-gray">
                                                            <i class="feather icon-feather-plus"></i><span class="fw-500 fs-17">How long will it take to process a return?</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div id="accordion-style-03-03" class="accordion-collapse collapse" data-bs-parent="#accordion-style-03">
                                                    <div class="accordion-body last-paragraph-no-margin border-bottom border-color-light-medium-gray">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took galley of type and scrambled to make type.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end accordion item -->
                                            <!-- start accordion item -->
                                            <div class="accordion-item">
                                                <div class="accordion-header border-bottom border-color-light-medium-gray">
                                                    <a href="#" data-bs-toggle="collapse" data-bs-target="#accordion-style-03-04" aria-expanded="false" data-bs-parent="#accordion-style-03">
                                                        <div class="accordion-title mb-0 position-relative text-dark-gray">
                                                            <i class="feather icon-feather-plus"></i><span class="fw-500 fs-17">Why does the refund amount exclude delivery?</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div id="accordion-style-03-04" class="accordion-collapse collapse" data-bs-parent="#accordion-style-03">
                                                    <div class="accordion-body last-paragraph-no-margin border-bottom border-color-light-medium-gray">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took galley of type and scrambled to make type.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end accordion item -->
                                            <!-- start accordion item -->
                                            <div class="accordion-item">
                                                <div class="accordion-header border-bottom border-color-light-medium-gray">
                                                    <a href="#" data-bs-toggle="collapse" data-bs-target="#accordion-style-03-05" aria-expanded="false" data-bs-parent="#accordion-style-03">
                                                        <div class="accordion-title mb-0 position-relative text-dark-gray">
                                                            <i class="feather icon-feather-plus"></i><span class="fw-500 fs-17">Need more help?</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div id="accordion-style-03-05" class="accordion-collapse collapse" data-bs-parent="#accordion-style-03">
                                                    <div class="accordion-body last-paragraph-no-margin border-bottom border-color-light-medium-gray">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took galley of type and scrambled to make type.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end accordion item -->
                                            <!-- start accordion item -->
                                            <div class="accordion-item">
                                                <div class="accordion-header border-bottom border-color-transparent">
                                                    <a href="#" data-bs-toggle="collapse" data-bs-target="#accordion-style-03-06" aria-expanded="false" data-bs-parent="#accordion-style-03">
                                                        <div class="accordion-title mb-0 position-relative text-dark-gray">
                                                            <i class="feather icon-feather-plus"></i><span class="fw-500 fs-17">What if my item is damaged or faulty?</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div id="accordion-style-03-06" class="accordion-collapse collapse" data-bs-parent="#accordion-style-03">
                                                    <div class="accordion-body last-paragraph-no-margin border-bottom border-color-transparent">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took galley of type and scrambled to make type.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end accordion item -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end tab content -->
                            <!-- start tab content -->
                            <div class="tab-pane fade in h-100" id="tab_seven4">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="accordion accordion-style-02" id="accordion-style-04" data-active-icon="icon-feather-minus" data-inactive-icon="icon-feather-plus">
                                            <!-- start accordion item -->
                                            <div class="accordion-item active-accordion">
                                                <div class="accordion-header border-bottom border-color-extra-medium-gray pt-0">
                                                    <a href="#" data-bs-toggle="collapse" data-bs-target="#accordion-style-04-01" aria-expanded="true" data-bs-parent="#accordion-style-04">
                                                        <div class="accordion-title mb-0 position-relative text-dark-gray">
                                                            <i class="feather icon-feather-minus"></i><span class="fw-500 fs-17">Can i order over the phone?</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div id="accordion-style-04-01" class="accordion-collapse collapse show" data-bs-parent="#accordion-style-04">
                                                    <div class="accordion-body last-paragraph-no-margin border-bottom border-color-light-medium-gray">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took galley of type and scrambled to make type.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end accordion item -->
                                            <!-- start accordion item -->
                                            <div class="accordion-item">
                                                <div class="accordion-header border-bottom border-color-extra-medium-gray">
                                                    <a href="#" data-bs-toggle="collapse" data-bs-target="#accordion-style-04-02" aria-expanded="false" data-bs-parent="#accordion-style-04">
                                                        <div class="accordion-title mb-0 position-relative text-dark-gray">
                                                            <i class="feather icon-feather-plus"></i><span class="fw-500 fs-17">I am having difficulty placing an order?</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div id="accordion-style-04-02" class="accordion-collapse collapse" data-bs-parent="#accordion-style-04">
                                                    <div class="accordion-body last-paragraph-no-margin border-bottom border-color-light-medium-gray">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took galley of type and scrambled to make type.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end accordion item -->
                                            <!-- start accordion item -->
                                            <div class="accordion-item">
                                                <div class="accordion-header border-bottom border-color-light-medium-gray">
                                                    <a href="#" data-bs-toggle="collapse" data-bs-target="#accordion-style-04-03" aria-expanded="false" data-bs-parent="#accordion-style-04">
                                                        <div class="accordion-title mb-0 position-relative text-dark-gray">
                                                            <i class="feather icon-feather-plus"></i><span class="fw-500 fs-17">What payment methods does accept?</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div id="accordion-style-04-03" class="accordion-collapse collapse" data-bs-parent="#accordion-style-04">
                                                    <div class="accordion-body last-paragraph-no-margin border-bottom border-color-light-medium-gray">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took galley of type and scrambled to make type.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end accordion item -->
                                            <!-- start accordion item -->
                                            <div class="accordion-item">
                                                <div class="accordion-header border-bottom border-color-light-medium-gray">
                                                    <a href="#" data-bs-toggle="collapse" data-bs-target="#accordion-style-04-04" aria-expanded="false" data-bs-parent="#accordion-style-04">
                                                        <div class="accordion-title mb-0 position-relative text-dark-gray">
                                                            <i class="feather icon-feather-plus"></i><span class="fw-500 fs-17">Can i amend my order once placed?</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div id="accordion-style-04-04" class="accordion-collapse collapse" data-bs-parent="#accordion-style-04">
                                                    <div class="accordion-body last-paragraph-no-margin border-bottom border-color-light-medium-gray">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took galley of type and scrambled to make type.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end accordion item -->
                                            <!-- start accordion item -->
                                            <div class="accordion-item">
                                                <div class="accordion-header border-bottom border-color-light-medium-gray">
                                                    <a href="#" data-bs-toggle="collapse" data-bs-target="#accordion-style-04-05" aria-expanded="false" data-bs-parent="#accordion-style-04">
                                                        <div class="accordion-title mb-0 position-relative text-dark-gray">
                                                            <i class="feather icon-feather-plus"></i><span class="fw-500 fs-17">How do i know if my order was successful?</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div id="accordion-style-04-05" class="accordion-collapse collapse" data-bs-parent="#accordion-style-04">
                                                    <div class="accordion-body last-paragraph-no-margin border-bottom border-color-light-medium-gray">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took galley of type and scrambled to make type.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end accordion item -->
                                            <!-- start accordion item -->
                                            <div class="accordion-item">
                                                <div class="accordion-header border-bottom border-color-transparent">
                                                    <a href="#" data-bs-toggle="collapse" data-bs-target="#accordion-style-04-06" aria-expanded="false" data-bs-parent="#accordion-style-04">
                                                        <div class="accordion-title mb-0 position-relative text-dark-gray">
                                                            <i class="feather icon-feather-plus"></i><span class="fw-500 fs-17">What if my order is incorrect?</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div id="accordion-style-04-06" class="accordion-collapse collapse" data-bs-parent="#accordion-style-04">
                                                    <div class="accordion-body last-paragraph-no-margin border-bottom border-color-transparent">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took galley of type and scrambled to make type.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end accordion item -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end tab content -->
                            <!-- start tab content -->
                            <div class="tab-pane fade in h-100" id="tab_seven5">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="accordion accordion-style-02" id="accordion-style-05" data-active-icon="icon-feather-minus" data-inactive-icon="icon-feather-plus">
                                            <!-- start accordion item -->
                                            <div class="accordion-item active-accordion">
                                                <div class="accordion-header border-bottom border-color-extra-medium-gray pt-0">
                                                    <a href="#" data-bs-toggle="collapse" data-bs-target="#accordion-style-05-01" aria-expanded="true" data-bs-parent="#accordion-style-05">
                                                        <div class="accordion-title mb-0 position-relative text-dark-gray">
                                                            <i class="feather icon-feather-minus"></i><span class="fw-500 fs-17">Can i order over the phone?</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div id="accordion-style-05-01" class="accordion-collapse collapse show" data-bs-parent="#accordion-style-05">
                                                    <div class="accordion-body last-paragraph-no-margin border-bottom border-color-light-medium-gray">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took galley of type and scrambled to make type.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end accordion item -->
                                            <!-- start accordion item -->
                                            <div class="accordion-item">
                                                <div class="accordion-header border-bottom border-color-extra-medium-gray">
                                                    <a href="#" data-bs-toggle="collapse" data-bs-target="#accordion-style-05-02" aria-expanded="false" data-bs-parent="#accordion-style-05">
                                                        <div class="accordion-title mb-0 position-relative text-dark-gray">
                                                            <i class="feather icon-feather-plus"></i><span class="fw-500 fs-17">I am having difficulty placing an order?</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div id="accordion-style-05-02" class="accordion-collapse collapse" data-bs-parent="#accordion-style-05">
                                                    <div class="accordion-body last-paragraph-no-margin border-bottom border-color-light-medium-gray">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took galley of type and scrambled to make type.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end accordion item -->
                                            <!-- start accordion item -->
                                            <div class="accordion-item">
                                                <div class="accordion-header border-bottom border-color-light-medium-gray">
                                                    <a href="#" data-bs-toggle="collapse" data-bs-target="#accordion-style-05-03" aria-expanded="false" data-bs-parent="#accordion-style-05">
                                                        <div class="accordion-title mb-0 position-relative text-dark-gray">
                                                            <i class="feather icon-feather-plus"></i><span class="fw-500 fs-17">What payment methods does accept?</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div id="accordion-style-05-03" class="accordion-collapse collapse" data-bs-parent="#accordion-style-05">
                                                    <div class="accordion-body last-paragraph-no-margin border-bottom border-color-light-medium-gray">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took galley of type and scrambled to make type.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end accordion item -->
                                            <!-- start accordion item -->
                                            <div class="accordion-item">
                                                <div class="accordion-header border-bottom border-color-light-medium-gray">
                                                    <a href="#" data-bs-toggle="collapse" data-bs-target="#accordion-style-05-04" aria-expanded="false" data-bs-parent="#accordion-style-05">
                                                        <div class="accordion-title mb-0 position-relative text-dark-gray">
                                                            <i class="feather icon-feather-plus"></i><span class="fw-500 fs-17">Can i amend my order once placed?</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div id="accordion-style-05-04" class="accordion-collapse collapse" data-bs-parent="#accordion-style-05">
                                                    <div class="accordion-body last-paragraph-no-margin border-bottom border-color-light-medium-gray">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took galley of type and scrambled to make type.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end accordion item -->
                                            <!-- start accordion item -->
                                            <div class="accordion-item">
                                                <div class="accordion-header border-bottom border-color-light-medium-gray">
                                                    <a href="#" data-bs-toggle="collapse" data-bs-target="#accordion-style-05-05" aria-expanded="false" data-bs-parent="#accordion-style-05">
                                                        <div class="accordion-title mb-0 position-relative text-dark-gray">
                                                            <i class="feather icon-feather-plus"></i><span class="fw-500 fs-17">How do i know if my order was successful?</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div id="accordion-style-05-05" class="accordion-collapse collapse" data-bs-parent="#accordion-style-05">
                                                    <div class="accordion-body last-paragraph-no-margin border-bottom border-color-light-medium-gray">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took galley of type and scrambled to make type.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end accordion item -->
                                            <!-- start accordion item -->
                                            <div class="accordion-item">
                                                <div class="accordion-header border-bottom border-color-transparent">
                                                    <a href="#" data-bs-toggle="collapse" data-bs-target="#accordion-style-05-06" aria-expanded="false" data-bs-parent="#accordion-style-05">
                                                        <div class="accordion-title mb-0 position-relative text-dark-gray">
                                                            <i class="feather icon-feather-plus"></i><span class="fw-500 fs-17">What if my order is incorrect?</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div id="accordion-style-05-06" class="accordion-collapse collapse" data-bs-parent="#accordion-style-05">
                                                    <div class="accordion-body last-paragraph-no-margin border-bottom border-color-transparent">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took galley of type and scrambled to make type.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end accordion item -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end tab content -->
                            <!-- start tab content -->
                            <div class="tab-pane fade in h-100" id="tab_seven6">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="accordion accordion-style-02" id="accordion-style-06" data-active-icon="icon-feather-minus" data-inactive-icon="icon-feather-plus">
                                            <!-- start accordion item -->
                                            <div class="accordion-item active-accordion">
                                                <div class="accordion-header border-bottom border-color-extra-medium-gray pt-0">
                                                    <a href="#" data-bs-toggle="collapse" data-bs-target="#accordion-style-06-01" aria-expanded="true" data-bs-parent="#accordion-style-06">
                                                        <div class="accordion-title mb-0 position-relative text-dark-gray">
                                                            <i class="feather icon-feather-minus"></i><span class="fw-500 fs-17">Can i order over the phone?</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div id="accordion-style-06-01" class="accordion-collapse collapse show" data-bs-parent="#accordion-style-06">
                                                    <div class="accordion-body last-paragraph-no-margin border-bottom border-color-light-medium-gray">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took galley of type and scrambled to make type.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end accordion item -->
                                            <!-- start accordion item -->
                                            <div class="accordion-item">
                                                <div class="accordion-header border-bottom border-color-extra-medium-gray">
                                                    <a href="#" data-bs-toggle="collapse" data-bs-target="#accordion-style-06-02" aria-expanded="false" data-bs-parent="#accordion-style-06">
                                                        <div class="accordion-title mb-0 position-relative text-dark-gray">
                                                            <i class="feather icon-feather-plus"></i><span class="fw-500 fs-17">I am having difficulty placing an order?</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div id="accordion-style-06-02" class="accordion-collapse collapse" data-bs-parent="#accordion-style-06">
                                                    <div class="accordion-body last-paragraph-no-margin border-bottom border-color-light-medium-gray">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took galley of type and scrambled to make type.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end accordion item -->
                                            <!-- start accordion item -->
                                            <div class="accordion-item">
                                                <div class="accordion-header border-bottom border-color-light-medium-gray">
                                                    <a href="#" data-bs-toggle="collapse" data-bs-target="#accordion-style-06-03" aria-expanded="false" data-bs-parent="#accordion-style-06">
                                                        <div class="accordion-title mb-0 position-relative text-dark-gray">
                                                            <i class="feather icon-feather-plus"></i><span class="fw-500 fs-17">What payment methods does accept?</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div id="accordion-style-06-03" class="accordion-collapse collapse" data-bs-parent="#accordion-style-06">
                                                    <div class="accordion-body last-paragraph-no-margin border-bottom border-color-light-medium-gray">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took galley of type and scrambled to make type.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end accordion item -->
                                            <!-- start accordion item -->
                                            <div class="accordion-item">
                                                <div class="accordion-header border-bottom border-color-light-medium-gray">
                                                    <a href="#" data-bs-toggle="collapse" data-bs-target="#accordion-style-06-04" aria-expanded="false" data-bs-parent="#accordion-style-06">
                                                        <div class="accordion-title mb-0 position-relative text-dark-gray">
                                                            <i class="feather icon-feather-plus"></i><span class="fw-500 fs-17">Can i amend my order once placed?</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div id="accordion-style-06-04" class="accordion-collapse collapse" data-bs-parent="#accordion-style-06">
                                                    <div class="accordion-body last-paragraph-no-margin border-bottom border-color-light-medium-gray">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took galley of type and scrambled to make type.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end accordion item -->
                                            <!-- start accordion item -->
                                            <div class="accordion-item">
                                                <div class="accordion-header border-bottom border-color-light-medium-gray">
                                                    <a href="#" data-bs-toggle="collapse" data-bs-target="#accordion-style-06-05" aria-expanded="false" data-bs-parent="#accordion-style-06">
                                                        <div class="accordion-title mb-0 position-relative text-dark-gray">
                                                            <i class="feather icon-feather-plus"></i><span class="fw-500 fs-17">How do i know if my order was successful?</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div id="accordion-style-06-05" class="accordion-collapse collapse" data-bs-parent="#accordion-style-06">
                                                    <div class="accordion-body last-paragraph-no-margin border-bottom border-color-light-medium-gray">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took galley of type and scrambled to make type.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end accordion item -->
                                            <!-- start accordion item -->
                                            <div class="accordion-item">
                                                <div class="accordion-header border-bottom border-color-transparent">
                                                    <a href="#" data-bs-toggle="collapse" data-bs-target="#accordion-style-06-06" aria-expanded="false" data-bs-parent="#accordion-style-06">
                                                        <div class="accordion-title mb-0 position-relative text-dark-gray">
                                                            <i class="feather icon-feather-plus"></i><span class="fw-500 fs-17">What if my order is incorrect?</span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div id="accordion-style-06-06" class="accordion-collapse collapse" data-bs-parent="#accordion-style-06">
                                                    <div class="accordion-body last-paragraph-no-margin border-bottom border-color-transparent">
                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took galley of type and scrambled to make type.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end accordion item -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end tab content -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end section -->
@endsection

