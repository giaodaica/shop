@extends('dashboard.layouts.layout')

@section('main-content')
    <div class="page-content">
        <div class="container-fluid">

            {{-- Ảnh nền profile --}}
            <div class="profile-foreground position-relative mx-n4 mt-n4">
                <div class="profile-wid-bg">
                    <img src="{{ asset('assets/images/profile-bg.jpg') }}" alt="" class="profile-wid-img" />
                </div>
            </div>

            {{-- Thông tin người dùng --}}
            <div class="pt-4 mb-4 pb-lg-4 profile-wrapper">
                <div class="row g-4">
                    <div class="col-auto">
                        <div class="avatar-lg">
                            <img src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="user-img"
                                class="img-thumbnail rounded-circle" />
                        </div>
                    </div>
                    <div class="col">
                        <div class="p-2">
                            <h3 class="text-white mb-1">{{ $user->name }}</h3>
                            <p class="text-white text-opacity-75">{{ $user->email }}</p>
                            <div class="hstack text-white-50 gap-2">
                                <div class="me-2"><i class="ri-phone-line me-1 text-white fs-16 align-middle"></i>
                                    {{ $user->default_phone ?? '-' }}
                                </div>
                                <div>
                                    <i class="ri-map-pin-line me-1 text-white fs-16 align-middle"></i>
                                    {{ $user->default_address ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-auto order-last order-lg-0">
                        <div class="row text text-white-50 text-center">
                            <div class="col-6">
                                <div class="p-2">
                                    <h4 class="text-white mb-1">{{ ucfirst($user->role) }}</h4>
                                    <p class="fs-14 mb-0">Vai trò</p>
                                </div>
                            </div>
                            @if ($user->role !== 'admin')
                                <div class="col-6">
                                    <div class="p-2">
                                        <h4 class="text-white mb-1">{{ ucfirst($user->rank) }}</h4>
                                        <p class="fs-14 mb-0">Hạng</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabs --}}
            <ul class="nav nav-tabs-custom card-header-tabs border-bottom-0" role="tablist">
                <li class="nav-item">
                    <a class="nav-link fw-semibold {{ $activeTab == 'overview' ? 'active text-white border-bottom border-white' : 'text-muted' }}"
                        href="{{ route('users.show', ['id' => $user->id, 'tab' => 'overview']) }}">
                        <i class="ri-user-3-line me-1 align-bottom"></i> Tổng quan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold {{ $activeTab == 'orders' ? 'active text-white border-bottom border-white' : 'text-muted' }}"
                        href="{{ route('users.show', ['id' => $user->id, 'tab' => 'orders']) }}">
                        <i class="ri-shopping-bag-3-line me-1 align-bottom"></i> Đơn hàng
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold {{ $activeTab == 'vouchers' ? 'active text-white border-bottom border-white' : 'text-muted' }}"
                        href="{{ route('users.show', ['id' => $user->id, 'tab' => 'vouchers']) }}">
                        <i class="ri-ticket-line me-1 align-bottom"></i> Voucher
                    </a>
                </li>
            </ul>

            {{-- Tab nội dung --}}
            <div class="tab-content pt-4 text-muted">
                @if ($activeTab == 'overview')
                    <div class="tab-pane fade show active" id="overview-tab" role="tabpanel">
                        <div class="row">
                            <div class="col-xxl-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Thông tin liên hệ</h5>
                                        <table class="table table-borderless mb-0">
                                            <tbody>
                                                <tr>
                                                    <th class="ps-0">Họ tên:</th>
                                                    <td class="text-muted">{{ $user->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-0">Email:</th>
                                                    <td class="text-muted">{{ $user->email }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-0">Điện thoại:</th>
                                                    <td class="text-muted">{{ $user->default_phone ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-0">Địa chỉ:</th>
                                                    <td class="text-muted">{{ $user->default_address ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-0">Vai trò:</th>
                                                    <td class="text-muted">
                                                        {{ $user->role === 'admin' ? 'Quản trị' : 'Khách hàng' }}</td>
                                                </tr>
                                                @if ($user->role !== 'admin')
                                                    <tr>
                                                        <th class="ps-0">Hạng:</th>
                                                        <td class="text-muted">{{ ucfirst($user->rank) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="ps-0">Điểm:</th>
                                                        <td class="text-muted">{{ $user->point }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="ps-0">Đã chi tiêu:</th>
                                                        <td class="text-muted">
                                                            {{ number_format($user->total_spent, 0, ',', '.') }} VNĐ</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xxl-8">
                                {{-- Tổng kết thành viên --}}
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div
                                            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-3">
                                            <div>
                                                <h5 class="card-title mb-1">Tổng kết thành viên</h5>
                                                <p class="text-muted fs-14 mb-0">Tổng tiền và số đơn hàng được tính chung từ
                                                    hệ thống.</p>
                                            </div>
                                           
                                        </div>

                                        <div class="row text-center">
                                            <div class="col-md-4">
                                                <div class="p-3">
                                                    <div class="fs-18 fw-semibold">{{ $user->orders_count ?? 0 }}</div>
                                                    <div class="text-muted">Tổng số đơn hàng đã mua</div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="p-3">
                                                    <div class="fs-18 fw-semibold">
                                                        {{ number_format($user->total_spent, 0, ',', '.') }}₫
                                                    </div>
                                                    <div class="text-muted">Tổng tiền tích lũy</div>
                                                    <small class="text-muted d-block mt-1">
                                                        Cần thêm
                                                        {{ number_format(max(0, $user->rank_next_threshold - $user->total_spent), 0, ',', '.') }}₫
                                                        để lên hạng {{ strtoupper($user->next_rank ?? '...') }}
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="p-3">
                                                    <div class="d-flex align-items-center justify-content-center mb-2">
                                                       
                                                        <strong>{{ ucfirst($user->rank) ?? '---' }}</strong>
                                                    </div>
                                                    <div class="text-muted">Hạng hiện tại</div>
                                                   
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Lịch sử đơn hàng --}}
                                <div class="card">
                                    <div class="card-header border-bottom">
                                        <h5 class="card-title mb-0">Lịch sử đơn hàng gần đây</h5>
                                    </div>
                                    <div class="card-body">
                                        @if ($recentOrders->isEmpty())
                                            <div class="alert alert-warning mb-0">
                                                <i class="ri-information-line me-1 align-middle"></i>
                                                Người dùng chưa có đơn hàng nào.
                                            </div>
                                        @else
                                            <ul class="list-group list-group-flush">
                                                @foreach ($recentOrders as $order)
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <strong>#{{ $order->id }}</strong> -
                                                            {{ $order->created_at->format('d/m/Y H:i') }}
                                                            <br>
                                                            <span class="text-muted">Tổng tiền:
                                                                <strong>{{ number_format($order->total_amount, 0, ',', '.') }}₫</strong>
                                                            </span>
                                                        </div>
                                                        <span
                                                            class="badge 
                                @switch($order->status)
                                    @case('pending') bg-warning text-dark @break
                                    @case('confirmed') bg-info @break
                                    @case('shipping') bg-primary @break
                                    @case('success') bg-success @break
                                    @case('cancelled') bg-secondary @break
                                    @case('failed') bg-danger @break
                                    @default bg-light text-dark
                                @endswitch">
                                                            {{ ucfirst($order->status) }}
                                                        </span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <div class="text-end mt-3">
                                                <a href="{{ route('users.show', ['id' => $user->id, 'tab' => 'orders']) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    Xem tất cả đơn hàng <i
                                                        class="ri-arrow-right-line align-middle ms-1"></i>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                @elseif ($activeTab == 'orders')
                    <div class="tab-pane fade show active" id="orders-tab" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Đơn hàng</h5>

                                <div class="row text-center">
                                    @foreach ($orderStats as $status => $count)
                                        <div class="col-md-2 mb-3">
                                            <div class="border rounded p-2 bg-light">
                                                <div class="fs-5 fw-bold">{{ $count }}</div>
                                                <div class="text-muted">
                                                    @switch($status)
                                                        @case('total')
                                                            Tổng cộng
                                                        @break

                                                        @case('pending')
                                                            Chờ xác nhận
                                                        @break

                                                        @case('confirmed')
                                                            Đã xác nhận
                                                        @break

                                                        @case('shipping')
                                                            Đang giao
                                                        @break

                                                        @case('success')
                                                            Đã giao
                                                        @break

                                                        @case('cancelled')
                                                            Đã hủy
                                                        @break

                                                        @case('failed')
                                                            Thất bại
                                                        @break
                                                    @endswitch
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Có thể thêm bảng chi tiết đơn hàng ở đây nếu muốn --}}
                            </div>
                        </div>
                    </div>
                @elseif ($activeTab == 'vouchers')
                    <div class="tab-pane fade show active" id="vouchers-tab" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3 text-dark">Danh sách Voucher</h5>

                                @if ($vouchers->isEmpty())
                                    <div class="alert alert-info mb-0">
                                        <i class="ri-information-line me-1 align-middle"></i>
                                        Người dùng này <strong>chưa sử dụng voucher nào</strong>.
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Mã</th>
                                                    <th>Tên</th>
                                                    <th>Giá trị</th>
                                                    <th>Hết hạn</th>
                                                    <th>Trạng thái</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($vouchers as $voucher)
                                                    <tr>
                                                        <td><strong>{{ $voucher['code'] }}</strong></td>
                                                        <td>{{ $voucher['name'] }}</td>
                                                        <td>
                                                            @if ($voucher['type'] === 'percent')
                                                                {{ $voucher['value'] }}%
                                                            @else
                                                                {{ number_format($voucher['value'], 0, ',', '.') }}₫
                                                            @endif
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($voucher['end_date'])->format('d/m/Y') }}
                                                        </td>
                                                        <td>
                                                            @switch($voucher['status'])
                                                                @case('Đã dùng')
                                                                    <span class="badge bg-success">Đã dùng</span>
                                                                @break

                                                                @case('Chưa dùng')
                                                                    <span class="badge bg-warning text-dark">Chưa dùng</span>
                                                                @break

                                                                @case('Hết hạn')
                                                                    <span class="badge bg-danger">Hết hạn</span>
                                                                @break
                                                            @endswitch
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>

                @endif
            </div>

            {{-- Nút quay lại --}}
            <div class="mt-3">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="ri-arrow-left-line"></i> Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
@endsection
