@extends('dashboard.layouts.layout')

@section('main-content')
    <div class="page-content">
        <div class="container-fluid">

            {{-- Tiêu đề và breadcrumb --}}
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Lịch sử khóa tài khoản</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Bảng điều khiển</a></li>
                                <li class="breadcrumb-item active">Lịch sử khóa</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Thông báo --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                </div>
            @endif

            {{-- Bảng lịch sử --}}
            <div class="row">
                <div class="col-lg-12">
                    <div class="card" id="lockHistoryList">
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Danh sách lịch sử khóa</h5>
                                <form class="d-flex" method="GET">
                                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Tìm theo tên, email..." />
                                    <button class="btn btn-primary ms-2" type="submit">Tìm</button>
                                </form>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive table-card">
                                <table class="table align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Người bị khóa</th>
                                            <th>Email</th>
                                            <th>Lý do</th>
                                            <th>Ghi chú</th>
                                            <th>Khóa bởi</th>
                                            <th>Thời gian</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($locks as $lock)
                                            <tr>
                                                <td>{{ $lock->user->name ?? 'N/A' }}</td>
                                                <td>{{ $lock->user->email ?? '-' }}</td>
                                                <td>
                                                    <span class="badge bg-warning-subtle text-warning">{{ $lock->reason->name ?? 'Không rõ' }}</span>
                                                </td>
                                                <td>{{ $lock->note ?? '-' }}</td>
                                                <td>{{ $lock->lockedByUser->name ?? 'Hệ thống' }}</td>
                                                <td>{{ $lock->created_at->format('d/m/Y H:i') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Không có lịch sử khóa nào.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Phân trang --}}
                            <div class="mt-3">
                                {{ $locks->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
@endsection
