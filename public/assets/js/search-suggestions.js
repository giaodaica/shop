document.addEventListener('DOMContentLoaded', function () {
    // Constants and Configuration
    const CONFIG = {
        MAX_HISTORY_ITEMS: 5,
        DEBOUNCE_DELAY: 300,
        API_ENDPOINTS: {
            SEARCH: '/search',
            SUGGESTIONS: '/search/suggestions',
            FILTER: '/search/filter'
        }
    };

    // DOM Elements
    const elements = {
        searchInput: document.getElementById('search'),
        searchSuggestions: document.querySelector('.search-suggestions'),
        historyList: document.querySelector('.history-list'),
        clearHistoryBtn: document.querySelector('.btn-clear-history'),
        closeSuggestionsBtn: document.querySelector('.btn-close-suggestions'),
        trendingList: document.querySelector('.trending-list'),
        autocompleteResults: document.getElementById('autocomplete-results'),
        productContainer: document.querySelector('.shop-modern'),
        clearInput: document.getElementById('clearInput')
    };

    // State Management
    const state = {
        currentPage: 1,
        currentSort: 'default',
        currentKeyword: '',
        isLoading: false,
        debounceTimer: null,
        lastRequestedTerm: null,
        suggestionsVisible: true
    };

    // Get search parameters from URL
    const urlParams = new URLSearchParams(window.location.search);
    const searchQuery = urlParams.get('q');
    const searchPage = urlParams.get('page') || 1;
    const searchSort = urlParams.get('sort') || 'default';

    // Initialize state from URL parameters if they exist
    if (searchQuery) {
        state.currentKeyword = searchQuery;
        state.currentPage = parseInt(searchPage);
        state.currentSort = searchSort;
    }

    // Trending searches (should be fetched from API in production)
    // const trendingSearches = [ ... ];
    let trendingCategories = [];
    async function fetchTrendingCategories() {
        try {
            const res = await fetch('/search/trending-categories');
            if (!res.ok) throw new Error('Network error');
            const data = await res.json();
            if (data && data.data && Array.isArray(data.data) && data.data.length > 0) {
                trendingCategories = data.data;
            } else {
                trendingCategories = [
                    { name: "Áo thun nam" }, { name: "Quần jean nữ" }, { name: "Váy liền thân" }, { name: "Giày thể thao" },
                    { name: "Túi xách" }, { name: "Đồng hồ" }, { name: "Kính mát" }, { name: "Phụ kiện" }
                ];
            }
        } catch (e) {
            trendingCategories = [
                { name: "Áo thun nam" }, { name: "Quần jean nữ" }, { name: "Váy liền thân" }, { name: "Giày thể thao" },
                { name: "Túi xách" }, { name: "Đồng hồ" }, { name: "Kính mát" }, { name: "Phụ kiện" }
            ];
        }
    }
    // Gọi fetch trending khi load trang
    fetchTrendingCategories();

    // Initialize loading overlay
    const loadingOverlay = (() => {
        const overlay = document.createElement('div');
        overlay.className = 'loading-overlay';
        // overlay.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';
        document.body.appendChild(overlay);
        return overlay;
    })();

    // Utility Functions
    const utils = {
        debounce(func, wait) {
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(state.debounceTimer);
                    func(...args);
                };
                clearTimeout(state.debounceTimer);
                state.debounceTimer = setTimeout(later, wait);
            };
        },

        updateURL(params) {
            const currentPath = window.location.pathname;
            const searchParams = new URLSearchParams(window.location.search);

            // Cập nhật các tham số tìm kiếm
            Object.entries(params).forEach(([key, value]) => {
                if (value) {
                    searchParams.set(key, value);
                } else {
                    searchParams.delete(key);
                }
            });

            const newURL = `${currentPath}?${searchParams.toString()}`;
            window.history.pushState(null, null, newURL);
        },


        showLoading() {
            loadingOverlay.style.display = 'flex';
            state.isLoading = true;
        },

        hideLoading() {
            loadingOverlay.style.display = 'none';
            state.isLoading = false;
        },

        handleError(error, container) {
            console.error('Search error:', error);
            if (container) {
                container.innerHTML = `
                    <div class="col-12 text-center">
                        <p class="text-danger">Có lỗi xảy ra khi tìm kiếm. Vui lòng thử lại sau.</p>
                    </div>
                `;
            }
        }
    };

    // Search History Management
    const searchHistory = {
        get() {
            const history = localStorage.getItem('searchHistory');
            return history ? JSON.parse(history) : [];
        },

        save(keyword) {
            let history = this.get();
            history = history.filter(item => item !== keyword);
            history.unshift(keyword);
            if (history.length > CONFIG.MAX_HISTORY_ITEMS) {
                history = history.slice(0, CONFIG.MAX_HISTORY_ITEMS);
            }
            localStorage.setItem('searchHistory', JSON.stringify(history));
            this.updateUI();
        },

        remove(keyword) {
            let history = this.get();
            history = history.filter(item => item !== keyword);
            localStorage.setItem('searchHistory', JSON.stringify(history));
            this.updateUI();
        },

        clear() {
            localStorage.removeItem('searchHistory');
            this.updateUI();
        },

        updateUI() {
            if (elements.historyList) {
                this.display();
            }
            if (elements.clearHistoryBtn) {
                this.updateClearButton();
            }
        },

        display() {
            const history = this.get();
            let historyHtml = '';

            if (history.length > 0) {
                historyHtml = history.map(keyword => `
                  <div class="history-item p-2 border-bottom d-flex justify-content-between align-items-center" style="cursor: pointer;">
    <div class="d-flex align-items-center flex-grow-1 text-content">
        <i class="fa fa-history"></i>
        <span class="ms-2 keyword-text" title="${keyword}">${keyword}</span>
    </div>
    <button class="remove-history-item btn btn-sm btn-link p-0 ms-2" data-keyword="${keyword}" style="line-height: 1;">&times;</button>
</div>

                `).join('');
            } else {
                historyHtml = '<div class="p-2 fw-bold text-dark" style="font-size: 16px;">Chưa có lịch sử tìm kiếm</div>';
            }
            return historyHtml;
        },

        updateClearButton() {
            // The clear button is now dynamically rendered within showSuggestions
        }
    };

    // Search Functionality
    const search = {
        async perform(keyword, page = 1, sort = 'default', additionalParams = '') {
            if (state.isLoading) return;

            searchHistory.save(keyword);
            localStorage.setItem('currentSearch', keyword);
            utils.showLoading();

            try {
                // Log for debugging
                console.log('Performing search:', {
                    keyword,
                    page,
                    sort,
                    additionalParams
                });

                const params = new URLSearchParams();

                if (keyword) params.append('q', keyword);
                if (page) params.append('page', page);
                if (sort) params.append('sort', sort);

                // Nếu additionalParams là chuỗi, phân tách và thêm vào params
                if (additionalParams) {
                    const tempParams = new URLSearchParams(additionalParams);
                    for (const [key, value] of tempParams.entries()) {
                        params.append(key, value);
                    }
                }

                const url = `/search/filter?${params.toString()}&t=${Date.now()}`;
                console.log('Search URL:', url);

                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();
                if (!data.html) {
                    throw new Error('Empty HTML received from server');
                }

                this.updateUI(data.html, keyword, page, sort);
            } catch (error) {
                console.error('Search error:', error);
                utils.handleError(error, elements.productContainer);
            } finally {
                utils.hideLoading();
            }
        },

        updateUI(html, keyword, page, sort) {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            // Update search results count
            this.updateSearchCount(doc);

            // Update products
            this.updateProducts(doc);

            // Update pagination
            this.updatePagination(doc, keyword, page, sort);

            // Update sorting
            this.updateSorting(doc, keyword, page, sort);

            // Update URL
            utils.updateURL({
                q: keyword,
                page: page,
                sort: sort
            });
        },

        updateSearchCount(doc) {
            const searchResultsCount = doc.querySelector('.text-muted.fs-15');
            if (searchResultsCount) {
                const existingCount = document.querySelector('.text-muted.fs-15');
                if (existingCount) {
                    existingCount.remove();
                }
                document.querySelector('.col-md-6').appendChild(searchResultsCount.cloneNode(true));
            }
        },

        updateProducts(doc) {
            const newProducts = doc.querySelector('.shop-modern');
            if (!elements.productContainer) return;

            // Destroy existing Isotope
            if ($(elements.productContainer).data('isotope')) {
                $(elements.productContainer).isotope('destroy');
            }

            // Reset container
            elements.productContainer.innerHTML = '';
            const gridSizer = document.createElement('div');
            gridSizer.className = 'grid-sizer';
            elements.productContainer.appendChild(gridSizer);

            // Add new products if they exist
            if (newProducts) {
                const newProductItems = newProducts.querySelectorAll('.grid-item');
                if (newProductItems.length > 0) {
                    newProductItems.forEach(item => {
                        elements.productContainer.appendChild(item.cloneNode(true));
                    });
                } else {
                    // If no products found, show a message
                    elements.productContainer.innerHTML = `
                        <div class="col-12 text-center">
                            <p class="text-muted">Không tìm thấy sản phẩm phù hợp với từ khóa tìm kiếm.</p>
                        </div>
                    `;
                }
            } else {
                // If no products container found in response, show a message
                elements.productContainer.innerHTML = `
                    <div class="col-12 text-center">
                        <p class="text-muted">Không tìm thấy sản phẩm phù hợp với từ khóa tìm kiếm.</p>
                    </div>
                `;
            }

            // Update category counts
            const newCategoryCounts = doc.querySelectorAll('.category-filter .item-qty');
            const currentCategoryCounts = document.querySelectorAll('.category-filter .item-qty');
            newCategoryCounts.forEach((newCount, index) => {
                if (currentCategoryCounts[index]) {
                    currentCategoryCounts[index].textContent = newCount.textContent;
                }
            });

            // Get available sizes and colors from current products
            const availableSizes = new Map();
            const availableColors = new Map();

            const currentProducts = elements.productContainer.querySelectorAll('.grid-item');
            currentProducts.forEach(product => {
                // Get available sizes
                const sizeElements = product.querySelectorAll('.product-sizes .size');
                sizeElements.forEach(size => {
                    if (size && size.dataset && size.dataset.size) {
                        const sizeId = size.dataset.size;
                        availableSizes.set(sizeId, (availableSizes.get(sizeId) || 0) + 1);
                    }
                });

                // Get available colors
                const colorElements = product.querySelectorAll('.product-colors .color');
                colorElements.forEach(color => {
                    if (color && color.dataset && color.dataset.color) {
                        const colorId = color.dataset.color;
                        availableColors.set(colorId, (availableColors.get(colorId) || 0) + 1);
                    }
                });
            });

            // Update color filters
            const colorFilter = document.querySelector('.color-filter');
            if (colorFilter) {
                const colorCheckboxes = colorFilter.querySelectorAll('input[type="checkbox"]');
                colorCheckboxes.forEach(checkbox => {
                    if (!checkbox) return;

                    const colorLabel = checkbox.closest('li');
                    if (!colorLabel) return;

                    const colorId = checkbox.value;
                    const count = availableColors.get(colorId) || 0;
                    const countElement = colorLabel.querySelector('.item-qty');

                    if (count > 0) {
                        colorLabel.style.display = 'block';
                        checkbox.disabled = false;
                        if (countElement) {
                            countElement.textContent = count;
                        }
                    } else {
                        colorLabel.style.display = 'none';
                        checkbox.disabled = true;
                        checkbox.checked = false;
                        if (countElement) {
                            countElement.textContent = '0';
                        }
                    }
                });
            }

            // Update size filters
            const sizeFilter = document.querySelector('.size-filter');
            if (sizeFilter) {
                const sizeCheckboxes = sizeFilter.querySelectorAll('input[type="checkbox"]');
                sizeCheckboxes.forEach(checkbox => {
                    if (!checkbox) return;

                    const sizeLabel = checkbox.closest('li');
                    if (!sizeLabel) return;

                    const sizeId = checkbox.value;
                    const count = availableSizes.get(sizeId) || 0;
                    const countElement = sizeLabel.querySelector('.item-qty');

                    if (count > 0) {
                        sizeLabel.style.display = 'block';
                        checkbox.disabled = false;
                        if (countElement) {
                            countElement.textContent = count;
                        }
                    } else {
                        sizeLabel.style.display = 'none';
                        checkbox.disabled = true;
                        checkbox.checked = false;
                        if (countElement) {
                            countElement.textContent = '0';
                        }
                    }
                });
            }

            // Reattach event listeners to new filter inputs
            const newFilterInputs = document.querySelectorAll('input[type="checkbox"], input[type="radio"]');
            newFilterInputs.forEach(input => {
                if (!input) return;

                input.addEventListener('change', function () {
                    // Get current URL parameters
                    const urlParams = new URLSearchParams(window.location.search);
                    const currentKeyword = urlParams.get('q') || state.currentKeyword;

                    // Build filter parameters
                    const filterParams = new URLSearchParams();

                    // Add search keyword
                    if (currentKeyword) {
                        filterParams.append('q', currentKeyword);
                    }

                    // Add size filters
                    const selectedSizes = Array.from(document.querySelectorAll('.size-filter input[type="checkbox"]:checked')).map(cb => cb.value);
                    if (selectedSizes.length > 0) {
                        selectedSizes.forEach(size => {
                            filterParams.append('sizes[]', size);
                        });
                    }

                    // Add color filters
                    const selectedColors = Array.from(document.querySelectorAll('.color-filter input[type="checkbox"]:checked')).map(cb => cb.value);
                    if (selectedColors.length > 0) {
                        selectedColors.forEach(color => {
                            filterParams.append('colors[]', color);
                        });
                    }

                    // Add category filters
                    const selectedCategories = Array.from(document.querySelectorAll('.category-filter input[type="checkbox"]:checked')).map(cb => cb.value);
                    if (selectedCategories.length > 0) {
                        selectedCategories.forEach(category => {
                            filterParams.append('categories[]', category);
                        });
                    }

                    // Add price range
                    const selectedPriceRange = document.querySelector('input[type="radio"][name="price_range"]:checked')?.value;
                    if (selectedPriceRange) {
                        filterParams.append('price_range', selectedPriceRange);
                    }

                    // Add sort
                    const currentSort = document.querySelector('select[name="sort"]')?.value || 'default';
                    filterParams.append('sort', currentSort);

                    // Add page
                    filterParams.append('page', '1'); // Reset to page 1 when filters change

                    // Log for debugging
                    console.log('Filter parameters:', filterParams.toString());

                    // Update URL with new parameters
                    const newUrl = `${window.location.pathname}?${filterParams.toString()}`;
                    window.history.pushState({}, '', newUrl);

                    // Perform search with filters
                    search.perform(null, null, null, filterParams.toString());
                });
            });

            // Reinitialize Isotope only if there are products
            if (newProducts && newProducts.querySelectorAll('.grid-item').length > 0) {
                this.initializeIsotope();
            }
        },

        initializeIsotope() {
            const isotopeConfig = {
                layoutMode: 'fitRows',
                itemSelector: '.grid-item',
                percentPosition: true,
                stagger: 0,
                transitionDuration: '0.4s',
                fitRows: {
                    gutter: 30
                }
            };

            if (typeof imagesLoaded === 'function' && typeof $.fn.isotope === 'function') {
                $(elements.productContainer).imagesLoaded(function () {
                    $(elements.productContainer).removeClass('grid-loading');
                    $(elements.productContainer).isotope(isotopeConfig);
                    // Force layout update after images are loaded
                    setTimeout(() => {
                        $(elements.productContainer).isotope('layout');
                    }, 100);
                });
            } else if (typeof $.fn.isotope === 'function') {
                $(elements.productContainer).isotope(isotopeConfig);
                // Force layout update
                setTimeout(() => {
                    $(elements.productContainer).isotope('layout');
                }, 100);
            }
        },

        updatePagination(doc, keyword, page, sort) {
            const pagination = doc.querySelector('.pagination');
            if (!pagination) return;

            const existingPagination = document.querySelector('.pagination');
            if (existingPagination) {
                existingPagination.remove();
            }

            // Create a container for pagination to ensure proper centering
            const paginationContainer = document.createElement('div');
            paginationContainer.className = 'd-flex justify-content-center mt-4';
            paginationContainer.appendChild(pagination.cloneNode(true));

            // Find the appropriate container to append pagination
            const shopModern = document.querySelector('.shop-modern');
            if (shopModern) {
                shopModern.after(paginationContainer);
            }

            // Add click handlers to pagination links
            const paginationLinks = document.querySelectorAll('.pagination a');
            paginationLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const newPage = link.getAttribute('href').match(/page=(\d+)/)?.[1] || 1;
                    state.currentPage = parseInt(newPage);
                    this.perform(keyword, state.currentPage, sort);
                });
            });
        },

        updateSorting(doc, keyword, page, sort) {
            const sortSelect = document.querySelector('select[name="sort"]');
            if (!sortSelect) return;

            // Remove the onchange attribute to prevent form submission
            sortSelect.removeAttribute('onchange');
            sortSelect.value = (!sort || sort === 'default') ? '' : sort;

            // Prevent form submission
            const sortForm = sortSelect.closest('form');
            if (sortForm) {
                sortForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                });

                // Remove all hidden inputs to prevent them from affecting the search
                const hiddenInputs = sortForm.querySelectorAll('input[type="hidden"]');
                hiddenInputs.forEach(input => input.remove());
            }

            // Add change event listener
            sortSelect.addEventListener('change', (e) => {
                e.preventDefault();
                e.stopPropagation();

                if (!state.currentKeyword) {
                    sortSelect.value = 'Sắp xếp';
                    return;
                }

                const newSort = e.target.value;
                state.currentSort = newSort;
                state.currentPage = 1;

                // Log for debugging
                console.log('Sorting changed:', {
                    keyword: state.currentKeyword,
                    page: state.currentPage,
                    sort: newSort
                });

                // Perform search with new sort
                this.perform(state.currentKeyword, state.currentPage, newSort);
            });
        },

        async showSuggestions(term) {
            if (!elements.searchSuggestions) return;

            // Prevent showing suggestions if suggestionsVisible is false
            if (state.suggestionsVisible === false) {
                elements.searchSuggestions.style.display = 'none';
                return;
            }

            const trimmedTerm = term ? term.trim() : '';
            elements.searchSuggestions.innerHTML = ''; // Clear previous content

            // Cập nhật từ khóa cuối cùng được yêu cầu
            state.lastRequestedTerm = trimmedTerm;

            if (trimmedTerm === '') {
                // Đảm bảo trendingCategories đã fetch xong
                if (trendingCategories.length === 0) {
                    await fetchTrendingCategories();
                }
                // Display history and trending
                let contentHtml = `
                    <div class="suggestions-section mb-3">
                        <h6 class="mb-2 fw-bold text-dark" style="font-size: 16px;">
                            <i class="fa fa-history text-danger me-2"></i>Lịch sử tìm kiếm
                        </h6>
                        <div class="history-list-dynamic">
                            ${searchHistory.display()}
                        </div>
                        ${searchHistory.get().length > 0 ? `<button class="btn btn-sm btn-outline-danger btn-clear-history mt-2" style="border: none !important; color: #dc3545 !important; background: transparent !important;">Xóa lịch sử</button>` : ''}
                    </div>
                    <div class="products-section">
                        <h6 class="mb-2 fw-bold text-dark" style="font-size: 16px;">
                            <i class="fa fa-fire text-danger me-2"></i>Xu hướng tìm kiếm
                        </h6>
                        <div class="trending-list-dynamic">
                            ${handlers.displayTrendingSearches()}
                        </div>
                    </div>
                `;
                elements.searchSuggestions.innerHTML = contentHtml;

                // Re-attach event listeners for history and trending items
                elements.searchSuggestions.querySelectorAll('.history-item .text-content').forEach(item => {
                    item.addEventListener('click', () => {
                        const keyword = item.querySelector('span').textContent.trim();
                        elements.searchInput.value = keyword;
                        state.currentKeyword = keyword;
                        state.currentPage = 1;
                        elements.searchSuggestions.style.display = 'none'; // Hide dropdown
                        // Nếu không phải trang /search thì chuyển hướng
                        if (!window.location.pathname.includes('/search')) {
                            window.location.href = `/search?q=${encodeURIComponent(keyword)}`;
                        } else {
                            this.perform(keyword);
                        }
                    });
                });
                elements.searchSuggestions.querySelectorAll('.remove-history-item').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        searchHistory.remove(e.target.dataset.keyword);
                        search.showSuggestions(''); // Re-render
                    });
                });
                const clearBtn = elements.searchSuggestions.querySelector('.btn-clear-history');
                if (clearBtn) {
                    clearBtn.addEventListener('click', () => {
                        searchHistory.clear();
                        search.showSuggestions(''); // Re-render
                    });
                }
                elements.searchSuggestions.querySelectorAll('.trend-tag').forEach(tag => {
                    tag.addEventListener('click', (e) => {
                        e.preventDefault();
                        const keyword = tag.querySelector('span').textContent.trim();
                        elements.searchInput.value = keyword;
                        elements.searchSuggestions.style.display = 'none'; // Hide dropdown
                        state.currentKeyword = keyword;
                        state.currentPage = 1;
                        // Chuyển hướng đúng URL search
                        window.location.href = `/search?q=${encodeURIComponent(keyword)}`;
                    });
                });

                elements.searchSuggestions.style.display = 'block';

                // When showing suggestions, set suggestionsVisible to true
                state.suggestionsVisible = true;

                return;
            }

            // If there's a search term, proceed with fetching suggestions
            try {
                // Lấy từ khóa cho yêu cầu hiện tại để so sánh sau này
                const currentTermForRequest = trimmedTerm;

                // Gọi API lấy gợi ý
                const response = await fetch(`${CONFIG.API_ENDPOINTS.SUGGESTIONS}?q=${encodeURIComponent(currentTermForRequest)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();

                // QUAN TRỌNG: Chỉ cập nhật nếu đây là phản hồi cho yêu cầu gần nhất
                if (state.lastRequestedTerm !== currentTermForRequest) {
                    console.log(`Ignoring outdated suggestion response for term: ${currentTermForRequest}. Current term is: ${state.lastRequestedTerm}`);
                    return; // Bỏ qua phản hồi cũ
                }

                const suggestions = data.suggestions || [];
                const featuredProducts = data.featured_products || [];

                // Cập nhật nội dung search-suggestions
                elements.searchSuggestions.innerHTML = `
                    <div class="suggestions-section mb-3">
                        <h6 class="mb-2 fw-bold text-dark" style="font-size: 16px;">
                            <i class="fa fa-search text-danger me-2"></i>Gợi ý tìm kiếm
                        </h6>
                        <div class="suggestions-list">
                            ${suggestions.length > 0 ? suggestions.map(suggestion => `
                                <div class="suggestion-item p-2 border-bottom" style="cursor: pointer;">
                                    <div class="d-flex align-items-center">
                                        <i class="fa fa-search text-muted"></i>
                                        <span class="ms-2" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px;">${suggestion.name}</span>
                                    </div>
                                </div>
                            `).join('') : `
                                <div class="p-2 text-center text-muted">
                                    Không tìm thấy gợi ý phù hợp
                                </div>
                            `}
                        </div>
                    </div>
                    <div class="products-section">
                        <h6 class="mb-2 fw-bold text-dark" style="font-size: 16px;">
                            <i class="fa fa-star text-danger me-2"></i>Sản phẩm nổi bật
                        </h6>
                        <div class="products-list">
                            ${featuredProducts.length > 0 ? featuredProducts.map(product => `
                                <div class="product-item p-2 border-bottom" style="cursor: pointer;" data-product-id="${product.id}">
                                    <div class="d-flex align-items-center">
                                        <div class="product-image" style="width: 60px; height: 60px; margin-right: 15px;">
                                            <img src="${product.image}" alt="${product.name}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px;">
                                        </div>
                                        <div class="product-info" style="flex: 1;">
                                            <div class="product-name" style="font-size: 14px; margin-bottom: 2px; line-height: 2; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px;">${product.name}</div>
                                            <div class="product-price d-flex align-items-center gap-2" style="line-height: 1;">
                                                <span class="current-price text-danger fw-bold">${product.price}</span>
                                                ${product.old_price ? `
                                                    <span class="old-price text-muted" style="text-decoration: line-through; font-size: 13px;">${product.old_price}</span>
                                                ` : ''}
                                                ${product.discount ? `
                                                    <span class="discount" style="background: #f60; color: white; padding: 2px 6px; border-radius: 3px; font-size: 12px;">${product.discount}</span>
                                                ` : ''}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `).join('') : `
                                <div class="p-2 text-center text-muted">
                                    Không có sản phẩm nổi bật
                                </div>
                            `}
                        </div>
                    </div>
                `;

                // Thêm event listeners cho các suggestion items
                elements.searchSuggestions.querySelectorAll('.suggestion-item').forEach(item => {
                    item.addEventListener('click', () => {
                        const keyword = item.querySelector('span').textContent.trim();
                        elements.searchInput.value = keyword;
                        state.currentKeyword = keyword;
                        state.currentPage = 1;
                        elements.searchSuggestions.style.display = 'none'; // Hide dropdown
                        this.perform(keyword);
                    });
                });

                // Thêm event listeners cho các product items
                elements.searchSuggestions.querySelectorAll('.product-item').forEach(item => {
                    item.addEventListener('click', () => {
                        const productId = item.dataset.productId;
                        if (productId) {
                            window.location.href = `/aonam/${productId}`;
                        }
                    });
                });
                elements.searchSuggestions.style.display = 'block';

                // When showing suggestions, set suggestionsVisible to true
                state.suggestionsVisible = true;

            } catch (error) {
                // Chỉ hiển thị lỗi nếu đây là lỗi cho yêu cầu gần nhất
                if (state.lastRequestedTerm === trimmedTerm) {
                    console.error('Error fetching suggestions:', error);
                    elements.searchSuggestions.innerHTML = `
                        <div class="p-2 text-center text-danger">
                            <i class="fa fa-exclamation-circle me-2"></i>
                            Có lỗi xảy ra khi tải gợi ý. Vui lòng thử lại sau.
                        </div>
                    `;
                }
            }
        }
    };

    // Event Handlers
    const handlers = {
        init() {
            if (!elements.searchInput) {
                console.error('Không tìm thấy ô tìm kiếm');
                return;
            }

            // No longer need to updateUI or displayTrendingSearches here directly
            // searchHistory.updateUI(); // This will be handled by showSuggestions
            // this.displayTrendingSearches(); // This will be handled by showSuggestions

            // Set up event listeners
            this.setupEventListeners();

            // If there's a search query in the URL, perform the search
            if (searchQuery) {
                elements.searchInput.value = searchQuery;
                search.perform(searchQuery, state.currentPage, state.currentSort);
            }
        },

        setupEventListeners() {
            // Search input events
            elements.searchInput.addEventListener('focus', () => {
                // When focused, always show the suggestions container
                // Then, show history/trending if input is empty
                const searchValue = elements.searchInput.value.trim();
                if (searchValue.length === 0) {
                    search.showSuggestions(''); // Show history/trending
                } else {
                    search.showSuggestions(searchValue); // Show current suggestions
                }
            });

            elements.searchInput.addEventListener('input', utils.debounce(() => {
                search.showSuggestions(elements.searchInput.value);
            }, CONFIG.DEBOUNCE_DELAY));

            elements.searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    const keyword = elements.searchInput.value.trim();
                    if (keyword.length > 0) {
                        state.currentKeyword = keyword;
                        state.currentPage = 1;
                        // Hide search suggestions dropdown
                        if (elements.searchSuggestions) {
                            elements.searchSuggestions.style.display = 'none';
                        }
                        state.suggestionsVisible = false; // Hide suggestions after Enter
                        // Remove focus from input
                        elements.searchInput.blur();
                        // search.perform(keyword);
                        window.location.href = `/search?q=${encodeURIComponent(keyword)}`;
                    }
                }
            });

            // Clear history button is now dynamically added and handled in showSuggestions
            if (elements.clearHistoryBtn) {
                // elements.clearHistoryBtn.addEventListener('click', () => { // Remove this
                //     searchHistory.clear();
                // });
            }

            // Clear input button
            if (elements.clearInput) {
                elements.searchInput.addEventListener('input', () => {
                    elements.clearInput.style.display = elements.searchInput.value.length > 0 ? 'block' : 'none';
                });

                elements.clearInput.addEventListener('click', () => {
                    elements.searchInput.value = '';
                    elements.searchInput.focus();
                    elements.clearInput.style.display = 'none';
                    search.showSuggestions(''); // Show history/trending after clearing
                });
            }

            // Close suggestions when clicking outside
            document.addEventListener('click', (e) => {
                if (elements.searchInput && elements.searchSuggestions) {
                    if (!elements.searchInput.contains(e.target) && !elements.searchSuggestions.contains(e.target)) {
                        elements.searchSuggestions.style.display = 'none';
                    }
                }
            });
        },

        displayTrendingSearches() {
            // if (!elements.trendingList) return; // This will be removed

            // elements.trendingList.innerHTML = ''; // This will be removed
            return trendingCategories.map(keyword => `
                <a href="#" class="trend-tag">
                    <i class="fa fa-fire"></i>
                    <span>${keyword.name}</span>
                </a>
            `).join('');
        }
    };

    // Initialize the search functionality
    handlers.init();

    // Add filter change handler for search results
    if (window.location.pathname.includes('/search')) {
        const filterInputs = document.querySelectorAll('.filter-sidebar input[type="checkbox"], .filter-sidebar input[type="radio"]');
        filterInputs.forEach(input => {
            input.addEventListener('change', function () {
                // Get current URL parameters
                const urlParams = new URLSearchParams(window.location.search);
                const currentKeyword = urlParams.get('q') || state.currentKeyword;

                // Build filter parameters
                const filterParams = new URLSearchParams();

                // Add search keyword
                if (currentKeyword) {
                    filterParams.append('q', currentKeyword);
                }

                // Add size filters
                const selectedSizes = Array.from(document.querySelectorAll('.filter-sidebar .size-filter input[type="checkbox"]:checked')).map(cb => cb.value);
                if (selectedSizes.length > 0) {
                    selectedSizes.forEach(size => {
                        filterParams.append('sizes[]', size);
                    });
                }

                // Add color filters
                const selectedColors = Array.from(document.querySelectorAll('.filter-sidebar .color-filter input[type="checkbox"]:checked')).map(cb => cb.value);
                if (selectedColors.length > 0) {
                    selectedColors.forEach(color => {
                        filterParams.append('colors[]', color);
                    });
                }

                // Add category filters
                const selectedCategories = Array.from(document.querySelectorAll('.filter-sidebar .category-filter input[type="checkbox"]:checked')).map(cb => cb.value);
                if (selectedCategories.length > 0) {
                    selectedCategories.forEach(category => {
                        filterParams.append('categories[]', category);
                    });
                }

                // Add price range
                const selectedPriceRange = document.querySelector('.filter-sidebar input[type="radio"][name="price_range"]:checked')?.value;
                if (selectedPriceRange) {
                    filterParams.append('price_range', selectedPriceRange);
                }

                // Add sort
                const currentSort = document.querySelector('select[name="sort"]')?.value || 'default';
                filterParams.append('sort', currentSort);

                // Add page
                filterParams.append('page', '1'); // Reset to page 1 when filters change

                // Log for debugging
                console.log('Filter parameters:', filterParams.toString());

                // Update URL with new parameters
                const newUrl = `${window.location.pathname}?${filterParams.toString()}`;
                window.history.pushState({}, '', newUrl);

                // Perform search with filters
                search.perform(null, null, null, filterParams.toString());
            });
        });

        // Add sort change handler for search results
        const sortSelect = document.querySelector('select[name="sort"]');
        if (sortSelect) {
            sortSelect.addEventListener('change', function () {
                // Get current URL parameters
                const urlParams = new URLSearchParams(window.location.search);
                const currentKeyword = urlParams.get('q') || state.currentKeyword;
                const currentSort = this.value;

                // Build filter parameters
                const filterParams = new URLSearchParams();

                // Add search keyword
                if (currentKeyword) {
                    filterParams.append('q', currentKeyword);
                }

                // Add size filters
                const selectedSizes = Array.from(document.querySelectorAll('.filter-sidebar .size-filter input[type="checkbox"]:checked')).map(cb => cb.value);
                if (selectedSizes.length > 0) {
                    selectedSizes.forEach(size => {
                        filterParams.append('sizes[]', size);
                    });
                }

                // Add color filters
                const selectedColors = Array.from(document.querySelectorAll('.filter-sidebar .color-filter input[type="checkbox"]:checked')).map(cb => cb.value);
                if (selectedColors.length > 0) {
                    selectedColors.forEach(color => {
                        filterParams.append('colors[]', color);
                    });
                }

                // Add category filters
                const selectedCategories = Array.from(document.querySelectorAll('.filter-sidebar .category-filter input[type="checkbox"]:checked')).map(cb => cb.value);
                if (selectedCategories.length > 0) {
                    selectedCategories.forEach(category => {
                        filterParams.append('categories[]', category);
                    });
                }

                // Add price range
                const selectedPriceRange = document.querySelector('.filter-sidebar input[type="radio"][name="price_range"]:checked')?.value;
                if (selectedPriceRange) {
                    filterParams.append('price_range', selectedPriceRange);
                }

                // Add sort
                filterParams.append('sort', currentSort);

                // Add page
                filterParams.append('page', '1'); // Reset to page 1 when sort changes

                // Log for debugging
                console.log('Sort parameters:', filterParams.toString());

                // Update URL with new parameters
                const newUrl = `${window.location.pathname}?${filterParams.toString()}`;
                window.history.pushState({}, '', newUrl);

                // Perform search with filters
                search.perform(null, null, null, filterParams.toString());
            });
        }
    }
});
