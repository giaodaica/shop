@extends('layouts.layout')


@section('content')
    <section class="page-title-center-alignment cover-background top-space-padding">
        <div class="container">
            <div class="row">

                <div class="col-12 breadcrumb breadcrumb-style-01 d-flex justify-content-center">
                    <ul>
                        <li><a href="{{ route('home') }}">Trang chủ</a></li>
                        <li>Chi tiết đơn hàng</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="py-4">
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-success">
                    {{ session('error') }}
                </div>
            @endif
            <div class="card mb-4">

                <div class="card-body">
                    <div class="col-lg-12">
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold fs-14">Mã hóa đơn</p>
                                    <strong>{{ $order->code_order }}</strong>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold fs-14">Ngày đặt</p>
                                    <h5 class="fs-15 mb-0">
                                        <strong>{{ formatDate($order->created_at) }}</strong>
                                        {{-- <small class="text-muted"
                                            id="invoice-time">{{ formatDate($order->created_at, 'H:i:s') }}</small> --}}
                                    </h5>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold fs-14">Trạng thái đơn hàng</p>
                                    @php
                                        $statusMap = [
                                            'pending' => ['color' => 'warning', 'label' => 'Chờ xác nhận'],
                                            'confirmed' => ['color' => 'info', 'label' => 'Đã xác nhận'],
                                            'shipping' => ['color' => 'primary', 'label' => 'Đang giao'],
                                            'success' => ['color' => 'success', 'label' => 'Thành công'],
                                            'failed' => ['color' => 'danger', 'label' => 'Thất bại'],
                                            'cancelled' => ['color' => 'secondary', 'label' => 'Đã hủy'],
                                        ];
                                        $status = $order->status;
                                        $statusColor = $statusMap[$status]['color'] ?? 'secondary';
                                        $statusLabel = $statusMap[$status]['label'] ?? ucfirst($status);
                                    @endphp
                                    <span class="badge bg-{{ $statusColor }}-subtle text-{{ $statusColor }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                                <div class="col-lg-3 col-6">
                                    <p class="text-muted mb-2 text-uppercase fw-semibold fs-14">Tổng tiền</p>
                                    <strong>{{ number_format($order->total_amount) }}đ</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        @if (isset($order))
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1 pe-3">
                                    <h6 class="mb-2 text-primary fw-bold">

                                        {{ $order->is_default ? 'Địa chỉ mặc định' : 'Địa chỉ giao hàng' }}
                                    </h6>
                                    <p class="mb-1"><strong>Người nhận:</strong> {{ $order->name }}</p>
                                    <p class="mb-1"><strong>Địa chỉ:</strong> {{ $order->address }}</p>
                                    <p class="mb-1">{{ $order->ward_name }}, {{ $order->district_name }},
                                        {{ $order->province_name }}</p>
                                    <p class="mb-0"><strong>Điện thoại:</strong> {{ $order->phone }}</p>
                                </div>

                                {{-- Nút chỉnh sửa --}}
                                <div>
                                    <a href="javascript:void(0);" onclick="showEditForm()" class="btn btn-sm no-hover">
                                        <i class="fas fa-edit me-1"></i> Sửa
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Form chỉnh sửa địa chỉ (ẩn mặc định) -->
                    <form id="addressForm" action="{{ route('order.update', $order->id) }}" method="POST"
                        style="display: none;">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="ward_code_hidden" id="ward_code_hidden"
                            value="{{ $order->ward_code }}">

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Họ và tên</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ $order->name }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Số điện thoại</label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        value="{{ $order->phone }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="province_code" class="form-label">Tỉnh/Thành phố</label>
                                    <select class="form-select" id="province_code" name="province_code">
                                        <option value="{{ $order->province_code }}">{{ $order->province_name }}</option>
                                        @foreach ($provinces as $province)
                                            <option value="{{ $province->province_code }}">{{ $province->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="ward_code" class="form-label">Xã/Phường</label>
                                    <select class="form-select" id="ward_code" name="ward_code" disabled>
                                        <option value="{{ $order->ward_code }}">{{ $order->ward_name }}</option>
                                    </select>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="address" class="form-label">Địa chỉ cụ thể</label>
                                    <textarea class="form-control" id="address" name="address" rows="2">{{ $order->address }}</textarea>
                                </div>
                                <div class="col-12 d-flex justify-content-between">
                                    <button type="submit" class="btn btn-primary no-hover">Cập nhật</button>
                                    <button type="button" class="btn btn-secondary no-hover"
                                        onclick="hideEditForm()">Hủy</button>
                                </div>
                            </div>
                        </div>
                    </form>


                    <!-- Bảng cho màn hình lớn -->
                    <div class="table-responsive mb-3 d-none d-md-block">
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Chi tiết sản phẩm</th>
                                    <th>Giá</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->orderItems as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="d-flex align-items-center gap-2">
                                            <img src="{{ asset($item->product_image_url) }}"
                                                alt="{{ $item->product_name }}" class="order-img"
                                                style="width: 60px; height: 60px;" />
                                            <div style="line-height:1.3;">
                                                <div class="product-name-truncate fw-bold">
                                                    {{ $item->product_name }}
                                                </div>
                                                <div style="font-size: 13px; color: #555;">
                                                    {{ $item->productVariant->color->color_name ?? $item->color_name }},
                                                    {{ $item->productVariant->size->size_name ?? $item->size_name }}
                                                    <br>
                                                    x{{ $item->quantity }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ number_format($item->sale_price) }}đ</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Layout cho mobile -->
                    <div class="d-block d-md-none">
                        @foreach ($order->orderItems as $index => $item)
                            <div class="border rounded p-2 mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ asset($item->product_image_url) }}" alt="{{ $item->product_name }}"
                                        style="width: 60px; height: 60px;" />
                                    <div style="line-height: 1.3;">
                                        <div class="fw-bold">{{ $item->product_name }}</div>
                                        <div style="font-size: 13px; color: #555;">
                                            {{ $item->productVariant->color->color_name ?? '-' }},
                                            {{ $item->productVariant->size->size_name ?? '-' }}<br>
                                            x{{ $item->quantity }}
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2 text-end fw-bold text-danger">
                                    {{ number_format($item->sale_price) }}đ
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mb-3">
                        <table class="table table-borderless w-auto ms-auto">
                            <tr>
                                <td>Tổng phụ</td>
                                <td class="text-end">{{ number_format($subtotal) }}đ</td>
                            </tr>
                            @if ($discount > 0)
                                <tr>
                                    <td>Giảm giá</td>
                                    <td class="text-end">- {{ number_format($discount) }}đ</td>
                                </tr>
                            @endif
                            <tr>
                                <td>Phí vận chuyển</td>
                                <td class="text-end">{{ number_format($shipping) }}đ</td>
                            </tr>
                            <tr>
                                <th>Tổng cộng</th>
                                <th class="text-end">{{ number_format($total) }}đ</th>
                            </tr>
                        </table>
                    </div>
                    <div class="mb-3">
                        <strong>Chi tiết thanh toán:</strong><br>
                        Phương thức thanh toán: <strong>{{ $order->pay_method }}</strong><br>
                        Thời gian thanh toán: <strong>{{ $order->created_at }}</strong>
                    </div>

                    @if ($order->user_confirm)
                        <div class="mb-3">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Thông tin xác nhận nhận hàng
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            @if ($order->user_comment)
                                                <div class="mb-3">
                                                    <p class="mb-2"><strong>Ghi chú:</strong></p>
                                                    <p class="text-muted mb-0">{{ $order->user_comment }}</p>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            @if ($order->image_user)
                                                <div class="mb-3">

                                                    <div class="text-center">
                                                        <div>
                                                            <strong class="me-2">Ảnh xác nhận:</strong>
                                                        </div>
                                                        <img src="{{ asset($order->image_user) }}"
                                                            alt="Ảnh xác nhận nhận hàng" class="img-fluid rounded"
                                                            style="max-width: 200px; max-height: 200px; cursor: pointer;"
                                                            onclick="openImageModal('{{ asset($order->image_user) }}', 'Ảnh xác nhận nhận hàng')">
                                                        <br>
                                                        <small class="text-muted">Click để xem ảnh lớn</small>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="d-flex justify-content-end gap-2">
                        @if ($order->status == 'pending')
                            <a href="javascript:void(0)" class="btn btn-danger no-hover" data-bs-toggle="modal"
                                data-bs-target="#cancelOrderModal">Hủy đơn hàng</a>
                        @endif
                        @if (!empty($order->image_ship))
                            <a href="javascript:void(0)" class="btn btn-info no-hover" data-bs-toggle="modal"
                                data-bs-target="#shipperImageModal">
                                Xem ảnh giao hàng
                            </a>
                        @endif
                        @if (!empty($order->image_ship) && !$order->user_confirm)
                            <a href="javascript:void(0)" class="btn btn-success no-hover" data-bs-toggle="modal"
                                data-bs-target="#userConfirmationModal">Xác nhận nhận hàng</a>
                        @endif
                        @if ($order->user_confirm)
                            <span class="btn btn-success no-hover">Đã xác nhận nhận hàng</span>
                        @endif
                        <a href="{{ route('home.info') }}" class="btn btn-warning no-hover">Quay lại</a>

                        @if ($refund)
                            @if ($refund->status == 'approved')
                                <span class="btn btn-success no-hover">Đã hoàn tiền</span>
                                @if (!empty($refund->images))
                                    <button type="button" class="btn btn-info no-hover" data-bs-toggle="modal"
                                        data-bs-target="#qrImageModal">Xem bill</button>
                                @endif
                            @elseif ($refund->status == 'pending')
                                <span class="btn btn-secondary no-hover">Đang chờ xử lý</span>
                                <button type="button" class="btn btn-info no-hover" data-bs-toggle="modal"
                                    data-bs-target="#refundDetailModal">
                                    Xem chi tiết
                                </button>
                            @else
                                <a href="{{ route('order.refund.request', $order->id) }}"
                                    class="btn btn-primary no-hover">Yêu cầu hoàn tiền</a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Hủy đơn hàng -->
    <div class="modal fade" id="cancelOrderModal" tabindex="-1" role="dialog" aria-labelledby="cancelOrderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="POST" action="{{ route('order.cancel', $order->id) }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cancelOrderModalLabel">Chọn lý do hủy đơn hàng</h5>

                    </div>
                    <div class="modal-body">
                        <select name="cancel_reason" class="form-control" required>
                            <option value="">-- Chọn lý do --</option>
                            <option value="Đổi ý, không muốn mua nữa">Đổi ý, không muốn mua nữa</option>
                            <option value="Đặt nhầm sản phẩm">Đặt nhầm sản phẩm</option>
                            <option value="Tìm được giá tốt hơn">Tìm được giá tốt hơn</option>
                            <option value="Không liên lạc được với shop">Không liên lạc được với shop</option>
                            <option value="Thời gian giao hàng quá lâu">Thời gian giao hàng quá lâu</option>
                            <option value="Muốn thay đổi địa chỉ nhận hàng">Muốn thay đổi địa chỉ nhận hàng</option>
                            <option value="Sản phẩm không còn nhu cầu">Sản phẩm không còn nhu cầu</option>
                            <option value="Khác">Khác</option>
                        </select>
                        <textarea name="cancel_note" class="form-control mt-2" placeholder="Ghi chú thêm (nếu có)"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary no-hover" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-danger no-hover">Xác nhận hủy đơn</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Xác nhận nhận hàng -->
    <div class="modal fade" id="userConfirmationModal" tabindex="-1" role="dialog"
        aria-labelledby="userConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form method="POST" action="{{ route('order.submit.confirmation', $order->id) }}"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userConfirmationModalLabel">Xác nhận nhận hàng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="user_image" class="form-label">Ảnh xác nhận nhận hàng <span
                                            class="text-danger">*</span></label>
                                    <input type="file" class="form-control" id="user_image" name="user_image"
                                        accept="image/*" required onchange="handleImageChange(event)"
                                        style="cursor: pointer;">
                                    <div class="form-text">Vui lòng chụp ảnh sản phẩm đã nhận để xác nhận</div>
                                </div>
                                <div class="mb-3">
                                    <label for="user_comment" class="form-label">Ghi chú (tùy chọn)</label>
                                    <textarea class="form-control" id="user_comment" name="user_comment" rows="4"
                                        placeholder="Nhập ghi chú nếu có..."></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Xem trước ảnh</label>
                                    <div id="imagePreview"
                                        class="border rounded p-3 text-center d-flex flex-column align-items-center justify-content-center"
                                        style="min-height: 250px; background-color: #f8f9fa; border: 2px dashed #dee2e6;">
                                        <i class="fas fa-image text-muted"
                                            style="font-size: 3rem; margin-bottom: 0.5rem;"></i>
                                        <p class="text-muted mb-0">Chưa có ảnh</p>
                                        <small class="text-muted">Chọn file ảnh để xem trước</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary no-hover" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-success no-hover">Xác nhận nhận hàng</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Hoàn tiền -->
    <!-- Đã chuyển sang view riêng, xóa modal này -->

    @if ($refund && $refund->status == 'pending')
        <!-- Modal Chi tiết hoàn tiền -->
        <div class="modal fade" id="refundDetailModal" tabindex="-1" role="dialog"
            aria-labelledby="refundDetailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content refund-detail-modal-content">
                    <div class="modal-header border-0 pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="modal-title fw-bold" id="refundDetailModalLabel">Chi tiết yêu cầu hoàn tiền</h5>
                        <button type="button" class="btn-close btn-close-lg" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-4">
                        <div class="row g-3">
                            @php
                                $hasBank =
                                    !empty($refund->bank) ||
                                    !empty($refund->stk) ||
                                    !empty($refund->bank_account_name) ||
                                    !empty($refund->reason);
                                $hasQR = !empty($refund->QR_images);
                            @endphp
                            @if ($hasBank)
                                <div class="col-12 col-md-6">
                                    @if (!empty($refund->bank))
                                        <div class="mb-2"><span class="fw-semibold text-muted">Ngân hàng:</span> <span
                                                class="fw-bold">{{ $refund->bank }}</span></div>
                                    @endif
                                    @if (!empty($refund->stk))
                                        <div class="mb-2"><span class="fw-semibold text-muted">Số tài khoản:</span>
                                            <span class="fw-bold">{{ $refund->stk }}</span>
                                        </div>
                                    @endif
                                    @if (!empty($refund->bank_account_name))
                                        <div class="mb-2"><span class="fw-semibold text-muted">Tên chủ thẻ:</span> <span
                                                class="fw-bold">{{ $refund->bank_account_name }}</span></div>
                                    @endif
                                    <div class="mb-2"><span class="fw-semibold text-muted">Số tiền hoàn:</span> <span
                                            class="fw-bold text-danger">{{ number_format($refund->amount) }}đ</span></div>
                                    @if (!empty($refund->reason))
                                        <div class="mb-2"><span class="fw-semibold text-muted">Lý do:</span> <span
                                                class="fw-bold">{{ $refund->reason }}</span></div>
                                    @endif
                                    <div class="mb-2 mt-3"><span class="fw-semibold text-muted">Trạng thái:</span> <span
                                            class="badge bg-secondary-subtle text-secondary">Đang chờ xử lý</span></div>
                                </div>
                            @endif
                            @if ($hasQR)
                                <div
                                    class="col-12 col-md-{{ $hasBank ? '6' : '12' }} d-flex flex-column align-items-center justify-content-center">
                                    <div class="mb-2"><span class="fw-semibold text-muted">Mã QR đã upload:</span></div>
                                    <img src="{{ asset($refund->QR_images) }}" alt="QR Code"
                                        class="img-fluid refund-detail-qr-img shadow"
                                        style="max-width: 220px; border-radius: 14px; border: 2px solid #f1f1f1; transition: transform 0.2s; cursor: pointer;" />
                                    <div class="mt-3"><span class="fw-semibold text-muted">Số tiền hoàn:</span> <span
                                            class="fw-bold text-danger">{{ number_format($refund->amount) }}đ</span></div>
                                    <div class="mb-2 mt-3"><span class="fw-semibold text-muted">Trạng thái:</span> <span
                                            class="badge bg-secondary-subtle text-secondary">Đang chờ xử lý</span></div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 d-flex justify-content-center">
                        <button type="button" class="btn btn-secondary no-hover px-4 py-2 fs-5"
                            data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .refund-detail-modal-content {
                border-radius: 18px;
                box-shadow: 0 8px 32px rgba(60, 60, 60, 0.18);
                background: #fff;
            }

            .refund-detail-qr-img {
                border-radius: 14px;
                border: 2px solid #f1f1f1;
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.10);
                max-width: 90vw;
                max-height: 40vh;
                margin: 0 auto;
                display: block;
            }

            .refund-detail-qr-img:hover {
                transform: scale(1.05);
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.18);
            }

            .btn-close-lg {
                font-size: 1.5rem;
                width: 2.5rem;
                height: 2.5rem;
            }

            @media (max-width: 600px) {
                .refund-detail-qr-img {
                    max-width: 98vw;
                    max-height: 30vh;
                }

                .modal-content.refund-detail-modal-content {
                    padding: 0 2px;
                }
            }
        </style>
    @endif

    <!-- Modal hiển thị ảnh QR -->
    <div class="modal fade" id="qrImageModal" tabindex="-1" role="dialog" aria-labelledby="qrImageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content qr-modal-content">
                <div class="modal-header border-0 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="modal-title fw-bold" id="qrImageModalLabel">Bill hoàn tiền</h5>
                    <button type="button" class="btn-close btn-close-lg" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div id="qrImageLoading" style="display:none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    @if (!empty($refund->images))
                        <img id="qrBillImage" src="{{ asset($refund->images) }}" alt="QR Code"
                            class="img-fluid qr-bill-img shadow"
                            style="max-width: 350px; border-radius: 16px; border: 2px solid #f1f1f1; transition: transform 0.2s; cursor: pointer;"
                            onload="document.getElementById('qrImageLoading').style.display='none';"
                            onerror="document.getElementById('qrImageLoading').style.display='none';" />
                    @else
                        <div class="alert alert-warning mt-3">Chưa có bill hoàn tiền.</div>
                    @endif
                    {{-- @php
                    dd($refund);
                    @endphp --}}
                </div>
                <div class="modal-footer border-0 pt-0 d-flex justify-content-center">
                    <button type="button" class="btn btn-secondary no-hover px-4 py-2 fs-5"
                        data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal hiển thị ảnh shipper -->
    <div class="modal fade" id="shipperImageModal" tabindex="-1" role="dialog"
        aria-labelledby="shipperImageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content shipper-modal-content">
                <div class="modal-header border-0 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="modal-title fw-bold" id="shipperImageModalLabel">
                        <i class="fas fa-truck me-2"></i>Ảnh giao hàng từ shipper
                    </h5>
                    <button type="button" class="btn-close btn-close-lg" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div id="shipperImageLoading" style="display:none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    @if (!empty($order->image_ship))
                        <img id="shipperImage" src="{{ asset($order->image_ship) }}" alt="Shipper Image"
                            class="img-fluid shipper-img shadow"
                            style="max-width: 400px; border-radius: 16px; border: 2px solid #f1f1f1; transition: transform 0.2s; cursor: pointer;"
                            onload="document.getElementById('shipperImageLoading').style.display='none';"
                            onerror="document.getElementById('shipperImageLoading').style.display='none';" />
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Ảnh này được chụp bởi shipper khi giao hàng
                            </small>
                        </div>
                    @else
                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Chưa có ảnh giao hàng từ shipper.
                        </div>
                    @endif
                </div>
                <div class="modal-footer border-0 pt-0 d-flex justify-content-center">
                    <button type="button" class="btn btn-secondary no-hover px-4 py-2 fs-5"
                        data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal hiển thị ảnh xác nhận nhận hàng -->
    <div class="modal fade" id="userConfirmationImageModal" tabindex="-1" role="dialog"
        aria-labelledby="userConfirmationImageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content user-confirmation-modal-content">
                <div class="modal-header border-0 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="modal-title fw-bold" id="userConfirmationImageModalLabel">
                        <i class="fas fa-check-circle me-2"></i>Ảnh xác nhận nhận hàng
                    </h5>
                    <button type="button" class="btn-close btn-close-lg" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div id="userConfirmationImageLoading" style="display:none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <img id="userConfirmationImage" src="" alt="User Confirmation Image"
                        class="img-fluid user-confirmation-img shadow"
                        style="max-width: 400px; border-radius: 16px; border: 2px solid #f1f1f1; transition: transform 0.2s; cursor: pointer;"
                        onload="document.getElementById('userConfirmationImageLoading').style.display='none';"
                        onerror="document.getElementById('userConfirmationImageLoading').style.display='none';" />
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Ảnh này được chụp bởi khách hàng khi xác nhận nhận hàng
                        </small>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 d-flex justify-content-center">
                    <button type="button" class="btn btn-secondary no-hover px-4 py-2 fs-5"
                        data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
@endsection
<style>
    .order-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
    }

    .no-hover:hover,
    .no-hover:focus,
    .no-hover:active {
        background-color: #dc3545 !important;
        color: #fff !important;
        text-decoration: none !important;
        box-shadow: none !important;
        border-color: #dc3545 !important;
        outline: none !important;
    }

    /* Image preview styles */
    #imagePreview {
        transition: all 0.3s ease;
        border: 2px dashed #dee2e6 !important;
    }

    #imagePreview:hover {
        border-color: #007bff !important;
        background-color: #f8f9fa !important;
    }

    #imagePreview img {
        transition: transform 0.2s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    #imagePreview img:hover {
        transform: scale(1.02);
    }

    .image-preview-loading {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .image-preview-loading .spinner-border {
        width: 2rem;
        height: 2rem;
    }

    /* Shipper image modal styles */
    .shipper-modal-content {
        border-radius: 18px;
        box-shadow: 0 8px 32px rgba(60, 60, 60, 0.18);
        background: #fff;
    }

    .shipper-img {
        border-radius: 14px;
        border: 2px solid #f1f1f1;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.10);
        max-width: 90vw;
        max-height: 50vh;
        margin: 0 auto;
        display: block;
        transition: transform 0.2s ease;
    }

    .shipper-img:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.18);
    }

    @media (max-width: 600px) {
        .shipper-img {
            max-width: 98vw;
            max-height: 40vh;
        }

        .modal-content.shipper-modal-content {
            padding: 0 2px;
        }
    }

    /* User confirmation image modal styles */
    .user-confirmation-modal-content {
        border-radius: 18px;
        box-shadow: 0 8px 32px rgba(60, 60, 60, 0.18);
        background: #fff;
    }

    .user-confirmation-img {
        border-radius: 14px;
        border: 2px solid #f1f1f1;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.10);
        max-width: 90vw;
        max-height: 50vh;
        margin: 0 auto;
        display: block;
        transition: transform 0.2s ease;
    }

    .user-confirmation-img:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.18);
    }

    @media (max-width: 600px) {
        .user-confirmation-img {
            max-width: 98vw;
            max-height: 40vh;
        }

        .modal-content.user-confirmation-modal-content {
            padding: 0 2px;
        }
    }
</style>

<script>
    // Function xử lý thay đổi ảnh - đặt ở global scope ngay từ đầu
    function handleImageChange(e) {
        var file = e.target.files[0];
        var imagePreview = document.getElementById('imagePreview');

        if (file && imagePreview) {
            // Kiểm tra loại file
            if (!file.type.startsWith('image/')) {
                alert('Vui lòng chọn file ảnh!');
                return;
            }

            // Kiểm tra kích thước file (giới hạn 5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('File ảnh quá lớn! Vui lòng chọn file nhỏ hơn 5MB.');
                return;
            }

            // Hiển thị loading
            imagePreview.innerHTML = `
                <div class="image-preview-loading">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-muted mt-2 mb-0">Đang tải ảnh...</p>
                </div>
            `;

            var reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.innerHTML = `
                    <img src="${e.target.result}" alt="Preview" class="img-fluid" style="max-height: 250px; max-width: 100%; border-radius: 8px; object-fit: contain;">
                `;
            };
            reader.onerror = function() {
                imagePreview.innerHTML = `
                    <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem; margin-bottom: 0.5rem;"></i>
                    <p class="text-warning mb-0">Lỗi đọc file</p>
                    <small class="text-muted">Vui lòng thử lại</small>
                `;
            };
            reader.readAsDataURL(file);
        } else if (imagePreview) {
            imagePreview.innerHTML = `
                <i class="fas fa-image text-muted" style="font-size: 3rem; margin-bottom: 0.5rem;"></i>
                <p class="text-muted mb-0">Chưa có ảnh</p>
                <small class="text-muted">Chọn file ảnh để xem trước</small>
            `;
        }
    }

    // Function test để debug
    function testImagePreview() {
        console.log('Test function called');
        var userImageInput = document.getElementById('user_image');
        var imagePreview = document.getElementById('imagePreview');

        console.log('userImageInput:', userImageInput);
        console.log('imagePreview:', imagePreview);

        if (userImageInput && imagePreview) {
            console.log('Elements found, testing preview...');
            imagePreview.innerHTML = `
                <i class="fas fa-check-circle text-success" style="font-size: 3rem; margin-bottom: 0.5rem;"></i>
                <p class="text-success mb-0">Test thành công!</p>
                <small class="text-muted">Preview đang hoạt động</small>
            `;
        } else {
            console.log('Elements not found');
            alert('Không tìm thấy elements!');
        }
    }

    // Function để mở modal xem ảnh
    function openImageModal(imageSrc, title) {
        var modal = document.getElementById('userConfirmationImageModal');
        var image = document.getElementById('userConfirmationImage');
        var modalTitle = document.getElementById('userConfirmationImageModalLabel');

        if (modal && image && modalTitle) {
            image.src = imageSrc;
            modalTitle.innerHTML = '<i class="fas fa-check-circle me-2"></i>' + title;

            // Hiển thị loading
            document.getElementById('userConfirmationImageLoading').style.display = 'block';

            // Mở modal
            var bootstrapModal = new bootstrap.Modal(modal);
            bootstrapModal.show();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Call API lấy danh sách ngân hàng khi mở modal
        var bankSelect = document.getElementById('bankSelect');
        if (bankSelect) {
            fetch('https://api.vietqr.io/v2/banks')
                .then(res => res.json())
                .then(data => {
                    if (data && data.data) {
                        data.data.forEach(function(bank) {
                            var option = document.createElement('option');
                            option.value = bank.code;
                            option.text = bank.shortName + ' - ' + bank.name;
                            bankSelect.appendChild(option);
                        });
                    }
                });
        }
        // Sửa số tiền ở tab STK
        document.getElementById('editAmountStk').addEventListener('click', function() {
            var input = document.getElementById('refundAmountStk');
            input.readOnly = !input.readOnly;
            if (!input.readOnly) input.focus();
        });
        // Sửa số tiền ở tab QR
        document.getElementById('editAmountQr').addEventListener('click', function() {
            var input = document.getElementById('refundAmountQr');
            input.readOnly = !input.readOnly;
            if (!input.readOnly) input.focus();
        });

        // Preview ảnh QR khi upload
        var qrImageInput = document.getElementById('qrImageInput');
        var qrImagePreview = document.getElementById('qrImagePreview');
        var previewImage = document.getElementById('previewImage');

        if (qrImageInput) {
            qrImageInput.addEventListener('change', function(e) {
                var file = e.target.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        qrImagePreview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    qrImagePreview.style.display = 'none';
                }
            });
        }

        // Preview ảnh xác nhận nhận hàng - đơn giản hóa
        function initUserImagePreview() {
            // Không cần thêm event listener vì đã có inline handler
        }

        // Khởi tạo preview khi modal được mở
        $('#userConfirmationModal').on('shown.bs.modal', function() {
            // Reset preview khi mở modal
            var imagePreview = document.getElementById('imagePreview');
            if (imagePreview) {
                imagePreview.innerHTML = `
                    <i class="fas fa-image text-muted" style="font-size: 3rem; margin-bottom: 0.5rem;"></i>
                    <p class="text-muted mb-0">Chưa có ảnh</p>
                    <small class="text-muted">Chọn file ảnh để xem trước</small>
                `;
            }

            // Đợi một chút để đảm bảo DOM đã được render
            setTimeout(function() {
                initUserImagePreview();
            }, 100);
        });

        // Thêm event listener cho modal khi mở bằng vanilla JS
        document.addEventListener('DOMContentLoaded', function() {
            var modal = document.getElementById('userConfirmationModal');
            if (modal) {
                modal.addEventListener('shown.bs.modal', function() {
                    setTimeout(function() {
                        initUserImagePreview();
                    }, 100);
                });
            }
        });

        // Khởi tạo preview ngay khi trang load
        setTimeout(function() {
            initUserImagePreview();
        }, 500);
    });

    function showEditForm() {
        document.getElementById('addressForm').style.display = 'block';
    }

    function hideEditForm() {
        document.getElementById('addressForm').style.display = 'none';
    }
    // Lấy phường theo tỉnh
    document.getElementById('province_code').addEventListener('change', function() {
        const provinceCode = this.value;
        const wardSelect = document.getElementById('ward_code');

        if (provinceCode) {
            fetch(`/addresses/wards?province_code=${provinceCode}`)
                .then(response => response.json())
                .then(data => {
                    wardSelect.innerHTML = '<option value="">Chọn xã/phường</option>';
                    data.forEach(ward => {
                        wardSelect.innerHTML +=
                            `<option value="${ward.ward_code}">${ward.name}</option>`;
                    });
                    wardSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error:', error);
                    wardSelect.innerHTML = '<option value="">Có lỗi xảy ra</option>';
                });
        } else {
            wardSelect.innerHTML = '<option value="">Chọn xã/phường</option>';
            wardSelect.disabled = false;
        }
    });

    function editAddress(id, name, phone, provinceCode, wardCode, address) {
        document.getElementById('formTitle').textContent = 'Sửa địa chỉ';
        document.getElementById('addressId').value = id;
        document.getElementById('name').value = name;
        document.getElementById('phone').value = phone;
        document.getElementById('address').value = address;
        document.getElementById('isEdit').value = 'PUT';
        document.getElementById('addressForm').action = `/addresses/${id}`;
        document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save me-1"></i>Cập nhật';
        document.getElementById('cancelBtn').style.display = 'inline-block';

        // Set province
        const provinceSelect = document.getElementById('province_code');
        provinceSelect.value = provinceCode;

        const wardSelect = document.getElementById('ward_code');
        wardSelect.innerHTML = '<option value="">Chọn xã/phường</option>';
        wardSelect.disabled = true;

        if (provinceCode) {
            fetch(`/addresses/wards?province_code=${provinceCode}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(ward => {
                        const selected = ward.ward_code === wardCode ? 'selected' : '';
                        wardSelect.innerHTML +=
                            `<option value="${ward.ward_code}" ${selected}>${ward.name}</option>`;
                    });
                    wardSelect.disabled = false;
                });
        }

        // // Luôn luôn hiển thị modal
        // var modal = new bootstrap.Modal(document.getElementById('addressModal'));
        // modal.show();
    }
</script>
