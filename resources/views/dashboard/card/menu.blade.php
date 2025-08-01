<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{ url('dashboard') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ asset('admin/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('admin/images/logo-dark.png') }}" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{ url('dashboard') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ asset('admin/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('admin/images/logo-light.png') }}" alt="" height="17">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarDashboards" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Đơn hàng</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarDashboards">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('dashboard.order') }}" class="nav-link" data-key="t-analytics"> Danh
                                    sách
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('dashboard/refund') }}" class="nav-link" data-key="t-crm"> Yêu cầu hoàn
                                    tiền </a>
                            </li>
                        </ul>
                    </div>
                </li> <!-- end Dashboard Menu -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarApps" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarApps">
                        <i class="ri-apps-2-line"></i> <span data-key="t-apps">Thống Kê</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarApps">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('dashboard.revenue') }}" class="nav-link" data-key="t-revenue">Doanh
                                    Thu</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-pages">Khuyến mại</span></li>
                <a class="nav-link menu-link" href="#sidebarDashboards1" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarDashboards">
                    <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Chương trình</span>
                </a>
                <div class="collapse menu-dropdown" id="sidebarDashboards1">
                    <ul class="nav nav-sm flex-column">
                        @foreach ($menu as $render_menu)
                            <li class="nav-item">
                                <a href="{{ url("dashboard/voucher/$render_menu->slug") }}" class="nav-link"
                                    data-key="t-analytics">{{ $render_menu->name }}
                                </a>
                            </li>
                        @endforeach
                        <li class="nav-item">
                            <a href="" class="nav-link">Lịch Sử</a>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal"
                                id="create-btn" data-bs-target="#showModalAds">Thêm chiến dịch mới</button>
                        </li>
                    </ul>
                </div>
                <a class="nav-link menu-link" href="#sidebarDashboards2" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarDashboards">
                    <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Flash Sale</span>
                </a>
                <div class="collapse menu-dropdown" id="sidebarDashboards2">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ route('flash-sale') }}" class="nav-link" data-key="t-analytics">Xem
                            </a>
                        </li>
                    </ul>
                </div>
                <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-pages">Sản phẩm</span></li>

                <a class="nav-link menu-link" href="#sidebarProducts" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarProducts">
                    <i class="ri-layout-3-line"></i> <span data-key="t-layouts">Danh sách</span>
                </a>
                <div class="collapse menu-dropdown" id="sidebarProducts">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ url('dashboard/categories') }}" class="nav-link"
                                data-key="t-horizontal">Quản lý danh mục</a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url('dashboard/products') }}" class="nav-link" data-key="t-horizontal">Quản
                                lý sản phẩm</a>
                        </li>


                        <li class="nav-item">
                            <a href="{{ url('dashboard/comments') }}" class="nav-link" data-key="t-horizontal">Quản
                                lý bình luận</a>
                        </li>

                    </ul>
                </div>
                <a class="nav-link menu-link" href="#sidebarAttributes" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="sidebarAttributes">
                    <i class="ri-layout-3-line"></i> <span data-key="t-layouts">Biến thể</span>
                </a>
                <div class="collapse menu-dropdown" id="sidebarAttributes">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ url('dashboard/variants') }}" class="nav-link" data-key="t-horizontal">Sản
                                phẩm</a>
                        </li>
                    </ul>
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ url('dashboard/sizes') }}" class="nav-link" data-key="t-horizontal">Size</a>
                        </li>
                    </ul>
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ url('dashboard/colors') }}" class="nav-link" data-key="t-horizontal">Màu</a>
                        </li>
                    </ul>
                </div>

                <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-users">Tài khoản</span></li>

                <a class="nav-link menu-link" href="{{ route('users.index') }}" role="button">
                    <i class="ri-user-settings-line"></i> <span>Quản lý tài khoản</span>
                </a>
                <a class="nav-link menu-link" href="{{ route('users.lock-history') }}" role="button">
                    <i class="fas fa-history"></i> <span>Lịch sử khóa tài khoản</span>
                </a>
                {{-- Phân quyền --}}
                <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-pages">Phân quyền</span></li>
                <a class="nav-link menu-link" href="{{ route('dashboard.roles.index') }}" role="button">
                    <i class="ri-layout-3-line"></i> <span>Vai trò người dùng</span>
                </a>
                <a class="nav-link menu-link" href="{{ route('dashboard.permissions.index') }}" role="button">
                    <i class="ri-layout-3-line"></i> <span>Quyền truy cập</span>
                </a>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<div class="modal fade" id="showModalAds" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title" id="exampleModalLabel">Thêm Chiến dịch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    id="close-modal"></button>
            </div>
            <form class="tablelist-form" name="_form" value="ads" id="myFormAds" autocomplete="off"
                action="{{ url('/dashboard/voucher/ads') }}" method="POST">
                @csrf
                <input type="hidden" name="_form" value="ads">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="customername-field" class="form-label">Tên chiến dịch</label>
                        <input type="text" id="name" name="name" class="form-control"
                            placeholder="Nhập tên chiến dịch" required value="{{ old('name') }}" />
                        <div class="text-danger">
                            @error('name')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="customername-field" class="form-label">Slug</label>
                        <input type="text" id="slug" name="slug" class="form-control"
                            placeholder="Nhập slug" required value="{{ old('slug') }}" />
                        <div class="text-danger">
                            @error('slug')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-success" id="add-btn">Thêm chiến dịch</button>
                        <!-- <button type="button" class="btn btn-success" id="edit-btn">Update</button> -->
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('#myFormAds');
        const nameInput = document.querySelector('#name');
        const slugInput = document.querySelector('#slug');

        // Tự động tạo slug khi gõ tên chiến dịch
        if (nameInput && slugInput) {
            nameInput.addEventListener('input', function() {
                let slug = nameInput.value
                    .toLowerCase()
                    .normalize('NFD') // chuyển tiếng Việt có dấu thành không dấu
                    .replace(/[\u0300-\u036f]/g, '') // xóa ký tự dấu
                    .replace(/[^a-z0-9 -]/g, '') // xóa ký tự đặc biệt
                    .replace(/\s+/g, '-') // thay khoảng trắng thành dấu -
                    .replace(/-+/g, '-') // loại bỏ dấu - liên tiếp
                    .replace(/^-+|-+$/g, ''); // xóa - ở đầu hoặc cuối

                slugInput.value = slug;
            });
        }

        // Validate form nếu form tồn tại
        if (form) {
            const validation = new JustValidate('#myFormAds');

            validation
                .addField('#name', [{
                        rule: 'required',
                        errorMessage: 'Vui lòng nhập tên chiến dịch',
                    },
                    {
                        rule: 'minLength',
                        value: 10,
                        errorMessage: 'Tên chiến dịch ít nhất 10 ký tự',
                    },
                    {
                        rule: 'maxLength',
                        value: 50,
                        errorMessage: 'Tên chiến dịch tối đa 50 ký tự',
                    },
                ])
                .addField('#slug', [{
                    rule: 'required',
                    errorMessage: 'Vui lòng nhập slug',
                }])
                .onSuccess((event) => {
                    event.target.submit();
                });
        }

        // Hiển thị modal nếu có lỗi từ server
        @if ($errors->any() && old('_form') === 'ads')
            var myModal = new bootstrap.Modal(document.getElementById('showModalAds'));
            myModal.show();
        @endif
    });
</script>
