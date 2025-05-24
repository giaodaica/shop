   <script src="{{asset('admin/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('admin/libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{asset('admin/libs/node-waves/waves.min.js')}}"></script>
    <script src="{{asset('admin/libs/feather-icons/feather.min.js')}}"></script>
    <script src="{{asset('admin/js/pages/plugins/lord-icon-2.1.0.js')}}"></script>
    <script src="{{asset('admin/js/plugins.js')}}"></script>

    <!-- apexcharts -->
    <script src="{{asset('admin/libs/apexcharts/apexcharts.min.js')}}"></script>

    <!-- Vector map-->
    <script src="{{asset('admin/libs/jsvectormap/js/jsvectormap.min.js')}}"></script>
    <script src="{{asset('admin/libs/jsvectormap/maps/world-merc.js')}}"></script>

    <!--Swiper slider js-->
    <script src="{{asset('admin/libs/swiper/swiper-bundle.min.js')}}"></script>

    <!-- Dashboard init -->
    <script src="{{asset('admin/js/pages/dashboard-ecommerce.init.js')}}"></script>

    <!-- App js -->
    <script src="{{asset('admin/js/app.js')}}"></script>

    <!-- gridjs js -->
    <script src="{{asset('admin/libs/gridjs/gridjs.umd.js')}}"></script>
    <script src="{{asset('https://unpkg.com/gridjs/plugins/selection/dist/selection.umd.js')}}"></script>

    <script src="{{asset('admin/js/pages/ecommerce-product-list.init.js')}}"></script>

    <script src="{{asset('admin/libs/nouislider/nouislider.min.js')}}"></script>
    <script src="{{asset('admin/libs/wnumb/wNumb.min.js')}}"></script>

    <script>
  // Khi click mở menu "Danh sách", lưu trạng thái mở
  document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.querySelector('[href="#sidebarDashboards"]');
    const menuDropdown = document.getElementById('sidebarDashboards');

    // Đọc trạng thái từ localStorage
    const isOpen = localStorage.getItem('sidebarDashboardsOpen');
    if (isOpen === 'true') {
      menuDropdown.classList.add('show');
      menuToggle.setAttribute('aria-expanded', 'true');
    }

    // Ghi trạng thái mỗi khi click
    menuToggle.addEventListener('click', function () {
      const willOpen = !menuDropdown.classList.contains('show');
      localStorage.setItem('sidebarDashboardsOpen', willOpen);
    });
  });
</script>


    
