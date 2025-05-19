@extends('layouts.layout')
@section('content')
        <!-- start section -->
        <section class="top-space-margin half-section bg-gradient-very-light-gray">
            <div class="container">
                <div class="row align-items-center justify-content-center" data-anime='{ "el": "childs", "translateY": [-15, 0], "opacity": [0,1], "duration": 300, "delay": 0, "staggervalue": 200, "easing": "easeOutQuad" }'>
                    <div class="col-12 col-xl-8 col-lg-10 text-center position-relative page-title-extra-large">
                        <h1 class="alt-font fw-600 text-dark-gray mb-10px">Checkout</h1>
                    </div>
                    <div class="col-12 breadcrumb breadcrumb-style-01 d-flex justify-content-center">
                        <ul>
                            <li><a href="demo-fashion-store.html">Home</a></li>
                            <li>Checkout</li>
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
                                <span class="d-inline-block text-dark-gray align-middle alt-font fw-500">Returning customer? <a href="#" class="text-decoration-line-bottom fw-600 text-dark-gray">Click here to login</a></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto d-none d-lg-inline-block">
                        <span class="w-1px h-20px bg-extra-medium-gray d-block"></span>
                    </div>
                    <div class="col-auto icon-with-text-style-08">
                        <div class="feature-box feature-box-left-icon">
                            <div class="feature-box-icon me-5px">
                                <i class="feather icon-feather-scissors top-9px position-relative text-dark-gray icon-small"></i>
                            </div>
                            <div class="feature-box-content">
                                <span class="d-inline-block text-dark-gray align-middle alt-font fw-500">Have a coupon? <a href="#" class="text-decoration-line-bottom fw-600 text-dark-gray">Click here to enter your code</a></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row align-items-start">
                    <div class="col-lg-7 pe-50px md-pe-15px md-mb-50px xs-mb-35px">
                        <span class="fs-26 alt-font fw-600 text-dark-gray mb-20px d-block">Thông tin giao hàng</span>
                        <form class="">
                            <div class="row">
                                <div class="col-md-6 mb-20px">
                                    <label class="mb-10px">First name <span class="text-red">*</span></label>
                                    <input class="border-radius-4px input-small" type="text" aria-label="first-name" required>
                                </div>
                                <div class="col-md-6 mb-20px">
                                    <label class="mb-10px">Last name <span class="text-red">*</span></label>
                                    <input class="border-radius-4px input-small" type="text" aria-label="last-name" required>
                                </div>
                                <div class="col-12 mb-20px">
                                    <label class="mb-10px">Địa chỉ</label>
                                    <input class="border-radius-4px input-small" type="text" aria-label="company-name">
                                </div>
                                <div class="col-12 mb-20px">
                                    <label class="mb-10px">Phone <span class="text-red">*</span></label>
                                    <input class="border-radius-4px input-small" type="text" aria-label="phone" required>
                                </div>
                                <div class="col-12 mb-20px">
                                    <label class="mb-10px">Email address <span class="text-red">*</span></label>
                                    <input class="border-radius-4px input-small" type="email" aria-label="email" required>
                                </div>
                                <!-- start tab content -->
                                <div class="col-md-12 mb-5px checkout-accordion">
                                    <div class="position-relative terms-condition-box text-start d-flex align-items-center">
                                        <label>
                                            <input type="checkbox" name="terms_condition" value="1" class="check-box align-middle">
                                            <span class="box">Create an account?</span>
                                            <a class="accordion-toggle" data-bs-toggle="collapse" data-bs-parent="#accordion1" href="#collapseThree"></a>
                                        </label>
                                    </div>
                                    <div id="collapseThree" class="collapse">
                                        <div class="ps-30px mb-30px mt-15px">
                                            <label class="mb-10px">Account username <span class="text-red">*</span></label>
                                            <input class="border-radius-4px input-small mb-15px" type="email" required>
                                            <label class="mb-10px">Create account password <span class="text-red">*</span></label>
                                            <input class="border-radius-4px input-small" type="email" required>
                                        </div>
                                    </div>
                                </div>
                                <!-- end tab content -->
                                <!-- start tab content -->
                                <div class="col-md-12 mb-20px checkout-accordion">
                                    <div class="position-relative terms-condition-box text-start d-flex align-items-center">
                                        <label>
                                            <input type="checkbox" name="terms_condition" value="1" class="check-box align-middle">
                                            <span class="box">Ship to a different address?</span>
                                            <a class="accordion-toggle" data-bs-toggle="collapse" data-bs-parent="#accordion1" href="#collapseFour"></a>
                                        </label>
                                    </div>
                                   <!-- filepath: d:\shop\shop\resources\views\pages\shop\checkout.blade.php -->
<div id="collapseFour" class="collapse">
    <div class="row ps-30px mb-30px mt-15px">
        <style>
            .address-card {
                transition: box-shadow 0.2s, border-color 0.2s;
                border: 2px solid #eee;
                cursor: pointer;
            }
            .address-card.selected, .address-card:hover {
                border-color: #343a40;
                box-shadow: 0 0 0 2px #343a4022;
                background: #f8f9fa;
            }
            .address-radio:checked + .address-info .address-card {
                border-color: #343a40;
                background: #f8f9fa;
            }
            .address-note {
                font-size: 1rem;
                color: #343a40;
                font-weight: 600;
                margin-bottom: 4px;
                display: flex;
                align-items: center;
            }
            .address-note i {
                margin-right: 6px;
                color: #0d6efd;
            }
            .address-detail {
                font-size: 0.97rem;
                color: #555;
            }
        </style>
        <div class="col-12 mb-20px">
            <label class="w-100 d-block">
                <input type="radio" name="shipping_address_id" value="1" class="address-radio d-none" checked>
                <div class="address-info">
                    <div class="address-card border p-3 rounded mb-2 d-flex flex-column">
                        <div class="address-note"><i class="fas fa-home"></i>Home Address</div>
                        <div class="address-detail"><span class="fw-500">Tên người nhận:</span> Nguyễn Văn A</div>
                        <div class="address-detail"><span class="fw-500">Địa chỉ:</span> 123 Đường ABC, Quận 1, TP.HCM</div>
                        <div class="address-detail"><span class="fw-500">Số điện thoại:</span> 0909123456</div>
                    </div>
                </div>
            </label>
        </div>
        <div class="col-12 mb-20px">
            <label class="w-100 d-block">
                <input type="radio" name="shipping_address_id" value="2" class="address-radio d-none">
                <div class="address-info">
                    <div class="address-card border p-3 rounded mb-2 d-flex flex-column">
                        <div class="address-note"><i class="fas fa-briefcase"></i>Work Address</div>
                        <div class="address-detail"><span class="fw-500">Tên người nhận:</span> Nguyễn Văn B</div>
                        <div class="address-detail"><span class="fw-500">Địa chỉ:</span> 456 Đường XYZ, Quận 3, TP.HCM</div>
                        <div class="address-detail"><span class="fw-500">Số điện thoại:</span> 0911222333</div>
                    </div>
                </div>
            </label>
        </div>
        <div class="col-12">
            <a href="#" class=""><i class="fas fa-plus me-1"></i>Thêm địa chỉ mới</a>
        </div>
    </div>
    <script>
        // Highlight card khi chọn radio
        document.querySelectorAll('.address-radio').forEach(function(radio) {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.address-card').forEach(function(card) {
                    card.classList.remove('selected');
                });
                if (radio.checked) {
                    radio.closest('.address-info').querySelector('.address-card').classList.add('selected');
                }
            });
            // Set mặc định
            if (radio.checked) {
                radio.closest('.address-info').querySelector('.address-card').classList.add('selected');
            }
        });
    </script>
    <!-- Thêm link CDN FontAwesome nếu chưa có -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
</div>
                                </div>
                                <!-- end tab content -->
                                <div class="col-12">
                                    <label class="mb-10px">Order notes (optional)</label>
                                    <textarea class="border-radius-4px textarea-small" rows="5" cols="5" placeholder="Notes about your order, e.g. special notes for delivery."></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-5">
                        <div class="bg-very-light-gray border-radius-6px p-50px lg-p-25px your-order-box">
                            <span class="fs-26 alt-font fw-600 text-dark-gray mb-5px d-block">Your order</span>
                            <table class="w-100 total-price-table your-order-table">
                                <tbody>
                                    <tr>
                                        <th class="w-60 lg-w-55 xs-w-50 fw-600 text-dark-gray alt-font">Product</th>
                                        <td class="fw-600 text-dark-gray alt-font">Total</td>
                                    </tr>
                                    <tr class="product">
                                        <td class="product-thumbnail">
                                            <a href="demo-jewellery-store-single-product.html" class="text-dark-gray fw-500 d-block lh-initial">Textured sweater x 1</a>
                                            <span class="fs-14 d-block">Color: Pink</span>
                                        </td>
                                        <td class="product-price" data-title="Price">$23.00</td>
                                    </tr>
                                    <tr class="product">
                                        <td class="product-thumbnail">
                                            <a href="demo-jewellery-store-single-product.html" class="text-dark-gray fw-500 d-block lh-initial">Bermuda shorts x 2</a>
                                            <span class="fs-14 d-block">Color: Brown</span>
                                        </td>
                                        <td class="product-price" data-title="Price">$70.00</td>
                                    </tr>
                                    <tr class="product">
                                        <td class="product-thumbnail">
                                            <a href="demo-jewellery-store-single-product.html" class="text-dark-gray fw-500 d-block lh-initial">Pocket sweatshirt x 1</a>
                                            <span class="fs-14 d-block">Color: White</span>
                                        </td>
                                        <td class="product-price" data-title="Price">$15.00</td>
                                    </tr>
                                    <tr>
                                        <th class="w-50 fw-600 text-dark-gray alt-font">Subtotal</th>
                                        <td class="text-dark-gray fw-600">$405.00</td>
                                    </tr>
                                    <tr class="shipping">
                                        <th class="fw-600 text-dark-gray alt-font">Shipping</th>
                                        <td data-title="Shipping">
                                            <ul class="p-0">
                                                <li class="d-flex align-items-center">
                                                    <input id="free_shipping" type="radio" name="shipping-option" class="d-block w-auto mb-0 me-10px p-0" checked="checked">
                                                    <label class="md-line-height-18px" for="free_shipping">Free shipping</label>
                                                </li>
                                                <li class="d-flex align-items-center">
                                                    <input id="flat" type="radio" name="shipping-option" class="d-block w-auto mb-0 me-10px p-0">
                                                    <label class="md-line-height-18px" for="flat">Flat: $12.00</label>
                                                </li>
                                                <li class="d-flex align-items-center">
                                                    <input id="local_pickup" type="radio" name="shipping-option" class="d-block w-auto mb-0 me-10px p-0">
                                                    <label class="md-line-height-18px" for="local_pickup">Local pickup</label>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr class="total-amount">
                                        <th class="fw-600 text-dark-gray alt-font">Total</th>
                                        <td data-title="Total">
                                            <h6 class="d-block fw-700 mb-0 text-dark-gray alt-font">$405.00</h6>
                                            <span class="fs-14">(Includes $19.29 tax)</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="p-40px lg-p-25px bg-white border-radius-6px box-shadow-large mt-10px mb-30px sm-mb-25px checkout-accordion">
                                <div class="w-100" id="accordion-style-05">
                                    <!-- start tab content -->
                                    <div class="heading active-accordion">
                                        <label class="mb-5px">
                                            <input class="d-inline w-auto me-5px mb-0 p-0" type="radio" name="payment-option" checked="checked">
                                            <span class="d-inline-block text-dark-gray fw-500">Direct bank transfer</span>
                                            <a class="accordion-toggle" data-bs-toggle="collapse" data-bs-parent="#accordion-style-05" href="#style-5-collapse-1"></a>
                                        </label>
                                    </div>
                                    <div id="style-5-collapse-1" class="collapse show" data-bs-parent="#accordion-style-05">
                                        <div class="p-25px bg-very-light-gray mt-20px mb-20px fs-14 lh-24">Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order will not be shipped until the funds have cleared in our account.</div>
                                    </div>
                                    <!-- end tab content -->
                                    <!-- start tab content -->
                                    <div class="heading active-accordion">
                                        <label class="mb-5px">
                                            <input class="d-inline w-auto me-5px mb-0 p-0" type="radio" name="payment-option">
                                            <span class="d-inline-block text-dark-gray fw-500">Check payments</span>
                                            <a class="accordion-toggle" data-bs-toggle="collapse" data-bs-parent="#accordion-style-05" href="#style-5-collapse-2"></a>
                                        </label>
                                    </div>
                                    <div id="style-5-collapse-2" class="collapse" data-bs-parent="#accordion-style-05">
                                        <div class="p-25px bg-very-light-gray mt-20px mb-20px fs-14 lh-24">Please send a check to store name, store street, store town, store state / county, store postcode.</div>
                                    </div>
                                    <!-- end tab content -->
                                    <!-- start tab content -->
                                    <div class="heading active-accordion">
                                        <label class="mb-5px">
                                            <input class="d-inline w-auto me-5px mb-0 p-0" type="radio" name="payment-option">
                                            <span class="d-inline-block text-dark-gray fw-500">Cash on delivery</span>
                                            <a class="accordion-toggle" data-bs-toggle="collapse" data-bs-parent="#accordion-style-05" href="#style-5-collapse-3"></a>
                                        </label>
                                    </div>
                                    <div id="style-5-collapse-3" class="collapse" data-bs-parent="#accordion-style-05">
                                        <div class="p-25px bg-very-light-gray mt-20px mb-20px fs-14 lh-24">Pay with cash upon delivery.</div>
                                    </div>
                                    <!-- end tab content -->
                                    <!-- start tab content -->
                                    <div class="heading active-accordion">
                                        <label class="mb-5px">
                                            <input class="d-inline w-auto me-5px mb-0 p-0" type="radio" name="payment-option">
                                            <span class="d-inline-block text-dark-gray fw-500">PayPal <img src="images/paypal-logo.jpg" class="w-120px ms-10px" alt=""/></span>
                                            <a class="accordion-toggle" data-bs-toggle="collapse" data-bs-parent="#accordion-style-05" href="#style-5-collapse-4"></a>
                                        </label>
                                    </div>
                                    <div id="style-5-collapse-4" class="collapse" data-bs-parent="#accordion-style-05">
                                        <div class="p-25px bg-very-light-gray mt-20px fs-14 lh-24">You can pay with your credit card if you don't have a PayPal account.</div>
                                    </div>
                                    <!-- end tab content -->
                                </div>
                            </div>
                            <p class="fs-14 lh-24">Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our <a class="text-decoration-line-bottom text-dark-gray fw-500" href="#">privacy policy.</a></p>
                            <div class="position-relative terms-condition-box text-start d-flex align-items-center">
                                <label>
                                    <input type="checkbox" name="terms_condition" value="1" class="check-box align-middle">
                                    <span class="box fs-14 lh-28">I have agree to the website <a href="#" class="text-decoration-line-bottom text-dark-gray fw-500">terms and conditions.</a></span>
                                </label>
                            </div>
                            <a href="{{route('home.done')}}" class="btn btn-dark-gray btn-large btn-switch-text btn-round-edge btn-box-shadow w-100 mt-30px">
                                <span>
                                    <span class="btn-double-text" data-text="Place order">Place order</span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end section -->
@endsection
