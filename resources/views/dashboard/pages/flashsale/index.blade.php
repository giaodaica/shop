@extends('dashboard.layouts.layout')

@section('css-content')
    <!-- Thêm CSS nếu cần -->
    <style>
        .flash-sale-table th, .flash-sale-table td {
            text-align: center;
            vertical-align: middle;
        }
    </style>
@endsection

@section('main-content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Flash Sale</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Hệ Thống</a></li>
                                <li class="breadcrumb-item active">Flash Sale</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <!-- Flash Sale Table -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Danh Mục Flash Sale</h5>
                            <a href="{{ route('flash-sales.create') }}" class="btn btn-primary btn-sm">
                                <i class="ri-add-line"></i> Thêm Flash Sale
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered flash-sale-table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Khung Giờ</th>
                                            <th>Giá Trị Giảm (%)</th>
                                            <th>Trạng Thái</th>
                                            <th>Ngày Bắt Đầu</th>
                                            <th>Ngày Kết Thúc</th>
                                            <th>Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($flashSales as $key => $flashSale)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ date('H:i', strtotime($flashSale->start_date)) }} - {{ date('H:i', strtotime($flashSale->end_date)) }}</td>
                                                <td>{{ $flashSale->discount }}%</td>
                                                <td>
                                                    @if($flashSale->status == 'active')
                                                        <span class="badge bg-success">Đang diễn ra</span>
                                                    @elseif($flashSale->status == 'upcoming')
                                                        <span class="badge bg-warning">Sắp diễn ra</span>
                                                    @else
                                                        <span class="badge bg-secondary">Kết thúc</span>
                                                    @endif
                                                </td>
                                                <td>{{ date('d/m/Y H:i', strtotime($flashSale->start_date)) }}</td>
                                                <td>{{ date('d/m/Y H:i', strtotime($flashSale->end_date)) }}</td>
                                                <td>
                                                    <a href="{{ route('flash-sales.edit', $flashSale->id) }}" class="btn btn-sm btn-warning"><i class="ri-edit-2-fill"></i></a>
                                                    <a href="{{ route('flash-sales.show', $flashSale->id) }}" class="btn btn-sm btn-primary"><i class="ri-eye-line"></i></a>
                                                    <form action="{{ route('flash-sales.destroy', $flashSale->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa?')"><i class="ri-delete-bin-6-fill"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7">Không có Flash Sale nào.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end row-->

        </div>
    </div>
@endsection

@section('js-content')
    <!-- Thêm JS nếu cần -->
@endsection
