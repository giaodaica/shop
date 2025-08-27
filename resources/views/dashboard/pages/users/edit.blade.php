@extends('dashboard.layouts.layout')

@section('main-content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Chỉnh sửa người dùng</h5>
                        </div>
                        <form action="{{ route('users.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="card-body">
                                {{-- Hiển thị lỗi chung --}}
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <strong>Đã có lỗi xảy ra:</strong>
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-lg-6">
                                        {{-- Họ tên --}}
                                        <div class="mb-3">
                                            <label for="user-name" class="form-label">Họ tên</label>
                                            <input type="text" id="user-name" name="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                value="{{ old('name', $user->name) }}">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Email --}}
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                                class="form-control @error('email') is-invalid @enderror" id="email">

                                            @error('email')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        {{-- Số điện thoại --}}
                                        <div class="mb-3">
                                            <label for="user-phone" class="form-label">Số điện thoại</label>
                                            <input type="text" id="user-phone" name="default_phone"
                                                class="form-control @error('default_phone') is-invalid @enderror"
                                                value="{{ old('default_phone', $user->default_phone) }}">
                                            @error('default_phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Địa chỉ --}}
                                        <div class="mb-3">
                                            <label for="user-address" class="form-label">Địa chỉ</label>
                                            <input type="text" id="user-address" name="default_address"
                                                class="form-control @error('default_address') is-invalid @enderror"
                                                value="{{ old('default_address', $user->default_address) }}">
                                            @error('default_address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Mật khẩu --}}
                                        <div class="mb-3">
                                            <label for="user-password" class="form-label">Mật khẩu mới</label>
                                            <input type="password" id="user-password" name="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                {{ $user->id !== auth()->id() ? 'disabled' : '' }}
                                                placeholder="{{ $user->id === auth()->id() ? 'Nhập mật khẩu mới (để trống nếu không đổi)' : 'Không thể thay đổi mật khẩu người khác' }}">
                                            @if ($user->id !== auth()->id())
                                                <small class="text-warning">
                                                    <i class="ri-information-line me-1"></i>
                                                    Bạn không thể thay đổi mật khẩu của người khác
                                                </small>
                                            @else
                                                <small class="text-muted">Để trống nếu không muốn thay đổi mật khẩu</small>
                                            @endif
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Xác nhận mật khẩu --}}
                                        <div class="mb-3">
                                            <label for="user-password-confirmation" class="form-label">Xác nhận mật khẩu mới</label>
                                            <input type="password" id="user-password-confirmation" name="password_confirmation"
                                                class="form-control"
                                                {{ $user->id !== auth()->id() ? 'disabled' : '' }}
                                                placeholder="{{ $user->id === auth()->id() ? 'Nhập lại mật khẩu mới' : 'Không thể thay đổi mật khẩu người khác' }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        {{-- Chọn Role --}}
                                        @if (isset($roles) && $roles->count() > 0)
                                            <div class="mb-3">
                                                <label for="user-role" class="form-label">Vai trò</label>
                                                <select id="user-role" name="role_id" class="form-select @error('role_id') is-invalid @enderror" 
                                                    {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                                    <option value="">-- Chọn vai trò --</option>
                                                    @foreach ($roles as $role)
                                                        <option value="{{ $role->id }}" 
                                                            {{ old('role_id', $user->roles->first() ? $user->roles->first()->id : '') == $role->id ? 'selected' : '' }}>
                                                            {{ $role->name }}
                                                            @if ($role->description)
                                                                - {{ $role->description }}
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @if ($user->id === auth()->id())
                                                    <small class="text-warning">
                                                        <i class="ri-information-line me-1"></i>
                                                        Bạn không thể thay đổi vai trò của chính mình
                                                    </small>
                                                @else
                                                    <small class="text-muted">Chọn vai trò cho người dùng này</small>
                                                @endif
                                                @error('role_id')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        @else
                                            <div class="mb-3">
                                                <div class="alert alert-info">
                                                    <i class="ri-information-line me-2"></i>
                                                    Không có vai trò nào được định nghĩa. Vui lòng chạy seeder để tạo roles.
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Hidden field để gửi vai trò hiện tại khi select bị disable --}}
                                        @if ($user->id === auth()->id())
                                            <input type="hidden" name="role_id" value="{{ $user->roles->first() ? $user->roles->first()->id : '' }}">
                                        @endif

                                        {{-- Hidden field để gửi mật khẩu hiện tại khi input bị disable --}}
                                        @if ($user->id !== auth()->id())
                                            <input type="hidden" name="password" value="">
                                            <input type="hidden" name="password_confirmation" value="">
                                        @endif
                                    </div>
                                </div>

                                {{-- Hidden fields --}}
                                <input type="hidden" name="rank" value="">
                                <input type="hidden" name="point" value="">
                                <input type="hidden" name="total_spent" value="">
                            </div>

                            <div class="card-footer text-end">
                                <a href="{{ route('users.index') }}" class="btn btn-secondary">Quay lại</a>
                                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
