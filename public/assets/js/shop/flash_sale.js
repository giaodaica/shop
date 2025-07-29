  // Flash Sale App - Tối ưu hóa
    const FlashSaleApp = {
        // Khởi tạo countdown
        initCountdown() {
            document.querySelectorAll('.flash-countdown').forEach(el => {
                const endTime = new Date(el.dataset.time).getTime();
                
                const updateCountdown = () => {
                    const now = new Date().getTime();
                    const distance = endTime - now;

                    if (distance <= 0) {
                        el.innerText = "Đã kết thúc";
                        return;
                    }

                    const hours = Math.floor((distance / (1000 * 60 * 60)) % 24);
                    const minutes = Math.floor((distance / (1000 * 60)) % 60);
                    const seconds = Math.floor((distance / 1000) % 60);

                    el.innerText = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                };

                updateCountdown();
                setInterval(updateCountdown, 1000);
            });
        },

        // Xử lý click tabs
        initTabs() {
            const buttons = document.querySelectorAll('.btn-show-products');
            
            buttons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.handleTabClick(button);
                });
            });

            // Kích hoạt tab đầu tiên
            const defaultTab = document.querySelector('.btn-show-products.default-tab');
            if (defaultTab) {
                setTimeout(() => defaultTab.click(), 100);
            }
        },

        // Xử lý logic click tab
        handleTabClick(button) {
            const saleId = button.dataset.id;
            const container = document.getElementById('products-' + saleId);

            // Toggle hiển thị sản phẩm
            if (container.innerHTML.trim() !== '') {
                container.innerHTML = '';
                this.removeActiveStates();
                return;
            }

            // Ẩn tất cả sản phẩm khác
            document.querySelectorAll('.flash-sale-products').forEach(div => div.innerHTML = '');

            // Load sản phẩm
            this.loadProducts(saleId, container);
            
            // Cập nhật active state
            this.setActiveState(button);
        },

        // Load sản phẩm qua AJAX
        async loadProducts(saleId, container) {
            try {
                const response = await fetch(`/flash-sales/${saleId}/products`);
                if (!response.ok) throw new Error('Lỗi khi tải dữ liệu');
                
                const html = await response.text();
                container.innerHTML = html;
            } catch (error) {
                container.innerHTML = '<p class="text-danger">Không thể tải sản phẩm.</p>';
                console.error(error);
            }
        },

        // Cập nhật active state
        setActiveState(activeButton) {
            // Remove active từ tất cả
            this.removeActiveStates();

            // Add active cho button được click
            const clickedCard = activeButton.querySelector('.flash-sale-card');
            const label = clickedCard.querySelector('.sale-label');

            activeButton.classList.add('active');
            clickedCard.classList.add('active');
            if (label) label.classList.add('active-label');
        },

        // Remove tất cả active states
        removeActiveStates() {
            document.querySelectorAll('.flash-sale-card').forEach(card => {
                card.classList.remove('active');
                const label = card.querySelector('.sale-label');
                if (label) label.classList.remove('active-label');
            });

            document.querySelectorAll('.btn-show-products').forEach(btn => {
                btn.classList.remove('active');
            });
        },

        // Khởi tạo app
        init() {
            document.addEventListener('DOMContentLoaded', () => {
                this.initCountdown();
                this.initTabs();
            });
        }
    };

    // Khởi chạy ứng dụng
    FlashSaleApp.init();