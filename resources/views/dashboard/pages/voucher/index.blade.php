@extends('dashboard.layouts.layout')
@section('main-content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Khuyến mại</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Khuyến mại</a></li>
                                <li class="breadcrumb-item active">{{ $type }}</li>
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
                                    <h5 class="card-title mb-0">Mã giảm giá > {{ $type }}</h5>
                                </div>
                                <div class="col-sm-auto">
                                    <div class="d-flex gap-1 flex-wrap">
                                        <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal"
                                            id="create-btn" data-bs-target="#showModal"><i
                                                class="ri-add-line align-bottom me-1"></i> Thêm mã giảm giá</button>
                                        <button class="btn btn-soft-danger" id="remove-actions"
                                            onClick="deleteMultiple()"><i class="ri-delete-bin-2-line"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="card-body border border-dashed border-end-0 border-start-0">
                                    <form>
                                        <div class="row g-3">
                                            <div class="col-xxl-5 col-sm-6">
                                                <div class="search-box">
                                                    <input type="text" class="form-control search" placeholder="Search for order ID, customer, order status or something...">
                                                    <i class="ri-search-line search-icon"></i>
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-xxl-2 col-sm-6">
                                                <div>
                                                    <input type="text" class="form-control" data-provider="flatpickr" data-date-format="d M, Y" data-range-date="true" id="demo-datepicker" placeholder="Select date">
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-xxl-2 col-sm-4">
                                                <div>
                                                    <select class="form-control" data-choices data-choices-search-false name="choices-single-default" id="idStatus">
                                                        <option value="">Trạng thái</option>
                                                        <option value="all" selected>Tất cả</option>
                                                        <option value="Pending">Pending</option>
                                                        <option value="Inprogress">Inprogress</option>
                                                        <option value="Cancelled">Cancelled</option>
                                                        <option value="Pickups">Pickups</option>
                                                        <option value="Returns">Returns</option>
                                                        <option value="Delivered">Delivered</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-xxl-2 col-sm-4">
                                                <div>
                                                    <select class="form-control" data-choices data-choices-search-false name="choices-single-default" id="idPayment">
                                                        <option value="">Select Payment</option>
                                                        <option value="all" selected>All</option>
                                                        <option value="Mastercard">Mastercard</option>
                                                        <option value="Paypal">Paypal</option>
                                                        <option value="Visa">Visa</option>
                                                        <option value="COD">COD</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-xxl-1 col-sm-4">
                                                <div>
                                                    <button type="button" class="btn btn-primary w-100" onclick="SearchData();"> <i class="ri-equalizer-fill me-1 align-bottom"></i>
                                                        Filters
                                                    </button>
                                                </div>
                                            </div>
                                            <!--end col-->
                                        </div>
                                        <!--end row-->
                                    </form>
                                </div> --}}
                        <div class="card-body pt-0">
                            <div>
                                <ul class="nav nav-tabs nav-tabs-custom nav-success mb-3" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active All py-3" data-bs-toggle="" id="All"
                                            href="{{ url("dashboard/voucher/$id") }}" role="" aria-selected="true">
                                            <i class="ri-store-2-fill me-1 align-bottom"></i> Tất cả
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link py-3 Delivered" data-bs-toggle="" id="Delivered"
                                            href="{{ url("dashboard/voucher/$id?type=active") }}" role=""
                                            aria-selected="false">
                                            <i class="ri-checkbox-circle-line me-1 align-bottom"></i> Đang hoạt động
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link py-3 Pickups" data-bs-toggle="" id="Pickups"
                                            href="{{ url("dashboard/voucher/$id?type=expired") }}" role="tab"
                                            aria-selected="false">
                                            <i class="ri-record-circle-line me-1 align-bottom"></i> Hết hạn <span
                                                class="badge bg-danger align-middle ms-1">2</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link py-3 Returns" data-bs-toggle="" id="Returns"
                                            href="{{ url("dashboard/voucher/$id?type=disabled") }}" role="tab"
                                            aria-selected="false">
                                            <i class="ri-arrow-left-right-fill me-1 align-bottom"></i> Vô hiệu hóa
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link py-3 Cancelled" data-bs-toggle="" id="Cancelled"
                                            href="{{ url("dashboard/voucher/$id?type=used_up") }}" role="tab"
                                            aria-selected="false">
                                            <i class="ri-close-circle-line me-1 align-bottom"></i> Hết lượt sử dụng
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link py-3 Cancelled" data-bs-toggle="" id="Cancelled"
                                            href="{{ url("dashboard/voucher/$id?type=draft") }}" role="tab"
                                            aria-selected="false">
                                            <i class="ri-close-circle-line me-1 align-bottom"></i> Chưa phát hành
                                        </a>
                                    </li>
                                </ul>

                                <div class="table-responsive table-card mb-1">
                                    <table class="table table-nowrap align-middle" id="orderTable">
                                        <thead class="text-muted table-light">
                                            <tr class="text-uppercase">
                                                <th scope="col" style="width: 25px;">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="checkAll"
                                                            value="option">
                                                    </div>
                                                </th>
                                                <th class="sort" data-sort="id">Mã code</th>
                                                <th class="sort" data-sort="date">Bắt đầu</th>
                                                <th class="sort" data-sort="amount">Kết thúc</th>
                                                <th class="sort" data-sort="status">Trạng thái</th>
                                                <th class="sort" data-sort="city">Chi tiết</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                            @if ($data_voucher->isEmpty())
                                                <tr>
                                                    <th>
                                                        <div class="text-center">

                                                            Không có voucher nào
                                                        </div>
                                                    </th>
                                                </tr>
                                            @endif

                                            @foreach ($data_voucher as $render_voucher)
                                                <tr>
                                                    <th scope="row">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="checkAll" value="option1">
                                                        </div>
                                                    </th>
                                                    <td class="id"><a href="apps-ecommerce-order-details.html"
                                                            class="fw-medium link-primary">{{ $render_voucher->code }}</a>
                                                    </td>
                                                    <td class="date">{{ $render_voucher->start_date }}</td>
                                                    <td class="amount">{{ $render_voucher->end_date }}</td>
                                                    @switch($render_voucher->status)
                                                        @case('active')
                                                            <td class="status"><span
                                                                    class="badge bg-success-subtle text-success text-uppercase">Đang
                                                                    hoạt động</span>
                                                            </td>
                                                        @break

                                                        @case('expired')
                                                            <td class="status"><span
                                                                    class="badge bg-warning-subtle text-warning text-uppercase">Hết
                                                                    hạn</span>
                                                            </td>
                                                        @break

                                                        @case('disabled')
                                                            <td class="status"><span
                                                                    class="badge bg-danger-subtle text-danger text-uppercase">Vô
                                                                    hiệu hóa</span>
                                                            </td>
                                                        @break

                                                        @case('used_up')
                                                            <td class="status"><span
                                                                    class="badge bg-info-subtle text-info text-uppercase">Đã hết
                                                                    lượt</span>
                                                            </td>
                                                        @break

                                                        @case('draft')
                                                            <td class="status"><span
                                                                    class="badge bg-primary-subtle text-black text-uppercase">Chưa
                                                                    phát hành</span>
                                                            </td>
                                                        @break

                                                        @case('save')
                                                            <td class="status"><span
                                                                    class="badge bg-muted-subtle text-black text-uppercase">Lưu trữ</span>
                                                            </td>
                                                        @break

                                                        @default
                                                    @endswitch

                                                    <td>
                                                        <ul class="list-inline hstack gap-2 mb-0">
                                                            <li class="list-inline-item" data-bs-toggle="tooltip"
                                                                data-bs-trigger="hover" data-bs-placement="top"
                                                                title="View">
                                                                <a href="{{ url("dashboard/voucher/$id/$render_voucher->id") }}"
                                                                    class="text-primary d-inline-block">
                                                                    <i class="ri-eye-fill fs-16"></i>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                    @switch($render_voucher->status)
                                                        @case('active')
                                                            <td>
                                                                <ul class="list-inline hstack gap-2 mb-0">
                                                                    <li class="list-inline-item" data-bs-toggle="tooltip"
                                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                                        title="View">
                                                                        <form
                                                                            action="{{ url('dashboard/voucher/disable/' . $render_voucher->id) }}"
                                                                            method="post">
                                                                            @csrf
                                                                            <button class="btn btn-danger">Vô hiệu hóa</button>
                                                                        </form>
                                                                    </li>
                                                                </ul>
                                                            </td>
                                                        @break

                                                        @case('draft')
                                                            <td>
                                                                <ul class="list-inline hstack gap-2 mb-0">
                                                                    <li class="list-inline-item" data-bs-toggle="tooltip"
                                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                                        title="View">
                                                                        <form
                                                                            action="{{ url('dashboard/voucher/active/' . $render_voucher->id) }}"
                                                                            method="post">
                                                                            @csrf
                                                                            <button class="btn btn-success">Khởi động</button>
                                                                        </form>
                                                                    </li>
                                                                </ul>
                                                            </td>
                                                        @break

                                                        @default
                                                    @endswitch
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
                                            <p class="text-muted">We've searched more than 150+ Orders We did not find any
                                                orders for you search.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <div class="pagination-wrap hstack gap-2">
                                        {{ $data_voucher->appends(request()->query())->links() }}
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">

                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-light p-3">
                                            <h5 class="modal-title" id="exampleModalLabel">Thêm voucher</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close" id="close-modal"></button>
                                        </div>
                                        <form class="tablelist-form" name="_form" value="add" id="myForm"
                                            autocomplete="off"
                                            action="{{ url('dashboard/voucher/add_voucher?action=' . $id) }}"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="_form" value="add">

                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="customername-field" class="form-label">Mã voucher</label>
                                                    <input type="text" id="name" name="code"
                                                        class="form-control" placeholder="Nhập mã code" required
                                                        value="{{ old('code') }}" />
                                                    <div class="text-danger">
                                                        @error('code')
                                                            {{ $message }}
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="" class="form-label">Kiểu giảm giá</label>
                                                    <select name="type_discount" id="type_discount" class="form-control">
                                                        <option value="">Chọn phương thức</option>
                                                        <option value="percent"
                                                            {{ old('type_discount') === 'percent' ? 'selected' : '' }}>Giảm
                                                            theo phần trăm</option>
                                                        <option value="value"
                                                            {{ old('type_discount') === 'value' ? 'selectd' : '' }}>Giảm
                                                            trực tiếp</option>
                                                    </select>
                                                    <div class="text-danger">
                                                        @error('type_discount')
                                                            {{ $message }}
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="customername-field" class="form-label">Giá trị
                                                        giảm</label>
                                                    <input type="text" id="value" name="value"
                                                        class="form-control" placeholder="5% hoặc 50000" required
                                                        value="{{ old('value') }}" />
                                                    <div class="text-danger">
                                                        @error('value')
                                                            {{ $message }}
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="max_discount" class="form-label">Giá trị giảm giá tối
                                                        đa</label>
                                                    <input type="text" id="max_discount" name="max_discount"
                                                        class="form-control"
                                                        placeholder="bỏ qua nếu không có hoặc kiểu giảm giá là phần trăm"
                                                        value="{{ old('max_discount') }}" />
                                                    <div class="text-danger">
                                                        @error('max_discount')
                                                            {{ $message }}
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="category_id" class="form-label">Danh Mục</label>
                                                    <select class="form-control" data-trigger name="category_id"
                                                        id="category_id" required>
                                                        <option value="">Loại giảm giá</option>
                                                        @foreach ($name_voucher as $render_name)
                                                            <option value="{{ $render_name->id }}"
                                                                {{ old('category_id') == $render_name->id ? 'selected' : '' }}>
                                                                {{ $render_name->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="text-danger">
                                                        @error('category_id')
                                                            {{ $message }}
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="date-field" class="form-label">Thời gian bắt đầu</label>
                                                    <input type="datetime-local" id="start_date" name="start_date"
                                                        class="form-control" data-provider="flatpickr"
                                                        data-date-format="d M, Y" data-enable-time
                                                        placeholder="chọn thời gian" value="{{ old('start_date') }}" />
                                                    <div class="text-danger">
                                                        @error('start_date')
                                                            {{ $message }}
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="date-field" class="form-label">Thời gian kết thúc</label>
                                                    <input type="datetime-local" id="end_date" name="end_date"
                                                        class="form-control" data-provider="flatpickr"
                                                        data-date-format="d M, Y" data-enable-time
                                                        placeholder="chọn thời gian" value="{{ old('end_date') }}" />
                                                    <div class="text-danger">
                                                        @error('end_date')
                                                            {{ $message }}
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="block-field" class="form-label">Nơi hiển thị<span>( <a
                                                                href="http://example.com" target="_blank"
                                                                rel="noopener noreferrer">Xem thêm</a> )</span></label>
                                                    <select name="block" id="block" class="form-select">
                                                        <option value="">Chọn nơi hiển thị</option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="image-field" class="form-label">Ảnh đại diện</label>
                                                    <input type="file" id="image" name="image"
                                                        class="form-control" accept=".jpg, .jpeg, .png" />
                                                    <div class="text-danger">
                                                        @error('image')
                                                            {{ $message }}
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row gy-4 mb-3">
                                                    <div class="col-md-6">
                                                        <div>
                                                            <label for="amount-field" class="form-label">Số lượt sử dụng
                                                                tối đa</label>
                                                            <input type="text" id="max_used" name="max_used"
                                                                class="form-control" placeholder="Nhập giới hạn"
                                                                value="{{ old('max_used') }}" />
                                                            <div class="text-danger">
                                                                @error('max_used')
                                                                    {{ $message }}
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div>
                                                            <label for="payment-field" class="form-label">Yêu cầu đơn hàng
                                                                tối thiểu</label>
                                                            <input type="text" id="min_order_value"
                                                                name="min_order_value" class="form-control"
                                                                placeholder="Đơn tối thiểu"
                                                                value="{{ old('min_order_value') }}" />
                                                            <div class="text-danger">
                                                                @error('min_order_value')
                                                                    {{ $message }}
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <div class="hstack gap-2 justify-content-end">
                                                    <button type="button" class="btn btn-light"
                                                        data-bs-dismiss="modal">Đóng</button>
                                                    <button type="submit" class="btn btn-success" id="add-btn">Thêm mã
                                                        giảm giá</button>
                                                    <!-- <button type="button" class="btn btn-success" id="edit-btn">Update</button> -->
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal -->
                        </div>
                    </div>

                </div>
                <!--end col-->
            </div>
            <!--end row-->

        </div>
        <!-- container-fluid -->
    </div>
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
    <script>
        const validation = new JustValidate('#myForm');

        validation
            .addField('#name', [{
                    rule: 'required',
                    errorMessage: 'Vui lòng nhập mã voucher',
                },
                {
                    rule: 'minLength',
                    value: 10,
                    errorMessage: 'Mã voucher ít nhất 10 ký tự',
                },
                {
                    rule: 'maxLength',
                    value: 50,
                    errorMessage: 'Mã voucher tối đa 50 ký tự',
                },
            ])
            .addField('#type_discount', [{
                rule: 'required',
                errorMessage: 'Vui lòng chọn kiểu giảm giá',
            }])
            .addField('#value', [{
                    rule: 'required',
                    errorMessage: 'Vui lòng nhập giá trị giảm',
                },
                {
                    validator: (value, fields) => {
                        const type = document.querySelector('#type_discount').value;
                        if (type === 'percent') {
                            // Nếu là phần trăm, kiểm tra giá trị phải là số từ 1 đến 100
                            const num = parseFloat(value);
                            return !isNaN(num) && num > 0 && num <= 100;
                        } else if (type === 'value') {
                            // Nếu giảm trực tiếp, kiểm tra là số dương
                            const num = parseFloat(value);
                            return !isNaN(num) && num > 0;
                        }
                        return false;
                    },
                    errorMessage: 'Giá trị giảm không hợp lệ theo kiểu giảm giá',
                }
            ])


            .addField('#category_id', [{
                rule: 'required',
                errorMessage: 'Vui lòng chọn danh mục',
            }])
            .addField('#start_date', [{
                rule: 'required',
                errorMessage: 'Vui lòng chọn thời gian bắt đầu',
            }])
            .addField('#end_date', [{
                    rule: 'required',
                    errorMessage: 'Vui lòng chọn thời gian kết thúc',
                },
                {
                    validator: (value, fields) => {
                        const startDate = document.querySelector('#start_date').value;
                        if (!startDate) return true; // Nếu start_date chưa nhập thì không kiểm ở đây
                        return new Date(value) > new Date(startDate);
                    },
                    errorMessage: 'Thời gian kết thúc phải lớn hơn thời gian bắt đầu',
                }
            ])
            .addField('#max_used', [{
                rule: 'number',
                errorMessage: 'Số lượt sử dụng phải là số',
            }])
            .addField('#min_order_value', [{
                rule: 'number',
                errorMessage: 'Yêu cầu đơn hàng tối thiểu phải là số',
            }])

            .onSuccess((event) => {
                event.target.submit();
            });
        document.addEventListener('DOMContentLoaded', function() {
            @if ($errors->any() && old('_form') === 'add')

                var myModal = new bootstrap.Modal(document.getElementById('showModal'));
                myModal.show();
            @endif
        });
    </script>
@endsection
