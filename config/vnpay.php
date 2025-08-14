<?php

return [
    /*
    |--------------------------------------------------------------------------
    | VNPAY Payment Configuration
    |--------------------------------------------------------------------------
    |
    | Cấu hình thanh toán VNPAY cho môi trường test và production
    |
    */

    'test' => [
        'tmn_code' => env('VNPAY_TEST_TMN_CODE', '3KPU6ZJV'),
        'hash_secret' => env('VNPAY_TEST_HASH_SECRET', 'C9NGMA6EMSEFVMJRWANCY8FQZPC4PQP7'),
        'url' => env('VNPAY_TEST_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
        'return_url' => env('VNPAY_TEST_RETURN_URL', 'http://127.0.0.1:8000/vnpay/return'),
        'api_url' => env('VNPAY_TEST_API_URL', 'https://sandbox.vnpayment.vn/merchant_webapi/api/transaction'),
    ],

    'production' => [
        'tmn_code' => env('VNPAY_PROD_TMN_CODE'),
        'hash_secret' => env('VNPAY_PROD_HASH_SECRET'),
        'url' => env('VNPAY_PROD_URL', 'https://pay.vnpay.vn/vpcpay.html'),
        'return_url' => env('VNPAY_PROD_RETURN_URL', 'https://yourdomain.com/vnpay/return'),
        'api_url' => env('VNPAY_PROD_API_URL', 'https://pay.vnpay.vn/merchant_webapi/api/transaction'),
    ],

    // Sử dụng môi trường nào (test hoặc production)
    'environment' => env('VNPAY_ENVIRONMENT', 'test'),
    // Các thông số khác
    'version' => env('VNPAY_VERSION', '2.1.0'),
    'currency' => env('VNPAY_CURRENCY', 'VND'),
    'locale' => env('VNPAY_LOCALE', 'vn'),
    'order_type' => env('VNPAY_ORDER_TYPE', 'other'),
    'expire_minutes' => env('VNPAY_EXPIRE_MINUTES', 15),
]; 