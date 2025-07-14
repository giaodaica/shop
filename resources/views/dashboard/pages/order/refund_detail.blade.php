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
                        <h4 class="mb-sm-0">Chi tiết hoàn tiền</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Hệ Thống</a></li>
                                <li class="breadcrumb-item active">Chi tiết hoàn tiền</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Thông tin hoàn tiền</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered mb-0">
                                <tr>
                                    <th>Khách hàng</th>
                                    <td>{{ $refund->user->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Đơn hàng liên quan</th>
                                    <td>{{ $refund->order->code_order ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Số tiền hoàn</th>
                                    <td>{{ number_format($refund->amount ?? 0, 0, ',', '.') }} đ</td>
                                </tr>
                                <tr>
                                    <th>Tên ngân hàng</th>
                                    <td>{{ $refund->bank ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Tên chủ tài khoản</th>
                                    <td>{{ $refund->bank_account_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Số tài khoản</th>
                                    <td>{{ $refund->stk ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Lý do hoàn tiền</th>
                                    <td>{{ $refund->reason ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Trạng thái</th>
                                    <td>
                                        @if (isset($refund->status))
                                            @if ($refund->status == 'pending')
                                                <span class="badge bg-warning">Chờ xử lý</span>
                                            @elseif($refund->status == 'approved')
                                                <span class="badge bg-success">Đã duyệt</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $refund->status }}</span>
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Ngày yêu cầu</th>
                                    <td>{{ $refund->created_at ? $refund->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                </tr>
                            </table>
                            <div class="mt-3 text-end">
                                <a class="btn btn-primary" href="{{ route('dashboard.order.refund') }}">Quay lại</a>
                                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#qrModal"
                                    {{ empty($refund->qr_image) ? 'disabled' : '' }}>
                                    Xem ảnh QR
                                </button>
                                <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                    data-bs-target="#billModal" {{ empty($refund->images) ? 'disabled' : '' }}>
                                    Xem ảnh bill
                                </button>
                                <button type="button" class="btn btn-success"
                                    {{ $refund->status != 'pending' ? 'disabled' : '' }} data-bs-toggle="modal"
                                    data-bs-target="#adminQrModal">
                                    Thành công
                                </button>
                            </div>

                            <!-- Modal hiển thị ảnh QR khách gửi -->
                            <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="qrModalLabel">Ảnh QR khách gửi</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Đóng"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            @if (!empty($refund->QR_images))
                                                <img src="{{ asset('storage/' . $refund->qr_image) }}" alt="QR Image"
                                                    class="img-fluid" style="max-height:90vh;">
                                            @else
                                                <p>Không có ảnh QR.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal hiển thị ảnh bill admin upload -->
                            <div class="modal fade" id="billModal" tabindex="-1" aria-labelledby="billModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="billModalLabel">Ảnh bill xác nhận hoàn tiền</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Đóng"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            @if (!empty($refund->images))
                                                <img src="{{ asset($refund->images) }}" alt="Bill Image" class="img-fluid"
                                                    style="width:1100px; height:600px;">
                                            @else
                                                <p>Chưa có ảnh bill.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal upload QR và thay đổi trạng thái -->
                            <div class="modal fade" id="adminQrModal" tabindex="-1" aria-labelledby="adminQrModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="adminQrModalLabel">Bill</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Đóng"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ url('dashboard/change/refund/' . $refund->id) }}"
                                                method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('POST')
                                                <div class="mb-3">
                                                    <label for="images" class="form-label">Ảnh bill</label>
                                                    <input class="form-control" type="file" id="images"
                                                        name="images" accept="image/*" required>
                                                    <input type="hidden" name="status_old"
                                                        value="{{ $refund->status }}">
                                                    <input type="hidden" name="status_new" value="approved">
                                                </div>
                                                <div class="text-end">
                                                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->

        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
@endsection
@section('js-content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: "{{ session('success') }}",
        });
    @endif

    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            text: "{{ session('error') }}",
        });
    @endif
</script>
@endsection
