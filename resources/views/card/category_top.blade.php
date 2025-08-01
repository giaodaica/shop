<section class="half-section">
    <div class="container">
        <div class="row row-cols-1 row-cols-xl-4 row-cols-lg-4 row-cols-md-2 row-cols-sm-2"
            data-anime='{ "el": "childs", "translateX": [30, 0], "opacity": [0,1], "duration": 800, "delay": 200, "staggervalue": 300, "easing": "easeOutQuad" }'>
            <!-- start features box item -->
            <div class="col icon-with-text-style-01 md-mb-35px">
                <div class="feature-box feature-box-left-icon-middle last-paragraph-no-margin">
                    <div class="feature-box-icon me-20px">
                        <i class="line-icon-Box-Close icon-large text-dark-gray"></i>
                    </div>
                    <div class="feature-box-content">
                        <span class="alt-font fs-20 fw-500 d-block text-dark-gray">Miễn phí giao hàng</span>
                        <p class="fs-16 lh-24">Cho đơn từ 200k</p>
                    </div>
                </div>
            </div>
            <!-- end features box item -->
            <!-- start features box item -->
            <div class="col icon-with-text-style-01 md-mb-35px">
                <div class="feature-box feature-box-left-icon-middle last-paragraph-no-margin">
                    <div class="feature-box-icon me-20px">
                        <i class="line-icon-Reload-3 icon-large text-dark-gray"></i>
                    </div>
                    <div class="feature-box-content">
                        <span class="alt-font fs-20 fw-500 d-block text-dark-gray">Hoàn trả</span>
                        <p class="fs-16 lh-24">Hoàn tiền từ 3-7 ngày</p>
                    </div>
                </div>
            </div>
            <!-- end features box item -->
            <!-- start features box item -->
            <div class="col icon-with-text-style-01 xs-mb-35px">
                <div class="feature-box feature-box-left-icon-middle last-paragraph-no-margin">
                    <div class="feature-box-icon me-20px">
                        <i class="line-icon-Credit-Card2 icon-large text-dark-gray"></i>
                    </div>
                    <div class="feature-box-content">
                        <span class="alt-font fs-20 fw-500 d-block text-dark-gray">Thanh toán an toàn</span>
                        <p class="fs-16 lh-24">Được bảo vệ 100%</p>
                    </div>
                </div>
            </div>
            <!-- end features box item -->
            <!-- start features box item -->
            <div class="col icon-with-text-style-01">
                <div class="feature-box feature-box-left-icon-middle last-paragraph-no-margin">
                    <div class="feature-box-icon me-20px">
                        <i class="line-icon-Phone-2 icon-large text-dark-gray"></i>
                    </div>
                    <div class="feature-box-content">
                        <span class="alt-font fs-20 fw-500 d-block text-dark-gray">Hỗ trợ</span>
                        <p class="fs-16 lh-24">Hỗ trợ 24/7</p>
                    </div>
                </div>
            </div>
            <!-- end features box item -->
        </div>
    </div>
</section>
@if($category_product->isEmpty())

@else
<section class="pt-0 pb-0 ps-7 pe-7 lg-ps-3 lg-pe-3 xs-p-0">
    <div class="container-fluid">
        <div class="row row-cols-1 row-cols-xl-4 row-cols-lg-2 row-cols-md-2" data-anime='{ "el": "childs", "translateY": [-15, 0], "perspective": [1200,1200], "scale": [1.1, 1], "rotateX": [50, 0], "opacity": [0,1], "duration": 400, "delay": 100, "staggervalue": 200, "easing": "easeOutQuad" }'>
            <!-- start categories style -->
            @foreach ($category_product as $render_category )
                <div class="col categories-style-02 lg-mb-30px">
                <div class="categories-box">
                    <a href="{{ route('home.shop',  ['categories[]' => $render_category->id]) }}">
                        <img class="sm-w-100" src="{{asset($render_category->image)}}" alt=""/>
                    </a>
                    <div class="border-color-transparent-dark-very-light border alt-font fw-500 text-dark-gray text-uppercase ps-15px pe-15px fs-11 lh-26 border-radius-100px d-inline-block position-absolute right-20px top-20px">8 items</div>
                    <div class="absolute-bottom-center bottom-40px md-bottom-25px">
                        <a href="" class="btn btn-white btn-switch-text btn-round-edge btn-box-shadow fs-18 text-uppercase-inherit p-5 min-w-150px">
                            <span>
                                <span class="btn-double-text ls-0px" data-text="Đồ nữ">{{$render_category->name}}</span>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
            <!-- end categories style -->
            <!-- start categories style -->

            <!-- end categories style -->
            <!-- start categories style -->

            <!-- end categories style -->
            <!-- start categories style -->

            <!-- end categories style -->
        </div>
    </div>
</section>
@endif
