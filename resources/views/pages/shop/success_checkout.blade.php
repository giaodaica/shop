<!doctype html>
<html class="no-js" lang="en">
@include('card.head')
    <body data-mobile-nav-style="classic">
        <!-- start header -->

        <!-- end header -->
        <!-- start section -->
        <section class="cover-background full-screen ipad-top-space-margin md-h-550px" style="background-image: url({{asset('assets/images/404-bg.jpg')}});">
            <div class="container h-100">
                <div class="row align-items-center justify-content-center h-100">
                    <div class="col-12 col-xl-6 col-lg-7 col-md-9 text-center" data-anime='{ "el": "childs", "translateY": [50, 0], "opacity": [0,1], "duration": 600, "delay": 0, "staggervalue": 300, "easing": "easeOutQuad" }'>
                        <h1 class="fs-170 sm-fs-150 text-dark-gray fw-700 ls-minus-6px">Wow!</h1>
                        <h4 class="text-dark-gray fw-600 sm-fs-22 mb-10px ls-minus-1px">Cảm ơn bạn đã mua hàng</h4>
                        <p class="mb-30px lh-28 sm-mb-30px">Đơn hàng của bạn đã được chúng tôi tiếp nhận để tra cứu đơn hàng hãy dùng mã GIAODAICA.</p>
                        <a href="{{route('home')}}" class="btn btn-large left-icon btn-rounded btn-dark-gray btn-box-shadow text-transform-none"><i class="fa-solid fa-arrow-left"></i>Trở về trang chủ</a>
                    </div>
                </div>
            </div>
        </section>
        <!-- end section -->
        <!-- start footer -->

        <!-- end footer -->
        <!-- javascript libraries -->
       @include('card.js')
    </body>
</html>
