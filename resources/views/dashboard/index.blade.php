@extends('dashboard.layouts.layout')

@section('main-content')
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Dashboard</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item active">Trang chủ</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->



        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <span class="text-muted text-uppercase fw-medium">Tổng người dùng</span>
                                <h4 class="fs-4 fw-semibold ff-secondary mb-0"><span class="counter-value" data-target="{{ $totalUsers }}">0</span></h4>
                            </div>
                            <div class="text-primary">
                                <i class="ri-user-3-line fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <span class="text-muted text-uppercase fw-medium">Tổng đơn hàng</span>
                                <h4 class="fs-4 fw-semibold ff-secondary mb-0"><span class="counter-value" data-target="{{ $totalOrders }}">0</span></h4>
                            </div>
                            <div class="text-success">
                                <i class="ri-shopping-cart-2-line fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <span class="text-muted text-uppercase fw-medium">Tổng sản phẩm</span>
                                <h4 class="fs-4 fw-semibold ff-secondary mb-0"><span class="counter-value" data-target="{{ $totalProducts }}">0</span></h4>
                            </div>
                            <div class="text-info">
                                <i class="ri-store-2-line fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <span class="text-muted text-uppercase fw-medium">Chào mừng</span>
                                <h4 class="fs-4 fw-semibold ff-secondary mb-0">{{ Auth::user()->name }}</h4>
                            </div>
                            <div class="text-warning">
                                <i class="ri-hand-heart-line fs-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Đơn hàng gần đây</h4>
                    </div><!-- end card header -->

                    <div class="card-body">
                        <div class="table-responsive table-card">
                            <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                                <thead class="text-muted table-light">
                                    <tr>
                                        <th scope="col">Mã đơn hàng</th>
                                        <th scope="col">Khách hàng</th>
                                        <th scope="col">Tổng tiền</th>
                                        <th scope="col">Trạng thái</th>
                                        <th scope="col">Ngày tạo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentOrders as $order)
                                    <tr>
                                        <td>
                                            <a href="#" class="fw-medium link-primary">#{{ $order->id }}</a>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <img src="{{ asset('admin/images/users/avatar-1.jpg') }}" alt="" class="avatar-xs rounded-circle" />
                                                </div>
                                                <div class="flex-grow-1">{{ $order->user->name ?? 'N/A' }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-success">{{ number_format($order->total_amount, 0, ',', '.') }} đ</span>
                                        </td>
                                        <td>
                                            @if($order->status == 'pending')
                                                <span class="badge bg-warning-subtle text-warning">Chờ xử lý</span>
                                            @elseif($order->status == 'processing')
                                                <span class="badge bg-info-subtle text-info">Đang xử lý</span>
                                            @elseif($order->status == 'shipped')
                                                <span class="badge bg-primary-subtle text-primary">Đã giao hàng</span>
                                            @elseif($order->status == 'delivered')
                                                <span class="badge bg-success-subtle text-success">Đã nhận hàng</span>
                                            @elseif($order->status == 'cancelled')
                                                <span class="badge bg-danger-subtle text-danger">Đã hủy</span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary">{{ $order->status }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Chưa có đơn hàng nào</td>
                                    </tr>
                                    @endforelse
                                </tbody><!-- end tbody -->
                            </table><!-- end table -->
                        </div>
                    </div>
                </div> <!-- .card-->
            </div> <!-- .col-->
        </div> <!-- end row-->

    </div> <!-- container-fluid -->
</div><!-- End Page-content -->
@endsection
