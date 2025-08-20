<head>
    <!-- title -->
    <title>OUTFITLY : Bộ sưu tập mùa dự án tốt nghiệp</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <meta name="author" content="OUTFITLY">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- description -->
    <meta name="description"
        content="Mua quần áo thời trang đẹp, chất lượng tại OUTFITLY. Cập nhật xu hướng mới nhất với áo thun, váy, quần jeans, phụ kiện phong cách tại OUTFITLY.">
    <meta name="keywords"
        content="OUTFITLY thời trang, quần áo, cửa hàng thời trang, xu hướng thời trang, áo thun, váy đẹp, quần jeans, phụ kiện thời trang, streetwear, boutique, phong cách, quần áo giá rẻ, thời trang nam, thời trang nữ">
    <!-- favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/outfitly-32.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/outfitly-57.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('assets/images/outfitly-72.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('assets/images/outfitly-144.png') }}">
    <script>
    // Khôi phục ngay khi người dùng quay lại bất kỳ trang nào của site (đặt sớm để không phải chờ)
    (function(){
        try {
            var tokenMeta = document.querySelector('meta[name="csrf-token"]');
            var csrf = tokenMeta ? tokenMeta.getAttribute('content') : '';
            // Gọi sớm nhất có thể; endpoint sẽ no-op nếu không có đơn chờ
            fetch("{{ route('checkout.cancelPendingPayment') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                },
                credentials: 'same-origin',
                keepalive: true
            }).catch(function(){});
        } catch(e) {}
    })();
    </script>
    <!-- google fonts preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- style sheets and font icons  -->
    <link rel="stylesheet" href="{{ asset('assets/css/vendors.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/icon.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/demos/fashion-store/fashion-store.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/search-suggestions.css') }}" rel="stylesheet">
    
    
</head>
