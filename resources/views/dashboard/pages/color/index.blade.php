@extends('dashboard.layouts.layout')

@section('main-content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- Tiêu đề trang -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Màu</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Thương mại điện tử</a></li>
                                <li class="breadcrumb-item active">Màu</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Kết thúc tiêu đề -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
             @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="row">
                <div class="col-lg-12">
                    <div class="card" id="categoryList">
                        <div class="card-header border-0">
                            <div class="row align-items-center gy-3">
                                <div class="col-sm"></div>
                                <div class="col-sm-auto">
                                    <div class="d-flex gap-1 flex-wrap">
                                        <a href="{{ route('colors.create') }}" class="btn btn-success" id="addCategory-btn">
                                            <i class="ri-add-line align-bottom me-1"></i> Thêm màu
                                        </a>
                                        <button type="button" id="delete-selected" class="btn btn-danger">
                                            <i class="ri-delete-bin-5-fill align-bottom me-1"></i> Xóa đã chọn
                                        </button>

                                        {{-- Nút xóa nhiều chưa có logic --}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body pt-0">
                            <div class="table-responsive table-card mb-1">
                                <table class="table table-nowrap align-middle" id="colorTable">
                                    <thead class="text-muted table-light">
                                        <tr class="text-uppercase">
                                            <th scope="col" style="width: 25px;">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="checkAll"
                                                        value="option">
                                                </div>
                                            </th>
                                            <th class="sort" data-sort="id">Mã</th>
                                            <th class="sort" data-sort="color_name">Tên màu</th>
                                            <th class="sort" data-sort="created_at">Ngày tạo</th>
                                            <th class="sort" data-sort="action">Hành động</th>
                                            <th class="sort" data-sort="color_code">Mã màu</th>
                                        </tr>
                                    </thead>

                                    <tbody class="list form-check-all">
                                        @foreach ($colors as $color)
                                            <tr>
                                                <th scope="row">
                                                    <div class="form-check">
                                                        <input class="form-check-input color-checkbox" type="checkbox"
                                                            value="{{ $color->id }}">

                                                    </div>
                                                </th>
                                                <td class="id">
                                                    <a href="{{ route('colors.show', $color->id) }}"
                                                        class="fw-medium link-primary">COLOR{{ $color->id }}</a>
                                                </td>
                                                <td class="color_name">{{ $color->color_name }}</td>
                                                <td class="created_at">{{ $color->created_at->format('d/m/Y') }}</td>
                                                <td>
                                                    <ul class="list-inline hstack gap-2 mb-0">

                                                        <li class="list-inline-item edit" data-bs-toggle="tooltip"
                                                            data-bs-trigger="hover" data-bs-placement="top"
                                                            title="Chỉnh sửa">
                                                            <a href="{{ route('colors.edit', $color->id) }}"
                                                                class="text-primary d-inline-block">
                                                                <i class="ri-pencil-fill fs-16"></i>
                                                            </a>
                                                        </li>
                                                        <li class="list-inline-item" data-bs-toggle="tooltip"
                                                            data-bs-trigger="hover" data-bs-placement="top" title="Xóa">
                                                            <form action="{{ route('colors.destroy', $color->id) }}"
                                                                method="POST" class="delete-form" style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-link p-0 text-danger d-inline-block remove-item-btn"
                                                                    style="border:none; background:none;">
                                                                    <i class="ri-delete-bin-5-fill fs-16"></i>
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </td>
                                                <td>
                                                    <div
                                                        style="width: 30px; height: 30px; background-color: {{ $color->color_code }}; border-radius: 4px; border: 1px solid #ccc;">
                                                    </div>
                                                    <span>{{ $color->color_code }}</span>

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                @if ($colors->isEmpty())
                                    <div class="noresult text-center">
                                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                            colors="primary:#405189,secondary:#0ab39c"
                                            style="width:75px;height:75px"></lord-icon>
                                        <h5 class="mt-2">Rất tiếc! Không tìm thấy kết quả</h5>
                                        <p class="text-muted">Chúng tôi đã tìm nhưng không thấy màu nào phù hợp.</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Phân trang -->
                            <div class="d-flex justify-content-end">
                                <div class="pagination-wrap hstack gap-2">
                                    {{ $colors->links() }}
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <script>
                        document.write(new Date().getFullYear())
                    </script> © Velzon.
                </div>
                <div class="col-sm-6">
                    <div class="text-sm-end d-none d-sm-block">
                        Thiết kế & phát triển bởi Themesbrand
                    </div>
                </div>
            </div>
        </div>
    </footer>
@endsection
@section('js-content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForms = document.querySelectorAll('.delete-form');

            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault(); // Chặn submit mặc định

                    Swal.fire({
                        title: 'Bạn có chắc chắn?',
                        text: "Hành động này sẽ không thể hoàn tác!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Xóa',
                        cancelButtonText: 'Hủy'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // Chấp nhận xóa
                        }
                    });
                });
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const checkAll = document.getElementById('checkAll');
            const checkboxes = document.querySelectorAll('.color-checkbox');
            const deleteSelectedBtn = document.getElementById('delete-selected');

            // Chọn tất cả
            checkAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = checkAll.checked);
            });

            // Xóa nhiều
            deleteSelectedBtn.addEventListener('click', function() {
                const selectedIds = Array.from(checkboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value);

                if (selectedIds.length === 0) {
                    Swal.fire('Chưa chọn mục nào', 'Vui lòng chọn ít nhất một màu để xóa.', 'warning');
                    return;
                }

                Swal.fire({
                    title: 'Bạn có chắc chắn?',
                    text: "Hành động này sẽ không thể hoàn tác!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('{{ route('colors.deleteMultiple') }}', {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    ids: selectedIds
                                })
                            })
                            .then(response => {
                                if (response.ok) {
                                    location.reload();
                                } else {
                                    Swal.fire('Lỗi', 'Không thể xóa các màu đã chọn.', 'error');
                                }
                            })
                            .catch(() => Swal.fire('Lỗi', 'Có lỗi xảy ra khi xóa.', 'error'));
                    }
                });
            });
        });
    </script>
@endsection
