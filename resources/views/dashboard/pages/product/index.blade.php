@extends('dashboard.layouts.layout')

@section('main-content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Quản lý sản phẩm</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Thương mại điện tử</a></li>
                                <li class="breadcrumb-item active">Quản lý sản phẩm</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <!-- end page title -->
            <div class="row">
                <div class="col-xl-12 col-lg-8">
                    <div>
                        <div class="card">
                            <div class="card-header border-0">
                                <div class="row g-4">
                                    <div class="col-sm-auto">
                                        <div>
                                            <a href="{{ route('products.create') }}" class="btn btn-success"
                                                id="addproduct-btn">
                                                <i class="ri-add-line align-bottom me-1"></i> Thêm sản phẩm
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <form method="GET" action="{{ route('products.index') }}" class="row g-2">
                                            <input type="hidden" name="status" value="{{ $status }}">
                                            <div class="col">
                                                <input type="text" name="keyword" class="form-control"
                                                    placeholder="Tìm tên hoặc slug..." value="{{ request('keyword') }}">
                                            </div>
                                            <div class="col">
                                                <select name="category_id" class="form-select">
                                                    <option value="">-- Tất cả danh mục --</option>
                                                    @foreach ($categories as $cat)
                                                        <option value="{{ $cat->id }}"
                                                            {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                                            {{ $cat->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-auto">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="ri-search-line"></i> Tìm
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <ul class="nav nav-pills mb-3">
                                            <li class="nav-item">
                                                <a class="nav-link {{ $status == 'active' ? 'active' : '' }}"
                                                    href="{{ route('products.index', ['status' => 'active']) }}">
                                                    Đang hoạt động ({{ $totalActive }})
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link {{ $status == 'trashed' ? 'active' : '' }}"
                                                    href="{{ route('products.index', ['status' => 'trashed']) }}">
                                                    Đã xóa ({{ $totalTrashed }})
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link {{ $status == 'all' ? 'active' : '' }}"
                                                    href="{{ route('products.index', ['status' => 'all']) }}">
                                                    Tất cả ({{ $totalAll }})
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-auto">
                                        <div id="selection-element">
                                            <div class="my-n1 d-flex align-items-center text-muted">
                                                Select <div id="select-content" class="text-body fw-semibold px-1"></div>
                                                Result <button type="button" class="btn btn-link link-danger p-0 ms-3"
                                                    data-bs-toggle="modal" data-bs-target="#removeItemModal">Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if ($products->isEmpty())
                                <div class="card-body">
                                    <div class="noresult text-center">
                                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                            colors="primary:#405189,secondary:#0ab39c"
                                            style="width:75px;height:75px"></lord-icon>
                                        <h5 class="mt-2">Rất tiếc! Không tìm thấy kết quả</h5>
                                        <p class="text-muted">Chúng tôi đã tìm nhưng không thấy sản phẩm nào phù hợp.</p>
                                    </div>
                                </div>
                            @else
                                <!-- end card header -->
                                <div class="card-body">
                                    <table class="table align-middle table-nowrap mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th scope="col">#</th>

                                                <th scope="col">Tên sản phẩm</th>

                                                <th scope="col">Slug</th>
                                                <th scope="col">Danh mục</th>
                                                <th scope="col">Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($products as $index => $product)
                                                <tr>
                                                    <td>{{ $products->firstItem() + $index }}</td>

                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0 me-3">
                                                                <div class="avatar-sm bg-light rounded p-1"
                                                                    style="width: 100px; height: 100px; overflow: hidden;">
                                                                    <img src="{{ asset($product->image_url) }}"
                                                                        alt="{{ $product->name }}"
                                                                        class="img-fluid d-block">
                                                                </div>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <h5 class="fs-14 mb-1">
                                                                    <a href="{{ route('products.show', $product->id) }}"
                                                                        class="text-body text-truncate d-inline-block"
                                                                        style="max-width: 200px;"
                                                                        title="{{ $product->name }}">
                                                                        {{ $product->name }}
                                                                    </a>
                                                                </h5>
                                                                <p class="text-muted mb-0">Danh mục: <span
                                                                        class="fw-medium">{{ $product->category->name ?? 'Chưa có' }}</span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td class="text-truncate" style="max-width: 150px;"
                                                        title="{{ $product->slug }}">
                                                        {{ $product->slug }}
                                                    </td>

                                                    <td>{{ $product->category->name ?? 'Chưa có' }}</td>

                                                    <td>
                                                        @if ($product->deleted_at)
                                                            <!-- Nếu sản phẩm đã bị xóa mềm -->
                                                            <form action="{{ route('products.restore', $product->id) }}"
                                                                method="POST" class="restore-form d-inline">
                                                                @csrf
                                                                <button type="button"
                                                                    class="btn btn-success btn-sm btn-restore">Khôi
                                                                    phục</button>
                                                            </form>
                                                            <form
                                                                action="{{ route('products.forceDelete', $product->id) }}"
                                                                method="POST" class="force-delete-form d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm btn-force-delete">Xóa vĩnh
                                                                    viễn</button>
                                                            </form>
                                                        @else
                                                            <div class="dropdown">
                                                                <button class="btn btn-soft-secondary btn-sm"
                                                                    type="button" data-bs-toggle="dropdown"
                                                                    aria-expanded="false">
                                                                    <i class="ri-more-fill"></i>
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                    <li>
                                                                        <a class="dropdown-item"
                                                                            href="{{ route('products.show', $product->id) }}">
                                                                            <i
                                                                                class="ri-eye-fill align-bottom me-2 text-muted"></i>
                                                                            Xem
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a class="dropdown-item"
                                                                            href="{{ route('products.edit', $product->id) }}">
                                                                            <i
                                                                                class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                                                                            Sửa
                                                                        </a>
                                                                    </li>

                                                                    <li>
                                                                        <form
                                                                            action="{{ route('products.destroy', $product->id) }}"
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
                                                                    {{-- <li>
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('variants.create', $product->id) }}">
                                                                        <i
                                                                            class="ri-add-fill align-bottom me-2 text-muted"></i>
                                                                        Thêm biến thể
                                                                    </a>
                                                                </li> --}}
                                                                    <li>
                                                                        <a class="dropdown-item"
                                                                            href="{{ route('variants.index', ['product_id' => $product->id]) }}">
                                                                            <i
                                                                                class="ri-file-list-3-line align-bottom me-2 text-muted"></i>
                                                                            Xem biến thể
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {{-- Phân trang --}}
                                    <div class="mt-3">
                                        {{ $products->links() }}
                                    </div>
                                </div>
                                <!-- end card body -->
                            @endif
                        </div>
                        <!-- end card -->
                    </div>
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->

        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
    <div class="d-flex justify-content-center mt-4">
        {{ $products->appends(request()->query())->links() }}
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
                        Design & Develop by Themesbrand
                    </div>
                </div>
            </div>
        </div>
    </footer>
@endsection
@section('js-content')
<script src="{{ asset('admin/libs/nouislider/nouislider.min.js') }}"></script>
<script src="{{ asset('admin/libs/wnumb/wNumb.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('admin/libs/gridjs/gridjs.umd.js') }}"></script>
<script src="https://unpkg.com/gridjs/plugins/selection/dist/selection.umd.js"></script>

<script>
    // Ẩn alert sau 3 giây
    setTimeout(function () {
        let alert = document.querySelector('.alert');
        if (alert) {
            alert.classList.remove('show');
            alert.classList.add('hide');
        }
    }, 3000);

    document.addEventListener('DOMContentLoaded', function () {
        // Hàm xử lý confirm SweetAlert
        function handleConfirm(selector, options) {
            document.querySelectorAll(selector).forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const form = button.closest('form');

                    Swal.fire({
                        title: options.title,
                        text: options.text,
                        icon: options.icon,
                        showCancelButton: true,
                        confirmButtonText: options.confirmButtonText,
                        cancelButtonText: 'Hủy',
                        confirmButtonColor: options.confirmButtonColor || '#3085d6',
                        cancelButtonColor: '#d33'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        }

        // Xóa mềm
        handleConfirm('.btn-soft-delete', {
            title: 'Xác nhận xóa',
            text: 'Bạn có chắc muốn xóa sản phẩm này?',
            icon: 'warning',
            confirmButtonText: 'Xóa',
            confirmButtonColor: '#d33'
        });

        // Khôi phục
        handleConfirm('.btn-restore', {
            title: 'Xác nhận khôi phục',
            text: 'Bạn có chắc muốn khôi phục sản phẩm này?',
            icon: 'question',
            confirmButtonText: 'Khôi phục',
            confirmButtonColor: '#3085d6'
        });

        // Xóa vĩnh viễn
        handleConfirm('.btn-force-delete', {
            title: 'Xác nhận xóa vĩnh viễn',
            text: 'Hành động này không thể hoàn tác!',
            icon: 'error',
            confirmButtonText: 'Xóa vĩnh viễn',
            confirmButtonColor: '#d33'
        });
    });
</script>
@endsection
