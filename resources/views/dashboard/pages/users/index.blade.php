@extends('dashboard.layouts.layout')

@section('main-content')
    <div class="page-content">

        <div class="container-fluid">

            {{-- Tiêu đề và breadcrumb --}}
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Quản lý người dùng</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Bảng điều khiển</a></li>
                                <li class="breadcrumb-item active">Người dùng</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                </div>
            @endif

            @if (session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                </div>
            @endif
            {{-- Danh sách người dùng --}}
            <div class="row">
                <div class="col-lg-12">
                    <div class="card" id="userList">
                        <div class="card-header border-0">
                            <div class="row g-4 align-items-center">
                                <div class="col-sm-3">
                                    <div class="search-box">
                                        <input type="text" class="form-control search" placeholder="Tìm kiếm...">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>
                                <div class="col-sm-auto ms-auto">
                                    <div class="hstack gap-2">
                                        {{-- Nút xoá hàng loạt - ẩn ban đầu --}}
                                        <button class="btn btn-danger d-none" id="bulk-delete-button" data-bs-toggle="modal"
                                            data-bs-target="#deleteRecordModal">
                                            <i class="ri-delete-bin-2-line align-bottom me-1"></i> Xoá đã chọn
                                        </button>
                                        {{-- Nút thêm --}}
                                        <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal"
                                            data-bs-target="#showModal">
                                            <i class="ri-add-line align-bottom me-1"></i> Thêm người dùng
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Bảng danh sách --}}
                        <div class="card-body">
                            <div class="table-responsive table-card">
                                <table class="table align-middle" id="userTable">
                                    <thead class="table-light">
                                        <tr>
                                            {{-- <th style="width: 50px;">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="checkAll">
                                                </div>
                                            </th> --}}
                                            <th>Họ tên</th>
                                            <th>Email</th>
                                            <th>Điện thoại</th>

                                            <th>Vai trò</th>
                                            <th>Hành động</th>

                                        </tr>
                                    </thead>
                                    <tbody class="list form-check-all">
                                        @foreach ($users as $user)
                                            <tr>
                                                {{-- <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input user-checkbox" type="checkbox"
                                                            value="{{ $user->id }}">
                                                    </div>
                                                </td> --}}
                                                <td class="name">{{ $user->name }}</td>
                                                <td class="email">{{ $user->email }}</td>
                                                <td class="phone">{{ $user->default_phone ?? '-' }}</td>

                                                <td class="role">
                                                    @if ($user->role === 'admin')
                                                        Quản trị
                                                    @else
                                                        Khách hàng
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('users.show', $user->id) }}" class="text-primary">Chi
                                                        tiết</a> |
                                                    <a href="{{ route('users.edit', $user->id) }}" class="text-info">Sửa</a>
                                                    | @if ($user->status === 'active')
                                                        <a href="javascript:void(0);" class="text-warning lock-user-link"
                                                            data-user-id="{{ $user->id }}" data-bs-toggle="modal"
                                                            data-bs-target="#lockUserModal">
                                                            Khóa
                                                        </a>
                                                    @else
                                                        <a href="{{ route('users.unlock', $user->id) }}"
                                                            class="text-success"
                                                            onclick="event.preventDefault(); document.getElementById('unlock-user-{{ $user->id }}').submit();">
                                                            Mở
                                                        </a>
                                                        <form id="unlock-user-{{ $user->id }}"
                                                            action="{{ route('users.unlock', $user->id) }}" method="POST"
                                                            style="display: none;">
                                                            @csrf
                                                        </form>
                                                    @endif
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>


                                </table>
                            </div>

                            {{-- Modal Thêm người dùng --}}
                            @include('dashboard.pages.users.create')
                            <!-- Modal Khóa người dùng -->
                            <div class="modal fade" id="lockUserModal" tabindex="-1" aria-labelledby="lockUserLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="{{ route('users.lock') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="user_id" id="lock-user-id">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="lockUserLabel">Khóa tài khoản người dùng</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Đóng"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="reason" class="form-label">Lý do khóa</label>
                                                    <select class="form-select" name="lock_reason_id" required>
                                                        <option value="">-- Chọn lý do --</option>
                                                        @foreach ($lockReasons as $reason)
                                                            <option value="{{ $reason->id }}">{{ $reason->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="note" class="form-label">Ghi chú thêm (nếu có)</label>
                                                    <textarea class="form-control" name="note" rows="2" placeholder="Nhập ghi chú..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light"
                                                    data-bs-dismiss="modal">Huỷ</button>
                                                <button type="submit" class="btn btn-warning">Xác nhận khóa</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            {{-- Modal xác nhận xoá hàng loạt --}}
                            <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1"
                                aria-labelledby="deleteRecordLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close" id="btn-close"></button>
                                        </div>
                                        <div class="modal-body p-5 text-center">
                                            <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                                                colors="primary:#405189,secondary:#f06548" style="width:90px;height:90px">
                                            </lord-icon>
                                            <div class="mt-4 text-center">
                                                <h4 class="fs-semibold">Bạn có chắc chắn muốn xoá các người dùng đã chọn?
                                                </h4>
                                                {{-- <p class="text-muted fs-14 mb-4 pt-1">Dữ liệu sẽ không thể khôi phục.</p> --}}
                                                <div class="hstack gap-2 justify-content-center remove">
                                                    <button
                                                        class="btn btn-link link-success fw-medium text-decoration-none"
                                                        data-bs-dismiss="modal">
                                                        <i class="ri-close-line me-1 align-middle"></i> Huỷ
                                                    </button>
                                                    <button class="btn btn-danger" id="delete-selected">Xoá</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Phân trang --}}
                            <div class="mt-3">
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js-content')
    <script>
        const checkAll = document.getElementById('checkAll');
        const deleteButton = document.getElementById('bulk-delete-button');
        let deleteIds = []; // Biến dùng chung cho cả xoá 1 và xoá nhiều

        function updateDeleteButtonVisibility() {
            const checked = document.querySelectorAll('.user-checkbox:checked').length;
            deleteButton.classList.toggle('d-none', checked === 0);
        }

        checkAll.addEventListener('change', function() {
            document.querySelectorAll('.user-checkbox').forEach(cb => cb.checked = this.checked);
            updateDeleteButtonVisibility();
        });

        document.querySelectorAll('.user-checkbox').forEach(cb => {
            cb.addEventListener('change', updateDeleteButtonVisibility);
        });

        // === Xoá nhiều ===
        document.getElementById('delete-selected').addEventListener('click', function() {
            if (deleteIds.length === 0) return;

            fetch("{{ route('users.bulk-delete') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        ids: deleteIds
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) location.reload();
                    else alert("Đã có lỗi xảy ra.");
                });
        });

        // === Khi bấm nút xoá từng người dùng ===
        document.querySelectorAll('.delete-single-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.closest('form').dataset.id;
                deleteIds = [id]; // Gán id người dùng vào danh sách xoá
                const modal = new bootstrap.Modal(document.getElementById('deleteRecordModal'));
                modal.show();
            });
        });

        // === Khi bấm nút xoá nhiều ===
        deleteButton.addEventListener('click', function() {
            deleteIds = Array.from(document.querySelectorAll('.user-checkbox:checked')).map(cb => cb.value);
        });

        document.addEventListener('DOMContentLoaded', function() {
            const lockUserModal = document.getElementById('lockUserModal');
            if (lockUserModal) {
                lockUserModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const userId = button.getAttribute('data-user-id');
                    document.getElementById('lock-user-id').value = userId;
                });
            }
        });
    </script>
@endsection
