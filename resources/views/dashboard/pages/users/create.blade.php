<div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title" id="exampleModalLabel">Thêm người quản trị</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <form class="user-form" method="POST" action="{{ route('users.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-lg-12">
                            <label for="user-name" class="form-label">Họ tên</label>
                            <input type="text" id="user-name" name="name"
                                class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                >
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-12">
                            <label for="user-email" class="form-label">Email</label>
                            <input type="email" id="user-email" name="email"
                                class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                                >
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-6">
                            <label for="user-password" class="form-label">Mật khẩu</label>
                            <input type="password" id="user-password" name="password"
                                class="form-control @error('password') is-invalid @enderror" >
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-6">
                            <label for="user-password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                            <input type="password" id="user-password_confirmation" name="password_confirmation"
                                class="form-control" >
                        </div>

                        <div class="col-lg-6">
                            <label for="user-phone" class="form-label">Số điện thoại</label>
                            <input type="text" id="user-phone" name="default_phone"
                                class="form-control @error('default_phone') is-invalid @enderror"
                                value="{{ old('default_phone') }}">
                            @error('default_phone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-6">
                            <label for="user-address" class="form-label">Địa chỉ</label>
                            <input type="text" id="user-address" name="default_address"
                                class="form-control @error('default_address') is-invalid @enderror"
                                value="{{ old('default_address') }}">
                            @error('default_address')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Chọn Role --}}
                        @if (isset($roles) && $roles->count() > 0)
                            <div class="col-lg-12">
                                <label for="user-role" class="form-label">Vai trò</label>
                                <select id="user-role" name="role_id" class="form-select @error('role_id') is-invalid @enderror">
                                    <option value="">-- Chọn vai trò --</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                            @if ($role->description)
                                                - {{ $role->description }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Chọn vai trò cho người dùng này</small>
                                @error('role_id')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        @else
                            <div class="col-lg-12">
                                <div class="alert alert-info">
                                    <i class="ri-information-line me-2"></i>
                                    Không có vai trò nào được định nghĩa. Vui lòng chạy seeder để tạo roles.
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-success">Lưu</button>
                </div>
            </form>
          
        </div>
    </div>
</div>

<style>
/* Đảm bảo modal hiển thị đúng cách */
.modal-backdrop {
    z-index: 1040;
}

.modal {
    z-index: 1050;
}

/* Xóa backdrop khi modal bị ẩn */
.modal:not(.show) + .modal-backdrop {
    display: none !important;
}

/* Đảm bảo body không bị khóa */
body:not(.modal-open) {
    overflow: auto !important;
    padding-right: 0 !important;
}
</style>

@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modalElement = document.getElementById('showModal');
            let modal = null;
            
            // Function để cleanup modal hoàn toàn
            function cleanupModal() {
                // Xóa backdrop thủ công nếu cần
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) {
                    backdrop.remove();
                }
                
                // Xóa class modal-open khỏi body
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
                
                // Reset form
                const form = modalElement.querySelector('form');
                if (form) {
                    form.reset();
                    // Xóa tất cả class is-invalid
                    form.querySelectorAll('.is-invalid').forEach(input => {
                        input.classList.remove('is-invalid');
                    });
                    // Xóa tất cả error messages
                    form.querySelectorAll('.invalid-feedback, .text-danger').forEach(error => {
                        error.remove();
                    });
                }
                
                // Xóa tất cả class show và fade
                modalElement.classList.remove('show', 'fade');
                modalElement.style.display = 'none';
                modalElement.setAttribute('aria-hidden', 'true');
            }
            
            // Function để đóng modal an toàn
            function closeModal() {
                if (modal) {
                    modal.hide();
                } else {
                    cleanupModal();
                }
            }
            
            // Khởi tạo modal
            try {
                modal = new bootstrap.Modal(modalElement, {
                    backdrop: 'static',
                    keyboard: false
                });
                
                // Hiển thị modal
                modal.show();
            } catch (error) {
                console.error('Error initializing modal:', error);
                // Fallback: hiển thị modal thủ công
                modalElement.style.display = 'block';
                modalElement.classList.add('show');
                modalElement.setAttribute('aria-hidden', 'false');
            }
            
            // Xử lý khi modal bị ẩn
            modalElement.addEventListener('hidden.bs.modal', function () {
                cleanupModal();
            });
            
            // Xử lý khi đóng modal bằng nút đóng
            const closeButtons = modalElement.querySelectorAll('[data-bs-dismiss="modal"]');
            closeButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    closeModal();
                });
            });
            
            // Xử lý khi click bên ngoài modal
            modalElement.addEventListener('click', function(e) {
                if (e.target === modalElement) {
                    closeModal();
                }
            });
            
            // Xử lý ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modalElement.classList.contains('show')) {
                    closeModal();
                }
            });
            
            // Xử lý khi submit form thành công
            const form = modalElement.querySelector('form');
            if (form) {
                form.addEventListener('submit', function() {
                    // Ẩn modal khi submit
                    setTimeout(() => {
                        closeModal();
                    }, 100);
                });
            }
        });
    </script>
@else
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modalElement = document.getElementById('showModal');
            let modal = null;
            
            // Function để cleanup modal hoàn toàn
            function cleanupModal() {
                // Xóa backdrop thủ công nếu cần
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) {
                    backdrop.remove();
                }
                
                // Xóa class modal-open khỏi body
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
                
                // Reset form
                const form = modalElement.querySelector('form');
                if (form) {
                    form.reset();
                }
            }
            
            // Function để đóng modal an toàn
            function closeModal() {
                if (modal) {
                    modal.hide();
                } else {
                    cleanupModal();
                }
            }
            
            // Khởi tạo modal
            try {
                modal = new bootstrap.Modal(modalElement, {
                    backdrop: 'static',
                    keyboard: false
                });
            } catch (error) {
                console.error('Error initializing modal:', error);
            }
            
            // Xử lý khi modal bị ẩn
            modalElement.addEventListener('hidden.bs.modal', function () {
                cleanupModal();
            });
            
            // Xử lý khi đóng modal bằng nút đóng
            const closeButtons = modalElement.querySelectorAll('[data-bs-dismiss="modal"]');
            closeButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    closeModal();
                });
            });
            
            // Xử lý khi click bên ngoài modal
            modalElement.addEventListener('click', function(e) {
                if (e.target === modalElement) {
                    closeModal();
                }
            });
            
            // Xử lý ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modalElement.classList.contains('show')) {
                    closeModal();
                }
            });
        });
    </script>
@endif
