@extends('layouts.layout');
@section('content')
    <section class="top-space-margin half-section bg-gradient-very-light-gray">
        <div class="container">
            <div class="row align-items-center justify-content-center"
                data-anime='{ "el": "childs", "translateY": [-15, 0], "opacity": [0,1], "duration": 300, "delay": 0, "staggervalue": 200, "easing": "easeOutQuad" }'>
                <div class="col-12 col-xl-8 col-lg-10 text-center position-relative page-title-extra-large">
                    <h1 class="alt-font fw-600 text-dark-gray mb-10px">Liên hệ</h1>
                </div>
                <div class="col-12 breadcrumb breadcrumb-style-01 d-flex justify-content-center">
                    <ul>
                        <li><a href="{{ route('home') }}">Trang chủ</a></li>
                        <li>Liên hệ</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- end section -->
    <!-- start section -->
    <section class="pt-0 position-relative overflow-hidden">
        <div class="container">
            <div class="row">
                <div class="col-xxl-5 col-xl-6 col-lg-6 md-mb-8 text-center text-sm-start"
                    data-anime='{ "el": "childs", "translateY": [30, 0], "opacity": [0,1], "duration": 300, "delay": 0, "staggervalue": 300, "easing": "easeOutQuad" }'>
                    <div class="alt-font text-dark-gray mb-15px fs-20"><span class="text-highlight">Hãy liên hệ với chúng
                            tôi<span class="bg-base-color h-8px bottom-0px"></span></span></div>
                    <h2 class="alt-font text-dark-gray fw-400 ls-minus-1px">Gọi điện hoặc email <span class="fw-600">cho
                            tôi.</span></h2>
                    <div class="fs-22 fw-700 text-dark-gray mb-10px">Hà Nội</div>
                    <div class="row row-cols-1 row-cols-sm-2 mb-10">
                        <div class="col last-paragraph-no-margin xs-mb-20px">
                            <span class="fs-18 fw-600 d-block text-dark-gray">Hoàng Mai</span>
                            <p class="w-95 xl-w-100">OCT1<br>Bắc Linh Đàm</p>
                        </div>
                        <div class="col">
                            <span class="fs-18 fw-600 d-block text-dark-gray">Liên Lạc</span>
                            <a href="tel:12345678910">0775713230</a><br>
                            <a href="mailto:hieutestmail911@gmail.com"
                                class="text-decoration-line-bottom text-dark-gray">hieutestmail911@gmail.com</a>
                        </div>
                    </div>
                    <div class="fs-22 fw-700 text-dark-gray mb-10px">Hà Nam</div>
                    <div class="row row-cols-1 row-cols-sm-2">
                        <div class="col last-paragraph-no-margin xs-mb-20px">
                            <span class="fs-18 fw-600 d-block text-dark-gray">Vĩnh Trụ</span>
                            <p class="w-95 xl-w-100">Nhân khang<br></p>
                        </div>
                        <div class="col">
                            <span class="fs-18 fw-600 d-block text-dark-gray"> Liên lạc</span>
                            <a href="tel:12345678910">0775713230</a><br>
                            <a href="mailto:hieutestmail911@gmail.com"
                                class="text-decoration-line-bottom text-dark-gray">hieutestmail911@gmail.com</a>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-6 offset-xxl-1 col-lg-6">
                    <!-- start map -->
                    <div class="outside-box-right-30 position-relative"
                        data-anime='{ "el": "childs", "translateX": [30, 0], "opacity": [0,1], "duration": 300, "delay": 0, "staggervalue": 300, "easing": "easeOutQuad" }'>
                        <img src="{{ asset('assets/images/shop/contacthoa.png') }}" alt="" />
                        <div
                            class="bg-base-color video-icon-box video-icon-medium feature-box-icon-rounded position-absolute top-100px left-100px mt-10 ms-15 w-40px h-40px rounded-circle d-flex align-items-center justify-content-center">
                        </div>
                    </div>
                    <!-- end map -->
                </div>
            </div>
        </div>
    </section>
    <!-- end section -->
    <!-- start section -->
    <section class="h-600px md-h-500px sm-h-400px section-dark" data-parallax-background-ratio="0.5"
        style="background-image: url({{ asset('assets/images/shop/vietnamtuoidep.png') }});"></section>
    <!-- end section -->
    <!-- start section -->
    <section class="position-relative sm-pt-20px">
        <div class="container overlap-section overlap-section-three-fourth">
            <div class="row row-cols-md-1 justify-content-center">
                <div class="col-xl-10"
                    data-anime='{ "el": "childs", "translateY": [30, 0], "opacity": [0,1], "duration": 500, "delay": 200, "staggervalue": 300, "easing": "easeOutQuad" }'>
                    <div class="bg-white pt-8 pb-8 box-shadow-double-large ps-10 pe-10 border-radius-6px sm-pe-5 sm-ps-5">
                        <div class="row mb-2">
                            <div class="col-10">
                                <h2 class="alt-font text-dark-gray ls-minus-2px">Chúng tôi có thể <span
                                        class="text-highlight fw-600">giúp<span
                                            class="bg-base-color h-5px bottom-2px"></span></span> gì cho bạn</h2>
                            </div>
                            <div class="col-2 text-end">
                                <i class="bi bi-send icon-large text-dark-gray animation-float"></i>
                            </div>
                        </div>
                        <!-- start contact form -->
                        <form action="{{ route('contact-send') }}" method="post" class="contact-form-style-03">
                            @csrf
                            <div class="row justify-content-center">
                                {{-- Tên --}}
                                <div class="col-md-6 sm-mb-30px">
                                    <label for="exampleInputEmail1" class="form-label fw-600 text-dark-gray mb-0">Nhập tên
                                        của bạn*</label>
                                    <div class="position-relative form-group mb-25px">
                                        <span class="form-icon"><i class="bi bi-emoji-smile"></i></span>
                                        <input
                                            class="ps-0 border-radius-0px border-color-extra-medium-gray bg-transparent form-control required"
                                            id="exampleInputEmail1" type="text" name="name"
                                            value="{{ old('name') }}" placeholder="Hãy điền tên của bạn?" />
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Email --}}
                                <div class="col-md-6 sm-mb-30px">
                                    <label for="exampleInputEmail2" class="form-label fw-600 text-dark-gray mb-0">Địa chỉ
                                        email*</label>
                                    <div class="position-relative form-group mb-25px">
                                        <span class="form-icon"><i class="bi bi-envelope"></i></span>
                                        <input
                                            class="ps-0 border-radius-0px border-color-extra-medium-gray bg-transparent form-control required"
                                            id="exampleInputEmail2" type="email" name="email"
                                            value="{{ old('email') }}" placeholder="Hãy điền email của bạn" />
                                        @error('email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Số điện thoại --}}
                                <div class="col-md-6 sm-mb-30px">
                                    <label for="exampleInputEmail3" class="form-label fw-600 text-dark-gray mb-0">Số điện
                                        thoại*</label>
                                    <div class="position-relative form-group mb-25px">
                                        <span class="form-icon"><i class="bi bi-telephone"></i></span>
                                        <input
                                            class="ps-0 border-radius-0px border-color-extra-medium-gray bg-transparent form-control required"
                                            id="exampleInputEmail3" type="tel" name="phone"
                                            value="{{ old('phone') }}" placeholder="Hãy nhập số điện thoại" />
                                        @error('phone')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Câu hỏi / tiêu đề --}}
                                <div class="col-md-6 sm-mb-30px">
                                    <label for="exampleInputEmail4" class="form-label fw-600 text-dark-gray mb-0">Câu
                                        hỏi</label>
                                    <div class="position-relative form-group mb-25px">
                                        <span class="form-icon"><i class="bi bi-journals"></i></span>
                                        <input
                                            class="ps-0 border-radius-0px border-color-extra-medium-gray bg-transparent form-control"
                                            id="exampleInputEmail4" type="text" name="title"
                                            value="{{ old('title') }}" placeholder="Vấn đề của bạn?" />
                                        @error('title')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Nội dung --}}
                                <div class="col-12 mb-4">
                                    <label for="exampleInputEmail5" class="form-label fw-600 text-dark-gray mb-0">Nội
                                        dung</label>
                                    <div class="position-relative form-group form-textarea mb-0">
                                        <textarea class="ps-0 border-radius-0px border-color-extra-medium-gray bg-transparent form-control" name="content"
                                            placeholder="Nội dung" rows="4">{{ old('content') }}</textarea>
                                        <span class="form-icon"><i class="bi bi-chat-square-dots"></i></span>
                                        @error('content')
                                            <small class="text-danger d-block">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Ghi chú và nút gửi --}}
                                <div class="col-md-6">
                                    <p class="mb-0 fs-14 lh-24 text-center text-md-start">Chúng tôi không thu thập dữ liệu
                                        cá nhân của bạn khi chưa được sự cho phép.</p>
                                </div>

                                <div class="col-md-6 text-center text-md-end sm-mt-25px">
                                    <input id="exampleInputEmail5" type="hidden" name="redirect" value="">
                                    <button
                                        class="btn btn-very-small btn-dark-gray btn-box-shadow btn-round-edge text-transform-none primary-font"
                                        type="submit">Gửi</button>
                                </div>

                                <div class="col-12">
                                    <div class="form-results mt-20px d-none"></div>
                                </div>
                            </div>
                        </form>

                        <!-- end contact form -->
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('js-page-custom')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: "{{ session('success') }}",
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: "{{ session('error') }}",
            });
        @endif
    </script>
@endsection
