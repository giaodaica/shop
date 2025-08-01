@extends('dashboard.layouts.layout')

@section('main-content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- Tiêu đề trang -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Tạo danh mục</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Danh mục</a></li>
                                <li class="breadcrumb-item active">Tạo danh mục</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Kết thúc tiêu đề -->

            <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">

                    {{-- Form nhập --}}
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">

                                {{-- Tên danh mục --}}
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tên danh mục</label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                        id="name" placeholder="Nhập tên danh mục">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Trạng thái --}}
                                <div class="mb-3">
                                    <label for="choices-publish-status-input" class="form-label">Trạng thái</label>

                                    <select name="status" id="choices-publish-status-input"
                                        class="form-select w-100 @error('status') is-invalid @enderror">
                                        <option value="" disabled {{ old('status') === null ? 'selected' : '' }}>
                                            -- Chọn trạng thái --
                                        </option>
                                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Không hoạt động
                                        </option>
                                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Hoạt động
                                        </option>
                                    </select>

                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary mt-3 w-100">Tạo danh mục</button>
                                <a href="{{ route('categories.index') }}" class="btn btn-secondary mt-2 w-100">Quay lại</a>
                            </div>
                        </div>
                    </div>

                    {{-- Upload ảnh --}}
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <label for="image" class="form-label">Ảnh danh mục</label>
                                <input type="file" name="image" id="image"
                                    class="form-control @error('image') is-invalid @enderror">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                {{-- Ảnh xem trước --}}
                                <img id="preview-image" src="#" alt="Ảnh xem trước" class="img-fluid mt-3"
                                    style="display: none; max-height: 180px;">
                            </div>
                        </div>
                    </div>


                </div>
            </form>
        </div>
    </div>

    {{-- Script xem trước ảnh trước khi upload --}}
@endsection
@section('js-content')
    <script>
        document.getElementById('image').addEventListener('change', function(e) {
            const [file] = e.target.files;
            if (file) {
                const preview = document.getElementById('preview-image');
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block'; // Hiện ảnh
            }
        });
    </script>
@endsection
