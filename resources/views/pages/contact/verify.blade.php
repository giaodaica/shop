@extends('layouts.layout');
@section('content')
    <section class="top-space-margin half-section bg-gradient-very-light-gray">
        <div class="container">
            <div class="row align-items-center justify-content-center"
                data-anime='{ "el": "childs", "translateY": [-15, 0], "opacity": [0,1], "duration": 300, "delay": 0, "staggervalue": 200, "easing": "easeOutQuad" }'>
                <div class="col-12 col-xl-8 col-lg-10 text-center position-relative page-title-extra-large">
                    <h1 class="alt-font fw-600 text-dark-gray mb-10px">Chính sách</h1>
                </div>
                <div class="col-12 breadcrumb breadcrumb-style-01 d-flex justify-content-center">
                    <ul>
                        <li><a href="{{ route('home') }}">Trang chủ</a></li>
                        <li>Chính sách</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- end section -->
    <!-- start section -->
    <section class="pt-0 position-relative overflow-hidden">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="bg-white p-4 rounded-3 shadow-sm">
                        <h2 class="mb-3 text-dark">Chính sách mua hàng, đổi trả và sử dụng voucher</h2>
                        <h5 class="mt-4 mb-2 text-dark">1. Chính sách mua hàng</h5>
                        <ul>
                            <li>Khách hàng vui lòng kiểm tra kỹ thông tin sản phẩm trước khi đặt hàng.</li>
                            <li>Đơn hàng sẽ được xác nhận qua email hoặc số điện thoại đã đăng ký.</li>
                            <li>Thời gian xử lý đơn hàng từ 1-2 ngày làm việc.</li>
                        </ul>
                        <h5 class="mt-4 mb-2 text-dark">2. Chính sách đổi trả</h5>
                        <ul>
                            <li>Khách hàng được đổi trả sản phẩm trong vòng 7 ngày kể từ ngày nhận hàng.</li>
                            <li>Sản phẩm đổi trả phải còn nguyên tem, nhãn mác, chưa qua sử dụng và còn đầy đủ hóa đơn mua hàng.</li>
                            <li>Phí vận chuyển đổi trả (nếu có) sẽ do khách hàng chi trả, trừ trường hợp sản phẩm lỗi do nhà cung cấp.</li>
                        </ul>
                        <h5 class="mt-4 mb-2 text-dark">3. Chính sách sử dụng voucher</h5>
                        <ul>
                            <li>Voucher chỉ áp dụng cho các đơn hàng hợp lệ và trong thời gian còn hiệu lực.</li>
                            <li>Mỗi đơn hàng chỉ sử dụng được một voucher, không cộng dồn nhiều voucher.</li>
                            <li>Voucher không có giá trị quy đổi thành tiền mặt và không hoàn lại tiền thừa.</li>
                            <li>Voucher vẫn sẽ được hoàn lại kể cả khi đổi trả sản phẩm.</li>
                        </ul>
                        <p class="mt-4 mb-0 text-dark">Mọi thắc mắc về chính sách mua hàng, đổi trả hoặc voucher, vui lòng liên hệ bộ phận chăm sóc khách hàng qua hotline hoặc email hỗ trợ của chúng tôi.</p>
                    </div>
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

@endsection
@section('js-page-custom')

@endsection
