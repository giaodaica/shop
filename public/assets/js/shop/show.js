document.addEventListener('DOMContentLoaded', function () {
    // Nếu có hash trên URL, active tab tương ứng
    if (window.location.hash) {
        let hash = window.location.hash;
        let tabLink = document.querySelector('a[href="' + hash + '"]');
        if (tabLink) {
            let tab = new bootstrap.Tab(tabLink);
            tab.show();
        }
    }

    // Khi click vào tab, cập nhật hash trên URL
    document.querySelectorAll('.nav-tabs .nav-link').forEach(function (tabLink) {
        tabLink.addEventListener('shown.bs.tab', function (e) {
            history.replaceState(null, null, e.target.getAttribute('href'));
        });
    });

    // Ẩn các review-item từ số 4 trở đi
    let reviews = document.querySelectorAll('.review-item');
    let showMoreBtn = document.querySelector('.btn-text');
    let showMoreBtnA = showMoreBtn ? showMoreBtn.closest('a') : null;
    let expanded = false;

    function collapseReviews() {
        reviews.forEach((item, idx) => {
            item.style.display = (idx > 2) ? 'none' : '';
        });
        if (showMoreBtn) showMoreBtn.textContent = 'Hiển thị thêm đánh giá';
        expanded = false;
    }

    function expandReviews() {
        reviews.forEach(item => item.style.display = '');
        if (showMoreBtn) showMoreBtn.textContent = 'Thu gọn đánh giá';
        expanded = true;
    }

    if (reviews.length > 3) {
        collapseReviews();
        if (showMoreBtnA) showMoreBtnA.style.display = '';
    } else if (showMoreBtnA) {
        showMoreBtnA.style.display = 'none';
    }

    if (showMoreBtnA) {
        showMoreBtnA.addEventListener('click', function (e) {
            e.preventDefault();
            if (!expanded) {
                expandReviews();
            } else {
                collapseReviews();
                // Scroll về vị trí review-list-container nếu cần
                document.getElementById('review-list-container').scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    }

    // Toast notification for all messages
    document.querySelectorAll('.toast-message').forEach(function (el) {
        const msg = el.getAttribute('data-message');
        const type = el.getAttribute('data-type') || 'info';
        if (msg) showToast(msg, type);
    });

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

    // Function để thay đổi ảnh sản phẩm khi chọn màu
    function updateProductImage(colorId) {
        if (window.colorImageMap && window.colorImageMap[colorId]) {
            const images = window.colorImageMap[colorId];

            // Lấy ảnh đầu tiên từ mảng ảnh của màu này
            const newImageUrl = Array.isArray(images) ? images[0] : images;

            const productSlider = document.querySelector('.product-image-slider .swiper-wrapper');

            if (productSlider) {
                // Tìm tất cả ảnh trong slider
                const allImages = productSlider.querySelectorAll('.swiper-slide img');

                // Preload ảnh mới trước khi hiển thị
                const preloadImage = new Image();
                preloadImage.onload = function () {
                    // Thêm hiệu ứng fade out cho ảnh hiện tại
                    allImages.forEach((img, index) => {
                        // Thêm transition CSS nếu chưa có
                        if (!img.style.transition) {
                            img.style.transition = 'opacity 0.3s ease-in-out';
                        }

                        // Fade out
                        img.style.opacity = '0';

                        // Sau khi fade out, cập nhật ảnh và fade in
                        setTimeout(() => {
                            img.src = newImageUrl;
                            if (img.parentElement && img.parentElement.tagName === 'A') {
                                img.parentElement.href = newImageUrl;
                            }

                            // Fade in
                            img.style.opacity = '1';
                        }, 300);
                    });

                    // Tìm Swiper instance nếu chưa có
                    if (!window.productImageSwiper) {
                        const swiperElement = document.querySelector('.product-image-slider');
                        if (swiperElement && swiperElement.swiper) {
                            window.productImageSwiper = swiperElement.swiper;
                        } else {
                            // Thử tìm trong global scope
                            const allSwipers = document.querySelectorAll('.swiper');
                            allSwipers.forEach(swiper => {
                                if (swiper.swiper && swiper.classList.contains('product-image-slider')) {
                                    window.productImageSwiper = swiper.swiper;
                                }
                            });
                        }
                    }

                    // Dừng auto-play khi chọn màu
                    if (window.productImageSwiper) {
                        window.productImageSwiper.autoplay.stop();
                        isAutoPlayPaused = true;
                    }

                    // Reinitialize swiper và force update sau khi ảnh đã load
                    setTimeout(() => {
                        if (window.productImageSwiper) {
                            window.productImageSwiper.update();
                            window.productImageSwiper.updateSlides();
                            window.productImageSwiper.updateProgress();
                            window.productImageSwiper.updateSlidesClasses();
                        }
                    }, 350); // Đợi fade effect hoàn thành
                };

                preloadImage.onerror = function () {
                    // Fallback: cập nhật ảnh trực tiếp nếu preload thất bại
                    allImages.forEach((img, index) => {
                        img.src = newImageUrl;
                        if (img.parentElement && img.parentElement.tagName === 'A') {
                            img.parentElement.href = newImageUrl;
                        }
                    });
                };

                // Bắt đầu preload
                preloadImage.src = newImageUrl;
            }
        }
    }

    // Thêm tính năng click để bỏ chọn radio button
    document.querySelectorAll('input[name="color"], input[name="size"]').forEach(function (input) {
        let wasChecked = false;

        input.addEventListener('mousedown', function (e) {
            wasChecked = this.checked;
        });

        input.addEventListener('click', function (e) {
            // Nếu radio button này đã được chọn trước khi click, bỏ chọn nó
            if (wasChecked) {
                e.preventDefault();
                e.stopPropagation();

                // Tạo một radio button ẩn để bỏ chọn tất cả
                let hiddenRadio = document.createElement('input');
                hiddenRadio.type = 'radio';
                hiddenRadio.name = this.name;
                hiddenRadio.style.display = 'none';
                hiddenRadio.checked = false;

                // Thêm vào DOM tạm thời
                this.parentNode.appendChild(hiddenRadio);

                // Bỏ chọn radio button hiện tại
                this.checked = false;

                // Xóa radio button ẩn
                setTimeout(() => {
                    if (hiddenRadio.parentNode) {
                        hiddenRadio.parentNode.removeChild(hiddenRadio);
                    }
                }, 100);

                updateStockInfo();
                return false;
            }
        });
    });

    function updateStockInfo() {
        var color = document.querySelector('input[name="color"]:checked');
        var size = document.querySelector('input[name="size"]:checked');
        var stockInfo = document.getElementById('stock-info');
        var stockIcon = document.getElementById('stock-icon');
        var stockText = document.getElementById('stock-text');
        var addToCartBtn = document.querySelector('.btn-cart');
    
        if (!color || !size) {
            stockText.textContent = '';
            stockIcon.style.display = 'none';
            addToCartBtn.disabled = true;
            return;
        }
    
        var key = color.value + '-' + size.value;
    
        // Kiểm tra flash sale
        var isFlashSale = typeof window.flashSaleStock !== 'undefined' && window.flashSaleStock[key] !== undefined;
    
        if (isFlashSale) {
            var stock = window.flashSaleStock[key] ?? 0;
            addToCartBtn.disabled = stock <= 0;
    
            if (stock > 0) {
                stockText.textContent = stock <= 5 ? `Tồn kho: ${stock} (Sắp hết hàng!)` : `Tồn kho: ${stock}`;
                stockInfo.className = stock <= 5 ? 'text-danger fw-bold d-flex align-items-center' : 'text-success d-flex align-items-center';
                stockIcon.className = stock <= 5 ? 'bi bi-exclamation-triangle-fill me-2' : 'bi bi-check-circle-fill me-2';

            } else {
                stockText.textContent = 'Hết hàng';
                stockInfo.className = 'text-danger fw-bold d-flex align-items-center';
                stockIcon.className = 'bi bi-x-circle-fill me-2';
            }

            stockIcon.style.display = 'inline-block';
    
        } else {
            // Variant bình thường
            var variantData = window.variantStock[key] ?? { stock: 0, is_show: 0 };
            var stock = variantData.stock;
            var isShow = variantData.is_show;
    
            addToCartBtn.disabled = !isShow || stock <= 0;
    

            if (stock > 0 && isShow) {
                stockText.textContent = stock <= 5 ? `Tồn kho: ${stock} (Sắp hết hàng!)` : `Tồn kho: ${stock}`;
                stockInfo.className = stock <= 5 ? 'text-danger fw-bold d-flex align-items-center' : 'text-success d-flex align-items-center';
                stockIcon.className = stock <= 5 ? 'bi bi-exclamation-triangle-fill me-2' : 'bi bi-check-circle-fill me-2';
            } else {
                stockText.textContent = isShow ? 'Hết hàng' : '';
                stockInfo.className = 'text-danger fw-bold d-flex align-items-center';
                stockIcon.className = isShow ? 'bi bi-x-circle-fill me-2' : '';
            }

            stockIcon.style.display = 'inline-block';
        }
    }
    




    document.querySelectorAll('input[name="color"], input[name="size"]').forEach(function (input) {
        input.addEventListener('change', function () {
            updateStockInfo();

            // Nếu là color input, cập nhật ảnh sản phẩm
            if (this.name === 'color' && this.checked) {
                updateProductImage(this.value);
            }
        });
    });

    // Gọi lần đầu nếu có sẵn lựa chọn
    updateStockInfo();

    // Cập nhật ảnh ban đầu nếu có màu được chọn sẵn
    const initialColor = document.querySelector('input[name="color"]:checked');
    if (initialColor) {
        updateProductImage(initialColor.value);
    }

    // Thêm event listener cho các ảnh thumbnail để khôi phục slide
    document.querySelectorAll('.product-image-thumb .swiper-slide img').forEach(function (thumbImg) {
        thumbImg.addEventListener('click', function () {
            // Khi click vào thumbnail, khôi phục lại ảnh chính từ thumbnail đó
            const mainSlider = document.querySelector('.product-image-slider .swiper-wrapper');
            const mainImage = mainSlider.querySelector('.swiper-slide:first-child img');
            if (mainImage) {
                mainImage.src = this.src;
                mainImage.parentElement.href = this.src;
            }

            // Khôi phục auto-play khi click vào thumbnail
            if (window.productImageSwiper && isAutoPlayPaused) {
                window.productImageSwiper.autoplay.start();
                isAutoPlayPaused = false;
            }

            // Cập nhật swiper
            if (window.productImageSwiper) {
                window.productImageSwiper.update();
            }
        });
    });
});
$('.qty-plus').click(function () {
    var th = $(this).closest('.quantity').find('.qty-text');
    th.val(+th.val() + 1);
    updateCartTotals(); // Cập nhật tổng giá sau khi tăng
});

$('.qty-minus').click(function () {
    var th = $(this).closest('.quantity').find('.qty-text');
    if (th.val() > 1)
        th.val(+th.val() - 1);
    updateCartTotals(); // Cập nhật tổng giá sau khi giảm
});

$('.qty-text').on('input', function () {
    updateCartTotals();
});


function updateProductPrice() {
    // Nếu đang ở Flash Sale thì không đụng vào giá
    if (document.querySelector('.alert.alert-danger')) {
        // Nếu muốn vẫn update giá flash khi đổi biến thể:
        const flashPriceEl = document.getElementById('product-sale-price');
        const color = document.querySelector('input[name="color"]:checked')?.value;
        const size = document.querySelector('input[name="size"]:checked')?.value;
        if (flashPriceEl && color && size) {
            const flashPrice = document.querySelector(`input[name="color"]:checked`)?.dataset.flashPrice;
            if (flashPrice) {
                flashPriceEl.textContent = Number(flashPrice).toLocaleString() + 'đ';
            }
        }
        return;
    }

    // Trường hợp bình thường
    const color = document.querySelector('input[name="color"]:checked')?.value;
    const size = document.querySelector('input[name="size"]:checked')?.value;
    if (!color || !size) return;

    const key = color + '-' + size;
    const priceData = window.variantPriceMap[key];
    if (!priceData) return;

    const salePriceEl = document.getElementById('product-sale-price');
    if (salePriceEl) {
        salePriceEl.textContent = Number(priceData.sale_price).toLocaleString() + 'đ';
    }

    const listedPriceEl = document.getElementById('product-listed-price');
    if (listedPriceEl) {
        if (priceData.listed_price != priceData.sale_price) {
            listedPriceEl.textContent = Number(priceData.listed_price).toLocaleString() + 'đ';
            listedPriceEl.style.display = '';
        } else {
            listedPriceEl.style.display = 'none';
        }
    }
}


// Lắng nghe sự kiện thay đổi
document.querySelectorAll('input[name="color"], input[name="size"]').forEach(el => {
    el.addEventListener('change', updateProductPrice);
});

// Gọi lần đầu khi trang load
updateProductPrice();
function updateColorName() {
    const checked = document.querySelector('input[name="color"]:checked');
    const nameSpan = document.getElementById('selected-color-name');
    nameSpan.textContent = checked ? checked.getAttribute('data-color-name') : '';
}
function updateSizeName() {
    const checked = document.querySelector('input[name="size"]:checked');
    const nameSpan = document.getElementById('selected-size-name');
    nameSpan.textContent = checked ? checked.getAttribute('data-size-name') : '';
}
document.querySelectorAll('input[name="color"]').forEach(input => {
    input.addEventListener('change', updateColorName);
});
document.querySelectorAll('input[name="size"]').forEach(input => {
    input.addEventListener('change', updateSizeName);
});
updateColorName();
updateSizeName();
