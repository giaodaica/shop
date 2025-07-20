@extends('layouts.layout')
@section('content')
    <!-- start section -->
    <section class="top-space-margin half-section bg-gradient-very-light-gray">
        <div class="container">
            <div class="row align-items-center justify-content-center"
                data-anime='{ "el": "childs", "translateY": [-15, 0], "opacity": [0,1], "duration": 300, "delay": 0, "staggervalue": 200, "easing": "easeOutQuad" }'>
                <div class="col-12 col-xl-8 col-lg-10 text-center position-relative page-title-extra-large">
                    <h1 class="alt-font fw-600 text-dark-gray mb-10px">Thanh toán</h1>
                </div>
                <div class="col-12 breadcrumb breadcrumb-style-01 d-flex justify-content-center">
                    <ul>
                        <li><a href="{{ route('home') }}">Trang chủ</a></li>
                        <li>Thanh toán</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- end section -->
    <!-- start section -->
    <section class="pt-0">
        <div class="container">
            <div class="row justify-content-center mb-8 lg-mb-10 align-items-center">
                <div class="col-auto icon-with-text-style-08 lg-mb-10px">
                    <div class="feature-box feature-box-left-icon">
                        <div class="feature-box-icon me-5px">
                            <i class="feather icon-feather-user top-9px position-relative text-dark-gray icon-small"></i>
                        </div>
                        <div class="feature-box-content">
                            <span class="d-inline-block text-dark-gray align-middle alt-font fw-500">Khách hàng: <span
                                    class="fw-600 text-dark-gray">{{ Auth::user()->name }}</span></span>
                        </div>
                    </div>
                </div>
                <div class="col-auto d-none d-lg-inline-block">
                    <span class="w-1px h-20px bg-extra-medium-gray d-block"></span>
                </div>
                <div class="col-auto icon-with-text-style-08">
                    <div class="feature-box feature-box-left-icon">
                        <div class="feature-box-icon me-5px">
                            <i
                                class="feather icon-feather-scissors top-9px position-relative text-dark-gray icon-small"></i>
                        </div>
                        <div class="feature-box-content">
                            @if ($appliedVoucher)
                                <span class="d-inline-block text-dark-gray align-middle alt-font fw-500">Mã giảm giá: <span
                                        class="fw-600 text-dark-gray">{{ $appliedVoucher->code }}</span></span>
                            @else
                                <span class="d-inline-block text-dark-gray align-middle alt-font fw-500">Chưa có mã giảm
                                    giá</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <form action="{{ route('home.processCheckout') }}" method="POST">
                @csrf

                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <h6 class="alert-heading">Có lỗi xảy ra:</h6>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger mb-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                <div class="row align-items-start">
                    <div class="col-lg-7 pe-50px md-pe-15px md-mb-50px xs-mb-35px">
                        <span class="fs-26 alt-font fw-600 text-dark-gray mb-20px d-block">Thông tin giao hàng</span>

                        @if ($addresses->count() > 0)
                            <div class="mb-20px">
                                <label class="mb-10px">Chọn địa chỉ giao hàng <span class="text-red">*</span></label>
                                @foreach ($addresses as $address)
                                    <div class="mb-10px">
                                        <label class="w-100 d-block">
                                            <input type="radio" name="address_id" value="{{ $address->id }}"
                                                class="address-radio d-none" {{ $loop->first ? 'checked' : '' }}>
                                            <div class="address-info">
                                                <div class="address-card border p-3 rounded mb-2" data-province-code="{{ $address->province_code }}">
                                                    <div class="mb-2">
                                                        <strong>Địa chỉ {{ $loop->iteration }}</strong>
                                                    </div>
                                                    <div class="mb-1">
                                                        <strong>Tên người nhận:</strong> {{ $address->name }}
                                                    </div>
                                                    <div class="mb-1">
                                                        <strong>Địa chỉ:</strong> {{ $address->address }}, {{ $address->ward->name ?? '' }}, {{ $address->province->name ?? '' }}
                                                    </div>
                                                    <div class="mb-0">
                                                        <strong>Số điện thoại:</strong> {{ $address->phone }}
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Bạn chưa có địa chỉ giao hàng. Vui lòng thêm địa chỉ mới.
                            </div>
                        @endif

                        <div class="col-12 mb-20px">
                            <a href="{{ route('addresses.index') }}" class="btn btn-outline-dark-gray btn-small">
                                <i class="fas fa-plus me-1"></i>Thêm địa chỉ mới
                            </a>
                        </div>

                        <div class="col-12">
                            <label class="mb-10px">Ghi chú đơn hàng (tùy chọn)</label>
                            <textarea name="notes" class="border-radius-4px textarea-small" rows="5" cols="5"
                                placeholder="Ghi chú về đơn hàng, ví dụ: ghi chú đặc biệt cho việc giao hàng."></textarea>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="bg-very-light-gray border-radius-6px p-50px lg-p-25px your-order-box">
                            <span class="fs-26 alt-font fw-600 text-dark-gray mb-5px d-block">Đơn hàng của bạn</span>
                            <table class="w-100 total-price-table your-order-table">
                                <tbody>
                                    <tr>
                                        <th class="w-60 lg-w-55 xs-w-50 fw-600 text-dark-gray alt-font">Sản phẩm</th>
                                        <td class="fw-600 text-dark-gray alt-font">Tổng</td>
                                    </tr>
                                    @foreach ($cartItems as $item)
                                        <tr class="product">
                                            <td class="product-thumbnail">
                                                <a href="{{ route('home.show', $item->productVariant->product->slug) }}"
                                                    class="text-dark-gray fw-500 d-block lh-initial">
                                                    {{ $item->productVariant->product->name }} x {{ $item->quantity }}
                                                </a>
                                                <span class="fs-14 d-block">
                                                    Màu: {{ $item->productVariant->color->color_name }} |
                                                    Size: {{ $item->productVariant->size->size_name }}
                                                </span>
                                            </td>
                                            <td class="product-price" data-title="Price">
                                                {{ number_format($item->quantity * $item->price_at_time, 0, ',', '.') }} đ
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <th class="w-50 fw-600 text-dark-gray alt-font">Tạm tính</th>
                                        <td class="text-dark-gray fw-600">{{ number_format($subtotal, 0, ',', '.') }} đ
                                        </td>
                                    </tr>
                                    @if ($voucherDiscount > 0)
                                        <tr>
                                            <th class="fw-600 text-dark-gray alt-font">Giảm giá</th>
                                            <td class="text-dark-gray fw-600">
                                                -{{ number_format($voucherDiscount, 0, ',', '.') }} đ</td>
                                        </tr>
                                    @endif
                                    <tr class="total-amount">
                                        <th class="fw-600 text-dark-gray alt-font">Tổng cộng</th>
                                        <td data-title="Total">
                                            <h6 class="d-block fw-700 mb-0 text-dark-gray alt-font total-display">
                                                {{ number_format($total, 0, ',', '.') }} đ</h6>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div
                                class="p-40px lg-p-25px bg-white border-radius-6px box-shadow-large mt-10px mb-30px sm-mb-25px checkout-accordion">
                                <div class="w-100" id="accordion-style-05">
                                    <!-- start shipping tab content -->
                                    <div class="heading active-accordion">
                                        <label class="mb-5px">
                                            <input class="d-inline w-auto me-5px mb-0 p-0" type="radio"
                                                name="shipping_type" value="basic"
                                                {{ $shippingType == 'basic' ? 'checked' : '' }}>
                                            <span class="d-inline-block text-dark-gray fw-500">Vận chuyển cơ bản</span>
                                            <a class="accordion-toggle" data-bs-toggle="collapse"
                                                data-bs-parent="#accordion-style-05"
                                                href="#style-5-collapse-shipping-1"></a>
                                        </label>
                                    </div>
                                    <div id="style-5-collapse-shipping-1" class="collapse show"
                                        data-bs-parent="#accordion-style-05">
                                        <div class="p-25px bg-very-light-gray mt-20px mb-20px fs-14 lh-24">
                                            @if ($subtotal >= 200000)
                                                Miễn phí vận chuyển cho đơn hàng từ 200.000 đ trở lên.
                                            @else
                                                Phí vận chuyển: 20.000 đ cho đơn hàng dưới 200.000 đ.
                                            @endif
                                        </div>
                                    </div>
                                    <!-- end shipping tab content -->
                                    <!-- start shipping tab content -->
                                    <div class="heading active-accordion">
                                        <label class="mb-5px">
                                            <input class="d-inline w-auto me-5px mb-0 p-0" type="radio"
                                                name="shipping_type" value="express"
                                                {{ $shippingType == 'express' ? 'checked' : '' }}>
                                            <span class="d-inline-block text-dark-gray fw-500">Vận chuyển nhanh</span>
                                            <a class="accordion-toggle" data-bs-toggle="collapse"
                                                data-bs-parent="#accordion-style-05"
                                                href="#style-5-collapse-shipping-2"></a>
                                        </label>
                                    </div>
                                    <div id="style-5-collapse-shipping-2" class="collapse"
                                        data-bs-parent="#accordion-style-05">
                                        <div class="p-25px bg-very-light-gray mt-20px mb-20px fs-14 lh-24">
                                            @if ($subtotal >= 200000)
                                                Phí vận chuyển nhanh: +30.000 đ (miễn phí cơ bản + 30.000 đ).
                                            @else
                                                Phí vận chuyển nhanh: 50.000 đ (20.000 đ cơ bản + 30.000 đ).
                                            @endif
                                        </div>
                                    </div>
                                    <!-- end shipping tab content -->
                                </div>
                            </div>
                            <div
                                class="p-40px lg-p-25px bg-white border-radius-6px box-shadow-large mt-10px mb-30px sm-mb-25px checkout-accordion">
                                <div class="w-100" id="accordion-style-06">
                                    <!-- start payment tab content -->
                                    <div class="heading active-accordion">
                                        <label class="mb-5px">
                                            <input class="d-inline w-auto me-5px mb-0 p-0" type="radio"
                                                name="payment_method" value="COD" checked="checked">
                                            <span class="d-inline-block text-dark-gray fw-500">Thanh toán khi nhận hàng
                                                (COD)</span>
                                            <a class="accordion-toggle" data-bs-toggle="collapse"
                                                data-bs-parent="#accordion-style-06" href="#style-6-collapse-1"></a>
                                        </label>
                                    </div>
                                    <div id="style-6-collapse-1" class="collapse show"
                                        data-bs-parent="#accordion-style-06">
                                        <div class="p-25px bg-very-light-gray mt-20px mb-20px fs-14 lh-24">Thanh toán bằng
                                            tiền mặt khi nhận hàng.</div>
                                    </div>
                                    <!-- end payment tab content -->
                                    <!-- start payment tab content -->
                                    <div class="heading active-accordion">
                                        <label class="mb-5px">
                                            <input class="d-inline w-auto me-5px mb-0 p-0" type="radio"
                                                name="payment_method" value="VNPAY">
                                            <span class="d-inline-block text-dark-gray fw-500">Thanh toán qua VNPAY</span>
                                            <a class="accordion-toggle" data-bs-toggle="collapse"
                                                data-bs-parent="#accordion-style-06" href="#style-6-collapse-2"></a>
                                        </label>
                                    </div>
                                    <div id="style-6-collapse-2" class="collapse" data-bs-parent="#accordion-style-06">
                                        <div class="p-25px bg-very-light-gray mt-20px mb-20px fs-14 lh-24">Thanh toán qua
                                            cổng thanh toán VNPAY (ATM, thẻ tín dụng, ví điện tử).</div>
                                    </div>
                                    <!-- end payment tab content -->
                                </div>
                            </div>
                            <p class="fs-14 lh-24">Thông tin cá nhân của bạn sẽ được sử dụng để xử lý đơn hàng, hỗ trợ trải
                                nghiệm của bạn trên trang web này và cho các mục đích khác được mô tả trong <a
                                    class="text-decoration-line-bottom text-dark-gray fw-500" href="#">chính sách
                                    bảo mật.</a></p>
                            <div class="position-relative terms-condition-box text-start d-flex align-items-center">
                                <label>
                                    <input type="checkbox" name="terms_condition" value="1"
                                        class="check-box align-middle" required>
                                    <span class="box fs-14 lh-28">Tôi đồng ý với <a href="#"
                                            class="text-decoration-line-bottom text-dark-gray fw-500">điều khoản và điều
                                            kiện</a> của website.</span>
                                </label>
                            </div>
                            <button type="submit"
                                class="btn btn-dark-gray btn-large btn-switch-text btn-round-edge btn-box-shadow w-100 mt-30px"
                                {{ $addresses->count() == 0 ? 'disabled' : '' }}>
                                <span>
                                    <span class="btn-double-text" data-text="Đặt hàng">Đặt hàng</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!-- end section -->

    <style>
        .address-card {
            transition: box-shadow 0.2s, border-color 0.2s;
            border: 2px solid #eee;
            cursor: pointer;
        }

        .address-card.selected,
        .address-card:hover {
            border-color: #343a40;
            box-shadow: 0 0 0 2px #343a4022;
            background: #f8f9fa;
        }

        .address-radio:checked+.address-info .address-card {
            border-color: #343a40;
            background: #f8f9fa;
        }
    </style>

    <script>
        document.querySelectorAll('.address-radio').forEach(function(radio) {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.address-card').forEach(function(card) {
                    card.classList.remove('selected');
                });

                const addressCard = radio.parentElement.querySelector('.address-card');
                if (addressCard) {
                    addressCard.classList.add('selected');
                }

                // Gọi hàm kiểm tra ship nhanh
                updateShippingExpressAvailability();
            });



            // Set mặc định khi load
            if (radio.checked) {
                const addressCard = radio.parentElement.querySelector('.address-card');
                if (addressCard) {
                    addressCard.classList.add('selected');
                }

                // Kiểm tra lúc khởi tạo
                updateShippingExpressAvailability();
            }
        });


        function updateShippingExpressAvailability() {
            const checkedRadio = document.querySelector('.address-radio:checked');
            if (!checkedRadio) return;

            const addressCard = checkedRadio.parentElement.querySelector('.address-card');
            
            // Lấy province_code từ data attribute
            const provinceCode = addressCard ? addressCard.getAttribute('data-province-code') : null;
            
            // Hà Nội có province_code = "01"
            const isHanoi = provinceCode === "01";

            const expressRadio = document.querySelector('input[name="shipping_type"][value="express"]');
            if (expressRadio) {
                expressRadio.disabled = !isHanoi;
                if (!isHanoi && expressRadio.checked) {
                    // Nếu đang chọn express mà không hợp lệ thì chuyển về basic
                    const basicRadio = document.querySelector('input[name="shipping_type"][value="basic"]');
                    if (basicRadio) basicRadio.checked = true;
                }
            }
        }
    </script>
    <!-- Thêm link CDN FontAwesome nếu chưa có -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
@endsection

