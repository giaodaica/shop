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
                                                   value="{{ old('name', $user->name) }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Email --}}
                                        <div class="mb-3">
                                            <label for="user-email" class="form-label">Email</label>
                                            <input type="email" id="user-email" name="email"
                                                   class="form-control @error('email') is-invalid @enderror"
                                                   value="{{ old('email', $user->email) }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
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
                                    </div>

                                    <div class="col-lg-6">
                                        {{-- Chọn Roles --}}
                                        @if(isset($roles) && $roles->count() > 0)
                                            <div class="mb-3">
                                                <label class="form-label">Phân quyền</label>
                                                <div class="border rounded p-3">
                                                    @foreach($roles as $role)
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="checkbox" 
                                                                   name="roles[]" 
                                                                   value="{{ $role->id }}" 
                                                                   id="role_{{ $role->id }}"
                                                                   @if($user->hasRole($role)) checked @endif>
                                                            <label class="form-check-label" for="role_{{ $role->id }}">
                                                                <strong>{{ $role->name }}</strong>
                                                                @if($role->description)
                                                                    <small class="text-muted d-block">{{ $role->description }}</small>
                                                                @endif
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <small class="text-muted">Chọn các vai trò cho người dùng này</small>
                                            </div>
                                        @else
                                            <div class="mb-3">
                                                <div class="alert alert-info">
                                                    <i class="ri-information-line me-2"></i>
                                                    Không có vai trò nào được định nghĩa. Vui lòng chạy seeder để tạo roles.
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Hiển thị permissions hiện tại --}}
                                        <div class="mb-3">
                                            <label class="form-label">Quyền hạn hiện tại</label>
                                            <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                                                @php
                                                    $userPermissions = $user->getAllPermissions()->groupBy('parent_id');
                                                @endphp
                                                @if($userPermissions->count() > 0)
                                                    @foreach($userPermissions as $parentId => $permissions)
                                                        @if($parentId === null)
                                                            <div class="mb-2">
                                                                <strong class="text-primary">Permissions chính:</strong>
                                                            </div>
                                                        @else
                                                            @php
                                                                $parentPermission = \Spatie\Permission\Models\Permission::find($parentId);
                                                            @endphp
                                                            <div class="mb-2">
                                                                <strong class="text-success">{{ $parentPermission ? $parentPermission->name : 'Unknown' }}:</strong>
                                                            </div>
                                                        @endif
                                                        <div class="ms-3 mb-2">
                                                            @foreach($permissions as $permission)
                                                                <span class="badge bg-light text-dark me-1 mb-1">{{ $permission->name }}</span>
                                                            @endforeach
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="text-muted">
                                                        <i class="ri-information-line me-1"></i>
                                                        Không có quyền hạn nào được gán.
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Hidden fields --}}
                                <input type="hidden" name="role" value="{{ $user->role }}">
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
