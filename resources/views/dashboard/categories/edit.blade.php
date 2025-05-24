@extends('dashboard.layouts.layout')

@section('main-content')
<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Edit Category</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="#">Ecommerce</a></li>
                            <li class="breadcrumb-item active">Edit Category</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Category Name</label>
                            <input type="text" class="form-control" value="Thời trang nữ">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category Description</label>
                            <div class="border rounded p-2" style="min-height: 100px;">
                                Danh mục các sản phẩm thời trang dành cho nữ giới như váy, áo, phụ kiện và giày dép.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Slug</label>
                            <input type="text" class="form-control" value="thoi-trang-nu">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select">
                                <option>Inactive</option>
                                <option selected>Active</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Category image -->
                <div class="card">
                    <div class="card-body text-center">
                        <label class="form-label">Category Image</label>
                        <div>
                            <img src="https://via.placeholder.com/150/FF69B4/FFFFFF?text=Fashion" alt="Category Image" class="img-fluid rounded">
                        </div>
                    </div>
                </div>

                <!-- Extra info -->
                <div class="card">
                    <div class="card-body">
                        <label class="form-label">Short Description</label>
                        <textarea class="form-control" rows="3">Danh mục nổi bật cho các sản phẩm thời trang nữ cao cấp.</textarea>
                    </div>
                </div>

                <a href="#" class="btn btn-secondary mt-3 w-100">Quay lại</a>
            </div>
        </div>
    </div>
</div>


@endsection
