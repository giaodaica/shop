@extends('layouts.layout')
@section('cdn-custom')
    <link rel="stylesheet" href="{{ asset('assets/css/info.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
@endsection
@section('js-page-custom')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('assets/js/info/info.js') }}"></script>
@endsection

@section('content')
    <!-- start page title -->
    <section class="page-title-center-alignment cover-background top-space-padding">
        <div class="container">
            <div class="row">

                <div class="col-12 breadcrumb breadcrumb-style-01 d-flex justify-content-center">
                    <ul>
                        <li><a href="{{ route('home') }}">Trang chủ</a></li>
                        <li>Quản lý tài khoản</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>


    <!-- end page title -->
    <!-- start section -->
    <section class="position-relative">
        <div class="container">
            <!-- Alert Section -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

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
                                        <span class="me-5px"><i class="bi bi-ticket"></i></span>
                                        <span>Voucher của tôi</span>
                                    </span>
                                    <span class="bg-hover bg-base-color"></span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <a href="#" onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="nav-link">
                                        <span>
                                            <span class="me-5px"><i class="bi bi-power"></i></span>
                                            <span>Đăng xuất</span>
                                        </span>
                                        <span class="bg-hover bg-base-color"></span>
                                    </a>
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
                                                <h6 class="text-primary text-uppercase fs-5 mb-0">Thông
                                                    tin khách hàng</h6>
                                                <a href="#" class="badge bg-light text-primary fw-normal"
                                                    data-bs-toggle="modal" data-bs-target="#editCustomerInfoModal"><i
                                                        class="bi bi-pencil-fill me-1"></i>Chỉnh sửa</a>
                                            </div>

                                            <div class="table-responsive px-1 mb-5">
                                                <table class="table table-borderless table-sm">
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-muted">Họ và tên</td>
                                                            <td class="fw-semibold">
                                                                {{ Auth::user()->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">Số điện thoại</td>
                                                            <td class="fw-semibold">
                                                                {{ Auth::user()->default_phone ? Auth::user()->default_phone : 'Chưa cập nhật' }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">Email</td>
                                                            <td class="fw-semibold">
                                                                {{ Auth::user()->email }}</td>
                                                        </tr>

                                                        <tr>
                                                            <td class="text-muted">Thành viên từ</td>
                                                            <td class="fw-semibold">
                                                                {{ Auth::user()->created_at->format('d/m/Y H:i:s') }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">Mật khẩu</td>
                                                            <td class="fw-semibold">
                                                                <span class="password-mask">***********</span>
                                                                <a href="#"
                                                                    class="badge bg-light text-primary fw-normal ms-2"
                                                                    id="showChangePasswordForm">
                                                                    <i class="bi bi-key-fill me-1"></i>Đổi mật khẩu
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <!-- Form đổi mật khẩu (ẩn mặc định) -->
                                            <div id="changePasswordForm" class="mb-5" style="display: none;">
                                                <div class="card border-0 shadow-sm">
                                                    <div class="card-body">
                                                        <h6 class="text-primary text-uppercase fs-5 mb-4">Đổi mật khẩu</h6>
                                                        <form action="" method="POST">
                                                            @csrf

                                                            <div class="row g-3">
                                                                <div class="col-md-12">
                                                                    <div class="mb-3">
                                                                        <label for="current_password"
                                                                            class="form-label">Mật khẩu hiện tại</label>
                                                                        <input type="password" class="form-control"
                                                                            id="current_password" name="current_password"
                                                                            required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <label for="new_password" class="form-label">Mật
                                                                            khẩu mới</label>
                                                                        <input type="password" class="form-control"
                                                                            id="new_password" name="new_password"
                                                                            required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <label for="new_password_confirmation"
                                                                            class="form-label">Xác nhận mật khẩu
                                                                            mới</label>
                                                                        <input type="password" class="form-control"
                                                                            id="new_password_confirmation"
                                                                            name="new_password_confirmation" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <button type="submit"
                                                                        class="btn btn-medium btn-dark-gray">Cập nhật mật
                                                                        khẩu</button>
                                                                    <button type="button"
                                                                        class="btn btn-fancy btn-medium btn-light-gray ms-2"
                                                                        id="cancelChangePassword">Hủy</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Section: Địa chỉ giao hàng -->
                                            <div
                                                class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-4">
                                                <h6 class="text-primary text-uppercase fs-5 mb-0">Địa
                                                    chỉ giao hàng</h6>
                                                <a href="{{ route('addresses.store') }}">
                                                    <span class="badge bg-light text-primary fw-normal"><i
                                                            class="bi bi-geo-alt-fill me-1"></i>Chỉnh sửa
                                                    </span></a>
                                            </div>
                                            <div class="row g-4">
                                                <!-- Home Address -->
                                                @foreach ($addresses as $addr)
                                                    <div class="col-md-6">
                                                        <div class="card border-0 shadow-sm h-100">
                                                            <div class="card-body position-relative">
                                                                <h6 class="fw-bold mb-1">{{ $addr->name }}</h6>
                                                                <p class="text-muted mb-1"><i class="bi bi-geo-alt-fill me-1"></i>{{ $addr->address }}, {{ $addr->ward->name ?? '' }}, {{ $addr->province->name ?? '' }}</p>
                                                                <p class="text-muted mb-0"><i class="bi bi-telephone-fill me-1"></i>{{ $addr->phone }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
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
                                                Chọn khoảng thời gian
                                                <input type="text" name="daterange" id="daterange" style="margin-left: -10px; min-width:220px; max-width:300px; width:260px;" />
                                            </li>
                                        </ul>
                                        <ul
                                            class="nav justify-content-center text-center fw-500 border-color-light-medium-gray mb-7 gap-2">
                                            <li class="nav-item"><a class="nav-link active border text-black rounded"
                                                    data-bs-toggle="tab" href="#tab_third1">Tất cả</a>
                                            </li>
                                            <li class="nav-item"><a class="nav-link border text-black rounded"
                                                    data-bs-toggle="tab" href="#tab_third2">Chờ xác
                                                    nhận</a></li>
                                            <li class="nav-item"><a class="nav-link border text-black rounded"
                                                    data-bs-toggle="tab" href="#tab_third3">Đã xác
                                                    nhận</a></li>
                                            <li class="nav-item"><a class="nav-link border text-black rounded"
                                                    data-bs-toggle="tab" href="#tab_third4">Đang vận
                                                    chuyển</a></li>
                                            <li class="nav-item"><a class="nav-link border text-black rounded"
                                                    data-bs-toggle="tab" href="#tab_third5">Đã giao
                                                    hàng</a></li>
                                            <li class="nav-item"><a class="nav-link border text-black rounded"
                                                    data-bs-toggle="tab" href="#tab_third6">Đã hủy</a>
                                            </li>
                                            
                                        </ul>
                                        <div class="tab-content">
                                            {{-- <pre>{{ print_r($orders, true) }}</pre> --}}
                                            <!-- start tab content -->
                                            <div class="tab-pane fade active" id="tab_third1">
                                                @include('pages.shop.partials.order-list', ['orders' => $orders])
                                            </div>
                                            <div class="tab-pane fade" id="tab_third2">
                                                @include('pages.shop.partials.order-list', ['orders' => $pendingOrders])
                                            </div>
                                            <div class="tab-pane fade" id="tab_third3">
                                                @include('pages.shop.partials.order-list', ['orders' => $confirmedOrders])
                                            </div>
                                            <div class="tab-pane fade" id="tab_third4">
                                                @include('pages.shop.partials.order-list', ['orders' => $shippingOrders])
                                            </div>
                                            <div class="tab-pane fade" id="tab_third5">
                                                @include('pages.shop.partials.order-list', ['orders' => $successOrders])
                                            </div>
                                            <div class="tab-pane fade" id="tab_third6">
                                                @include('pages.shop.partials.order-list', ['orders' => $cancelledOrders])
                                            </div>
                                            <!-- end tab content -->

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end tab đơn hàng -->
                        <!-- start tab Voucher của tôi -->
                        <div class="tab-pane fade in h-100" id="tab_seven3">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card shadow-sm rounded-lg border-0">
                                        <div class="card-body p-4">
                                            <h5 class="fs-5 text-primary text-uppercase mb-4 border-bottom pb-2">
                                                Danh sách Voucher của bạn
                                            </h5>
                                            @if($vouchers->isEmpty())
                                                <div class="alert alert-info mb-0">
                                                    <i class="bi bi-info-circle me-1 align-middle"></i>
                                                    Bạn chưa có voucher nào.
                                                </div>
                                            @else
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Mã voucher</th>
                                                                <th>Tên loại</th>
                                                                <th>Loại giảm</th>
                                                                <th>Giá trị</th>
                                                                <th>Hạn dùng</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($vouchers as $voucher)
                                                                <tr>
                                                                    <td><strong>{{ $voucher['code'] }}</strong></td>
                                                                    <td>{{ $voucher['name'] }}</td>
                                                                    <td>{{ $voucher['type'] === 'percent' ? 'Phần trăm' : 'Tiền mặt' }}</td>
                                                                    <td>
                                                                        @if($voucher['type'] === 'percent')
                                                                            {{ $voucher['value'] }}%
                                                                        @else
                                                                            {{ number_format($voucher['value'], 0, ',', '.') }}₫
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ \Carbon\Carbon::parse($voucher['end_date'])->format('d/m/Y') }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end tab Voucher của tôi -->

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end section -->

    @include('pages.shop.modals.edit-customer-info')
@endsection
