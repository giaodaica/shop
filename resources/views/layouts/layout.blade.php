<!doctype html>
<html class="no-js" lang="en">
@include('card.head')
<style>
    .nav-link.no-wrap{
        white-space: nowrap;
    }
</style>
    @yield('cdn-custom')

{{-- @vite(['resources/js/app.js']) --}}
    <body data-mobile-nav-style="classic">
        <!-- start header -->
        <header class="header-with-topbar">
            <!-- start header top bar -->
            <div class="header-top-bar top-bar-light bg-base-color disable-fixed md-border-bottom border-color-transparent-dark-very-light">
                <div class="container-fluid">
                    <div class="row h-40px align-items-center m-0">
                        <div class="col-12 justify-content-center alt-font fs-13 fw-500 text-uppercase">
                            <div class="text-dark-gray">Miễn phí giao hàng với đơn hàng từ 200.000 VND.</div>
                            <a href="#" class="text-dark-gray fw-600 ms-5px text-dark-gray-hover"><span class="text-decoration-line-bottom">Mua Ngay</span></a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end header top bar -->
            <!-- start navigation -->
          @include('card.nav')
        </header>
        <!-- end header -->
      @yield('content')
        <!-- start footer -->
        @include('card.footer')
        <!-- end footer -->
        <!-- start cookie message -->
        <div id="cookies-model" class="cookie-message bg-dark-gray border-radius-8px">
            <div class="cookie-description fs-14 text-white mb-20px lh-22">We use cookies to enhance your browsing experience, serve personalized ads or content, and analyze our traffic. By clicking "Allow cookies" you consent to our use of cookies. </div>
            <div class="cookie-btn">
                <a href="#" class="btn btn-transparent-white border-1 border-color-transparent-white-light btn-very-small btn-switch-text btn-rounded w-100 mb-15px" aria-label="btn">
                    <span>
                        <span class="btn-double-text" data-text="Cookie policy">Cookie policy</span>
                    </span>
                </a>
                <a href="#" class="btn btn-white btn-very-small btn-switch-text btn-box-shadow accept_cookies_btn btn-rounded w-100" data-accept-btn aria-label="text">
                    <span>
                        <span class="btn-double-text" data-text="Allow cookies">Allow cookies</span>
                    </span>
                </a>
            </div>
        </div>
        <!-- end cookie message -->
        <!-- start sticky elements -->
        <div class="sticky-wrap z-index-1 d-none d-xl-inline-block" data-animation-delay="100" data-shadow-animation="true">
            <div class="elements-social social-icon-style-10">
                <ul class="fs-16">
                    <li class="me-30px"><a class="facebook" href="https://www.facebook.com/" target="_blank">
                            <i class="fa-brands fa-facebook-f me-10px"></i>
                            <span class="alt-font">Facebook</span>
                        </a>
                    </li>
                    <li class="me-30px">
                        <a class="dribbble" href="http://www.dribbble.com" target="_blank">
                            <i class="fa-brands fa-dribbble me-10px"></i>
                            <span class="alt-font">Dribbble</span>
                        </a>
                    </li>
                    <li class="me-30px">
                        <a class="twitter" href="http://www.twitter.com" target="_blank">
                            <i class="fa-brands fa-twitter me-10px"></i>
                            <span class="alt-font">Twitter</span>
                        </a>
                    </li>
                    <li>
                        <a class="instagram" href="http://www.instagram.com" target="_blank">
                            <i class="fa-brands fa-instagram me-10px"></i>
                            <span class="alt-font">Instagram</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- end sticky elements -->
        <!-- start scroll progress -->
        <div class="scroll-progress d-none d-xxl-block">
            <a href="#" class="scroll-top" aria-label="scroll">
                <span class="scroll-text">Cuộn</span><span class="scroll-line"><span class="scroll-point"></span></span>
            </a>
        </div>
        <!-- end scroll progress -->
        <!-- javascript libraries -->
        @include('card.js')
@yield('js-page-custom')
    </body>
</html>
