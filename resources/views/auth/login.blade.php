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
                    <span class="fs-26 xs-fs-24 alt-font fw-600 text-dark-gray mb-20px d-block">Đăng nhập</span>
                    <form action="{{ route('login') }}" method="post">
                        @csrf
                        <div>
                            <label class="text-dark-gray mb-10px fw-500" for="email">{{ __('Email') }}<span
                                    class="text-red">*</span></label>
                            <input id="email"
                                class="mb-20px bg-very-light-gray form-control required @error('email') is-invalid @enderror"
                                type="email" value="{{ old('email') }}" name="email" placeholder="Nhập email của bạn"
                                required autocomplete="email" autofocus />
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="text-dark-gray mb-F10px fw-500">{{ __('Mật khẩu') }}<span
                                    class="text-red">*</span></label>
                            <input id="password"
                                class="mb-20px bg-very-light-gray form-control required @error('password') is-invalid @enderror"
                                type="password" name="password" placeholder="Nhập mật khẩu của bạn" required
                                autocomplete="current-password" />
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="position-relative terms-condition-box text-start d-flex align-items-center mb-20px">
                            <label>
                                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}
                                    class="terms-condition check-box align-middle required">
                                <span class="box fs-14">Ghi nhớ</span>
                            </label>
                            @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="fs-14 text-dark-gray fw-500 text-decoration-line-bottom ms-auto">Quên Mật Khẩu</a>
                        @endif
                        </div>

                        <span class="fs-13 lh-22 w-90 lg-w-100 md-w-90 sm-w-100 d-block mb-30px">Dữ liệu cá nhân của bạn sẽ
                            được sử dụng để hỗ trợ trải nghiệm trên trang web này, quản lý quyền truy cập vào tài khoản của
                            bạn và cho các mục đích khác được mô tả trong <a href="#"
                                class="text-dark-gray text-decoration-line-bottom fw-500">chính sách bảo mật</a> của chúng
                            tôi.</span>
                            <a href="{{route('register')}}" class="text-primary">Chưa có tài khoản đăng ký ngay</a>
                        <button class="btn btn-medium btn-round-edge btn-dark-gray btn-box-shadow w-100"
                            type="submit">Đăng nhập</button>
                        <div class="form-results mt-20px d-none"></div>
                    </form>
                    <!-- Thêm nút Đăng nhập bằng Google -->
                    <div class="mt-20px">
                        <a href="{{ route('google.redirect') }}"
                            class="btn btn-medium btn-round-edge btn-outline-dark btn-box-shadow w-100 d-flex align-items-center justify-content-center">
                            <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google"
                                style="width: 20px; margin-right: 10px;">
                            <span class="text-black">Đăng nhập bằng Google</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
