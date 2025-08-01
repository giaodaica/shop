@extends('dashboard.layouts.layout')
@section('css-content')
    <link rel="stylesheet" href="{{ asset('admin/libs/glightbox/css/glightbox.min.css') }}">
@endsection
@section('main-content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Chi tiết đơn hàng</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Hệ thống</a></li>
                                <li class="breadcrumb-item active">Chi tiết</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-xl-9">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h5 class="card-title flex-grow-1 mb-0">Đơn hàng#{{ $data_order->code_order }} @if (empty($data_refund))
                                    @else
                                        <span class="badge bg-danger">
                                            <a href="{{ route('dashboard.order.refund.show', $data_refund->id) }}"
                                                class="text-white text-decoration-none">Đã hoàn tiền (xem)</a>
                                        </span>
                                    @endif
                                </h5>
                                <div class="flex-shrink-0">
                                    {{-- <a href="apps-invoices-details.html" class="btn btn-success btn-sm"><i
                                            class="ri-download-2-fill align-middle me-1"></i> Invoice</a> --}}
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive table-card">
                                <table class="table table-nowrap align-middle table-borderless mb-0">
                                    <thead class="table-light text-muted">
                                        <tr>
                                            <th scope="col">Sản phẩm</th>
                                            <th scope="col">Giá</th>
                                            <th scope="col">Số lượng</th>
                                            <th scope="col" class="text-end">Tổng tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data_order_items as $rende_order_items)
                                            <tr>
                                                <td>
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0 avatar-md bg-light rounded p-1">
                                                            <img src="{{ asset($rende_order_items->product_image_url) }}"
                                                                alt="" class="img-fluid d-block">
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <h5 class="fs-15"><a rel="noopener noreferrer" target="_blank"
                                                                    href="{{ url('dashboard/variants/' . $rende_order_items->product_variant_id) }}"
                                                                    class="link-primary">{{ $rende_order_items->product_name . ' ' . $rende_order_items->color_name }}
                                                                    @if (!empty($rende_order_items->flash_sale_items_id))
                                                                        (*)
                                                                    @else
                                                                    @endif
                                                                </a>
                                                            </h5>
                                                            <p class="text-muted mb-0">Màu: <span
                                                                    class="fw-medium">{{ $rende_order_items->color_name }}</span>
                                                            </p>
                                                            <p class="text-muted mb-0">Size: <span
                                                                    class="fw-medium">{{ $rende_order_items->size_name }}</span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ number_format($rende_order_items->sale_price) }}</td>
                                                <td>{{ $rende_order_items->quantity }}</td>
                                                <td class="fw-medium text-end">
                                                    {{ number_format($rende_order_items->sale_price * $rende_order_items->quantity) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr class="border-top border-top-dashed">
                                            <td colspan="3"></td>
                                            <td colspan="2" class="fw-medium p-0">
                                                <table class="table table-borderless mb-0">
                                                    <tbody>
                                                        <tr>
                                                            <td>Tổng đơn :</td>
                                                            <td class="text-end">
                                                                {{ number_format($data_order->total_amount) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Giảm giá : <br>
                                                                <b><a rel="noopener noreferrer" target="_blank"
                                                                        href="{{ url("dashboard/voucher/s/$data_order->voucher_id") }}">{{ $data_order->code }}</a></b>
                                                            </td>

                                                            <td class="text-end">
                                                                -{{ number_format($data_order->discount_amount) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Phí giao hàng
                                                                <span>{{ $data_order->shipping_method == 'express' ? '(Giao hàng nhanh)' : '(Giao hàng tiêu chuẩn)' }}</span>
                                                                :
                                                            </td>
                                                            <td class="text-end">
                                                                {{ number_format($data_order->shipping_fee) }}</td>
                                                        </tr>
                                                        {{-- <tr>
                                                                    <td>Estimated Tax :</td>
                                                                    <td class="text-end">$44.99</td>
                                                                </tr> --}}
                                                        <tr class="border-top border-top-dashed">
                                                            <th scope="row">Tổng (VND) :</th>
                                                            <th class="text-end">
                                                                {{ number_format($data_order->final_amount) }}</th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @if (!empty($rende_order_items->flash_sale_items_id))
                   <p class="text-info">* sản phẩm này thuộc 1 chương trình flash sale</p>
                    @else
                    @endif
                    <!--end card-->
                    <div class="card">
                        <div class="card-header">
                            <div class="d-sm-flex align-items-center">
                                <h5 class="card-title flex-grow-1 mb-0">Trạng thái đơn hàng</h5>
                                <div class="flex-shrink-0 mt-2 mt-sm-0">
                                    <button type="button" class="btn btn-soft-info btn-sm mt-2 mt-sm-0"
                                        data-bs-toggle="modal" data-id="{{ $data_order->id }}" id="create-btn"
                                        data-bs-target="#showModalchange"><i
                                            class="ri-map-pin-line align-middle me-1"></i>Thay đổi địa chỉ</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">STT</th>
                                            <th scope="col">Người duyệt</th>
                                            <th scope="col">Thời gian duyệt</th>
                                            <th scope="col">Trạng thái thay đổi</th>
                                            <th scope="col">Nội dung</th>
                                            <th scope="col">Ghi chú</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($histoty_order as $key => $history)
                                            <tr>
                                                <td>{{ count($histoty_order) - $key }}</td>
                                                <td><a
                                                        href="{{ route('users.show', $history->user_id) }}">{{ $history->user_name }}</a>
                                                </td>
                                                <td>{{ formatDate($history->created_at) }}</td>
                                                @php
                                                    $statusMap = [
                                                        'pending' => 'Chờ duyệt',
                                                        'confirmed' => 'Đã duyệt',
                                                        'shipping' => 'Đang giao',
                                                        'success' => 'Hoàn thành',
                                                        'failed' => 'Giao thất bại',
                                                        'cancelled' => 'Đã hủy',
                                                        // Thêm các trạng thái khác nếu có
                                                    ];
                                                @endphp
                                                <td>
                                                    {{ $statusMap[$history->from_status] ?? $history->from_status }}
                                                    =>
                                                    {{ $statusMap[$history->to_status] ?? $history->to_status }}
                                                </td>
                                                <td>{{ $history->note }}</td>
                                                <td>{{ $history->content }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">Chưa có lịch sử duyệt đơn
                                                    hàng</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div>
                                    <td class="">
                                        @switch($data_order->status)
                                            @case('pending')
                                                <div class="d-flex justify-content-end gap-2 pt-3 px-3">
                                                    <form action="{{ url("dashboard/order/change/$data_order->id") }}"
                                                        method="post">
                                                        @csrf
                                                        <input type="hidden" name="change" value="pending">
                                                        <button class="btn btn-success">Xác nhận</button>
                                                    </form>
                                                    <button type="button" class="btn btn-danger add-btn" data-bs-toggle="modal"
                                                        data-id="{{ $data_order->id }}" id="create-btn"
                                                        data-bs-target="#showModalcancel">Hủy đơn</button>
                                                </div>
                                            @break

                                            @case('confirmed')
                                                <div class="d-flex justify-content-end gap-2 pt-3 px-3">
                                                    <form action="{{ url("dashboard/order/change/$data_order->id") }}"
                                                        method="post">
                                                        @csrf
                                                        <input type="hidden" name="change" value="confirmed">
                                                        <button class="btn btn-success">Giao Hàng</button>
                                                    </form>
                                                    <button type="button" class="btn btn-danger add-btn" data-bs-toggle="modal"
                                                        data-id="{{ $data_order->id }}" id="create-btn"
                                                        data-bs-target="#showModalcancel">Hủy đơn</button>
                                                </div>
                                            @break

                                            @case('shipping')
                                                <div class="d-flex justify-content-end gap-2 pt-3 px-3">
                                                    <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal"
                                                        data-id="{{ $data_order->id }}" id="create-btn"
                                                        data-bs-target="#showModalsuccess">Đã Giao</button>
                                                    <button type="button" class="btn btn-danger add-btn" data-bs-toggle="modal"
                                                        data-id="{{ $data_order->id }}" id="create-btn"
                                                        data-bs-target="#showModalfailed">Giao Thất Bại</button>
                                                </div>
                                            @break

                                            @case('failed')
                                                <div class="d-flex justify-content-end gap-2 pt-3 px-3">
                                                    <form action="{{ url("dashboard/order/change/$data_order->id") }}"
                                                        method="post">
                                                        @csrf
                                                        <input type="hidden" name="change" value="return">
                                                        <button class="btn btn-success">Giao lại</button>
                                                    </form>
                                                    <button type="button" class="btn btn-danger add-btn" data-bs-toggle="modal"
                                                        data-id="{{ $data_order->id }}" id="create-btn"
                                                        data-bs-target="#showModalcancel">Hủy đơn</button>
                                                </div>
                                            @break

                                            @default
                                        @endswitch

                                        <div class="d-flex justify-content-end gap-2 pt-3 px-3">
                                            <button type="button" class="btn btn-info " data-bs-toggle="modal"
                                                data-bs-target=".bs-example-modal-xl">Phản hồi của khách hàng</button>
                                        </div>
                                    </td>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end card-->
                </div>
                <!--end col-->
                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex">
                                <h5 class="card-title flex-grow-1 mb-0"><i
                                        class="mdi mdi-truck-fast-outline align-middle me-1 text-muted"></i> Vận chuyển
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <lord-icon src="https://cdn.lordicon.com/uetqnvvg.json" trigger="loop"
                                    colors="primary:#405189,secondary:#0ab39c" style="width:80px;height:80px"></lord-icon>
                                <h5 class="fs-16 mt-2">OUTFITLY Logistics</h5>
                                <p class="text-muted mb-0">ID: {{ $data_order->code_order }}</p>
                                <p class="text-muted mb-0">Phương thức thanh toán : {{ $data_order->pay_method }} </p>
                                @if ($data_order->status_pay == 'unpaid')
                                    <p class="text-danger fw-bold">Chưa thanh toán</p>
                                @elseif ($data_order->status_pay == 'paid')
                                    <p class="text-success fw-bold">Đã Thanh toán</p>
                                @endif

                            </div>
                        </div>
                    </div>
                    <!--end card-->

                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex">
                                <h5 class="card-title flex-grow-1 mb-0">Khách đặt</h5>
                                <div class="flex-shrink-0">
                                    <a href="javascript:void(0);" class="link-secondary">Thông tin</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0 vstack gap-3">
                                <li>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <img src="{{ asset('assets/images/avt.jpg') }}" alt=""
                                                class="avatar-sm rounded">
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="fs-14 mb-1">{{ $data_order->name }}</h6>
                                            <p class="text-muted mb-0">Khách hàng</p>
                                        </div>
                                    </div>
                                </li>
                                <li><i
                                        class="ri-mail-line me-2 align-middle text-muted fs-16"></i>{{ $data_order->email }}
                                </li>
                                <li><i
                                        class="ri-phone-line me-2 align-middle text-muted fs-16"></i>{{ $data_order->phone }}
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!--end card-->
                    {{-- <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="ri-map-pin-line align-middle me-1 text-muted"></i>
                                Billing Address</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled vstack gap-2 fs-13 mb-0">
                                <li class="fw-medium fs-14">Joseph Parker</li>
                                <li>+(256) 245451 451</li>
                                <li>2186 Joyce Street Rocky Mount</li>
                                <li>New York - 25645</li>
                                <li>United States</li>
                            </ul>
                        </div>
                    </div> --}}
                    <!--end card-->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="ri-map-pin-line align-middle me-1 text-muted"></i>
                                Địa chỉ nhận hàng</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled vstack gap-2 fs-13 mb-0">
                                <li class="fw-medium fs-14">Họ Tên : {{ $data_order->ad_name ?? $data_order->name }}</li>
                                <li>Số điện thoại : {{ $data_order->ad_phone ?? $data_order->phone }}</li>
                                <li>Địa chỉ : {{ $data_order->ad_address ?? $data_order->address }} -
                                    {{ $data_order->ward_b ?? $data_order->ward_o }} -
                                    {{ $data_order->province_b ?? $data_order->province_o }} </li>
                                {{-- <li>California - 24567</li> --}}
                                {{-- <li>United States</li> --}}
                            </ul>
                        </div>
                    </div>
                    <!--end card-->

                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i
                                    class="ri-secure-payment-line align-bottom me-1 text-muted"></i> Phương thức thanh toán
                            </h5>
                        </div>
                        @if ($data_order->pay_method == 'COD')
                            <div class="card-body">
                                <p class="text-muted mb-0">Thanh toán khi nhận hàng (COD)</p>
                            </div>
                        @elseif($data_order->pay_method == 'VNPAY')
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="flex-shrink-0">
                                        <p class="text-muted mb-0">Phương thức thanh toán:</p>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <h6 class="mb-0">VNPAY</h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <p class="text-muted mb-0">Số tiền:</p>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <h6 class="mb-0">{{ number_format($data_order->final_amount) }}</h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    @switch($data_order->status_pay)
                                        @case('paid')
                                            <h3 class="text-success">Đã Thanh Toán</h3>
                                        @break

                                        @case('unpaid')
                                            <h3 class="text-danger">Chưa Thanh Toán</h3>
                                        @break

                                        @case('failed')
                                            <h3 class="text-danger">Thanh Toán Thất Bại</h3>
                                        @break

                                        @case('cancelled')
                                            <h3 class="text-success">Đã hủy</h3>
                                        @break

                                        @case('cod_paid')
                                            <h3 class="text-success">Thanh toán khi nhận hàng</h3>
                                        @break

                                        @default
                                    @endswitch
                                </div>
                            </div>
                        @endif
                    </div>
                    <!--end card-->
                    @if (!empty($data_order->image_ship))
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="ri-image-line align-middle me-1 text-muted"></i> Ảnh giao hàng
                                </h5>
                            </div>
                            <div class="" data-category="designing development">
                                <div class="gallery-box card">
                                    <div class="gallery-container">
                                        <a class="image-popup" href="{{ asset($data_order->image_ship) }}"
                                            title="">
                                            <img class="gallery-img img-fluid mx-auto"
                                                src="{{ asset($data_order->image_ship) }}" alt="" />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="card-title   mb-0">
                                    <i class="ri-image-line align-middle me-1 text-muted"></i> Ảnh giao hàng
                                    <div class="text-muted">Chưa có ảnh giao hàng</div>
                                </h5>
                            </div>
                        </div>
                    @endif
                </div>
                <!--end col-->
            </div>
            <!--end row-->

        </div><!-- container-fluid -->
    </div><!-- End Page-content -->
    {{-- show modal --}}
    <div class="modal fade" id="showModalfailed" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title" id="exampleModalLabel">Giao hàng thất bại</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>
                <form action="{{ url("dashboard/order/change/$data_order->id") }}" method="post" class="tablelist-form"
                    autocomplete="off" id="reasonFormFailed">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="change" value="failed">
                        <input type="hidden" name="order_id" id="order_id_failed">

                        <div class="mb-3">
                            <label for="reason-select-failed" class="form-label">Lý do</label>
                            <select id="reason-select-failed" name="content1" class="form-control">
                                <option value="">-- Chọn lý do --</option>
                                <option value="Khách không nhận hàng">Khách không nhận hàng
                                </option>
                                <option value="Không liên lạc được">Không liên lạc được</option>
                                <option value="Địa chỉ không đúng">Địa chỉ không đúng</option>
                                <option value="Lý do khác">Lý do khác</option>
                            </select>
                        </div>
                        <div class="mb-3" id="other-reason-group-failed" style="display:none;">
                            <label for="other-reason-failed" class="form-label">Nhập lý do
                                khác</label>
                            <input type="text" id="other-reason-failed" name="content" class="form-control"
                                placeholder="Nhập lý do khác" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-success" id="add-btn">Cập
                                Nhật</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="showModalcancel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Hủy đơn hàng
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>
                <form action="{{ url("dashboard/order/change/$data_order->id") }}" class="tablelist-form"
                    autocomplete="off" id="reasonFormCancel" method="post">
                    @csrf
                    <input type="hidden" name="change" value="cancelled">
                    <input type="hidden" name="order_id" id="order_id_cancel">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="reason-select-cancel" class="form-label">Lý do</label>
                            <select id="reason-select-cancel" name="content1" class="form-control">
                                <option value="">-- Chọn lý do --</option>
                                <option value="Khách không nhận hàng">Khách không nhận hàng
                                </option>
                                <option value="Không liên lạc được">Không liên lạc được</option>
                                <option value="Địa chỉ không đúng">Địa chỉ không đúng</option>
                                <option value="Lý do khác">Lý do khác</option>
                            </select>
                        </div>
                        <div class="mb-3" id="other-reason-group-cancel" style="display:none;">
                            <label for="other-reason-cancel" class="form-label">Nhập lý do
                                khác</label>
                            <input type="text" id="other-reason-cancel" name="content" class="form-control"
                                placeholder="Nhập lý do khác" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-success">Cập
                                Nhật</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="showModalsuccess" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title" id="exampleModalLabel">Ảnh giao hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>
                <form action="{{ url("dashboard/order/change/$data_order->id") }}" value="success" method="post"
                    class="tablelist-form" autocomplete="off" id="reasonFormFailed" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="change" value="shipping">
                        <input type="hidden" name="_form" value="success">
                        <div class="mb-3">
                            <label for="reason-select-failed" class="form-label">Ảnh giao hàng</label>
                            <input type="file" id="reason-select-failed" name="image_ship" class="form-control">
                            @error('image_ship')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="reason-select-failed" class="form-label">Ghi chú</label>
                            <textarea id="reason-select-failed" name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-success" id="add-btn">Cập
                                Nhật</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="showModalchange" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title" id="exampleModalLabel">Thay đổi địa chỉ nhận hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>
                <form action="{{ url("dashboard/order/change-address/$data_order->id") }}" method="post"
                    class="tablelist-form" autocomplete="off">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="_form" value="change_address">

                        <div class="mb-3">
                            <label for="ad_name" class="form-label">Tên người nhận</label>
                            <input type="text" id="ad_name" name="ad_name" class="form-control"
                                value="{{ old('ad_name', $data_order->ad_name ?? $data_order->name) }}">
                            @error('ad_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="ad_phone" class="form-label">Số điện thoại</label>
                            <input type="text" id="ad_phone" name="ad_phone" class="form-control"
                                value="{{ old('ad_phone', $data_order->ad_phone ?? $data_order->phone) }}">
                            @error('ad_phone')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div>
                                <label for="ad_address" class="form-label">Địa chỉ nhận hàng</label>
                                <select name="province_id" id="province" class="form-control">
                                    <option value="">Tỉnh/Thành Phố</option>
                                    @foreach ($data_provinces as $render_provinces)
                                        <option value="{{ $render_provinces->province_code }}">
                                            {{ $render_provinces->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="pt-3" id="ward-container" style="display: none;">
                                <select name="ward_id" id="ward" class="form-control">
                                    <option value="">Chọn Xã Phường</option>
                                </select>
                            </div>
                            <div class="pt-3" id="address-detail-group" style="display: none;">
                                <label for="ad_address" class="form-label">Địa chỉ chi tiết (số nhà, đường,...)</label>
                                <input type="text" id="ad_address" name="ad_address" class="form-control"
                                    value="{{ old('ad_address', $data_order->ad_address ?? $data_order->address) }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <!--  Extra Large modal example -->
    <div class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myExtraLargeModalLabel">Đánh giá từ khách hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body text-center">
                    {{-- Ảnh người dùng upload nếu có --}}
                    @if (!empty($data_order->image_user))
                        <img src="{{ asset($data_order->image_user) }}" alt="Ảnh khách hàng"
                            class="img-fluid rounded shadow mb-4" style="max-height: 400px;">
                    @else
                        <p class="text-muted">Chưa có ảnh.</p>
                    @endif

                    {{-- Đánh giá nếu có --}}
                    @if (!empty($data_order->user_comment))
                        <blockquote class="blockquote">
                            <p class="mb-0">{{ $data_order->user_comment }}</p>
                            <footer class="blockquote-footer mt-2">khách hàng:
                                <cite title="Tên khách hàng">{{ $data_order->ad_name ?? $data_order->name }}</cite>
                            </footer>
                        </blockquote>
                    @else
                        <p class="text-muted">khách hàng chưa để lại đánh giá.</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js-content')
    <script>
        @if ($errors->any() && old('_form') === 'success')
            var myModal = new bootstrap.Modal(document.getElementById('showModalsuccess'));
            myModal.show();
        @endif
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#province').on('change', function() {
                let provinceId = $(this).val();
                if (provinceId) {
                    $.get('/wards', {
                        province_id: provinceId
                    }, function(data) {
                        $('#ward').empty().append('<option value="">Chọn Xã Phường</option>');
                        if (data.length > 0) {
                            data.forEach(function(ward) {
                                $('#ward').append(
                                    `<option value="${ward.ward_code}">${ward.name}</option>`
                                );
                            });
                            $('#ward-container').slideDown();
                        } else {
                            $('#ward').append('<option>Không có xã/phường</option>');
                            $('#ward-container').slideDown();
                        }

                        // Ẩn ô địa chỉ chi tiết nếu chưa chọn xã
                        $('#address-detail-group').slideUp();
                    });
                } else {
                    $('#ward-container').slideUp();
                    $('#ward').empty().append('<option value="">Chọn Xã Phường</option>');
                    $('#address-detail-group').slideUp();
                }
            });

            // Khi chọn xã/phường thì mới hiện ô địa chỉ chi tiết
            $('#ward').on('change', function() {
                let wardId = $(this).val();
                if (wardId) {
                    $('#address-detail-group').slideDown();
                } else {
                    $('#address-detail-group').slideUp();
                }
            });
        });
    </script>

    <script src="{{ asset('admin/libs/glightbox/js/glightbox.min.js') }}"></script>

    <!-- isotope-layout -->
    <script src="{{ asset('admin/libs/isotope-layout/isotope.pkgd.min.js') }}"></script>

    <script src="{{ asset('admin/js/pages/gallery.init.js') }}"></script>
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
