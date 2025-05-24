@extends('layouts.layout')

@section('content')
<section class="pt-0">
    <div class="container">
        <div class="row g-0 justify-content-center">
            <div class="col-xl-4 col-lg-5 col-md-10 contact-form-style-04 md-mb-50px">
                <img src="{{ asset('assets/images/shop/demo-fashion-store-product-01.jpg') }}" alt="">
            </div>
            <div class="col-lg-6 col-md-10 offset-xl-2 offset-lg-1 p-6 box-shadow-extra-large border-radius-6px">
                <span class="fs-26 alt-font fw-600 text-dark-gray mb-20px d-block">Quên mật khẩu</span>

                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div>
                        <label for="email" class="text-dark-gray mb-10px fw-500">
                            Nhập địa chỉ Email của bạn<span class="text-red">*</span>
                        </label>
                        <input id="email" type="email"
                            class="mb-20px bg-very-light-gray form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                            placeholder="Email của bạn">

                        @error('email')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-medium btn-round-edge btn-dark-gray btn-box-shadow w-100">
                        Gửi liên kết đặt lại mật khẩu
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
