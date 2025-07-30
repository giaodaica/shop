@extends('dashboard.layouts.layout')

@section('css-content')
    <style>
        .form-label {
            font-weight: 600;
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
                        <h4 class="mb-sm-0">Cập nhật Flash Sale</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item">Hệ Thống</li>
                                <li class="breadcrumb-item">Flash Sale</li>
                                <li class="breadcrumb-item active">Cập nhật</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-lg-6">
                    <form action="{{ route('flash-sales.update', $flashSale->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="discount" class="form-label">Giá trị giảm (%)</label>
                            <input type="number" name="discount" id="discount" class="form-control"
                                value="{{ old('discount', $flashSale->discount) }}" min="0" max="100" required>
                            @error('discount')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="check_date" class="form-label">Ngày Flash Sale</label>
                            <input type="date" name="check_date" id="check_date" class="form-control"
                                value="{{ old('check_date', \Carbon\Carbon::parse($flashSale->start_date)->format('Y-m-d')) }}"
                                required>
                            @error('check_date')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="slot_time" class="form-label">Khung giờ</label>
                            <select name="slot_time" id="slot_time" class="form-control" required>
                                <option value="">-- Chọn khung giờ --</option>
                                @foreach([
                                    1 => '00:00 – 02:00',
                                    2 => '02:00 – 04:00',
                                    3 => '04:00 – 06:00',
                                    4 => '06:00 – 08:00',
                                    5 => '08:00 – 10:00',
                                    6 => '10:00 – 12:00',
                                    7 => '12:00 – 14:00',
                                    8 => '14:00 – 16:00',
                                    9 => '16:00 – 18:00',
                                    10 => '18:00 – 20:00',
                                    11 => '20:00 – 22:00',
                                    12 => '22:00 – 00:00',
                                ] as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ old('slot_time', $flashSale->slot_time) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('slot_time')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <a href="{{ route('flash-sale') }}" class="btn btn-secondary">Quay lại</a>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('js-content')
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
