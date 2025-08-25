
<div class="order-history-responsive">
    <!-- Desktop Table (hiển thị trên màn hình lớn) -->
    <div class="table-responsive d-none d-md-block">
        <table class="table table-centered align-middle mb-0">
            <thead>
                <tr>
                    <th scope="col">Sản Phẩm</th>
                    <th scope="col">Tổng Tiền</th>
                    <th scope="col">Trạng Thái</th>
                    <th scope="col">Chức Năng</th>
                </tr>
            </thead>
            <tbody>

                @forelse($orders as $order)
                <tr>
                    <td class="d-flex align-items-center gap-2">
                        @php
                            $firstItem = $order->orderItems->first();
                        @endphp
                        @if($firstItem)
                            <img src="{{ asset($firstItem->product_image_url) }}"
                                 alt="{{ $firstItem->product_name }}" class="order-img " />
                            <div style="line-height:1.3;">
                                <div class="product-name-truncate" style="font-weight: bold;">
                                    {{ \Illuminate\Support\Str::limit($firstItem->product_name, 25) }}

                                </div>
                            </div>
                        @endif
                    </td>
                    <td>{{ number_format($order->final_amount, 0, ',', '.') }}₫</td>
                    <td>
                        @php
                            $statusMap = [
                                'pending' => ['color' => 'warning', 'label' => 'Chờ xác nhận'],
                                'confirmed' => ['color' => 'info', 'label' => 'Đã xác nhận'],
                                'shipping' => ['color' => 'primary', 'label' => 'Đang giao'],
                                'success' => ['color' => 'success', 'label' => 'Đã giao hàng'],
                                'failed' => ['color' => 'danger', 'label' => 'Giao hàng thất bại'],
                                'cancelled' => ['color' => 'secondary', 'label' => 'Đã hủy'],
                                'delivered' => ['color' => 'success', 'label' => 'Giao hàng thành công'],
                            ];
                            $status = $order->status;
                            $statusColor = $statusMap[$status]['color'] ?? 'secondary';
                            $statusLabel = $statusMap[$status]['label'] ?? ucfirst($status);
                        @endphp
                        <span class="badge bg-{{ $statusColor }}-subtle text-{{ $statusColor }}">
                            {{ $statusLabel }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('home.orderDetail',$order->id) }}"
                           class="btn btn-sm btn-soft-primary">
                            Xem chi tiết
                        </a>
                    </td>
                </tr>
                @empty
                   <tr>
                    <td colspan="5" class="text-center py-4">
                        <div class="text-muted">
                            <i class="bi bi-bag-x fs-1 text-muted mb-3"></i><br>

                            @if (!isset($emptyMessage))
                                <p class="text-muted">Bạn chưa có đơn hàng nào. Hãy mua sắm ngay!</p>
                                <a href="{{ route('home.shop') }}"  class="btn btn-sm btn-soft-primary">Mua sắm ngay</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards (hiển thị trên điện thoại) -->
    <div class="d-block d-md-none">
        @forelse($orders as $order)
            @php
                $firstItem = $order->orderItems->first();
            @endphp
            <div class="card order-card mb-3">
                <div class="card-body d-flex gap-2 align-items-start">
                    @if($firstItem)
                        <img src="{{ asset($firstItem->product_image_url) }}"
                             alt="{{ $firstItem->product_name }}" class="order-img me-2" />
                        <div style="flex:1;line-height:1.3;">
                            <div class="product-name-truncate" style="font-weight: bold;">
                                {{ \Illuminate\Support\Str::limit($firstItem->product_name, 30) }}
                                @if($order->orderItems->count() > 1)
                                    và {{ $order->orderItems->count() - 1 }} sản phẩm khác
                                @endif
                            </div>
                        </div>
                    @endif
                    <div style="margin-top: 6px; font-weight: bold; color: #0d6efd;">
                        {{ number_format($order->total_price, 0, ',', '.') }}₫
                    </div>
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
                    <span class="badge bg-{{ $statusColor }}-subtle text-{{ $statusColor }} mt-2">
                        {{ $statusLabel }}
                    </span>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0 pb-2 px-3">
                    <a href="{{ route('home.orderDetail',$order->id) }}" class="btn btn-sm btn-soft-primary w-100">Xem chi tiết</a>
                </div>
            </div>
        @empty
            <div class="text-center py-4">
                <div class="text-muted">
                    <i class="bi bi-bag-x fs-1 text-muted mb-3"></i><br>
                    @if (!isset($emptyMessage))
                        <p class="text-muted">Bạn chưa có đơn hàng nào. Hãy mua sắm ngay!</p>
                        <a href="{{ route('home.shop') }}" class="btn btn-sm btn-soft-primary">Mua sắm ngay</a>
                    @endif
                </div>
            </div>
        @endforelse
    </div>
</div>

<style>
    /* Thêm một số style để làm đẹp giao diện mobile */
    @media (max-width: 767px) {
        .order-card {
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .order-card .card-body {
            padding: 12px 15px;
        }
    }

    .order-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
    }

    .btn-soft-primary {
        background-color: #e7f1ff;
        color: #0d6efd;
        border: 1px solid #bccfea;
        transition: background 0.2s, color 0.2s, border 0.2s;
    }
    .btn-soft-primary:hover, .btn-soft-primary:focus {
        background-color: #0d6efd;
        color: #fff;
        border: 1px solid #0d6efd;
    }
</style>
