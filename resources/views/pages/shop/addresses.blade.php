@extends('layouts.layout')
@section('content')
    <!-- start section -->
    <section class="top-space-margin half-section bg-gradient-very-light-gray">
        <div class="container">
            <div class="row align-items-center justify-content-center" data-anime='{ "el": "childs", "translateY": [-15, 0], "opacity": [0,1], "duration": 300, "delay": 0, "staggervalue": 200, "easing": "easeOutQuad" }'>
                <div class="col-12 col-xl-8 col-lg-10 text-center position-relative page-title-extra-large">
                    <h1 class="alt-font fw-600 text-dark-gray mb-10px">Quản lý địa chỉ giao hàng</h1>
                </div>
                <div class="col-12 breadcrumb breadcrumb-style-01 d-flex justify-content-center">
                    <ul>
                        <li><a href="{{ route('home') }}">Trang chủ</a></li>
                        <li>Địa chỉ giao hàng</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- end section -->

    <!-- start section -->
    <section class="pt-0">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Danh sách địa chỉ -->
                    <div class="mb-5">
                        <h3 class="alt-font fw-600 text-dark-gray mb-4">Địa chỉ của bạn</h3>
                        
                        @if($addresses->count() > 0)
                            <div class="row">
                                @foreach($addresses as $address)
                                    <div class="col-md-6 mb-4">
                                        <div class="card border h-100">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <h6 class="card-title mb-0 fw-600">{{ $address->name }}</h6>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item" href="#" onclick="editAddress({{ $address->id }}, '{{ $address->name }}', '{{ $address->phone }}', '{{ $address->province_code }}', '{{ $address->ward_code }}', '{{ $address->address }}')">
                                                                <i class="fas fa-edit me-2"></i>Sửa
                                                            </a></li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteAddress({{ $address->id }})">
                                                                <i class="fas fa-trash me-2"></i>Xóa
                                                            </a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <p class="card-text mb-2">
                                                    <i class="fas fa-map-marker-alt me-2 text-muted"></i>
                                                    {{ $address->address }}, {{ $address->ward->name ?? '' }}, {{ $address->province->name ?? '' }}
                                                </p>
                                                <p class="card-text mb-0">
                                                    <i class="fas fa-phone me-2 text-muted"></i>
                                                    {{ $address->phone }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Bạn chưa có địa chỉ giao hàng nào</h5>
                                <p class="text-muted">Thêm địa chỉ để có thể đặt hàng dễ dàng hơn</p>
                            </div>
                        @endif
                    </div>

                    <!-- Form thêm/sửa địa chỉ -->
                    <div class="card border">
                        <div class="card-header bg-light">
                            <h5 class="mb-0 fw-600" id="formTitle">Thêm địa chỉ mới</h5>
                        </div>
                        <div class="card-body">
                            <form id="addressForm" action="{{ route('addresses.store') }}" method="POST" novalidate>
                                @csrf
                                <input type="hidden" id="addressId" name="address_id">
                                <input type="hidden" id="isEdit" name="_method" value="POST">
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Tên người nhận <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name">
                                        <div class="invalid-feedback">Vui lòng nhập tên người nhận.</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="phone" name="phone">
                                        <div class="invalid-feedback">Vui lòng nhập số điện thoại hợp lệ.</div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="province_code" class="form-label">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                                        <select class="form-control" id="province_code" name="province_code">
                                            <option value="">Chọn tỉnh/thành phố</option>
                                            @foreach($provinces as $province)
                                                <option value="{{ $province->province_code }}">{{ $province->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">Vui lòng chọn tỉnh/thành phố.</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="ward_code" class="form-label">Xã/Phường <span class="text-danger">*</span></label>
                                        <select class="form-control" id="ward_code" name="ward_code" disabled>
                                            <option value="">Chọn xã/phường</option>
                                        </select>
                                        <div class="invalid-feedback">Vui lòng chọn xã/phường.</div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="address" class="form-label">Địa chỉ chi tiết (số nhà, đường,...) <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                                    <div class="invalid-feedback">Vui lòng nhập địa chỉ chi tiết.</div>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-dark-gray" id="submitBtn">
                                        <i class="fas fa-plus me-1"></i>Thêm địa chỉ
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="resetForm()" id="cancelBtn" style="display: none;">
                                        Hủy
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        @php
                            $from = request('from', 'checkout'); // mặc định là checkout nếu không có
                        @endphp
                        @if($from === 'info')
                            <a href="{{ route('home.info') }}" class="btn btn-outline-dark-gray" style="display: inline-flex !important; align-items: center !important; white-space: nowrap !important;">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại
                            </a>
                        @else
                            <a href="{{ route('home.checkout') }}" class="btn btn-outline-dark-gray" style="display: inline-flex !important; align-items: center !important; white-space: nowrap !important;">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end section -->

    <script>
        // Load wards khi chọn province
        document.getElementById('province_code').addEventListener('change', function() {
            const provinceCode = this.value;
            const wardSelect = document.getElementById('ward_code');
            
            if (provinceCode) {
                fetch(`/addresses/wards?province_code=${provinceCode}`)
                    .then(response => response.json())
                    .then(data => {
                        wardSelect.innerHTML = '<option value="">Chọn xã/phường</option>';
                        data.forEach(ward => {
                            wardSelect.innerHTML += `<option value="${ward.ward_code}">${ward.name}</option>`;
                        });
                        wardSelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        wardSelect.innerHTML = '<option value="">Có lỗi xảy ra</option>';
                    });
            } else {
                wardSelect.innerHTML = '<option value="">Chọn xã/phường</option>';
                wardSelect.disabled = true;
            }
        });

        function editAddress(id, name, phone, provinceCode, wardCode, address) {
            document.getElementById('formTitle').textContent = 'Sửa địa chỉ';
            document.getElementById('addressId').value = id;
            document.getElementById('name').value = name;
            document.getElementById('phone').value = phone;
            document.getElementById('address').value = address;
            document.getElementById('isEdit').value = 'PUT';
            document.getElementById('addressForm').action = `/addresses/${id}`;
            document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save me-1"></i>Cập nhật';
            document.getElementById('cancelBtn').style.display = 'inline-block';
            
            // Set province và load wards
            const provinceSelect = document.getElementById('province_code');
            provinceSelect.value = provinceCode;
            
            if (provinceCode) {
                fetch(`/addresses/wards?province_code=${provinceCode}`)
                    .then(response => response.json())
                    .then(data => {
                        const wardSelect = document.getElementById('ward_code');
                        wardSelect.innerHTML = '<option value="">Chọn xã/phường</option>';
                        data.forEach(ward => {
                            const selected = ward.ward_code === wardCode ? 'selected' : '';
                            wardSelect.innerHTML += `<option value="${ward.ward_code}" ${selected}>${ward.name}</option>`;
                        });
                        wardSelect.disabled = false;
                    });
            }
        }

        function resetForm() {
            document.getElementById('formTitle').textContent = 'Thêm địa chỉ mới';
            document.getElementById('addressId').value = '';
            document.getElementById('name').value = '';
            document.getElementById('phone').value = '';
            document.getElementById('address').value = '';
            document.getElementById('province_code').value = '';
            document.getElementById('ward_code').innerHTML = '<option value="">Chọn xã/phường</option>';
            document.getElementById('ward_code').disabled = true;
            document.getElementById('isEdit').value = 'POST';
            document.getElementById('addressForm').action = '{{ route("addresses.store") }}';
            document.getElementById('submitBtn').innerHTML = '<i class="fas fa-plus me-1"></i>Thêm địa chỉ';
            document.getElementById('cancelBtn').style.display = 'none';

            // Clear validation states
            document.querySelectorAll('#addressForm .is-invalid').forEach(function(el){
                el.classList.remove('is-invalid');
            });
        }

        function deleteAddress(id) {
            if (confirm('Bạn có chắc chắn muốn xóa địa chỉ này?')) {
                fetch(`/addresses/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(response => {
                    if (response.ok) {
                        location.reload();
                    } else {
                        alert('Có lỗi xảy ra khi xóa địa chỉ');
                    }
                });
            }
        }
        // Client-side validation
        (function(){
            const form = document.getElementById('addressForm');
            const nameInput = document.getElementById('name');
            const phoneInput = document.getElementById('phone');
            const provinceSelect = document.getElementById('province_code');
            const wardSelect = document.getElementById('ward_code');
            const addressInput = document.getElementById('address');

            function setInvalid(el, condition){
                if (condition) {
                    el.classList.add('is-invalid');
                } else {
                    el.classList.remove('is-invalid');
                }
            }

            function isPhoneValid(value){
                return /^0\d{9,10}$/.test((value||'').trim());
            }

            form.addEventListener('submit', function(e){
                let hasError = false;
                const nameVal = nameInput.value.trim();
                const phoneVal = phoneInput.value.trim();
                const provinceVal = provinceSelect.value;
                const wardVal = wardSelect.value;
                const addressVal = addressInput.value.trim();

                setInvalid(nameInput, nameVal === '');
                hasError = hasError || (nameVal === '');

                setInvalid(phoneInput, !isPhoneValid(phoneVal));
                hasError = hasError || !isPhoneValid(phoneVal);

                setInvalid(provinceSelect, provinceVal === '');
                hasError = hasError || (provinceVal === '');

                setInvalid(wardSelect, wardVal === '');
                hasError = hasError || (wardVal === '');

                setInvalid(addressInput, addressVal === '');
                hasError = hasError || (addressVal === '');

                if (hasError) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            });

            [nameInput, phoneInput, provinceSelect, wardSelect, addressInput].forEach(function(el){
                el.addEventListener('input', function(){ setInvalid(el, false); });
                el.addEventListener('change', function(){ setInvalid(el, false); });
            });
        })();
    </script>
@endsection 