@extends('layouts.layout')
@section('cdn-custom')
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/info.css') }}">
@endsection
@section('js-page-custom')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('assets/js/info/info.js') }}"></script>
@endsection

@section('content')
    <!-- start page title -->
    <section class="page-title-center-alignment cover-background top-space-padding"
        style="background-image: url({{ asset('assets/images/demo-decor-store-title-bg.jpg') }})">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center position-relative page-title-extra-large">
                    <h1 class="text-white fw-700 mb-0" style="font-size: 2.5rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
                        Quản Lý Tài Khoản</h1>
                </div>
                <div class="col-12 breadcrumb breadcrumb-style-01 d-flex justify-content-center">
                    <ul>
                        <li><a href="{{ route('home') }}" class="text-white">Trang chủ</a></li>
                        <li class="text-white">Quản lý tài khoản</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- end page title -->
    <!-- start section -->
    <section class="position-relative">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-4 tab-style-07 md-mb-50px sm-mb-35px"
                    data-anime='{ "translate": [50, 0], "opacity": [0,1], "duration": 600, "delay":100, "staggervalue": 150, "easing": "easeOutQuad" }'>
                    <div class="position-sticky top-50px">
                        <ul
                            class="nav nav-tabs justify-content-center border-0 fw-500 text-left alt-font bg-very-light-gray border-radius-6px overflow-hidden">
                            <li class="nav-item">
                                <a data-bs-toggle="tab" href="#tab_seven1" class="nav-link active">
                                    <span>
                                        <span class="me-5px"><i class="bi bi-file-text"></i></span>
                                        <span>Thông Tin Khác Hàng</span>
                                    </span>
                                    <span class="bg-hover bg-base-color"></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#tab_seven2">
                                    <span>
                                        <span class="me-5px"><i class="bi bi-bag-plus"></i></span>
                                        <span>Lịch sử mua hàng</span>
                                    </span>
                                    <span class="bg-hover bg-base-color"></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#tab_seven3">
                                    <span>
                                        <span class="me-5px"><i class="bi bi-gear"></i></span>
                                        <span>Cài Đặt</span>
                                    </span>
                                    <span class="bg-hover bg-base-color"></span>
                                </a>
                            </li>
                          
                            <li class="nav-item">
                                <form class="nav-link" data-bs-toggle="tab" action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <span>
                                        <span class="me-5px"><i class="bi bi-power"></i></span>
                                    </span>
                                    <span>
                                        <button class="btn" type="submit">Đăng xuất</button>
                                    </span>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-8 offset-xl-1 lg-ps-50px md-ps-15px"
                    data-anime='{ "translateX": [0, 0], "opacity": [0,1], "duration": 600, "delay":150, "staggervalue": 150, "easing": "easeOutQuad" }'>
                    <div class="tab-content h-100">
                        <!-- start tab info -->
                        <div class="tab-pane fade show active" id="tab_seven1">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card shadow-sm border-0">
                                        <div class="card-body">

                                            <!-- Section: Thông tin khách hàng -->
                                            <div
                                                class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-4">
                                                <h6 class="text-primary text-uppercase fs-5 mb-0">Thông tin khách hàng</h6>
                                                <span class="badge bg-light text-primary fw-normal"><i
                                                        class="bi bi-person-fill me-1"></i>Customer Info</span>
                                            </div>

                                            <div class="table-responsive px-1 mb-5">
                                                <table class="table table-borderless table-sm">
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-muted">Họ và tên</td>
                                                            <td class="fw-semibold">{{ Auth::user()->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">Số điện thoại</td>
                                                            <td class="fw-semibold">{{ Auth::user()->phone ? Auth::user()->phone : 'Chưa cập nhật' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">Email</td>
                                                            <td class="fw-semibold">{{ Auth::user()->email }}</td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <td class="text-muted">Thành viên từ</td>
                                                            <td class="fw-semibold">{{ Auth::user()->created_at->format('d/m/Y H:i:s') }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <!-- Section: Địa chỉ giao hàng -->
                                            <div
                                                class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-4">
                                                <h6 class="text-primary text-uppercase fs-5 mb-0">Địa chỉ giao hàng</h6>
                                                <span class="badge bg-light text-primary fw-normal"><i
                                                        class="bi bi-geo-alt-fill me-1"></i>Address Info</span>
                                            </div>

                                            <div class="row g-4">
                                                <!-- Home Address -->
                                                <div class="col-md-6">
                                                    <div class="card border-0 shadow-sm h-100">
                                                        <div class="card-body position-relative">
                                                            <a href="address.html"
                                                                class="badge bg-primary-subtle text-primary position-absolute top-0 end-0 m-2">
                                                                <i class="bi bi-pencil-fill me-1"></i> Chỉnh sửa
                                                            </a>
                                                            <p class="text-muted text-uppercase fw-semibold small mb-1">
                                                                Home Address</p>
                                                            <h6 class="fw-bold mb-1">Raquel Murillo</h6>
                                                            <p class="text-muted mb-1">144 Cavendish Avenue, Indianapolis,
                                                                IN 46251</p>
                                                            <p class="text-muted mb-0">Mo. +(253) 01234 5678</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Shipping Address -->
                                                <div class="col-md-6">
                                                    <div class="card border-0 shadow-sm h-100">
                                                        <div class="card-body position-relative">
                                                            <a href="address.html"
                                                                class="badge bg-primary-subtle text-primary position-absolute top-0 end-0 m-2">
                                                                <i class="bi bi-pencil-fill me-1"></i> Chỉnh sửa
                                                            </a>
                                                            <p class="text-muted text-uppercase fw-semibold small mb-1">
                                                                Shipping Address</p>
                                                            <h6 class="fw-bold mb-1">James Honda</h6>
                                                            <p class="text-muted mb-1">1246 Virgil Street, Pensacola, FL
                                                                32501</p>
                                                            <p class="text-muted mb-0">Mo. +(253) 01234 5678</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end address row -->

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- end tab info -->

                        <!-- start tab order -->
                        <div class="tab-pane fade in h-100" id="tab_seven2">
                            <div class="row">
                                <div class="col-12">
                                    <div class="col tab-style-03">
                                        <ul
                                            class="nav justify-content-center text-center fw-500 border-color-light-medium-gray mb-7 gap-2">
                                            <li class="nav-item-date">
                                                Chọn khoảng thời gian<input type="text" name="daterange"
                                                    id="daterange" />
                                            </li>
                                        </ul>
                                        <ul
                                            class="nav justify-content-center text-center fw-500 border-color-light-medium-gray mb-7 gap-2">
                                            <li class="nav-item"><a class="nav-link active border text-black rounded"
                                                    data-bs-toggle="tab" href="#tab_third1">Tất cả</a></li>
                                            <li class="nav-item"><a class="nav-link border text-black rounded"
                                                    data-bs-toggle="tab" href="#tab_third2">Chờ xác nhận</a></li>
                                            <li class="nav-item"><a class="nav-link border text-black rounded"
                                                    data-bs-toggle="tab" href="#tab_third3">Đã xác nhận</a></li>
                                            <li class="nav-item"><a class="nav-link border text-black rounded"
                                                    data-bs-toggle="tab" href="#tab_third4">Đang vận chuyển</a></li>
                                            <li class="nav-item"><a class="nav-link border text-black rounded"
                                                    data-bs-toggle="tab" href="#tab_third5">Đã giao hàng</a></li>
                                            <li class="nav-item"><a class="nav-link border text-black rounded"
                                                    data-bs-toggle="tab" href="#tab_third6">Đã hủy</a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <!-- start tab content -->
                                            <div class="tab-pane fade in active show" id="tab_third1">
                                                <div class="card border-0 shadow-sm">
                                                    <div class="card-body p-4">
                                                        <!-- Order Header -->
                                                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
                                                            <div class="mb-2 mb-md-0">
                                                                <span class="text-muted small">Mã đơn hàng: #12345</span>
                                                                <span class="text-muted small ms-2">01/05/2024</span>
                                                            </div>
                                                            <span class="badge bg-warning text-dark">Chờ xác nhận</span>
                                                        </div>

                                                        <!-- Order Content -->
                                                        <div class="row g-3">
                                                            <!-- Product Image -->
                                                            <div class="col-4 col-md-2">
                                                                <img src="{{ asset('assets/images/shop/product1.png') }}" 
                                                                    alt="Product" class="img-fluid rounded w-100" />
                                                            </div>
                                                            
                                                            <!-- Product Info -->
                                                            <div class="col-8 col-md-7">
                                                                <h6 class="mb-1">Áo thun nam basic</h6>
                                                                <p class="text-muted small mb-1">Size: M | Màu: Đen</p>
                                                                <p class="text-muted small mb-0">Số lượng: 1</p>
                                                            </div>
                                                            
                                                            <!-- Price & Action -->
                                                            <div class="col-12 col-md-3 text-start text-md-end">
                                                                <h6 class="text-danger mb-2">350.000₫</h6>
                                                                <a href="#" class="text-primary">
                                                                    {{-- <i class="bi bi-eye me-1"></i> --}}
                                                                    <span>Xem Chi tiết</span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end tab content -->
                                           
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end tab đơn hàng -->
                        <!-- start tab Cài đặt -->
                        <div class="tab-pane fade in h-100" id="tab_seven3">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card shadow-sm rounded-lg border-0">
                                        <div class="card-body p-4">
                                            <form action="javascript:void(0);">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <h5 class="fs-5 text-primary text-uppercase mb-4 border-bottom pb-2">Thông tin cá nhân</h5>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="firstnameInput" class="form-label text-gray-600">Tên</label>
                                                            <input type="text" class="form-control border-gray-300 rounded-lg" id="firstnameInput" placeholder="Nhập tên của bạn">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="lastnameInput" class="form-label text-gray-600">Họ</label>
                                                            <input type="text" class="form-control border-gray-300 rounded-lg" id="lastnameInput" placeholder="Nhập họ của bạn" value="Murillo">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="phonenumberInput" class="form-label text-gray-600">Số điện thoại</label>
                                                            <input type="text" class="form-control border-gray-300 rounded-lg" id="phonenumberInput" placeholder="Nhập số điện thoại" value="+(253) 01234 5678">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="emailInput" class="form-label text-gray-600">Địa chỉ email</label>
                                                            <input type="email" class="form-control border-gray-300 rounded-lg" id="emailInput" placeholder="Nhập email của bạn" value="raque@toner.com">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="mb-3">
                                                            <label for="cityInput" class="form-label text-gray-600">Thành phố</label>
                                                            <input type="text" class="form-control border-gray-300 rounded-lg" id="cityInput" placeholder="Thành phố" value="Phoenix">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="mb-3">
                                                            <label for="countryInput" class="form-label text-gray-600">Quốc gia</label>
                                                            <input type="text" class="form-control border-gray-300 rounded-lg" id="countryInput" placeholder="Quốc gia" value="USA">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="mb-3">
                                                            <label for="zipcodeInput" class="form-label text-gray-600">Mã bưu điện</label>
                                                            <input type="text" class="form-control border-gray-300 rounded-lg" minlength="5" maxlength="6" id="zipcodeInput" placeholder="Nhập mã bưu điện" value="90011">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="mb-3 pb-2">
                                                            <label for="exampleFormControlTextarea" class="form-label text-gray-600">Mô tả</label>
                                                            <textarea class="form-control border-gray-300 rounded-lg" id="exampleFormControlTextarea" placeholder="Nhập mô tả của bạn" rows="3">Xin chào, tôi là Raquel Murillo. Tôi là một khách hàng thân thiết từ tháng 8 năm 2022. Rất mong được hỗ trợ nhanh chóng và hiệu quả.</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <div class="mb-3" id="changePassword">
                                                <h5 class="fs-5 text-primary text-uppercase mb-4 border-bottom pb-2">Thay đổi mật khẩu</h5>
                                                <form action="javascript:void(0);">
                                                    <div class="row g-3">
                                                        <div class="col-lg-4">
                                                            <div>
                                                                <label for="oldpasswordInput" class="form-label text-gray-600">Mật khẩu cũ*</label>
                                                                <input type="password" class="form-control border-gray-300 rounded-lg" id="oldpasswordInput" placeholder="Nhập mật khẩu hiện tại">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div>
                                                                <label for="newpasswordInput" class="form-label text-gray-600">Mật khẩu mới*</label>
                                                                <input type="password" class="form-control border-gray-300 rounded-lg" id="newpasswordInput" placeholder="Nhập mật khẩu mới">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <div>
                                                                <label for="confirmpasswordInput" class="form-label text-gray-600">Xác nhận mật khẩu*</label>
                                                                <input type="password" class="form-control border-gray-300 rounded-lg" id="confirmpasswordInput" placeholder="Xác nhận mật khẩu">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="mb-3">
                                                                <a href="auth-pass-reset-basic.html" class="text-blue-600 hover:underline">Quên mật khẩu?</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="mb-3" id="privacy">
                                                <h5 class="fs-5 text-primary text-uppercase mb-4 border-bottom pb-2">Chính sách bảo mật</h5>
                                                <div class="mb-3">
                                                    <h5 class="fs-15 mb-2 text-gray-800">Bảo mật:</h5>
                                                    <div class="d-flex flex-column align-items-center flex-sm-row mb-3">
                                                        <div class="flex-grow-1">
                                                            <p class="text-gray-600 fs-14 mb-0">Xác thực hai yếu tố</p>
                                                        </div>
                                                        <div class="flex-shrink-0 ms-sm-3">
                                                            <a href="javascript:void(0);" class="text-primary bg-primary bg-primary-subtle px-3 py-2 rounded">Kích hoạt xác thực hai yếu tố</a>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-column align-items-center flex-sm-row mb-3">
                                                        <div class="flex-grow-1">
                                                            <p class="text-gray-600 fs-14 mb-0">Xác minh thứ cấp</p>
                                                        </div>
                                                        <div class="flex-shrink-0 ms-sm-3">
                                                            <a href="javascript:void(0);" class="text-primary bg-primary bg-primary-subtle px-3 py-2 rounded">Thiết lập phương thức thứ cấp</a>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-column align-items-center flex-sm-row mb-3">
                                                        <div class="flex-grow-1">
                                                            <p class="text-gray-600 fs-14 mb-0">Mã dự phòng</p>
                                                        </div>
                                                        <div class="flex-shrink-0 ms-sm-3">
                                                            <a href="javascript:void(0);" class="text-primary bg-primary bg-primary-subtle px-3 py-2 rounded">Tạo mã dự phòng</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <h5 class="fs-15 mb-2 text-gray-800">Thông báo ứng dụng:</h5>
                                                    <ul class="list-unstyled mb-0">
                                                        <li class="d-flex align-items-center mb-2">
                                                            <div class="flex-grow-1">
                                                                <label for="directMessage" class="form-check-label fs-14 text-gray-600">Tin nhắn trực tiếp</label>
                                                            </div>
                                                            <div class="flex-shrink-0">
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input" type="checkbox" role="switch" id="directMessage" checked>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="d-flex align-items-center mb-2">
                                                            <div class="flex-grow-1">
                                                                <label class="form-check-label fs-14 text-gray-600" for="desktopNotification">Hiển thị thông báo trên máy tính</label>
                                                            </div>
                                                            <div class="flex-shrink-0">
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input" type="checkbox" role="switch" id="desktopNotification" checked>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="d-flex align-items-center mb-2">
                                                            <div class="flex-grow-1">
                                                                <label class="form-check-label fs-14 text-gray-600" for="emailNotification">Hiển thị thông báo email</label>
                                                            </div>
                                                            <div class="flex-shrink-0">
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input" type="checkbox" role="switch" id="emailNotification">
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="d-flex align-items-center mb-2">
                                                            <div class="flex-grow-1">
                                                                <label class="form-check-label fs-14 text-gray-600" for="chatNotification">Hiển thị thông báo trò chuyện</label>
                                                            </div>
                                                            <div class="flex-shrink-0">
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input" type="checkbox" role="switch" id="chatNotification">
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="d-flex align-items-center mb-2">
                                                            <div class="flex-grow-1">
                                                                <label class="form-check-label fs-14 text-gray-600" for="purchaesNotification">Hiển thị thông báo mua hàng</label>
                                                            </div>
                                                            <div class="flex-shrink-0">
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input" type="checkbox" role="switch" id="purchaesNotification">
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="text-sm-end mt-4">
                                                <a href="#!" class="btn btn-secondary rounded-lg hover:bg-gray-600 transition-colors duration-200">
                                                  Cập nhật hồ sơ
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                       
                        <!-- end tab Cài Đặt -->
                     
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end section -->
@endsection
