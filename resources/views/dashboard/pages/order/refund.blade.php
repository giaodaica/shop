@extends('dashboard.layouts.layout')
@section('css-content')
@endsection
@section('main-content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Hoàn tiền</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Hệ Thống</a></li>
                                <li class="breadcrumb-item active">Hoàn tiền</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-12">


                </div>
                <!--end col-->
            </div>
            <!--end row-->

            {{-- Hiển thị danh sách khách hàng cần hoàn tiền --}}
            <div class="row">
                <div class="col-12">
                    <div class="card border-warning">
                        <div class="card-header bg-warning text-dark fw-bold">
                            Danh sách khách hàng cần hoàn tiền
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-bordered table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tên khách hàng</th>
                                        <th>Số điện thoại</th>
                                        <th>Mã đơn hàng</th>
                                        <th>Số tiền hoàn</th>
                                        <th>Lý do hoàn tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data_refund as $i => $refund)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $refund->customer_name }}</td>
                                            <td>{{ $refund->customer_phone }}</td>
                                            <td><a target="_blank" rel="noopener noreferrer" href="{{url('dashboard/order/'.$refund->order_id)}}">{{ $refund->order_code }}</a></td>
                                            <td>{{ number_format($refund->amount) }} đ</td>
                                            <td>{{ $refund->reason }}</td>
                                            <td>
                                                @if($refund->status == 'pending')
                                                    <span class="badge bg-warning">Đang chờ</span>
                                                @elseif($refund->status == 'approved')
                                                    <span class="badge bg-success">Đã hoàn tiền</span>
                                                @elseif($refund->status == 'rejected')
                                                    <span class="badge bg-danger">Từ chối</span>
                                                @endif
                                            </td>
                                            <td>
                                               <a href="{{url('dashboard/refund/'.$refund->id)}}" class="btn btn-primary btn-sm">Xem</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">Không có khách hàng cần hoàn tiền</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
@endsection
