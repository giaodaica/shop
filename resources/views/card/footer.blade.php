<footer class="footer-dark bg-dark-gray p-0">
    <div class="container">
        <div class="row align-items-center pt-35px pb-35px">
            <!-- start footer column -->
            <div class="col-12 col-md-auto sm-mb-15px text-center text-md-start">
                <a href="{{route('home')}}" class="footer-logo"><img
                        src="{{ asset('assets/images/logotrang2.png') }}"
                        data-at2x="{{ asset('assets/images/logotrang2.png') }}" alt="" class="default-logo"></a>
            </div>
            <!-- end footer column -->
            <!-- start footer column -->
            <div class="col">
                <ul class="footer-navbar text-center text-md-end">
                    <li class="nav-item"><a href="{{ url('/') }}" class="nav-link">Trang chủ</a></li>
                    <li class="nav-item"><a href="{{ url('shop') }}" class="nav-link">Cửa hàng</a></li>
                </ul>
            </div>
            <!-- end footer column -->
        </div>
        <div class="row justify-content-center fs-15 lh-28 pb-50px xs-pb-35px">
            <div class="col-12 mb-50px sm-mb-35px">
                <div class="divider-style-03 divider-style-03-01 border-color-transparent-white-light"></div>
            </div>
            <!-- start footer column -->
            <div class="col-6 col-lg-2 col-sm-4 xs-mb-30px order-sm-3 order-lg-2">
                <span class="fw-500 d-block text-white mb-5px fs-17">Danh mục</span>
                @if ($banner->isEmpty())
                @else
                    @foreach ($banner as $render_footer)
                        <ul>
                            <li> <a href="{{ route('home.shop',  ['categories[]' => $render_footer->id]) }}">{{$render_footer->name}}</li>
                        </ul>
                    @endforeach
                @endif

            </div>
            <!-- end footer column -->
            <!-- start footer column -->
            <div class="col-6 col-lg-2 col-sm-4 xs-mb-30px order-sm-3 order-lg-2">
                <span class="fw-500 d-block text-white mb-5px fs-17">Thông tin</span>
                <ul>
                    <li><a href="demo-fashion-store-about.html">Về chúng tôi</a></li>
                    <li><a href="demo-fashion-store-contact.html">Liên hệ</a></li>
                    <li><a href="#">Điều khoản</a></li>
                    <li><a href="#">Chính sách bảo mật</a></li>
                </ul>
            </div>
            <!-- end footer column -->
            <!-- start footer column -->
            <div class="col-6 col-lg-2 col-sm-4 xs-mb-30px order-sm-3 order-lg-2">
                <span class="fw-500 d-block text-white mb-5px fs-17">Hỗ trợ dự án tốt nghiệp</span>
                <ul>
                    <li><a href="#">Vấn đề</a></li>
                    <li><a href="#">Giải pháp</a></li>
                    <li><a href="#">Cửa hàng của chúng tôi</a></li>
                    <li><a target="_blank" href="https://fb.com/trantrunghieu.04">Liên hệ</a></li>
                </ul>
            </div>
            <!-- end footer column -->
            <!-- start footer column -->
            <div
                class="col-6 col-lg-3 col-md-4 col-sm-5 md-mb-50px xs-mb-30px order-sm-2 order-lg-2 offset-md-2 offset-lg-0">
                <span class="fw-500 d-block text-white mb-10px fs-17">Thông tin liên hệ</span>
                <div><i class="feather icon-feather-phone-call fs-16 text-white me-10px xs-me-5px"></i><a
                        href="tel:0775713230">077 571 3230</a></div>
                <div class="mb-15px"><i class="feather icon-feather-mail fs-16 text-white me-10px xs-me-5px"></i><a
                        href="mailto:hieutestmail911@gmail.com" class="text-decoration-line-bottom">hieutestmail911@gmail.com</a></div>
                {{-- <span class="fw-500 d-block text-white mb-5px fs-17">Kết nối với chúng tôi</span> --}}
                {{-- <div class="elements-social social-icon-style-02">
                    <ul class="light">
                        <li><a class="facebook" href="https://www.facebook.com/" target="_blank"><i
                                    class="fa-brands fa-facebook-f"></i></a></li>
                        <li><a class="dribbble" href="http://www.dribbble.com" target="_blank"><i
                                    class="fa-brands fa-dribbble"></i></a></li>
                        <li><a class="twitter" href="http://www.twitter.com" target="_blank"><i
                                    class="fa-brands fa-twitter"></i></a></li>
                        <li><a class="instagram" href="http://www.instagram.com" target="_blank"><i
                                    class="fa-brands fa-instagram"></i></a></li>
                    </ul>
                </div> --}}
            </div>
            <!-- end footer column -->
            <!-- start footer column -->
            {{-- <div class="col-lg-3 col-md-6 col-sm-7 ps-20px sm-ps-15px md-mb-50px xs-mb-0 order-sm-1 order-lg-5">
                <span class="fw-500 d-block text-white mb-5px fs-17">Become a member</span>
                <div class="mb-15px">Join now and get 20% extra discount!</div>
                <div class="d-inline-block w-100 newsletter-style-04 position-relative mb-15px">
                    <form action="email-templates/subscribe-newsletter.php" method="post"
                        class="position-relative w-100">
                        <input
                            class="input-small bg-nero-grey border-radius-4px border-color-transparent w-100 form-control pe-50px ps-20px lg-ps-15px required"
                            type="email" name="email" placeholder="Enter your email" />
                        <input type="hidden" name="redirect" value="">
                        <button class="btn pe-20px submit" aria-label="submit"><i
                                class="icon bi bi-envelope icon-small text-white"></i></button>
                        <div
                            class="form-results border-radius-4px pt-5px pb-5px ps-15px pe-15px fs-14 lh-22 mt-10px w-100 text-center position-absolute d-none">
                        </div>
                    </form>
                </div>
                <div class="footer-card">
                    <a href="#" class="d-inline-block me-5px align-middle"><img
                            src="{{ asset('assets/images/shop/demo-decor-store-payment-icon-01.webp') }}"
                            alt=""></a>
                    <a href="#" class="d-inline-block me-5px align-middle"><img
                            src="{{ asset('assets/images/shop/demo-decor-store-payment-icon-01.webp') }}"
                            alt=""></a>
                    <a href="#" class="d-inline-block me-5px align-middle"><img
                            src="{{ asset('assets/images/shop/demo-decor-store-payment-icon-01.webp') }}"
                            alt=""></a>
                    <a href="#" class="d-inline-block me-5px align-middle"><img
                            src="{{ asset('assets/images/shop/demo-decor-store-payment-icon-01.webp') }}"
                            alt=""></a>
                </div>
            </div> --}}
            <!-- end footer column -->
        </div>
    </div>
    <div class="pt-30px pb-30px bg-nero-grey">
        <div class="container">
            <div class="row align-items-center fs-15">
                <div class="col-12 col-lg-7 last-paragraph-no-margin md-mb-15px text-center text-lg-start lh-22">
                    <p>Nhóm WD-57<a href="#"
                            class="text-white text-decoration-line-bottom">chính sách bảo mật</a> and <a href="#"
                            class="text-white text-decoration-line-bottom">điều khoản dịch vụ.</a></p>
                </div>
                <div class="col-12 col-lg-5 text-center text-lg-end lh-22">
                    <span>&copy; 2025  by <a href="fb.com/trantrunghieu.04" target="_blank"
                            class="text-decoration-line-bottom text-white">WD-57</a></span>
                </div>
            </div>
        </div>
    </div>
</footer>
