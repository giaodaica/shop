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
                        <h4 class="mb-sm-0">Đặt hàng</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Hệ Thống</a></li>
                                <li class="breadcrumb-item active">Đặt hàng</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="card" id="orderList">
                        <div class="card-header border-0">
                            <div class="row align-items-center gy-3">
                                <div class="col-sm">
                                    <h5 class="card-title mb-0">Lịch sử</h5>
                                </div>
                                <div class="col-sm-auto">
                                    <div class="d-flex gap-1 flex-wrap">
                                        <button class="btn btn-soft-danger" id="remove-actions"
                                            onClick="deleteMultiple()"><i class="ri-delete-bin-2-line"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body border border-dashed border-end-0 border-start-0">
                            <form action="{{ route('dashboard.order') }}" method="get">
                                @csrf
                                <div class="row g-3">
                                    <!-- Search everything -->
                                    <div class="col-xxl-4">
                                        <label for="everything" class="form-label">Tìm kiếm</label>
                                        <div class="search-box">
                                            <input name="everything" id="everything" type="text"
                                                class="form-control search" placeholder="Tên khách hàng, mã đơn hàng">
                                            <i class="ri-search-line search-icon"></i>
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-xxl-3 col-sm-6">
                                        <label for="idStatus" class="form-label">Trạng thái đơn hàng</label>
                                        <select class="form-control" data-choices data-choices-search-false name="status"
                                            id="idStatus">
                                            <option value="all" selected>Tất cả</option>
                                            <option value="pending">Chờ xác nhận</option>
                                            <option value="confirmed">Đã xác nhận</option>
                                            <option value="shipping">Đang giao hàng</option>
                                            <option value="success">Giao hàng thành công</option>
                                            <option value="failed">Giao hàng thất bại</option>
                                            <option value="cancelled">Đã hủy</option>
                                        </select>
                                    </div>

                                    <!-- Pay method -->
                                    <div class="col-xxl-2 col-sm-4">
                                        <label for="idPayment" class="form-label">Phương thức thanh toán</label>
                                        <select class="form-control" data-choices data-choices-search-false
                                            name="pay_method" id="idPayment">
                                            <option value="all" selected>Tất cả</option>
                                            <option value="VNPAY">VNPAY</option>
                                            <option value="COD">COD</option>
                                        </select>
                                    </div>

                                    <!-- Status pay -->
                                    <div class="col-xxl-3 col-sm-4">
                                        <label for="idStatusPay" class="form-label">Trạng thái thanh toán</label>
                                        <select class="form-control" data-choices data-choices-search-false
                                            name="status_pay" id="idStatusPay">
                                            <option value="all" selected>Tất cả</option>
                                            <option value="unpaid">Chưa thanh toán</option>
                                            <option value="paid">Thanh toán thành công</option>
                                            <option value="failed">Thanh toán thất bại</option>
                                            <option value="cod_paid">Thanh toán COD</option>
                                        </select>
                                    </div>

                                    <!-- Submit button -->
                                    <div class="col-xxl-1 col-sm-4 d-flex align-items-end gap-2 justify-content">
                                        <button type="submit" class="btn btn-primary w-100">
                                            Lọc
                                        </button>
                                        <a href="{{route('dashboard.order')}}" class="btn btn-danger">Xóa</a>
                                    </div>
                                </div>
                            </form>

                        </div>
                        <div class="card-body pt-0">
                            <div>
                                <ul class="nav nav-tabs nav-tabs-custom nav-success mb-3" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active All py-3" data-bs-toggle="" id="All"
                                            href="{{ route('dashboard.order') }}" role="tab" aria-selected="true">
                                            <i class="ri-store-2-fill me-1 align-bottom"></i> Tất cả
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link py-3 Delivered" data-bs-toggle="" id="Delivered"
                                            href="{{ route('dashboard.order', ['type' => 'pending']) }}" role="tab"
                                            aria-selected="false">
                                            <i class="ri-checkbox-circle-line me-1 align-bottom"></i> Chờ xác nhận
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link py-3 Pickups" data-bs-toggle="" id="Pickups"
                                            href="{{ route('dashboard.order', ['type' => 'confirmed']) }}" role="tab"
                                            aria-selected="false">
                                            <i class="ri-truck-line me-1 align-bottom"></i> Đã xác nhận <span
                                                class="badge bg-danger align-middle ms-1">2</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link py-3 Returns" data-bs-toggle="" id="Returns"
                                            href="{{ route('dashboard.order', ['type' => 'shipping']) }}" role="tab"
                                            aria-selected="false">
                                            <i class="ri-arrow-left-right-fill me-1 align-bottom"></i> Đang vận chuyển
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link py-3 Success" data-bs-toggle="" id="Success"
                                            href="{{ route('dashboard.order', ['type' => 'success']) }}" role="tab"
                                            aria-selected="false">
                                            <i class="ri-arrow-left-right-fill me-1 align-bottom"></i> Giao hàng thành công
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link py-3 Success" data-bs-toggle="" id="Success"
                                            href="{{ route('dashboard.order', ['type' => 'failed']) }}" role="tab"
                                            aria-selected="false">
                                            <i class="ri-arrow-left-right-fill me-1 align-bottom"></i> Giao hàng thất bại
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link py-3 Cancelled" data-bs-toggle="" id="Cancelled"
                                            href="{{ route('dashboard.order', ['type' => 'cancelled']) }}" role="tab"
                                            aria-selected="false">
                                            <i class="ri-close-circle-line me-1 align-bottom"></i> Đã Hủy
                                        </a>
                                    </li>
                                </ul>

                                @if ($data_order->isEmpty())
                                    <div class="alert alert-warning text-center" role="alert">
                                        Không có đơn hàng nào để hiển thị.
                                    </div>
                                @else
                                    <div class="table-responsive table-card mb-1">
                                        <table class="table table-nowrap align-middle" id="orderTable">
                                            <thead class="text-muted table-light">
                                                <tr class="text-uppercase">
                                                    <th scope="col" style="width: 25px;">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="checkAll" value="option">
                                                        </div>
                                                    </th>
                                                    <th>Order ID</th>
                                                    <th class="" data-sort="customer_name">Khách hàng</th>
                                                    <th class="" data-sort="date">Thời gian đặt</th>
                                                    <th class="" data-sort="amount">Đơn giá</th>
                                                    <th class="" data-sort="payment">Phương thức thanh toán</th>
                                                    <th class="" data-sort="payment_mothod">Trạng thái thanh toán
                                                    </th>
                                                    <th class="" data-sort="status">Trạng thái</th>
                                                    <th class="" data-sort="city">Hành động</th>

                                                </tr>
                                            </thead>
                                            <tbody class="list form-check-all">
                                                @foreach ($data_order as $render_order)
                                                    <tr>
                                                        <th scope="row">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="checkAll" value="option1">
                                                            </div>
                                                        </th>
                                                        <td class="id"><a
                                                                href="{{ url("dashboard/order/$render_order->id") }}"
                                                                class="fw-medium link-primary">{{ $render_order->code_order }}</a>
                                                        </td>
                                                        <td class="product_name">{{ $render_order->name }}</td>
                                                        <td class="date">
                                                            {{ formatDate($render_order->created_at) }}</small>
                                                        </td>
                                                        <td class="amount">
                                                            {{ number_format($render_order->final_amount) }}
                                                        </td>
                                                        <td class="payment">{{ $render_order->pay_method }}</td>
                                                        <td class="payment">
                                                            @switch($render_order->status_pay)
                                                                @case('unpaid')
                                                                    Chưa thanh toán
                                                                @break

                                                                @case('paid')
                                                                    Đã thanh toán
                                                                @break

                                                                @case('failed')
                                                                    Thanh toán thất bại
                                                                @break

                                                                @case('cancelled')
                                                                    Đã hủy thanh toán
                                                                @break

                                                                @case('cod_paid')
                                                                    Thanh toán khi nhận hàng
                                                                @break

                                                                @default
                                                            @endswitch
                                                        </td>
                                                        <td class="status">
                                                            @switch($render_order->status)
                                                                @case('pending')
                                                                    <span
                                                                        class="badge bg-warning-subtle text-warning text-uppercase">Chờ
                                                                        xác nhận</span>
                                                                @break

                                                                @case('confirmed')
                                                                    <span
                                                                        class="badge bg-primary-subtle text-primary text-uppercase">Đã
                                                                        Xác Nhận</span>
                                                                @break

                                                                @case('shipping')
                                                                    <span
                                                                        class="badge bg-info-subtle text-info text-uppercase">Đang
                                                                        giao hàng</span>
                                                                @break

                                                                @case('success')
                                                                    <span
                                                                        class="badge bg-success-subtle text-success text-uppercase">Giao
                                                                        hàng thành công</span>
                                                                @break

                                                                @case('failed')
                                                                    <span
                                                                        class="badge bg-warning-subtle text-warning text-uppercase">Giao
                                                                        hàng thất bại</span>
                                                                @break

                                                                @case('cancelled')
                                                                    <span
                                                                        class="badge bg-danger-subtle text-danger text-uppercase">Đã
                                                                        hủy</span>
                                                                @break

                                                                @default
                                                            @endswitch
                                                        </td>
                                                        <td>
                                                            <ul class="list-inline hstack gap-2 mb-0">
                                                                <li class="list-inline-item" data-bs-toggle="tooltip"
                                                                    data-bs-trigger="hover" data-bs-placement="top"
                                                                    title="View">
                                                                    <a href="{{ url("dashboard/order/$render_order->id") }}"
                                                                        class="text-primary d-inline-block">
                                                                        <i class="ri-eye-fill fs-16"></i>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="noresult" style="display: none">
                                            <div class="text-center">
                                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                                    colors="primary:#405189,secondary:#0ab39c"
                                                    style="width:75px;height:75px"></lord-icon>
                                                <h5 class="mt-2">Sorry! No Result Found</h5>
                                                <p class="text-muted">We've searched more than 150+ Orders We did not find
                                                    any
                                                    orders for you search.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        {{ $data_order->links('') }}
                                    </div>
                            </div>
                            {{-- show modal --}}
                            <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-light p-3">
                                            <h5 class="modal-title" id="exampleModalLabel">&nbsp;</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close" id="close-modal"></button>
                                        </div>
                                        <form class="tablelist-form" autocomplete="off">
                                            <div class="modal-body">
                                                <input type="hidden" id="id-field" />

                                                <div class="mb-3" id="modal-id">
                                                    <label for="orderId" class="form-label">ID</label>
                                                    <input type="text" id="orderId" class="form-control"
                                                        placeholder="ID" readonly />
                                                </div>

                                                <div class="mb-3">
                                                    <label for="customername-field" class="form-label">Customer
                                                        Name</label>
                                                    <input type="text" id="customername-field" class="form-control"
                                                        placeholder="Enter name" required />
                                                </div>

                                                <div class="mb-3">
                                                    <label for="productname-field" class="form-label">Product</label>
                                                    <select class="form-control" data-trigger name="productname-field"
                                                        id="productname-field" required />
                                                    <option value="">Product</option>
                                                    <option value="Puma Tshirt">Puma Tshirt</option>
                                                    <option value="Adidas Sneakers">Adidas Sneakers</option>
                                                    <option value="350 ml Glass Grocery Container">350 ml Glass Grocery
                                                        Container</option>
                                                    <option value="American egale outfitters Shirt">American egale
                                                        outfitters Shirt</option>
                                                    <option value="Galaxy Watch4">Galaxy Watch4</option>
                                                    <option value="Apple iPhone 12">Apple iPhone 12</option>
                                                    <option value="Funky Prints T-shirt">Funky Prints T-shirt</option>
                                                    <option value="USB Flash Drive Personalized with 3D Print">USB Flash
                                                        Drive Personalized with 3D Print</option>
                                                    <option value="Oxford Button-Down Shirt">Oxford Button-Down Shirt
                                                    </option>
                                                    <option value="Classic Short Sleeve Shirt">Classic Short Sleeve Shirt
                                                    </option>
                                                    <option value="Half Sleeve T-Shirts (Blue)">Half Sleeve T-Shirts (Blue)
                                                    </option>
                                                    <option value="Noise Evolve Smartwatch">Noise Evolve Smartwatch
                                                    </option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="date-field" class="form-label">Order Date</label>
                                                    <input type="date" id="date-field" class="form-control"
                                                        data-provider="flatpickr" required data-date-format="d M, Y"
                                                        data-enable-time required placeholder="Select date" />
                                                </div>

                                                <div class="row gy-4 mb-3">
                                                    <div class="col-md-6">
                                                        <div>
                                                            <label for="amount-field" class="form-label">Amount</label>
                                                            <input type="text" id="amount-field" class="form-control"
                                                                placeholder="Total amount" required />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div>
                                                            <label for="payment-field" class="form-label">Payment
                                                                Method</label>
                                                            <select class="form-control" data-trigger
                                                                name="payment-method" required id="payment-field">
                                                                <option value="">Payment Method</option>
                                                                <option value="Mastercard">Mastercard</option>
                                                                <option value="Visa">Visa</option>
                                                                <option value="COD">COD</option>
                                                                <option value="Paypal">Paypal</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div>
                                                    <label for="delivered-status" class="form-label">Delivery
                                                        Status</label>
                                                    <select class="form-control" data-trigger name="delivered-status"
                                                        required id="delivered-status">
                                                        <option value="">Delivery Status</option>
                                                        <option value="Pending">Pending</option>
                                                        <option value="Inprogress">Inprogress</option>
                                                        <option value="Cancelled">Cancelled</option>
                                                        <option value="Pickups">Pickups</option>
                                                        <option value="Delivered">Delivered</option>
                                                        <option value="Returns">Returns</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <div class="hstack gap-2 justify-content-end">
                                                    <button type="button" class="btn btn-light"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-success" id="add-btn">Add
                                                        Order</button>
                                                    <!-- <button type="button" class="btn btn-success" id="edit-btn">Update</button> -->
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal -->
                            @endif
                            <!--end modal -->
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
    <!-- list.js min js -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $("button").prop("disabled", true);

        $(window).on("load", function() {
            $("button").prop("disabled", false);
        });
    </script>
    <script src="{{ asset('admin/assets/libs/list.js/list.min.js') }}"></script>

    <!--list pagination js-->
    <script src="{{ asset('admin/assets/libs/list.pagination.js/list.pagination.min.js') }}"></script>

    <!-- ecommerce-order init js -->
    <script src="{{ asset('admin/assets/js/pages/ecommerce-order.init.js') }}"></script>

    <!-- Sweet Alerts js -->
    <script src="{{ asset('admin/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    <!-- App js -->
@endsection
