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
                        <h4 class="mb-sm-0">Kho flash sale</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Hệ Thống</a></li>
                                <li class="breadcrumb-item active">Kho flash sale</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <!-- Flash Sale Table -->
            <div class="row">
                <div class="col-lg-12">
                    <!-- Flash Sale Table -->
                    <div class="card">
                        <div class="card-body">
                            <form action="{{route('create-items-flashsale',$flash_sale_id)}}" method="post">
                                @csrf
                                <div class="table-responsive">
                                    <table class="table table-bordered flash-sale-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th><input type="checkbox" id="checkAll" name="checkall" value="1"></th> {{-- Chọn tất cả --}}
                                                <th>#</th>
                                                <th>Tên sản phẩm</th>
                                                <th>Size</th>
                                                <th>Màu</th>
                                                <th>Ảnh</th>
                                                <th>Đã bán</th>
                                                <th>Tồn kho</th>
                                                <th>Số lượng Flash Sale</th>
                                                <th>Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($products->isEmpty())
                                                <tr>
                                                    <td colspan="10" class="text-center">Không có sản phẩm trong Flash
                                                        Sale</td>
                                                </tr>
                                            @else
                                                @foreach ($products as $index => $product)
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox"
                                                                name="flash_sale[{{ $product->id }}][selected][{{$product->product_id}}]"
                                                                class="check-item">
                                                        </td>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $product->name }}</td>
                                                        <td>{{ $product->size_name }}</td>
                                                        <td>{{ $product->color_name }}</td>
                                                        <td><img src="{{ asset($product->variant_image_url) }}"
                                                                alt="" width="100"></td>
                                                        <td>{{ $product->sold_quantity }}</td>
                                                        <td>{{ $product->stock }}</td>
                                                        <td>
                                                            <input type="number"
                                                                name="flash_sale[{{ $product->id }}][quantity]"
                                                                class="form-control form-control-sm w-50 mx-auto"
                                                                min="0" value="0">
                                                        </td>
                                                        <td>
                                                            <a href="{{ url('dashboard/remove-flash-sale/' . $product->id) }}"
                                                                class="btn btn-danger">Xóa</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif

                                        </tbody>
                                    </table>

                                </div>

                                <div class="text-end mt-3">
                                    <a href="{{route('flash-sales.show',$flash_sale_id)}}" class="btn btn-info">Quay Lại</a>
                                    <button type="submit" class="btn btn-success">Lưu Flash Sale</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
            <!--end row-->

        </div>
    </div>
@endsection

@section('js-content')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkAll = document.getElementById('checkAll');
            const checkboxes = document.querySelectorAll('.check-item');

            checkAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
            });
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
