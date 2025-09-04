@extends('layouts.layout')

@section('content')
    <section class="page-title-center-alignment cover-background top-space-padding">
        <div class="container">
            <div class="refund-header text-center mb-4">

                <h2 class="refund-title">Yêu cầu hoàn tiền</h2>
                <p class="refund-subtitle">Vui lòng điền thông tin để xử lý yêu cầu hoàn tiền của bạn</p>
            </div>
        </div>
    </section>

    <section class="py-0">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <!-- Main Card -->
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger refund-alert">
                                <div class="alert-icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="alert-content">
                                    <strong>Có lỗi xảy ra:</strong>
                                    <ul class="mb-0 mt-2">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <!-- Order Summary -->
                        <div class="order-summary mb-4">
                            <h5 class="summary-title">
                                <i class="fas fa-receipt me-2"></i>
                                Thông tin đơn hàng
                            </h5>
                            <div class="summary-content">
                                <div class="summary-row">
                                    <span>Mã đơn hàng:</span>
                                    <span class="fw-bold">#{{ $order->id }}</span>
                                </div>
                                <div class="summary-row">
                                    <span>Tổng tiền:</span>
                                    <span class="fw-bold text-primary">{{ number_format($refund->amount ?? 0) }} VNĐ</span>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Navigation -->
                        <div class="refund-tabs mb-4">
                            <div class="tabs-header">
                                <button class="tab-btn active" data-tab="stk">
                                    <i class="fas fa-university me-2"></i>
                                    Tài khoản ngân hàng
                                </button>
                                <button class="tab-btn" data-tab="qr">
                                    <i class="fas fa-qrcode me-2"></i>
                                    Mã QR
                                </button>
                            </div>
                        </div>

                        <!-- Form -->
                        <form id="refundForm" method="POST" action="{{ route('order.refund', $order->id) }}"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- STK Tab -->
                            <div class="tab-content active" id="stk-content">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-building me-2"></i>
                                        Ngân hàng
                                    </label>
                                    <select class="form-input" name="bank_code" id="bankSelect">
                                        <option value="">Chọn ngân hàng</option>
                                    </select>
                                    @error('bank_code')
                                        <div class="error-message">{{ $message }}</div>
                                    @else
                                        <div class="error-message"></div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-credit-card me-2"></i>
                                        Số tài khoản
                                    </label>
                                    <input type="text" class="form-input" name="account_number"
                                        placeholder="Nhập số tài khoản" />
                                    @error('account_number')
                                        <div class="error-message">{{ $message }}</div>
                                    @else
                                        <div class="error-message"></div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-user me-2"></i>
                                        Tên chủ thẻ
                                    </label>
                                    <input type="text" class="form-input" name="account_name"
                                        placeholder="Nhập tên chủ thẻ" />
                                    @error('account_name')
                                        <div class="error-message">{{ $message }}</div>
                                    @else
                                        <div class="error-message"></div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-dollar-sign me-2"></i>
                                        Số tiền hoàn (VNĐ)
                                    </label>
                                    <div class="amount-input-group">
                                        <input type="text" id="refundAmountDisplay"
                                            value="{{ number_format($refund->amount ?? 0, 0, ',', '.') }}" readonly>
                                        <input type="hidden" name="amount" id="refundAmountReal"
                                            value="{{ $refund->amount ?? 0 }}">

                                        {{-- <button type="button" class="edit-btn" id="editAmountStk">
                                            <i class="fas fa-edit"></i>
                                        </button> --}}
                                    </div>
                                    {{-- <small class="form-hint">Bạn có thể chỉnh sửa số tiền nếu không đúng với đơn
                                        hàng.</small> --}}
                                    @error('amount')
                                        <div class="error-message">{{ $message }}</div>
                                    @else
                                        <div class="error-message"></div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-comment me-2"></i>
                                        Lý do hoàn tiền
                                    </label>
                                    <textarea class="form-input" name="reason" rows="3" placeholder="Nhập lý do hoàn tiền..."></textarea>
                                    @error('reason')
                                        <div class="error-message">{{ $message }}</div>
                                    @else
                                        <div class="error-message"></div>
                                    @enderror
                                </div>
                            </div>

                            <!-- QR Tab -->
                            <div class="tab-content" id="qr-content">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-upload me-2"></i>
                                        Upload mã QR
                                    </label>
                                    <div class="file-upload-area" id="fileUploadArea">
                                        <input type="file" class="file-input" name="qr_image" id="qrImageInput"
                                            accept="image/*" />
                                        <div class="upload-content">
                                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                            <p class="upload-text">Kéo thả hoặc click để chọn file</p>
                                            <p class="upload-hint">Hỗ trợ: JPG, PNG, GIF (Max: 5MB)</p>
                                        </div>
                                    </div>
                                    @error('qr_image')
                                        <div class="error-message">{{ $message }}</div>
                                    @else
                                        <div class="error-message"></div>
                                    @enderror

                                    <div id="qrImagePreview" class="image-preview">
                                        <img id="previewImage" src="" alt="QR Preview" />
                                        <button type="button" class="remove-image" id="removeImage">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-dollar-sign me-2"></i>
                                        Số tiền hoàn (VNĐ)
                                    </label>
                                    <div class="amount-input-group">
                                        <input type="text" id="refundAmountDisplay"
                                            value="{{ number_format($refund->amount ?? 0, 0, ',', '.') }}" readonly>
                                        <input type="hidden" name="amount" id="refundAmountReal"
                                            value="{{ $refund->amount ?? 0 }}">

                                        {{-- <button type="button" class="edit-btn" id="editAmountStk">
                                            <i class="fas fa-edit"></i>
                                        </button> --}}
                                    </div>
                                    @error('amount')
                                        <div class="error-message">{{ $message }}</div>
                                    @else
                                        <div class="error-message"></div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="form-actions">
                                <button type="submit" class="submit-btn">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Gửi yêu cầu hoàn tiền
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <link href="{{ asset('assets/css/refund.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/refund/refund.js') }}"></script>

@endpush

<script>
    window.orderTotal = {{ $refund->amount ?? 0 }};
</script>
