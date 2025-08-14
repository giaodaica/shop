@extends('dashboard.layouts.layout')

@section('main-content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Sản phẩm</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Thương mại điện tử</a></li>
                                <li class="breadcrumb-item active">Sản phẩm</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- thông báo -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-xl-12 col-lg-8">
                    <div>
                        <div class="card">

                            <!-- card-header -->
                            <div class="card-header border-0">
                                <div class="row g-4">

                                    <div class="col-sm">
                                        <form method="GET" action="{{ route('variants.index') }}" class="row g-2">
                                            <input type="hidden" name="status" value="{{ request('status') }}">
                                            <div class="col">
                                                <input type="text" name="keyword" class="form-control"
                                                    placeholder="Tìm tên biến thể..." value="{{ request('keyword') }}">
                                            </div>
                                            <div class="col">
                                                <select name="color_id" class="form-select">
                                                    <option value="">-- Màu sắc --</option>
                                                    @foreach ($colors as $color)
                                                        <option value="{{ $color->id }}"
                                                            {{ request('color_id') == $color->id ? 'selected' : '' }}>
                                                            {{ $color->color_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col">
                                                <select name="size_id" class="form-select">
                                                    <option value="">-- Size --</option>
                                                    @foreach ($sizes as $size)
                                                        <option value="{{ $size->id }}"
                                                            {{ request('size_id') == $size->id ? 'selected' : '' }}>
                                                            {{ $size->size_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-auto">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="ri-search-line"></i> Tìm kiếm
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Tabs -->
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <ul class="nav nav-tabs-custom card-header-tabs border-bottom-0" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link {{ request('status') === null || request('status') === 'active' ? 'active fw-semibold' : '' }}"
                                                    href="{{ route('variants.index') }}">Đang hoạt động</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link {{ request('status') === 'deleted' ? 'active fw-semibold' : '' }}"
                                                    href="{{ route('variants.index', ['status' => 'deleted']) }}">Đã
                                                    xóa</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link {{ request('status') === 'all' ? 'active fw-semibold' : '' }}"
                                                    href="{{ route('variants.index', ['status' => 'all']) }}">Tất cả</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Bảng -->
                            <div class="card-body table-responsive">
                                @if ($variants->isEmpty())
                                    <div class="noresult text-center">
                                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                            colors="primary:#405189,secondary:#0ab39c"
                                            style="width:75px;height:75px"></lord-icon>
                                        <h5 class="mt-2">Rất tiếc! Không tìm thấy kết quả</h5>
                                        <p class="text-muted">Chúng tôi đã tìm nhưng không thấy biến thể nào phù hợp.</p>
                                    </div>
                                @else
                                    <table class="table align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="min-width: 30px;">#</th>
                                                <th style="max-width: 150px;">Tên biến thể</th>
                                                <th>Ảnh</th>
                                                <th style="max-width: 120px;">Giá nhập</th>
                                                <th style="max-width: 120px;">Giá niêm yết</th>
                                                <th style="max-width: 120px;">Giá bán</th>
                                                <th style="max-width: 80px;">Kho</th>
                                                <th style="max-width: 150px;">Tên sản phẩm</th>
                                                <th style="max-width: 100px;">Size</th>
                                                <th style="max-width: 100px;">Màu sắc</th>
                                                <th>Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($variants as $index => $variant)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td class="fw-semibold text-break"
                                                        style="max-width: 150px; word-break: break-all; overflow-wrap: break-word;">
                                                        {{ $variant->name }}
                                                    </td>
                                                    <td>
                                                        <div class="avatar-sm bg-light rounded p-1 mb-3">
                                                            <img src="{{ $variant->variant_image_url ? asset($variant->variant_image_url) : asset('storage/no-image.png') }}"
                                                                alt="{{ $variant->name }}"
                                                                class="img-fluid d-block rounded" width="50"
                                                                height="50">
                                                        </div>
                                                    </td>
                                                    <td class="text-break" style="max-width: 120px; word-break: break-all;">
                                                        {{ number_format($variant->import_price, 0, ',', '.') }} đ
                                                    </td>
                                                    <td class="text-break" style="max-width: 120px; word-break: break-all;">
                                                        {{ number_format($variant->listed_price, 0, ',', '.') }} đ
                                                    </td>
                                                    <td class="text-break" style="max-width: 120px; word-break: break-all;">
                                                        {{ number_format($variant->sale_price, 0, ',', '.') }} đ
                                                    </td>
                                                    <td class="text-break" style="max-width: 80px; word-break: break-all;">
                                                        {{ $variant->stock }}
                                                    </td>
                                                    <td class="text-break" style="max-width: 150px; word-break: break-all;">
                                                        {{ $variant->product->name ?? 'Chưa có' }}
                                                    </td>
                                                    <td class="text-break"
                                                        style="max-width: 100px; word-break: break-all;">
                                                        {{ $variant->size->size_name ?? '-' }}
                                                    </td>
                                                    <td class="text-break"
                                                        style="max-width: 100px; word-break: break-all;">
                                                        {{ $variant->color->color_name ?? '-' }}
                                                    </td>
                                                    <td>
                                                        @if (request('status') == 'deleted')
                                                            <form action="{{ route('variants.restore', $variant->id) }}"
                                                                method="POST" class="restore-form">
                                                                @csrf
                                                                <button type="submit" class="dropdown-item text-success">
                                                                    <i
                                                                        class="ri-arrow-go-back-line align-bottom me-2 text-muted"></i>Khôi
                                                                    phục
                                                                </button>
                                                            </form>
                                                            <form
                                                                action="{{ route('variants.forceDelete', $variant->id) }}"
                                                                method="POST" class="force-delete-form d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger">
                                                                    <i
                                                                        class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>Xóa
                                                                    vĩnh viễn
                                                                </button>
                                                            </form>
                                                        @else
                                                            <div class="dropdown">
                                                                <button class="btn btn-soft-secondary btn-sm"
                                                                    type="button" data-bs-toggle="dropdown">
                                                                    <i class="ri-more-fill"></i>
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                    <li>
                                                                        <a class="dropdown-item"
                                                                            href="{{ route('variants.show', $variant->id) }}">
                                                                            <i
                                                                                class="ri-eye-fill align-bottom me-2 text-muted"></i>
                                                                            Xem
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item"
                                                                            href="{{ route('variants.edit', $variant->id) }}">
                                                                            <i
                                                                                class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                                                                            Sửa
                                                                        </a>
                                                                    </li>
                                                                    <li class="dropdown-divider"></li>
                                                                    <li>
                                                                        <form
                                                                            action="{{ route('variants.destroy', $variant->id) }}"
                                                                            method="POST" class="delete-form">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit"
                                                                                class="dropdown-item text-danger">
                                                                                <i
                                                                                    class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>
                                                                                Xóa
                                                                            </button>
                                                                        </form>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>

                            <!-- Phân trang -->
                            <div class="card-footer text-end">
                                {{ $variants->appends(request()->query())->links('pagination::bootstrap-5') }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
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
                            Design & Develop by Themesbrand
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
@endsection

@section('js-content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForms = document.querySelectorAll('.delete-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
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
                            form.submit();
                        }
                    });
                });
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            // Xóa mềm
            const deleteForms = document.querySelectorAll('.delete-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
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
                            form.submit();
                        }
                    });
                });
            });

            // Xóa vĩnh viễn
            const forceDeleteForms = document.querySelectorAll('.force-delete-form');
            forceDeleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Xóa vĩnh viễn?',
                        text: "Dữ liệu sẽ bị mất hoàn toàn và không thể khôi phục!",
                        icon: 'error',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Xóa vĩnh viễn',
                        cancelButtonText: 'Hủy'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
