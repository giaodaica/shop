@extends('dashboard.layouts.layout')

@section('css-content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('main-content')
<div class="page-content">
    <div class="container-fluid">

        {{-- Tiêu đề --}}
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">
                        @if ($start == $end)
                            Thống kê trong ngày
                        @else
                            Kết quả thống kê {{ 'Từ ' . formatDate($start ?? '01-01-2025') . ' đến ' . formatDate($end ?? now()) }}
                        @endif
                    </h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Hệ Thống</a></li>
                            <li class="breadcrumb-item active">Thống kê doanh thu</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form lọc --}}
        <div class="row mb-4">
            <div class="col-12">
                <form method="get" action="{{ route('dashboard.revenue') }}"
                    class="row g-3 align-items-end p-3 bg-light rounded shadow-sm" id="filter-form">
                    @csrf
                    <div class="col-md-3">
                        <label for="type" class="form-label fw-bold">Kiểu thống kê</label>
                        <select class="form-select" id="type" name="type">
                            <option value="day" {{ request('type') == 'day' ? 'selected' : '' }}>Theo ngày</option>
                            <option value="month" {{ request('type') == 'month' ? 'selected' : '' }}>Theo tháng</option>
                            <option value="year" {{ request('type') == 'year' ? 'selected' : '' }}>Theo năm</option>
                            <option value="hour" {{ request('type') == 'hour' ? 'selected' : '' }}>Theo giờ</option>
                        </select>
                    </div>

                    {{-- Bộ lọc ngày --}}
                    <div class="col-md-4" id="filter-date">
                        <label class="form-label fw-bold">Khoảng ngày</label>
                        <div class="input-group">
                            <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                            <span class="input-group-text">-</span>
                            <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                        </div>
                    </div>

                    {{-- Bộ lọc tháng --}}
                    <div class="col-md-4 d-none" id="filter-month">
                        <label class="form-label fw-bold">Khoảng tháng</label>
                        <div class="input-group">
                            <input type="month" class="form-control" id="month_from" name="month_from" value="{{ request('month_from') }}">
                            <span class="input-group-text">-</span>
                            <input type="month" class="form-control" id="month_to" name="month_to" value="{{ request('month_to') }}">
                        </div>
                    </div>

                    {{-- Bộ lọc năm --}}
                    <div class="col-md-4 d-none" id="filter-year">
                        <label class="form-label fw-bold">Khoảng năm</label>
                        <div class="input-group">
                            <input type="number" min="2000" max="2100" class="form-control" id="year_from" name="year_from" value="{{ request('year_from', date('Y')) }}">
                            <span class="input-group-text">-</span>
                            <input type="number" min="2000" max="2100" class="form-control" id="year_to" name="year_to" value="{{ request('year_to', date('Y')) }}">
                        </div>
                    </div>

                    {{-- Bộ lọc giờ --}}
                    <div class="col-md-4 d-none" id="filter-hour">
                        <label class="form-label fw-bold">Khoảng ngày/giờ</label>
                        <div class="input-group">
                            <input type="datetime-local" class="form-control" id="datetime_from" name="datetime_from" value="{{ request('datetime_from') }}">
                            <span class="input-group-text">-</span>
                            <input type="datetime-local" class="form-control" id="datetime_to" name="datetime_to" value="{{ request('datetime_to') }}">
                        </div>
                    </div>

                    <div class="col-md-1 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Lọc</button>
                        <a href="{{ route('dashboard.revenue') }}" class="btn btn-primary">Xóa</a>
                    </div>

                    @if ($errors->any())
                        <div class="text-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </div>
                    @endif
                </form>
            </div>
        </div>

        {{-- Thống kê nhanh --}}
       <div class="row mb-3 align-items-stretch">
    <div class="col-md-2">
        <div class="card text-center border-primary h-100 d-flex flex-column">
            <div class="card-body flex-grow-1">
                <div class="fw-bold text-primary" style="font-size: 1.5rem;">
                    {{ $data_order->sodonhang ?? 0 }}
                </div>
                <div class="text-muted">Tổng đơn hàng</div>
            </div>
            <div class="card-footer bg-transparent border-top-0">
                <a target="_blank" rel="noopener noreferrer" href="{{ route('dashboard.order') }}" class="btn btn-sm btn-outline-primary w-100">
                    Xem chi tiết
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center border-warning h-100 d-flex flex-column">
            <div class="card-body flex-grow-1">
                <div class="fw-bold text-warning" style="font-size: 1.5rem;">
                    {{ $data_order->donhang_dangcho ?? 0 }}
                </div>
                <div class="text-muted">Đơn chưa xử lý</div>
            </div>
            <div class="card-footer bg-transparent border-top-0">
                <a target="_blank" rel="noopener noreferrer" href="{{ route('dashboard.order', ['type' => 'pending']) }}" class="btn btn-sm btn-outline-warning w-100">
                    Xem chi tiết
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center border-info h-100 d-flex flex-column">
            <div class="card-body flex-grow-1">
                <div class="fw-bold text-info" style="font-size: 1.5rem;">
                    {{ $data_order->donhang_dangvanchuyen ?? 0 }}
                </div>
                <div class="text-muted">Đang giao hàng</div>
            </div>
            <div class="card-footer bg-transparent border-top-0">
                <a target="_blank" rel="noopener noreferrer" href="{{ route('dashboard.order', ['type' => 'shipping']) }}" class="btn btn-sm btn-outline-info w-100">
                    Xem chi tiết
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center border-success h-100 d-flex flex-column">
            <div class="card-body flex-grow-1">
                <div class="fw-bold text-success" style="font-size: 1.5rem;">
                    {{ $data_order->donhang_thanhcong ?? 0 }}
                </div>
                <div class="text-muted">Đơn đã hoàn thành</div>
            </div>
            <div class="card-footer bg-transparent border-top-0">
                <a target="_blank" rel="noopener noreferrer" href="{{ route('dashboard.order', ['type' => 'success']) }}" class="btn btn-sm btn-outline-success w-100">
                    Xem chi tiết
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center border-danger h-100 d-flex flex-column">
            <div class="card-body flex-grow-1">
                <div class="fw-bold text-danger" style="font-size: 1.5rem;">
                    {{ $data_order->donhang_huy ?? 0 }}
                </div>
                <div class="text-muted">Đơn đã hủy</div>
            </div>
            <div class="card-footer bg-transparent border-top-0">
                <a target="_blank" rel="noopener noreferrer" href="{{ route('dashboard.order', ['type' => 'cancelled']) }}" class="btn btn-sm btn-outline-danger w-100">
                    Xem chi tiết
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center border-danger h-100 d-flex flex-column">
            <div class="card-body flex-grow-1">
                <div class="fw-bold text-danger" style="font-size: 1.5rem;">
                    {{ $data_order->donhang_thatbai ?? 0 }}
                </div>
                <div class="text-muted">Giao thất bại</div>
            </div>
            <div class="card-footer bg-transparent border-top-0">
                <a target="_blank" rel="noopener noreferrer" href="{{ route('dashboard.order', ['type' => 'failed']) }}" class="btn btn-sm btn-outline-danger w-100">
                    Xem chi tiết
                </a>
            </div>
        </div>
    </div>
</div>

        {{-- Biểu đồ Doanh thu --}}
        <div class="card mb-4">
            <div class="card-header fw-bold">Thống Kê Doanh Thu</div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>

        {{-- Biểu đồ Top sản phẩm bán chạy --}}
        <div class="card mb-4 border-info">
            <div class="card-header bg-info text-white fw-bold">Top sản phẩm bán chạy</div>
            <div class="card-body">
                <canvas id="topProductsChart" height="120"></canvas>
            </div>
        </div>

        {{-- Biểu đồ Top khách hàng --}}
       <div class="card mb-4 border-success">
    <div class="card-header bg-success text-white fw-bold">Top khách hàng mua nhiều nhất</div>
    <div class="card-body" style="max-width: 600px; margin: auto; padding: 10px;">
        <canvas id="topUsersChart" height="120" style="max-height: 250px; width: 100%;"></canvas>
    </div>
</div>


    </div>
</div>
@endsection

@section('js-content')
<script>
function showFilterInput() {
    let type = document.getElementById('type').value;
    document.getElementById('filter-date').classList.add('d-none');
    document.getElementById('filter-month').classList.add('d-none');
    document.getElementById('filter-year').classList.add('d-none');
    document.getElementById('filter-hour').classList.add('d-none');
    if (type === 'day') document.getElementById('filter-date').classList.remove('d-none');
    if (type === 'month') document.getElementById('filter-month').classList.remove('d-none');
    if (type === 'year') document.getElementById('filter-year').classList.remove('d-none');
    if (type === 'hour') document.getElementById('filter-hour').classList.remove('d-none');
}
document.getElementById('type').addEventListener('change', showFilterInput);
document.addEventListener('DOMContentLoaded', showFilterInput);

// Biểu đồ Doanh thu
new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: ['Doanh thu', 'Số SP bán', 'Doanh thu TB/đơn', 'Tổng giảm giá'],
        datasets: [{
            label: 'Giá trị',
            data: [
                {{ $data_doanhthu->doanhthu }},
                {{ $data_loinhan->tongsanpham }},
                {{ $dtb }},
                {{ $data_doanhthu->tong_giam_gia }},
                {{ $data_loinhan->loinhuan }}
            ],
            backgroundColor: ['#0d6efd','#198754','#ffc107','#fd7e14']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: {
                label: function (context) {
                    let value = context.raw;
                    return value.toLocaleString('vi-VN') + ' đ';
                }
            }}
        },
        scales: { y: { beginAtZero: true } }
    }
});

// Biểu đồ Top sản phẩm bán chạy
new Chart(document.getElementById('topProductsChart'), {
    type: 'bar',
    data: {
        labels: @json($data_top_5->pluck('ten_san_pham')),
        datasets: [{
            label: 'Số lượng bán',
            data: @json($data_top_5->pluck('soluong_ban')),
            backgroundColor: '#0dcaf0'
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    // Format số lượng đơn giản (nếu cần)
                    callback: function(value) {
                        return value;
                    }
                }
            }
        },
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.raw; // hoặc format nếu cần
                    }
                }
            }
        }
    }
});

// Biểu đồ Top khách hàng mua nhiều nhất
new Chart(document.getElementById('topUsersChart'), {
    type: 'bar',
    data: {
        labels: @json($data_top_5_users->pluck('user_name')),
        datasets: [{
            label: 'Tổng tiền mua',
            data: @json($data_top_5_users->pluck('tong_tien_mua')),
            backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#fd7e14', '#dc3545']
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString('vi-VN') + ' đ';
                    }
                }
            }
        },
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.raw.toLocaleString('vi-VN') + ' đ';
                    }
                }
            }
        }
    }
});

</script>
@endsection
