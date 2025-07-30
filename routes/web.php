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
use App\Http\Controllers\FlashSaleController;
use App\Http\Controllers\FlashSaleItemsController;
use App\Http\Controllers\web\Flash_Sale;
use App\Models\FlashSale;

// Route::get('aonam/{flash_sale_id}/{variant_id}', [Flash_Sale::class, 'index'])->name('flashsale.show');

Route::get('/wards', [OrderController::class, 'getWards']);

Route::middleware(['cache'])->group(function () {
    Auth::routes();
});
Route::middleware([CheckUserStatus::class])->group(function () {
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('shop', [ProductController::class, 'index'])->name('home.shop');
Route::get('/flash', [ProductController::class, 'flash']);
// Flash Sale Routes

Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/search/suggestions', [SearchController::class, 'suggestions']);
Route::get('/search/filter', [SearchController::class, 'search'])->name('search.filter');
Route::get('/search/trending-categories', [SearchController::class, 'trendingCategories']);
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store')->middleware(['auth', 'throttle:5,1']);
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
    Route::get('/voucher/{id}', [VouchersController::class, 'show'])->name('dashboard.voucher');
    Route::post('voucher/add_voucher', [VouchersController::class, 'store']);
    Route::get('voucher/{action}/{id}', [VouchersController::class, 'detail']);
    Route::get('voucher/{action}/{id}/edit', [VouchersController::class, 'edit']);
    Route::post('voucher/{id}/update', [VouchersController::class, 'update']);
    Route::post('voucher/ads', [VouchersController::class, 'ads'])->middleware('throttle:5,1');
    Route::post('voucher/disable/{id}', [VouchersController::class, 'disable']);
    Route::post('voucher/active/{id}', [VouchersController::class, 'active']);
    Route::resource('products', ProductsController::class);
    Route::get('/products/variant-partial', [ProductsController::class, 'renderVariantPartial'])
        ->name('products.variant-partial');


    Route::post('/products/{id}/restore', [ProductsController::class, 'restore'])->name('products.restore');
    Route::resource('categories', CategoriesController::class);
    Route::post('/categories/{id}/restore', [CategoriesController::class, 'restore'])->name('categories.restore');

    // phần order
    Route::get('order', [OrderController::class, 'db_order'])->name('dashboard.order');
    Route::post('order/change/{id}', [OrderController::class, 'db_order_change'])->name('dashboard.order.change');
    Route::get('order/{id}', [OrderController::class, 'db_order_show']);
    Route::get('refund', [RefundMoneyController::class, 'index'])->name('dashboard.order.refund');
    Route::get('refund/{id}', [RefundMoneyController::class, 'show'])->name('dashboard.order.refund.show');
    Route::post('change/refund/{id}', [RefundMoneyController::class, 'change'])->name('dashboard.change.refund');
    Route::post('order/change-address/{id}', [OrderController::class, 'change_address']);
    // route flashsale
    route::get('flash-sale',[FlashSaleController::class,'index'])->name('flash-sale');
    route::post('flash-sale/tao-moi',[FlashSaleController::class,'create'])->name('flash-sales.create');
    route::get('flash-sale/show/{id}',[FlashSaleController::class,'show'])->name('flash-sales.show');
    route::get('flash-sale/edit/{id}',[FlashSaleController::class,'edit'])->name('flash-sales.edit');
    route::post('flash-sale/update/{id}',[FlashSaleController::class,'update'])->name('flash-sales.update');
    route::post('flash-sale/delete/{id}',[FlashSaleController::class,'destroy'])->name('flash-sales.destroy');
    route::get('flash-sale/tao-moi-items/{id}',[FlashSaleItemsController::class,'create'])->name('flash-sales-items.create');
    route::post('add-flash-sale/{id}',[ProductsController::class,'add_flash_sale'])->name('addflashsale');
    route::get('remove-flash-sale/{id}',[ProductsController::class,'remove_flashsale']);
    route::post('create-items-flashsale/{id}',[FlashSaleItemsController::class,'add_flash_sale_items'])->name('create-items-flashsale');
    route::get('remove-items-flashsale/{id}',[FlashSaleItemsController::class,'remove_flash_sale_items'])->name('remove-items-flashsale');
    route::post('active-flash-sale/{id}',[FlashSaleController::class,'change_active'])->name('active-flash-sale');
    // route thống kê
    Route::get('thong-ke', [RevenueController::class, 'index'])->name('dashboard.revenue');
    Route::post('fillter-revenue', [RevenueController::class, 'index'])->name('dashboard.order.fillter');
    // Route resource cho color và size
    Route::resource('colors', ColorController::class);
    Route::resource('sizes', SizeController::class);


    Route::get('variants', [ProductVariantsController::class, 'index'])->name('variants.index');
    Route::get('variants/create', [ProductVariantsController::class, 'create'])->name('variants.create');
    Route::post('variants/store', [ProductVariantsController::class, 'store'])->name('variants.store');
    Route::get('variants/{id}', [ProductVariantsController::class, 'show'])->name('variants.show');
    Route::get('variants/{id}/edit', [ProductVariantsController::class, 'edit'])->name('variants.edit');
    Route::put('variants/{id}/update', [ProductVariantsController::class, 'update'])->name('variants.update');
    Route::delete('variants/{id}', [ProductVariantsController::class, 'destroy'])->name('variants.destroy');
    Route::get('products/{product}/variants', [ProductVariantsController::class, 'showVariants'])->name('products.variants');
    Route::post('variants/{id}/restore', [ProductVariantsController::class, 'restore'])->name('variants.restore');
    Route::post('/products/upload-temp-image', [ProductsController::class, 'uploadTempImage'])->name('products.uploadTempImage');
    Route::post('/products/upload-temp-variant-image', [ProductsController::class, 'uploadTempVariantImage'])->name('products.uploadTempVariantImage');
    Route::post('users/lock', [UserController::class, 'lock'])->name('users.lock');
    Route::resource('users', UserController::class)->except(['show']);;
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::post('/users/bulk-delete', [UserController::class, 'bulkDelete'])->name('users.bulk-delete');

    Route::post('/users/{user}/unlock', [UserController::class, 'unlock'])->name('users.unlock');
    Route::get('order/{id}', [OrderController::class, 'db_order_show'])->name('orders.show');

});


Route::prefix('dashboard')->name('dashboard.')->middleware('dashboard.auth')->group(function () {
    // Phân quyền
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::post('roles/order', [RoleController::class, 'order'])->name('roles.order');
    Route::post('permissions/order', [PermissionController::class, 'order'])->name('permissions.order');
    //  Route::post('permission/order', [RoleController::class, 'order'])->name('permission.order');
});

    // Quản lý bình luận

Route::prefix('dashboard')->name('dashboard.')->middleware('dashboard.auth')->group(function () {
    Route::get('comments', [CommentController::class, 'index'])->name('comments.index');
    Route::put('comments/update/{id}', [CommentController::class, 'update'])->name('comments.update');

    Route::post('comments/{review}/reply', [ReviewReplyController::class, 'store'])->name('comments.reply'); // nên đặt tên rõ ràng

    Route::delete('comments/reply/{id}', [ReviewReplyController::class, 'destroy'])->name('comments.reply.destroy');
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
