@extends('dashboard.layouts.layout')

@section('css-content')
    <!-- Thêm CSS nếu cần -->
    <style>
        .flash-sale-table th,
        .flash-sale-table td {
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

            <!-- Bộ lọc trạng thái Flash Sale dạng tab -->
            <div class="mb-3">
                <ul class="nav nav-tabs nav-tabs-custom nav-success" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') == '' ? 'active' : '' }}"
                            href="{{ route('flash-sale') }}">
                            <i class="ri-list-check-2"></i> Tất cả
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') == 'active' ? 'active' : '' }}"
                            href="{{ route('flash-sale', ['status' => 'active']) }}">
                            <i class="ri-flashlight-fill"></i> Đang diễn ra
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') == 'upcoming' ? 'active' : '' }}"
                            href="{{ route('flash-sale', ['status' => 'upcoming']) }}">
                            <i class="ri-timer-flash-line"></i> Sắp diễn ra
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') == 'ended' ? 'active' : '' }}"
                            href="{{ route('flash-sale', ['status' => 'ended']) }}">
                            <i class="ri-checkbox-circle-line"></i> Kết thúc
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Flash Sale Table -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Danh Mục Flash Sale</h5>
                            <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal" id="create-btn"
                                data-bs-target="#showModalflashsale"><i class="ri-add-line align-bottom me-1"></i>Thêm Flash
                                Sale</button>

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
                                            <th>Người tạo</th>
                                            <th>Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($flashSales as $key => $flashSale)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ date('H:i', strtotime($flashSale->start_date)) }} -
                                                    {{ date('H:i', strtotime($flashSale->end_date)) }}</td>
                                                <td>{{ $flashSale->discount }}%</td>
                                                <td>
                                                    @if ($flashSale->status == 'active')
                                                        <span class="badge bg-success">Đang diễn ra</span>
                                                    @elseif($flashSale->status == 'upcoming')
                                                        <span class="badge bg-warning">Sắp diễn ra</span>
                                                    @else
                                                        <span class="badge bg-secondary">Kết thúc</span>
                                                    @endif
                                                </td>
                                                <td>{{ date('d/m/Y H:i', strtotime($flashSale->start_date)) }}</td>
                                                <td>{{ date('d/m/Y H:i', strtotime($flashSale->end_date)) }}</td>
                                                <td><a href="{{route('users.show',$flashSale->user_id)}}">{{ $flashSale->name }}</a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('flash-sales.edit', $flashSale->id) }}"
                                                        class="btn btn-sm btn-warning"><i class="ri-edit-2-fill"></i></a>
                                                    <a href="{{ route('flash-sales.show', $flashSale->id) }}"
                                                        class="btn btn-sm btn-primary"><i class="ri-eye-line"></i></a>
                                                    <form action="{{ route('flash-sales.destroy', $flashSale->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        <button class="btn btn-sm btn-danger"><i
                                                                class="ri-delete-bin-6-fill"></i></button>
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
    <div class="modal fade" id="showModalflashsale" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title" id="exampleModalLabel">Flash Sale</h5>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>
                <form action="{{ route('flash-sales.create') }}" class="tablelist-form" autocomplete="off" method="POST">
                    @csrf
                    @method('POST')

                                            <input type="hidden" name="_form" value="flashsale">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="customername-field" class="form-label">Giá trị giảm</label>
                            <input type="number" id="customername-field" class="form-control" value="{{old('discount')}}" name="discount"
                                max="100" min="0" placeholder="" required />
                        </div>
                        <div class="mb-3">
                            <label for="check_date" class="form-label">Ngày Flash Sale</label>
                            <input type="date" id="check_date" name="check_date" class="form-control"
                                value="{{ old('check_date', now()->format('Y-m-d')) }}" required />
                            <div class="text-danger">
                                @error('check_date')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="slot_time" class="form-label">Khung giờ Flash Sale</label>
                            <select id="slot_time" name="slot_time" class="form-control" required>
                                <option value="">-- Chọn khung giờ --</option>
                                <option value="1" {{ old('slot_time') == 1 ? 'selected' : '' }}>00:00 – 02:00
                                </option>
                                <option value="2" {{ old('slot_time') == 2 ? 'selected' : '' }}>02:00 – 04:00
                                </option>
                                <option value="3" {{ old('slot_time') == 3 ? 'selected' : '' }}>04:00 – 06:00
                                </option>
                                <option value="4" {{ old('slot_time') == 4 ? 'selected' : '' }}>06:00 – 08:00
                                </option>
                                <option value="5" {{ old('slot_time') == 5 ? 'selected' : '' }}>08:00 – 10:00
                                </option>
                                <option value="6" {{ old('slot_time') == 6 ? 'selected' : '' }}>10:00 – 12:00
                                </option>
                                <option value="7" {{ old('slot_time') == 7 ? 'selected' : '' }}>12:00 – 14:00
                                </option>
                                <option value="8" {{ old('slot_time') == 8 ? 'selected' : '' }}>14:00 – 16:00
                                </option>
                                <option value="9" {{ old('slot_time') == 9 ? 'selected' : '' }}>16:00 – 18:00
                                </option>
                                <option value="10" {{ old('slot_time') == 10 ? 'selected' : '' }}>18:00 – 20:00
                                </option>
                                <option value="11" {{ old('slot_time') == 11 ? 'selected' : '' }}>20:00 – 22:00
                                </option>
                                <option value="12" {{ old('slot_time') == 12 ? 'selected' : '' }}>22:00 – 00:00
                                </option>
                            </select>
                            <div class="text-danger">
                                @error('slot_time')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-success" id="add-btn">Thêm mới</button>
                            <!-- <button type="button" class="btn btn-success" id="edit-btn">Update</button> -->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
@endsection

@section('js-content')
  <script>
     document.addEventListener('DOMContentLoaded', function() {
            @if ($errors->any() && old('_form') === 'flashsale')

                var myModal = new bootstrap.Modal(document.getElementById('showModalflashsale'));
                myModal.show();
            @endif
        });
  </script>
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
            html: `{!! session('error') !!}`, // Dùng `html:` thay vì `text:`
        });
    @endif
    </script>
@endsection
