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
                                        <strong>{{ $order->created_at->format('d/m/Y') }}</strong>
                                        <small class="text-muted"
                                            id="invoice-time">{{ $order->created_at->format('H:i:s') }}</small>
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
                        @if (isset($shippingAddress))
                            <div>
                                <strong>{{ $shippingAddress->is_default ? 'Địa chỉ mặc định' : 'Địa chỉ giao hàng' }}</strong><br>
                                {{ $shippingAddress->name }}<br>
                                {{ $shippingAddress->address }}<br>
                                Điện thoại: {{ $shippingAddress->phone }}
                            </div>
                        @endif
                    </div>
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
                                            <img src="{{ asset($item->productVariant->product->image_url) }}"
                                                alt="{{ $item->product_name }}" class="order-img"
                                                style="width: 60px; height: 60px;" />
                                            <div style="line-height:1.3;">
                                                <div class="product-name-truncate fw-bold">
                                                    {{ $item->product_name }}
                                                </div>
                                                <div style="font-size: 13px; color: #555;">
                                                    {{ $item->productVariant->color->color_name ?? '-' }},
                                                    {{ $item->productVariant->size->size_name ?? '-' }}
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
                                    <img src="{{ asset($item->productVariant->product->image_url) }}"
                                        alt="{{ $item->product_name }}" style="width: 60px; height: 60px;" />
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
                    <div class="d-flex justify-content-end gap-2">
                        @if ($order->status == 'pending')
                            <a href="javascript:void(0)" class="btn btn-danger no-hover" data-bs-toggle="modal"
                                data-bs-target="#cancelOrderModal">Hủy đơn hàng</a>
                        @endif
                        <a href="{{ route('home.info') }}" class="btn btn-warning no-hover">Quay lại</a>
                        @if ($refund)
                            @if ($refund->status == 'approved')
                                <span class="btn btn-success no-hover">Đã hoàn tiền</span>
                                @if (!empty($refund->images))
                                    <button type="button" class="btn btn-info no-hover" data-bs-toggle="modal" data-bs-target="#qrImageModal">Xem bill</button>
                                @endif
                            @elseif ($refund->status == 'pending')
                                <span class="btn btn-secondary no-hover">Đang chờ xử lý</span>
                                <button type="button" class="btn btn-info no-hover" data-bs-toggle="modal" data-bs-target="#refundDetailModal">
                                    Xem chi tiết
                                </button>
                            @else
                                <a href="{{ route('order.refund.request', $order->id) }}" class="btn btn-primary no-hover">Yêu cầu hoàn tiền</a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
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

    <!-- Modal Hoàn tiền -->
    <!-- Đã chuyển sang view riêng, xóa modal này -->

    @if ($refund && $refund->status == 'pending')
        <!-- Modal Chi tiết hoàn tiền -->
        <div class="modal fade" id="refundDetailModal" tabindex="-1" role="dialog" aria-labelledby="refundDetailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content refund-detail-modal-content">
                    <div class="modal-header border-0 pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="modal-title fw-bold" id="refundDetailModalLabel">Chi tiết yêu cầu hoàn tiền</h5>
                        <button type="button" class="btn-close btn-close-lg" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-4">
                        <div class="row g-3">
                            @php
                                $hasBank = !empty($refund->bank) || !empty($refund->stk) || !empty($refund->bank_account_name) || !empty($refund->reason);
                                $hasQR = !empty($refund->QR_images);
                            @endphp
                            @if ($hasBank)
                                <div class="col-12 col-md-6">
                                    @if (!empty($refund->bank))
                                        <div class="mb-2"><span class="fw-semibold text-muted">Ngân hàng:</span> <span class="fw-bold">{{ $refund->bank }}</span></div>
                                    @endif
                                    @if (!empty($refund->stk))
                                        <div class="mb-2"><span class="fw-semibold text-muted">Số tài khoản:</span> <span class="fw-bold">{{ $refund->stk }}</span></div>
                                    @endif
                                    @if (!empty($refund->bank_account_name))
                                        <div class="mb-2"><span class="fw-semibold text-muted">Tên chủ thẻ:</span> <span class="fw-bold">{{ $refund->bank_account_name }}</span></div>
                                    @endif
                                    <div class="mb-2"><span class="fw-semibold text-muted">Số tiền hoàn:</span> <span class="fw-bold text-danger">{{ number_format($refund->amount) }}đ</span></div>
                                    @if (!empty($refund->reason))
                                        <div class="mb-2"><span class="fw-semibold text-muted">Lý do:</span> <span class="fw-bold">{{ $refund->reason }}</span></div>
                                    @endif
                                    <div class="mb-2 mt-3"><span class="fw-semibold text-muted">Trạng thái:</span> <span class="badge bg-secondary-subtle text-secondary">Đang chờ xử lý</span></div>
                                </div>
                            @endif
                            @if ($hasQR)
                                <div class="col-12 col-md-{{ $hasBank ? '6' : '12' }} d-flex flex-column align-items-center justify-content-center">
                                    <div class="mb-2"><span class="fw-semibold text-muted">Mã QR đã upload:</span></div>
                                    <img src="{{ asset($refund->QR_images) }}" alt="QR Code" class="img-fluid refund-detail-qr-img shadow" style="max-width: 220px; border-radius: 14px; border: 2px solid #f1f1f1; transition: transform 0.2s; cursor: pointer;" />
                                    <div class="mt-3"><span class="fw-semibold text-muted">Số tiền hoàn:</span> <span class="fw-bold text-danger">{{ number_format($refund->amount) }}đ</span></div>
                                    <div class="mb-2 mt-3"><span class="fw-semibold text-muted">Trạng thái:</span> <span class="badge bg-secondary-subtle text-secondary">Đang chờ xử lý</span></div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 d-flex justify-content-center">
                        <button type="button" class="btn btn-secondary no-hover px-4 py-2 fs-5" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .refund-detail-modal-content {
                border-radius: 18px;
                box-shadow: 0 8px 32px rgba(60,60,60,0.18);
                background: #fff;
            }
            .refund-detail-qr-img {
                border-radius: 14px;
                border: 2px solid #f1f1f1;
                box-shadow: 0 4px 16px rgba(0,0,0,0.10);
                max-width: 90vw;
                max-height: 40vh;
                margin: 0 auto;
                display: block;
            }
            .refund-detail-qr-img:hover {
                transform: scale(1.05);
                box-shadow: 0 8px 32px rgba(0,0,0,0.18);
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
    <div class="modal fade" id="qrImageModal" tabindex="-1" role="dialog" aria-labelledby="qrImageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content qr-modal-content">
                <div class="modal-header border-0 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="modal-title fw-bold" id="qrImageModalLabel">Bill hoàn tiền</h5>
                    <button type="button" class="btn-close btn-close-lg" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div id="qrImageLoading" style="display:none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    @if (!empty($refund->images))
                        <img id="qrBillImage" src="{{ asset($refund->images) }}" alt="QR Code" class="img-fluid qr-bill-img shadow" style="max-width: 350px; border-radius: 16px; border: 2px solid #f1f1f1; transition: transform 0.2s; cursor: pointer;" onload="document.getElementById('qrImageLoading').style.display='none';" onerror="document.getElementById('qrImageLoading').style.display='none';" />
                    @else
                        <div class="alert alert-warning mt-3">Chưa có bill hoàn tiền.</div>
                    @endif
                    {{-- @php
                    dd($refund);
                    @endphp --}}
                </div>
                <div class="modal-footer border-0 pt-0 d-flex justify-content-center">
                    <button type="button" class="btn btn-secondary no-hover px-4 py-2 fs-5" data-bs-dismiss="modal">Đóng</button>
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
</style>

<script>
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
    });
</script>
