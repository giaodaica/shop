@extends('dashboard.layouts.layout')

@section('main-content')
<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Edit Product</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="#">Ecommerce</a></li>
                            <li class="breadcrumb-item active">Edit Product</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <!-- Không cần form submit, chỉ hiển thị thông tin để chỉnh sửa -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Product Title</label>
                            <input type="text" class="form-control" value="Áo thun nam cotton 100%">
                        </div>
                        <div class="mb-3">
                            <label>Product Description</label>
                            <div class="border rounded p-2" style="min-height: 100px;">
                                Áo thun nam cotton 100%, thoáng mát, thấm hút mồ hôi. Thiết kế trẻ trung, phù hợp cho mọi hoạt động.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Manufacturer Name</label>
                            <input type="text" class="form-control" value="Công ty ABC">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Manufacturer Brand</label>
                            <input type="text" class="form-control" value="ABC Fashion">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stocks</label>
                            <input type="text" class="form-control" value="150">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price</label>
                            <input type="text" class="form-control" value="299000">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Discount (%)</label>
                            <input type="text" class="form-control" value="10">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Orders</label>
                            <input type="text" class="form-control" value="35">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Product image -->
                <div class="card">
                    <div class="card-body text-center">
                        <label class="form-label">Product Image</label>
                        <div>
                            <img src="https://via.placeholder.com/150" alt="Product Image" class="img-fluid rounded">
                        </div>
                    </div>
                </div>

                <!-- Category & Tags -->
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <input type="text" class="form-control" value="Thời trang nam">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tags</label>
                            <input type="text" class="form-control" value="Áo thun, Cotton, Nam">
                        </div>
                    </div>
                </div>

                <!-- Short Description -->
                <div class="card">
                    <div class="card-body">
                        <label class="form-label">Short Description</label>
                        <textarea class="form-control" rows="3">Áo thun đơn giản, dễ phối đồ, thích hợp mọi lứa tuổi.</textarea>
                    </div>
                </div>

                <a href="#" class="btn btn-secondary mt-3 w-100">Quay lại</a>
            </div>
        </div>
    </div>
</div>

@endsection
