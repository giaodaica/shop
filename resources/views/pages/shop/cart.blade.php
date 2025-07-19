@extends('layouts.layout')
@section('content')
    <!-- start section -->
    <section class="top-space-margin half-section bg-gradient-very-light-gray">
        <div class="container">
            <div class="row align-items-center justify-content-center"
                data-anime='{ "el": "childs", "translateY": [-15, 0], "opacity": [0,1], "duration": 300, "delay": 0, "staggervalue": 200, "easing": "easeOutQuad" }'>
                <div class="col-12 col-xl-8 col-lg-10 text-center position-relative page-title-extra-large">
                    <h1 class="alt-font fw-600 text-dark-gray mb-10px">Giỏ Hàng</h1>
                </div>
                <div class="col-12 breadcrumb breadcrumb-style-01 d-flex justify-content-center">
                    <ul>
                        <li><a href="{{ route('home') }}">Trang chủ</a></li>
                        <li>Giỏ Hàng</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- end section -->
    <!-- start section -->
<section class="pt-0">
    <div class="container">
        @if (session('success'))
            <div class="d-none toast-message" data-message="{{ session('success') }}" data-type="success"></div>
        @endif
        @if (session('error'))
            <div class="d-none toast-message" data-message="{{ session('error') }}" data-type="danger"></div>
        @endif
        @if (session('info'))
            <div class="d-none toast-message" data-message="{{ session('info') }}" data-type="info"></div>
        @endif

        <div id="toast-container" class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999;"></div>

        <div class="row align-items-start">
            <div class="col-lg-8 pe-50px md-pe-15px md-mb-50px xs-mb-35px">
                <div class="row align-items-center">
                    <div class="col-12">
                        <table class="table cart-products">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">
                                        <input type="checkbox" id="select-all-cart">
                                    </th>
                                    <th scope="col" class="alt-font fw-600">Sản phẩm</th>
                                    <th scope="col"></th>
                                    <th scope="col" class="alt-font fw-600">Giá</th>
                                    <th scope="col" class="alt-font fw-600">Số lượng</th>
                                    <th scope="col" class="alt-font fw-600">Tổng tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($cartItems->isEmpty())
                                    <tr><td colspan="6"><small class="text-danger d-block mt-1">Giỏ hàng trống kìa</small></td></tr>
                                @endif

                                @foreach($cartItems as $item)
                                    <tr class="cart-item-row">
                                        <td>
                                            <input type="checkbox" class="cart-item-checkbox" name="selected_ids[]" value="{{ $item->id }}" {{ in_array($item->id, $selectedIds ?? []) ? 'checked' : '' }}>
                                        </td>
                                        <td class="product-thumbnail">
                                            <a href="{{ route('home.show', $item->productVariant->product->slug) }}">
                                                <img class="cart-product-image" src="{{ $item->productVariant->variant_image_url }}" alt="">
                                            </a>
                                        </td>
                                        <td class="product-name">
                                            <a href="{{ route('home.show', $item->productVariant->product->slug) }}" class="truncate-text text-dark-gray fw-500 d-block lh-initial">
                                                {{ $item->productVariant->name }}
                                            </a>
                                            <span class="fs-14">Màu: {{ $item->productVariant->color->color_name ?? 'N/A' }}</span><br>
                                            <span class="fs-14">Size: {{ $item->productVariant->size->size_name ?? 'N/A' }}</span>
                                        </td>
                                        <td class="product-price" data-title="Price">
                                            {{ number_format($item->price_at_time, 0, ',', '.') }} đ
                                        </td>
                                      <td class="product-quantity" data-title="Quantity">
                                        <div class="quantity" data-id="{{ $item->id }}">
                                            <button type="button" class="qty-minus">-</button>
                                            <input 
                                                class="qty-text" 
                                                type="text" 
                                                value="{{ $item->quantity }}" 
                                                data-max="{{ $item->productVariant->stock_quantity }}"
                                                readonly>
                                            <button type="button" class="qty-plus">+</button>
                                        </div>
                                    </td>

                                        <td class="product-subtotal" data-title="Total">
                                            {{ number_format($item->quantity * $item->price_at_time, 0, ',', '.') }} đ
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row mt-20px">
                    <div class="col-xl-6 col-xxl-7 col-md-6">
                        <form action="{{ route('cart.applyVoucher') }}" method="POST" class="row g-2 align-items-center">
                            @csrf
                            <div class="col-8">
                                <select name="code" class="form-select" {{ $cartItems->isEmpty() ? 'disabled' : '' }} required>
                                    <option value="">-- Chọn mã giảm giá --</option>
                                    @foreach($availableVouchers as $voucher)
                                        <option value="{{ $voucher->code }}" {{ session('voucher_code') == $voucher->code ? 'selected' : '' }}>
                                            @if ($voucher->type_discount === 'percent')
                                                {{ $voucher->code }} - Giảm {{ $voucher->value }}% (tối đa {{ number_format($voucher->max_discount, 0, ',', '.') }} đ)
                                            @else
                                                {{ $voucher->code }} - Giảm {{ number_format($voucher->value, 0, ',', '.') }} đ
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4">
                                <button type="submit" class="btn btn-dark w-100">Áp dụng</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-xl-6 col-xxl-5 col-md-6 text-center text-md-end sm-mt-15px">
                      <button type="button" id="delete-selected-btn" class="btn btn-small border-1 btn-round-edge btn-transparent-light-gray text-transform-none me-15px lg-me-5px">
    Xóa sản phẩm đã chọn
</button>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="bg-very-light-gray border-radius-6px p-50px xl-p-30px lg-p-25px">
                    <span class="fs-26 alt-font fw-600 text-dark-gray mb-5px d-block">Tổng đơn hàng</span>
                    <table class="w-100 total-price-table">
                        <tbody>
                            <tr>
                                <th class="w-45 fw-600 text-dark-gray alt-font">Tạm tính</th>
                                <td class="text-dark-gray fw-600" id="subtotal">{{ number_format($subtotal, 0, ',', '.') }} đ</td>
                            </tr>
                            <tr class="max_discount">
                                <th class="fw-600 text-dark-gray alt-font">{{ session('voucher_code') ? 'Voucher' : 'Mã giảm giá' }}</th>
                                <td data-title="Voucher" id="voucher-row">
                                    @if(session('voucher_code'))
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span id="voucher-discount" class="text-danger fw-600">-{{ number_format(session('voucher_discount', 0), 0, ',', '.') }} đ</span><br>
                                                <small class="text-dark-gray">({{ session('voucher_code') }})</small>
                                            </div>
                                            <a href="{{ route('cart.removeVoucher') }}" class="text-danger ms-3">✕</a>
                                        </div>
                                    @else
                                        <span id="voucher-discount" class="text-muted">-0 đ</span>
                                    @endif
                                </td>
                            </tr>
                            <tr class="total-amount">
                                <th class="fw-600 text-dark-gray alt-font pb-0">Tổng tiền </th>
                                <td class="pb-0" data-title="Total">
                                    <h6 id="total" class="d-block fw-700 mb-0 text-dark-gray alt-font">{{ number_format($total, 0, ',', '.') }} đ</h6>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <a href="{{ route('home.checkout') }}"
                        class="btn btn-dark-gray btn-large btn-switch-text btn-round-edge btn-box-shadow w-100 mt-25px">
                            <span>
                                <span class="btn-double-text" data-text="Đặt Hàng">Đặt Hàng</span>
                            </span>
                        </a>
                </div>
            </div>
        </div>
    </div>

    
</section>

    <!-- end section -->
@endsection
@section('js-page-custom')

<script>
document.addEventListener('DOMContentLoaded', function () {
    const updateQuantityUrl = "{{ route('cart.updateQuantity') }}";
    const updateSelectedUrl = "{{ route('cart.ajaxUpdateSelected') }}";
    const csrfToken = "{{ csrf_token() }}";

    // Hàm cập nhật tổng tiền giỏ hàng
    function updateCartTotals() {
        let selectedItems = Array.from(document.querySelectorAll('.cart-item-checkbox:checked')).map(cb => {
            const row = cb.closest('.cart-item-row');
            const id = cb.value;
            const qty = row.querySelector('.qty-text')?.value || 1;
            return { id, qty };
        });

        fetch(updateSelectedUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ items: selectedItems })
        })
        .then(res => res.json())
        .then(res => {
            document.getElementById('subtotal').innerText = res.subtotal + ' đ';
            document.getElementById('voucher-discount').innerText = '-' + res.voucher_discount + ' đ';
            document.getElementById('total').innerText = res.total + ' đ';

            if (res.voucher_removed) {
                toastr.warning("Voucher bị xóa do không đủ điều kiện.");
            }
        })
        .catch(() => {
            toastr.error("Cập nhật giỏ hàng thất bại!");
        });
    }

    // Xử lý tăng/giảm số lượng
    document.querySelectorAll('.qty-plus, .qty-minus').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const parent = btn.closest('.quantity');
            const id = parent.dataset.id;
            const input = parent.querySelector('.qty-text');
            const max = parseInt(input.dataset.max) || 99999;
            let qty = parseInt(input.value) || 1;
            let action = btn.classList.contains('qty-plus') ? 'increase' : 'decrease';

            // Giới hạn tăng số lượng theo tồn kho
            if (action === 'increase' && qty >= max) {
                toastr.warning("Số lượng đã đạt tồn kho tối đa.");
                return;
            }
            if (action === 'increase') qty++;
            else if (qty > 1) qty--;

            fetch(updateQuantityUrl, {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ id: id, action: action })
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    input.value = res.quantity;
                    parent.closest('tr').querySelector('.product-subtotal').innerText = res.item_total;
                    document.getElementById('subtotal').innerText = res.subtotal;
                    document.getElementById('total').innerText = res.total;
                    updateCartTotals(); // Gọi lại để cập nhật tổng chi tiết
                } else {
                    toastr.error(res.message || 'Có lỗi xảy ra.');
                }
            })
            .catch(() => {
                toastr.error("Không thể cập nhật giỏ hàng.");
            });
        });
    });

    // Xử lý nhập số lượng bằng tay
    document.querySelectorAll('.qty-text').forEach(function (input) {
        input.addEventListener('input', function () {
            const max = parseInt(input.dataset.max) || 99999;
            let qty = parseInt(input.value) || 1;

            if (qty > max) {
                toastr.warning("Vượt quá tồn kho. Đã đặt lại bằng số lượng tồn kho.");
                input.value = max;
            } else if (qty < 1) {
                input.value = 1;
            }

            updateCartTotals();
        });
    });

    // Xử lý chọn checkbox từng item hoặc tất cả
    document.querySelectorAll('.cart-item-checkbox, #select-all-cart').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            const isSelectAll = this.id === 'select-all-cart';
            if (isSelectAll) {
                const checked = this.checked;
                document.querySelectorAll('.cart-item-checkbox').forEach(cb => cb.checked = checked);
            }
            updateCartTotals();
        });
    });
});
</script>




  <script>

document.addEventListener('DOMContentLoaded', function() {

    const selectAllDesktop = document.getElementById('select-all-cart');

    const selectAllMobile = document.getElementById('select-all-cart-mobile');

    const itemCheckboxes = document.querySelectorAll('.cart-item-checkbox:not(#select-all-cart):not(#select-all-cart-mobile)');

    function setAllCheckboxes(checked) {
        itemCheckboxes.forEach(cb => cb.checked = checked);
    }


    if (selectAllDesktop) {
        selectAllDesktop.addEventListener('change', function() {
            setAllCheckboxes(this.checked);
            if (selectAllMobile) selectAllMobile.checked = this.checked;
        });
    }


    if (selectAllMobile) {
        selectAllMobile.addEventListener('change', function() {
            setAllCheckboxes(this.checked);
            if (selectAllDesktop) selectAllDesktop.checked = this.checked;
        });
    }


    itemCheckboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            const allChecked = Array.from(itemCheckboxes).every(c => c.checked);
            if (selectAllDesktop) selectAllDesktop.checked = allChecked;
            if (selectAllMobile) selectAllMobile.checked = allChecked;
        });
    });
});

</script>
<script>
document.getElementById('delete-selected-btn').addEventListener('click', function (e) {
    e.preventDefault();

    const selected = document.querySelectorAll('.cart-item-checkbox:checked');
    const ids = Array.from(selected).map(cb => cb.value);

    if (ids.length === 0) {
        showToast("Vui lòng chọn sản phẩm để xoá.", "warning");
        return;
    }

    fetch("{{ route('cart.deleteSelected') }}", {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ ids })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast('Đã xoá sản phẩm thành công.', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('Xoá thất bại.', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Có lỗi xảy ra.', 'danger');
    });
});

</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.cart-item-checkbox');
    const selectAll = document.getElementById('select-all-cart');

    function updateCartSelected() {
        let selectedIds = Array.from(document.querySelectorAll('.cart-item-checkbox:checked'))
            .map(cb => cb.value);

        fetch("{{ route('cart.ajaxUpdateSelected') }}", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ ids: selectedIds })
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                document.getElementById('subtotal').innerText = res.subtotal;
                document.getElementById('total').innerText = res.total;

                const voucherRow = document.getElementById('voucher-row');
                const voucherDiscountEl = document.getElementById('voucher-discount');

                if (res.voucher_removed) {
                    voucherRow.innerHTML = `<span class="text-muted">Chưa áp dụng</span>`;
                } else if (voucherDiscountEl) {
                    voucherDiscountEl.innerText = '-' + res.voucher_discount;
                }
            }
        })
        .catch(err => {
            console.error('Lỗi khi cập nhật giỏ hàng:', err);
        });
    }

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            selectAll.checked = document.querySelectorAll('.cart-item-checkbox:checked').length === checkboxes.length;
            updateCartSelected();
        });
    });

    selectAll.addEventListener('change', function () {
        checkboxes.forEach(function (cb) {
            cb.checked = selectAll.checked;
        });
        updateCartSelected();
    });
});
</script>





<script>

function showToast(message, type = 'info') {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-bg-${type} border-0 show mb-2`;
    toast.role = 'alert';
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;
    container.appendChild(toast);

    setTimeout(() => {
        toast.classList.remove('show');
        toast.classList.add('hide');
        setTimeout(() => toast.remove(), 500);
    }, 4000);

    toast.querySelector('.btn-close').onclick = () => toast.remove();
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.toast-message').forEach(function(el) {
        const msg = el.getAttribute('data-message');
        const type = el.getAttribute('data-type') || 'info';
        if (msg) showToast(msg, type);
    });
});

</script>

@endsection
@section('cdn-custom')
    <style>

 .truncate-text {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
        max-width: 200px; 
    }


        .coupon-code-panel.d-block.d-sm-none::before {
            content: none;
        }
        @media (max-width: 575.98px) {
    .coupon-code-panel.mobile {
        padding-right: 1px !important;
    }
    .cart-item-checkbox {
        margin-right: 0 !important;
    }
}

    </style>
@endsection
