@extends('layouts.layout')
@section('content')
    <!-- start section -->
    <section class="top-space-margin half-section bg-gradient-very-light-gray">
        <div class="container">
            <div class="row align-items-center justify-content-center"
                data-anime='{ "el": "childs", "translateY": [-15, 0], "opacity": [0,1], "duration": 300, "delay": 0, "staggervalue": 200, "easing": "easeOutQuad" }'>
                <div class="col-12 col-xl-8 col-lg-10 text-center position-relative page-title-extra-large">
                </div>
                <div class="col-12 breadcrumb breadcrumb-style-01 d-flex justify-content-center">
                    <ul>
                        <li><a href="demo-fashion-store.html">Trang chủ</a></li>
                        <li>Tài khoản</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- end section -->
    <!-- start section -->
    <section class="pt-0">
        <div class="container">
            <div class="row g-0 justify-content-center">
                <div class="col-xl-4 col-lg-5 col-md-10 contact-form-style-04 md-mb-50px"
                    data-anime='{ "translateY": [0, 0], "opacity": [0,1], "duration": 600, "delay":100, "staggervalue": 150, "easing": "easeOutQuad" }'>
                    <img src="{{ asset('assets/images/shop/demo-fashion-store-product-01.jpg') }}" alt="">
                </div>
                <div class="col-lg-6 col-md-10 offset-xl-2 offset-lg-1 p-6 box-shadow-extra-large border-radius-6px"
                    data-anime='{ "translateY": [0, 0], "opacity": [0,1], "duration": 600, "delay":150, "staggervalue": 150, "easing": "easeOutQuad" }'>
                    <span class="fs-26 xs-fs-24 alt-font fw-600 text-dark-gray mb-20px d-block">Đăng ký tài khoản</span>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div>
                            <label class="text-dark-gray mb-10px fw-500">Họ và tên<span class="text-red">*</span></label>
                            <input id="name"
                                class="mb-20px bg-very-light-gray form-control required @error('name') is-invalid @enderror"
                                name="name" type="text" placeholder="Nhập tên của bạn" value="{{ old('name') }}"
                                required autocomplete="name" autofocus />
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div>
                            <label class="text-dark-gray mb-10px fw-500">Email<span class="text-red">*</span></label>
                            <input id="email"
                                class="mb-20px bg-very-light-gray form-control required @error('email') is-invalid @enderror"
                                name="email" type="text" placeholder="Nhập email của bạn" value="{{ old('email') }}"
                                required autocomplete="email" />
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div>
                            <label for="password" class="text-dark-gray mb-10px fw-500">Mật khẩu<span
                                    class="text-red">*</span></label>
                            <input id="password"
                                class="mb-20px bg-very-light-gray form-control required @error('password') is-invalid @enderror"
                                type="password" name="password" placeholder="Nhập mật khẩu" required
                                autocomplete="new-password" />
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <label for="password-confirm" class="text-dark-gray mb-10px fw-500">Xác nhận mật khẩu<span
                                class="text-red">*</span></label>
                        <input id="password-confirm" class="mb-20px bg-very-light-gray form-control required"
                            type="password" name="password_confirmation" placeholder="Nhập lại mật khẩu" required
                            autocomplete="new-password" />

                        <span class="fs-13 lh-22 w-90 lg-w-100 md-w-90 sm-w-100 d-block mb-30px">Dữ liệu cá nhân của bạn sẽ
                            được sử dụng để hỗ trợ trải nghiệm trên trang web này, quản lý quyền truy cập vào tài khoản của
                            bạn và cho các mục đích khác được mô tả trong <a href="#"
                                class="text-dark-gray text-decoration-line-bottom fw-500">chính sách bảo mật</a> của chúng
                            tôi.</span>
                        <a href="{{route('login')}}" class="text-primary">Đã có tài khoản đăng nhập ngay?</a>
                        <button class="btn btn-medium btn-round-edge btn-dark-gray btn-box-shadow w-100" type="submit">Đăng
                            ký</button>
                        <div class="form-results mt-20px d-none"></div>
                        <div class="mt-20px">
                            <a href="{{ route('google.redirect') }}"
                                class="btn btn-medium btn-round-edge btn-outline-dark btn-box-shadow w-100 d-flex align-items-center justify-content-center">
                                <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google"
                                    style="width: 20px; margin-right: 10px;">
                                <span class="text-black">Đăng ký bằng Google</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
