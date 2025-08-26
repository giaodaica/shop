@extends('dashboard.layouts.layout')
@section('main-content')
    <div class="page-content">
        <div class="card shadow">
            <div class="card-header text-white">
                <h4 class="mb-0">Chi tiết</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th>ID</th>
                            <td>{{ $data_voucher->id }}</td>
                        </tr>
                        <tr>
                            <th>Mã code</th>
                            <td>{{ $data_voucher->code }}</td>
                        </tr>
                        <tr>
                            <th>Loại giảm giá</th>
                            <td>
                                @if ($data_voucher->type_discount == 'percent')
                                    Giảm giá theo phần trăm
                                @elseif($data_voucher->type_discount == 'value')
                                    Giảm giá theo giá cố định
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Giá trị giảm</th>
                            <td>{{ number_format($data_voucher->value) . ($data_voucher->type_discount == 'percent' ? '%' : '') }}
                            </td>
                        </tr>
                        <tr>
                            <th>Giảm tối đa</th>
                            <td>{{ number_format($data_voucher->max_discount) }}</td>
                        <tr>
                            <th>Ngày bắt đầu</th>
                            <td>{{ $data_voucher->start_date }}</td>
                        </tr>
                        <tr>
                            <th>Ngày kết thúc</th>
                            <td>{{ $data_voucher->end_date }}</td>
                        </tr>
                        <tr>
                            <th>Đã sử dụng</th>
                            <td>{{ $data_voucher->used }}</td>
                        </tr>
                         <tr>
                            <th>Đã Nhận</th>
                            <td>{{ $data_voucher->received }}</td>
                        </tr>
                        <tr>
                            <th>Số lần sử dụng tối đa</th>
                            <td>{{ $data_voucher->max_used }}</td>
                        </tr>
                        <tr>
                            <th>Giá trị đơn hàng tối thiểu</th>
                            <td>{{ number_format($data_voucher->min_order_value) }}</td>
                        </tr>
                        <tr>
                            <th>Trạng thái</th>
                            @switch($data_voucher->status)
                                @case('active')
                                    <td class="status"><span class="badge bg-success-subtle text-success text-uppercase">Đang
                                            hoạt động</span>
                                    </td>
                                @break

                                @case('expired')
                                    <td class="status"><span class="badge bg-warning-subtle text-warning text-uppercase">Hết
                                            hạn</span>
                                    </td>
                                @break

                                @case('disabled')
                                    <td class="status"><span class="badge bg-danger-subtle text-danger text-uppercase">Vô
                                            hiệu hóa</span>
                                    </td>
                                @break

                                @case('used_up')
                                    <td class="status"><span class="badge bg-info-subtle text-info text-uppercase">Đã hết
                                            lượt</span>
                                    </td>
                                @break

                                @case('draft')
                                    <td class="status"><span class="badge bg-primary-subtle text-black text-uppercase">Chưa phát
                                            hành</span>
                                    </td>
                                @break
                                @case('save')
                                    <td class="status"><span class="badge bg-muted-subtle text-black text-uppercase">Lưu trữ</span>
                                    </td>
                                @break
                                @default
                            @endswitch
                        </tr>
                        <tr>
                            <th>Danh mục</th>
                            <td>{{ $data_voucher->cate_vouchers->name ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>Ngày tạo</th>
                            <td>{{ $data_voucher->created_at }}</td>
                        </tr>
                        <tr>
                            <th>Ngày cập nhật</th>
                            <td>{{ $data_voucher->updated_at }}</td>
                        </tr>
                        <tr>
                            <th>Ngày xóa</th>
                            <td>
                                @if ($data_voucher->deleted_at)
                                    {{ $data_voucher->deleted_at }}
                                @else
                                    <span class="text-muted">Chưa bị xóa</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Hình ảnh</th>
                            <td>
                                @if ($data_voucher->image)
                                    <img src="{{ asset( $data_voucher->image) }}" alt="Voucher Image"
                                        class="img-fluid" style="max-width: 200px;">
                                @else
                                    <span class="text-muted">Chưa có hình ảnh</span>
                                @endif
                            </td>
                        </tr>
                        <tr></tr>
                            <th>Nơi hiển thị</th>
                            <td>{{ $data_voucher->block ?? '' }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="d-flex gap-2 justify-content">
                    @if($action != 's')
                    <a href="{{ url("dashboard/voucher/$action") }}" class="btn btn-secondary mt-3"> <i
                            class="ri-arrow-left-line me-1"></i> Quay lại danh sách</a>
                    @endif
                    <button type="button" class="btn btn-secondary mt-3" data-bs-toggle="modal" id="create-btn"
                        data-bs-target="#showModal"><i
                            class="ri-edit-line align-bottom me-1"></i> Sửa</button>
                        <form action="{{route('delete',$data_voucher->id)}}" method="post">
                            @csrf
                            <button class="btn btn-danger mt-3">Xóa</button>
                        </form>
                        <form action="{{route('restore',$data_voucher->id)}}" method="post">
                            @csrf
                            <button class="btn btn-warning mt-3">Phát hành lại</button>
                        </form>
                </div>
                {{-- modal --}}
                <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">

                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-light p-3">
                                <h5 class="modal-title" id="exampleModalLabel">
                                    Sửa voucher
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                    id="close-modal"></button>
                            </div>
                            <form class="tablelist-form" name="_form" value="edit" id="myForm9" autocomplete="off"
                                action="{{ url("dashboard/voucher/$data_voucher->id/update") }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="_form" value="edit">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="customername-field" class="form-label">Mã voucher</label>
                                        <input type="text" id="code" name="code" class="form-control"
                                            placeholder="Nhập mã code" required value="{{ $data_voucher->code }}" @if($data_voucher->status == 'active')
                                            readonly
                                            @else

                                            @endif />
                                        <div class="text-danger">
                                            @error('code')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="" class="form-label">Kiểu giảm giá</label>
                                        <select name="type_discount" id="type_discount" class="form-control" @if($data_voucher->status == 'active')
                                            readonly
                                        @else
                                        @endif>
                                            <option value="" >Chọn phương thức</option>
                                            <option
                                                value="percent"{{ $data_voucher->type_discount === 'percent' ? 'selected' : '' }}>
                                                Giảm theo phần trăm</option>
                                            <option value="value"
                                                {{ $data_voucher->type_discount === 'value' ? 'selected' : '' }}>Giảm trực
                                                tiếp</option>
                                        </select>
                                        <div class="text-danger">
                                            @error('type_discount')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="customername-field" class="form-label">Giá trị
                                            giảm
                                            ({{ $data_voucher->type_discount == 'percent' ? '%' : 'Nghìn đồng' }})</label>
                                        <input type="text" id="value" name="value" class="form-control"
                                            placeholder="5% hoặc 50000" required value="{{ $data_voucher->value }}" @if($data_voucher->status == 'active')
                                            readonly
                                        @else
                                        @endif />
                                        <div class="text-danger">
                                            @error('value')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="max_discount" class="form-label">Giá trị giảm giá tối đa</label>
                                        <input type="text" id="max_discount" name="max_discount" class="form-control"
                                            placeholder="bỏ qua nếu không có hoặc kiểu giảm giá là phần trăm"
                                            value="{{ $data_voucher->max_discount }}" @if($data_voucher->status == 'active')
                                            readonly
                                        @else
                                        @endif />
                                        <div class="text-danger">
                                            @error('max_discount')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="category_id" class="form-label">Danh Mục</label>
                                        <select class="form-control" data-trigger name="category_id" id="category_id"
                                            required @if($data_voucher->status == 'active')
                                            readonly
                                        @else
                                        @endif>
                                            <option value="">Loại giảm giá</option>
                                            @foreach ($categories as $render_name)
                                                <option value="{{ $render_name->id }}"
                                                    {{ $data_voucher->cate_vouchers->id == $render_name->id ? 'selected' : '' }}>
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
                                            class="form-control" data-provider="flatpickr" data-date-format="d M, Y"
                                            data-enable-time placeholder="chọn thời gian"
                                            value="{{ $data_voucher->start_date }}" />
                                        <div class="text-danger">
                                            @error('start_date')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="date-field" class="form-label">Thời gian kết thúc</label>
                                        <input type="datetime-local" id="end_date" name="end_date" class="form-control"
                                            data-provider="flatpickr" data-date-format="d M, Y" data-enable-time
                                            placeholder="chọn thời gian" value="{{ $data_voucher->end_date }}" />
                                        <div class="text-danger">
                                            @error('end_date')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="block-field" class="form-label">Vị trí hiển thị</label>
                                        <select id="block" name="block" class="form-select" @if($data_voucher->status == 'active')
                                            readonly
                                        @else
                                        @endif>
                                            <option value="">Chọn vị trí</option>
                                            <option value="1" {{ $data_voucher->block == 1 ? 'selected' : '' }}>1</option>
                                            <option value="2" {{ $data_voucher->block == 2 ? 'selected' : '' }}>2</option>
                                            <option value="3" {{ $data_voucher->block == 3 ? 'selected' : '' }}>3</option>
                                        </select>
                                        <div class="text-danger">
                                            @error('block')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="image-field" class="form-label">Ảnh đại diện</label>
                                        <input type="file" id="image" name="image" class="form-control"
                                            accept=".jpg,.jpeg,.png" @if($data_voucher->status == 'active')
                                            readonly
                                        @else
                                        @endif />
                                        <div class="text-danger">
                                            @error('image')
                                                {{ $message }}
                                            @enderror
                                        </div>
                                        @if ($data_voucher->image)
                                            <div class="mt-2">
                                                <img src="{{ asset($data_voucher->image) }}" alt="Voucher Image"
                                                    class="img-fluid" style="max-width: 200px;">
                                            </div>
                                        @else
                                            <div class="mt-2">
                                                <span class="text-muted">Chưa có hình ảnh</span>
                                            </div>
                                        @endif
                                    <div class="row gy-4 mb-3">
                                        <div class="col-md-6">
                                            <div>
                                                <label for="amount-field" class="form-label">Số lượt sử dụng
                                                    tối đa</label>
                                                <input type="text" id="max_used" name="max_used"
                                                    class="form-control" placeholder="Nhập giới hạn"
                                                    value="{{ $data_voucher->max_used }}" />
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
                                                <input type="text" id="min_order_value" name="min_order_value"
                                                    class="form-control" placeholder="Đơn tối thiểu"
                                                    value="{{ $data_voucher->min_order_value }}" @if($data_voucher->status == 'active')
                                                    readonly
                                                @else
                                                @endif />
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
                                        <button type="submit" class="btn btn-success" id="add-btn">Sửa</button>
                                        <!-- <button type="button" class="btn btn-success" id="edit-btn">Update</button> -->
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- end modal --}}
            </div>
        </div>
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
        const validation = new JustValidate('#myForm9');

        validation
            .addField('#code', [{
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

            .addField('#max_discount', [{
                validator: (value, fields) => {
                    const type = document.querySelector('#type_discount').value;
                    if (type === 'percent') {

                        const num = parseFloat(value);
                        return !isNaN(num) && num >= 0;
                    }
                    return true;
                },
                errorMessage: 'Giá trị giảm giá tối đa phải là số dương',
            }])
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
            @if ($errors->any() && old('_form') === 'edit')

                var myModal = new bootstrap.Modal(document.getElementById('showModal'));
                myModal.show();
            @endif
        });
    </script>
@endsection
