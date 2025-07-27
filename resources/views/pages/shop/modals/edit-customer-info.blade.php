<!-- Modal chỉnh sửa thông tin khách hàng -->
<div class="modal fade" id="editCustomerInfoModal" tabindex="-1" aria-labelledby="editCustomerInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg rounded-4 border-0">
            <div class="modal-header bg-light border-0 rounded-top-4">
                <h5 class="modal-title text-uppercase text-primary fw-bold" id="editCustomerInfoModalLabel">
                    <i class="bi bi-person-lines-fill me-2"></i> Chỉnh sửa thông tin
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>

            <div class="modal-body p-4">
                <form action="{{ route('update-profile') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Họ và tên -->
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Họ và tên</label>
                        <input type="text" class="form-control rounded-3 shadow-sm" id="name" name="name"
                               value="{{ Auth::user()->name }}"  {{ Auth::user()->name ? 'readonly' : '' }}>
                    </div>

                    <!-- Số điện thoại -->
                    <div class="mb-3">
                        <label for="default_phone" class="form-label fw-semibold">Số điện thoại</label>
                        <input type="number"
                               class="form-control rounded-3 shadow-sm"
                               id="default_phone"
                               name="default_phone"
                               value="{{ Auth::user()->default_phone }}"
                               placeholder="Nhập số điện thoại"
                              >
                        {{-- @if(Auth::user()->default_phone)
                            <small class="text-muted fst-italic">Bạn không thể thay đổi số điện thoại sau khi đã nhập.</small>
                        @endif --}}
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control rounded-3 shadow-sm" id="email" name="email"
                               value="{{ Auth::user()->email }}"  {{ Auth::user()->email ? 'readonly' : '' }}>
                    </div>

                    <!-- Buttons -->
                    <div class="text-end pt-3">
                        <button type="button" class="btn btn-outline-secondary me-2 px-4 rounded-pill no-hover" data-bs-dismiss="modal">
                            Hủy
                        </button>
                        <button type="submit" class="btn btn-primary px-4 rounded-pill shadow-sm no-hover">
                            Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .no-hover:hover {
        background-color: inherit !important;
        border-color: inherit !important;
        color: inherit !important;
    }
    .btn-primary.no-hover:hover {
        background-color: #0d6efd !important;
        border-color: #0d6efd !important;
        color: #fff !important;
    }
    .btn-outline-secondary.no-hover:hover {
        background-color: transparent !important;
        border-color: #6c757d !important;
        color: #6c757d !important;
    }
</style>
