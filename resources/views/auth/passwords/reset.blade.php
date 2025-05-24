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
                        <li><a href="{{ route('home') }}">Trang chủ</a></li>
                        <li>Đặt lại mật khẩu</li>
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
                    <span class="fs-26 xs-fs-24 alt-font fw-600 text-dark-gray mb-20px d-block">Đặt lại mật khẩu</span>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div>
                            <label class="text-dark-gray mb-10px fw-500" for="email">Email<span
                                    class="text-red">*</span></label>
                            <input id="email" type="email"
                                class="mb-20px bg-very-light-gray form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus
                                placeholder="Nhập email của bạn" />
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div>
                            <label class="text-dark-gray mb-10px fw-500" for="password">Mật khẩu mới<span
                                    class="text-red">*</span></label>
                            <input id="password" type="password"
                                class="mb-20px bg-very-light-gray form-control @error('password') is-invalid @enderror"
                                name="password" required autocomplete="new-password" placeholder="Nhập mật khẩu mới" />
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div>
                            <label class="text-dark-gray mb-10px fw-500" for="password-confirm">Xác nhận mật khẩu<span
                                    class="text-red">*</span></label>
                            <input id="password-confirm" type="password" class="mb-30px bg-very-light-gray form-control"
                                name="password_confirmation" required autocomplete="new-password"
                                placeholder="Nhập lại mật khẩu" />
                        </div>

                        <button type="submit" class="btn btn-medium btn-round-edge btn-dark-gray btn-box-shadow w-100">
                            Đặt lại mật khẩu
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </section>
@endsection
