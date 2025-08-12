<?php

use App\Http\Controllers\Auth\ChangepasswordController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProductVariantsController;

use App\Http\Controllers\RevenueController;
use App\Http\Controllers\ReviewReplyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\web\SearchController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\Spatie\PermissionController;
use App\Http\Controllers\Spatie\RoleController;
use App\Http\Controllers\Spatie\UserRoleController;
use App\Http\Controllers\VouchersController;
use App\Http\Controllers\web\ProductController;
use App\Http\Controllers\web\ProductDetailController;
use App\Http\Controllers\web\ReviewController;
use App\Http\Controllers\AddressBookController;
use App\Http\Controllers\RefundMoneyController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\CheckUserStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Contracts\Role;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FlashSaleController;
use App\Http\Controllers\FlashSaleItemsController;
use App\Http\Controllers\web\Flash_Sale;
use App\Models\FlashSale;

Route::get('dashboard/users/lock-history', [UserController::class, 'lockHistory'])
    ->name('users.lock-history')->middleware('dashboard.auth', 'permission:Mở khóa tài khoản');

Route::get('/contact', [ContactController::class, 'hello'])->name('contact');
Route::get('/verify', [ContactController::class, 'verify'])->name('verify');
Route::post('contact-send', [ContactController::class, 'send'])->name('contact-send');
Route::get('/wards', [OrderController::class, 'getWards']);



Route::middleware(['cache'])->group(function () {
    Auth::routes();
});
Route::middleware([CheckUserStatus::class])->group(function () {

    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('shop', [ProductController::class, 'index'])->name('home.shop');
    Route::post('/orders/{id}/update', [InfoController::class, 'update'])->name('order.update');


    Route::get('/search', [SearchController::class, 'index'])->name('search');
    Route::get('/search/suggestions', [SearchController::class, 'suggestions']);
    Route::get('/search/filter', [SearchController::class, 'search'])->name('search.filter');
    Route::get('/search/trending-categories', [SearchController::class, 'trendingCategories']);
    // Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store')->middleware(['auth', 'throttle:5,1']);
    Route::post('/reviews', [InfoController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/list/{product_id}', [ReviewController::class, 'list'])->name('reviews.list');


    Route::post('add-to-cart/{id}', [CartController::class, 'add_to_cart'])->middleware('auth')->name('cart.add');

    // Route::post('add-to-cart/{id}', [CartController::class, 'add_to_cart'])->middleware('auth');
    Route::post('/order/{id}/cancel', [InfoController::class, 'cancel'])->name('order.cancel');
    Route::get('info', [InfoController::class, 'account'])->name('home.info')->middleware('auth', 'cache');
    Route::get('show/{id}', [InfoController::class, 'orderDetail'])->name('home.orderDetail')->middleware('auth', 'cache');
    Route::get('aonam/{slug}', [ProductDetailController::class, 'index'])->name('home.show');
    Route::get('cart', action: [CartController::class, 'index'])->name('home.cart');
    Route::delete('/cart/delete-selected', [CartController::class, 'deleteSelected'])->name('cart.deleteSelected');
    Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
    Route::post('/cart/calculate-total', [CartController::class, 'calculateTotal'])->name('cart.calculateTotal');
    Route::post('/cart/apply-voucher', [CartController::class, 'applyVoucher'])->name('cart.applyVoucher');
    Route::get('/cart/remove-voucher', [CartController::class, 'removeVoucher'])->name('cart.removeVoucher');
    Route::post('/cart/update-selected-ajax', [CartController::class, 'ajaxUpdateSelected'])->name('cart.ajaxUpdateSelected');

    Route::get('/account/orders', [InfoController::class, 'filterOrders'])->name('account.orders');
    Route::middleware(['auth'])->group(function () {
        Route::get('checkout', [OrderController::class, 'index'])->name('home.checkout');
        Route::post('checkout', [OrderController::class, 'processCheckout'])->name('home.processCheckout');
        Route::post('checkout/update-shipping-type', [OrderController::class, 'updateShippingType'])->name('checkout.updateShippingType');
        Route::get('done', [OrderController::class, 'done'])->name('home.done');

        // Address management
        Route::get('addresses', [AddressBookController::class, 'index'])->name('addresses.index');
        Route::post('addresses', [AddressBookController::class, 'store'])->name('addresses.store');
        Route::put('addresses/{id}', [AddressBookController::class, 'update'])->name('addresses.update');
        Route::delete('addresses/{id}', [AddressBookController::class, 'destroy'])->name('addresses.destroy');
        Route::get('addresses/wards', [AddressBookController::class, 'getWards'])->name('addresses.wards');
    });

    // Dashboard Authentication Routes
    //Route::get('dashboard/login', [DashboardController::class, 'login'])->name('dashboard.login')->middleware('dashboard.guest');
    //Route::post('dashboard/login', [DashboardController::class, 'authenticate'])->name('dashboard.authenticate')->middleware('dashboard.guest');
    //Route::post('dashboard/logout', [DashboardController::class, 'logout'])->name('dashboard.logout');

    // Dashboard Main Route (protected)
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index')->middleware('dashboard.auth');



    // Xác thực tài khoản

    Auth::routes(['verify' => true]);

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
        ->name('home');


    // Chatbot

    Route::post('/chat', [ChatBotController::class, 'reply']);
    // Login google
    Route::get('/auth/redirect/google', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
    Route::get('auth/callback/google', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

    // client voucher
    Route::post('accept_voucher/{id}', [VouchersController::class, 'accept_voucher'])->middleware('auth');

    Route::post('change-password', [ChangepasswordController::class, 'changePassword'])->name('change-password');
    Route::put('update-profile', [InfoController::class, 'updateProfile'])->name('update-profile');



    Route::get('/search', [SearchController::class, 'index'])->name('search');
    Route::get('/search/suggestions', [SearchController::class, 'suggestions']);
    Route::get('/search/filter', [SearchController::class, 'search'])->name('search.filter');
    Route::get('/search/trending-categories', [SearchController::class, 'trendingCategories']);
    // Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store')->middleware(['auth', 'throttle:5,1']);
    Route::get('/reviews/list/{product_id}', [ReviewController::class, 'list'])->name('reviews.list');


    Route::post('add-to-cart/{id}', [CartController::class, 'add_to_cart'])->middleware('auth');


    Route::post('/order/{id}/cancel', [InfoController::class, 'cancel'])->name('order.cancel');
    Route::get('info', [InfoController::class, 'account'])->name('home.info')->middleware('auth', 'cache');
    Route::get('show/{id}', [InfoController::class, 'orderDetail'])->name('home.orderDetail')->middleware('auth', 'cache');
    Route::get('aonam/{slug}', [ProductDetailController::class, 'index'])->name('home.show');
    Route::get('cart', action: [CartController::class, 'index'])->name('home.cart');
    Route::delete('/cart/delete-selected', [CartController::class, 'deleteSelected'])->name('cart.deleteSelected');
    Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
    Route::post('/cart/calculate-total', [CartController::class, 'calculateTotal'])->name('cart.calculateTotal');
    Route::post('/cart/apply-voucher', [CartController::class, 'applyVoucher'])->name('cart.applyVoucher');
    Route::get('/cart/remove-voucher', [CartController::class, 'removeVoucher'])->name('cart.removeVoucher');
    Route::post('/cart/update-selected-ajax', [CartController::class, 'ajaxUpdateSelected'])->name('cart.ajaxUpdateSelected');

    Route::get('/account/orders', [InfoController::class, 'filterOrders'])->name('account.orders');
    Route::middleware(['auth'])->group(function () {
        Route::get('checkout', [OrderController::class, 'index'])->name('home.checkout');
        Route::post('checkout', [OrderController::class, 'processCheckout'])->name('home.processCheckout');
        Route::post('checkout/update-shipping-type', [OrderController::class, 'updateShippingType'])->name('checkout.updateShippingType');
        Route::get('done', [OrderController::class, 'done'])->name('home.done');

        // Address management
        Route::get('addresses', [AddressBookController::class, 'index'])->name('addresses.index');
        Route::post('addresses', [AddressBookController::class, 'store'])->name('addresses.store');
        Route::put('addresses/{id}', [AddressBookController::class, 'update'])->name('addresses.update');
        Route::delete('addresses/{id}', [AddressBookController::class, 'destroy'])->name('addresses.destroy');
        Route::get('addresses/wards', [AddressBookController::class, 'getWards'])->name('addresses.wards');
    });

    // Dashboard Authentication Routes
    //Route::get('dashboard/login', [DashboardController::class, 'login'])->name('dashboard.login')->middleware('dashboard.guest');
    //Route::post('dashboard/login', [DashboardController::class, 'authenticate'])->name('dashboard.authenticate')->middleware('dashboard.guest');
    //Route::post('dashboard/logout', [DashboardController::class, 'logout'])->name('dashboard.logout');

    // Dashboard Main Route (protected)
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index')->middleware('dashboard.auth');



    // Xác thực tài khoản

    Auth::routes(['verify' => true]);

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
        ->name('home');


    // Chatbot

    Route::post('/chat', [ChatBotController::class, 'reply']);
    // Login google
    Route::get('/auth/redirect/google', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
    Route::get('auth/callback/google', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

    // client voucher
    Route::post('accept_voucher/{id}', [VouchersController::class, 'accept_voucher'])->middleware('auth');

    Route::post('change-password', [ChangepasswordController::class, 'changePassword'])->name('change-password');
    Route::put('update-profile', [InfoController::class, 'updateProfile'])->name('update-profile');




    Route::prefix('dashboard')->middleware('dashboard.auth')->group(function () {
        // Voucher Management
        Route::get('/voucher/{id}', [VouchersController::class, 'show'])->name('dashboard.voucher')->middleware('permission:Xem trang voucher');
        Route::post('voucher/add_voucher', [VouchersController::class, 'store'])->middleware('permission:Tạo voucher');
        Route::get('voucher/{action}/{id}', [VouchersController::class, 'detail'])->middleware('permission:Xem trang voucher');
        Route::get('voucher/{action}/{id}/edit', [VouchersController::class, 'edit'])->middleware('permission:Sửa voucher');
        Route::post('voucher/{id}/update', [VouchersController::class, 'update'])->middleware('permission:Sửa voucher');
        Route::post('voucher/ads', [VouchersController::class, 'ads'])->middleware(['throttle:5,1', 'permission:Quản lý quảng cáo voucher']);
        Route::post('voucher/disable/{id}', [VouchersController::class, 'disable'])->middleware('permission:Vô hiệu hóa voucher');
        Route::post('voucher/active/{id}', [VouchersController::class, 'active'])->middleware('permission:Kích hoạt voucher');
        Route::post('voucher/delete{id}', [VouchersController::class, 'delete'])->name('delete')->middleware('permission:Xóa voucher');

        // Product Management
        Route::resource('products', ProductsController::class)->middleware('permission:Quản lý Sản phẩm');
        Route::get('/products/variant-partial', [ProductsController::class, 'renderVariantPartial'])
            ->name('products.variant-partial')->middleware('permission:Xem trang sản phẩm');
        Route::post('/products/{id}/restore', [ProductsController::class, 'restore'])->name('products.restore')->middleware('permission:Khôi phục sản phẩm');
        Route::post('/products/upload-temp-image', [ProductsController::class, 'uploadTempImage'])->name('products.uploadTempImage')->middleware('permission:Tải ảnh sản phẩm');
        Route::post('/products/upload-temp-variant-image', [ProductsController::class, 'uploadTempVariantImage'])->name('products.uploadTempVariantImage')->middleware('permission:Tải ảnh biến thể');
        Route::delete('/products/{id}/force-delete', [ProductsController::class, 'forceDelete'])->name('products.forceDelete');
        Route::post('add-flash-sale/{id}', [ProductsController::class, 'add_flash_sale'])->name('addflashsale')->middleware('permission:Thêm sản phẩm vào flash sale');
        Route::get('remove-flash-sale/{id}', [ProductsController::class, 'remove_flashsale'])->middleware('permission:Xóa sản phẩm khỏi flash sale');

        // Category Management
        Route::resource('categories', CategoriesController::class)->middleware('permission:Quản lý Danh mục');
        Route::post('/categories/{id}/restore', [CategoriesController::class, 'restore'])->name('categories.restore')->middleware('permission:Khôi phục danh mục');

        // Order Management
        Route::get('order', [OrderController::class, 'db_order'])->name('dashboard.order')->middleware('permission:Xem trang đơn hàng');
        Route::post('order/change/{id}', [OrderController::class, 'db_order_change'])->name('dashboard.order.change')->middleware('permission:Thay đổi trạng thái đơn hàng');
        Route::get('order/{id}', [OrderController::class, 'db_order_show'])->middleware('permission:Xem trang đơn hàng');
        Route::post('order/change-address/{id}', [OrderController::class, 'change_address'])->middleware('permission:Thay đổi địa chỉ đơn hàng');
        Route::post('filter-order', [OrderController::class, 'db_order'])->name('filter.order');

        // Refund Management
        Route::get('refund', [RefundMoneyController::class, 'index'])->name('dashboard.order.refund')->middleware('permission:Xem trang hoàn tiền');
        Route::get('refund/{id}', [RefundMoneyController::class, 'show'])->name('dashboard.order.refund.show')->middleware('permission:Xem trang hoàn tiền');
        Route::post('change/refund/{id}', [RefundMoneyController::class, 'change'])->name('dashboard.change.refund')->middleware('permission:Phê duyệt hoàn tiền');


        // Flash Sale Management
        Route::get('flash-sale', [FlashSaleController::class, 'index'])->name('flash-sale')->middleware('permission:Xem trang flash sale');
        Route::post('flash-sale/tao-moi', [FlashSaleController::class, 'create'])->name('flash-sales.create')->middleware('permission:Tạo flash sale');
        Route::get('flash-sale/show/{id}', [FlashSaleController::class, 'show'])->name('flash-sales.show')->middleware('permission:Xem trang flash sale');
        Route::get('flash-sale/edit/{id}', [FlashSaleController::class, 'edit'])->name('flash-sales.edit')->middleware('permission:Sửa flash sale');
        Route::post('flash-sale/update/{id}', [FlashSaleController::class, 'update'])->name('flash-sales.update')->middleware('permission:Sửa flash sale');
        Route::post('flash-sale/delete/{id}', [FlashSaleController::class, 'destroy'])->name('flash-sales.destroy')->middleware('permission:Xóa flash sale');
        Route::get('flash-sale/tao-moi-items/{id}', [FlashSaleItemsController::class, 'create'])->name('flash-sales-items.create')->middleware('permission:Quản lý sản phẩm flash sale');
        Route::post('create-items-flashsale/{id}', [FlashSaleItemsController::class, 'add_flash_sale_items'])->name('create-items-flashsale')->middleware('permission:Quản lý sản phẩm flash sale');
        Route::post('remove-items-flashsale/{id}', [FlashSaleItemsController::class, 'remove_flash_sale_items'])->name('remove-items-flashsale')->middleware('permission:Quản lý sản phẩm flash sale');
        Route::post('active-flash-sale/{id}', [FlashSaleController::class, 'change_active'])->name('active-flash-sale')->middleware('permission:Kích hoạt flash sale');

        // Revenue Management
        Route::get('thong-ke', [RevenueController::class, 'index'])->name('dashboard.revenue')->middleware('permission:Xem trang doanh thu');
        Route::post('fillter-revenue', [RevenueController::class, 'index'])->name('dashboard.order.fillter')->middleware('permission:Lọc dữ liệu doanh thu');

        // Color & Size Management
        Route::resource('colors', ColorController::class)->middleware('permission:Quản lý Màu sắc');
        Route::resource('sizes', SizeController::class)->middleware('permission:Quản lý Kích thước');

        // Product Variant Management
        Route::get('variants', [ProductVariantsController::class, 'index'])->name('variants.index')->middleware('permission:Xem trang biến thể');
        Route::get('variants/create', [ProductVariantsController::class, 'create'])->name('variants.create')->middleware('permission:Tạo biến thể');
        Route::post('variants/store', [ProductVariantsController::class, 'store'])->name('variants.store')->middleware('permission:Tạo biến thể');
        Route::get('variants/{id}', [ProductVariantsController::class, 'show'])->name('variants.show')->middleware('permission:Xem trang biến thể');
        Route::get('variants/{id}/edit', [ProductVariantsController::class, 'edit'])->name('variants.edit')->middleware('permission:Sửa biến thể');
        Route::put('variants/{id}/update', [ProductVariantsController::class, 'update'])->name('variants.update')->middleware('permission:Sửa biến thể');
        Route::delete('variants/{id}', [ProductVariantsController::class, 'destroy'])->name('variants.destroy')->middleware('permission:Xóa biến thể');
        Route::get('products/{product}/variants', [ProductVariantsController::class, 'showVariants'])->name('products.variants')->middleware('permission:Xem trang biến thể');
        Route::post('variants/{id}/restore', [ProductVariantsController::class, 'restore'])->name('variants.restore')->middleware('permission:Khôi phục biến thể');

        // User Management
        Route::post('users/lock', [UserController::class, 'lock'])->name('users.lock')->middleware('permission:Khóa tài khoản');
        Route::resource('users', UserController::class)->except(['show'])->middleware('permission:Quản lý Tài khoản');
        Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show')->middleware('permission:Xem trang tài khoản');
        Route::post('/users/bulk-delete', [UserController::class, 'bulkDelete'])->name('users.bulk-delete')->middleware('permission:Xóa hàng loạt tài khoản');
        Route::post('/users/{user}/unlock', [UserController::class, 'unlock'])->name('users.unlock')->middleware('permission:Mở khóa tài khoản');
    });


    Route::prefix('dashboard')->name('dashboard.')->middleware('dashboard.auth')->group(function () {
        // Role Management
        Route::resource('roles', RoleController::class)->middleware('permission:Quản lý Vai trò');
        Route::post('roles/order', [RoleController::class, 'order'])->name('roles.order')->middleware('permission:Sắp xếp vai trò');

        // Permission Management
        Route::resource('permissions', PermissionController::class)->middleware('permission:Quản lý Quyền hạn');
        Route::post('permissions/order', [PermissionController::class, 'order'])->name('permissions.order')->middleware('permission:Sắp xếp quyền hạn');
    });

    // Quản lý bình luận

    Route::prefix('dashboard')->name('dashboard.')->middleware('dashboard.auth')->group(function () {
        // Comment Management
        Route::get('comments', [CommentController::class, 'index'])->name('comments.index')->middleware('permission:Xem trang bình luận');
        Route::put('comments/update/{id}', [CommentController::class, 'update'])->name('comments.update')->middleware('permission:Sửa bình luận');
        Route::post('comments/{review}/reply', [ReviewReplyController::class, 'store'])->name('comments.reply')->middleware('permission:Trả lời bình luận');
        Route::delete('comments/reply/{id}', [ReviewReplyController::class, 'destroy'])->name('comments.reply.destroy')->middleware('permission:Xóa trả lời bình luận');
    });



    // VNPAY Payment Routes
    Route::post('/vnpay/ipn', [OrderController::class, 'vnpayIpn'])->name('vnpay.ipn');
    Route::post('/order/{id}/refund', [RefundMoneyController::class, 'store'])->name('order.refund')->middleware('auth');
    Route::get('/order/{id}/refund-request', [App\Http\Controllers\RefundMoneyController::class, 'showRefundRequest'])->name('order.refund.request')->middleware('auth');
    Route::post('/order/{id}/upload-image', [OrderController::class, 'uploadUserImage'])->name('order.upload.image')->middleware('auth');
    Route::post('/order/{id}/submit-confirmation', [InfoController::class, 'submitUserConfirmation'])->name('order.submit.confirmation')->middleware('auth');


    Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verifyWithoutAuth'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
});



// web.php
Route::get('/flash-sales/{id}/products', [HomeController::class, 'getProducts'])->name('flash-sales.products');