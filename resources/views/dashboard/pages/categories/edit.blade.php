@extends('dashboard.layouts.layout')

@section('main-content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- Tiêu đề trang -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Chỉnh sửa danh mục</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="#">Ecommerce</a></li>
                                <li class="breadcrumb-item active">Chỉnh sửa danh mục</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Kết thúc tiêu đề -->

            <form action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                {{-- Tên danh mục --}}
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tên danh mục</label>
                                    <input type="text" id="name" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name') !== null ? old('name') : $category->name }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Trạng thái --}}
                                <div class="mb-3">
                                    <label for="choices-publish-status-input" class="form-label">Trạng thái</label>

                                    <select name="status" id="choices-publish-status-input"
                                        class="form-select w-100 @error('status') is-invalid @enderror" data-choices
                                        data-choices-search-false>
                                        <option value="" disabled {{ old('status') === null ? 'selected' : '' }}>
                                            -- Chọn trạng thái --
                                        </option>
                                        <option value="0"
                                            {{ old('status', $category->status) == 0 ? 'selected' : '' }}>Không hoạt động
                                        </option>
                                        <option value="1"
                                            {{ old('status', $category->status) == 1 ? 'selected' : '' }}>Hoạt động
                                        </option>
                                    </select>

                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Ảnh mới --}}
                                <div class="mb-3">
                                    <label for="image" class="form-label">Thay đổi ảnh</label>
                                    <input type="file" id="image" name="image"
                                        class="form-control @error('image') is-invalid @enderror">


                                    <small class="text-muted">Nếu không muốn thay đổi ảnh, bạn có thể bỏ qua</small>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-4">

                        </div>
                    </div>

                    <div class="col-lg-4">
                        <!-- Ảnh danh mục  -->
                        <div class="card">
                            <div class="card-body text-center">
                                <label class="form-label">Ảnh danh mục</label>
                                <div>
                                    <img id="preview-image"
                                        src="{{ $category->image ? asset($category->image) : 'https://via.placeholder.com/150?text=No+Image' }}"
                                        alt="{{ $category->name }}" class="img-fluid rounded"
                                        style="max-height: 235px; object-fit: cover;">
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('categories.index') }}" class="btn btn-secondary mt-3 w-100">Quay lại</a>
                        <button type="submit" class="btn btn-primary mt-2 w-100">Cập nhật</button>
                    </div>


                </div>
            </form>
        </div>
    </div>
@endsection
@section('js-content')
    <script>
        document.getElementById('image').addEventListener('change', function(event) {
            const [file] = event.target.files;
            const preview = document.getElementById('preview-image');

            if (file) {
                preview.src = URL.createObjectURL(file);
            }
        });
    </script>
@endsection
