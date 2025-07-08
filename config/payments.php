<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Payment Gateway
    |--------------------------------------------------------------------------
    |
    | Cổng thanh toán mặc định sẽ được sử dụng khi không chỉ định
    |
    */
    'default' => env('PAYMENT_DEFAULT_GATEWAY', 'zalopay'),

    /*
    |--------------------------------------------------------------------------
    | ZaloPay Configuration
    |--------------------------------------------------------------------------
    |
    | Cấu hình cho ZaloPay Sandbox
    | Đăng ký tại: https://sandbox.zalopay.com.vn/
    |
    */
    'zalopay' => [
        'app_id' => env('ZALOPAY_APP_ID', '2553'),
        'key1' => env('ZALOPAY_KEY1', 'PcY4iZIKFCIdgZvA6ueMcMHHUbRLYjPL'),
        'key2' => env('ZALOPAY_KEY2', 'kLtgPl8HHhfvMuDHPwKfgfsY4Ydm9eIz'),
        'endpoint' => env('ZALOPAY_ENDPOINT', 'https://sb-openapi.zalopay.vn/v2/create'),
        'query_endpoint' => env('ZALOPAY_QUERY_ENDPOINT', 'https://sb-openapi.zalopay.vn/v2/query'),
        'callback_url' => env('ZALOPAY_CALLBACK_URL', 'https://your-ngrok-url.ngrok.io/api/payments/zalopay/callback'),
        'redirect_url' => env('ZALOPAY_REDIRECT_URL', 'http://localhost:8000/thanh-toan/ket-qua'),
        'environment' => env('ZALOPAY_ENVIRONMENT', 'sandbox'), // sandbox hoặc production
    ],

    /*
    |--------------------------------------------------------------------------
    | VNPay Configuration
    |--------------------------------------------------------------------------
    |
    | Cấu hình cho VNPay
    | Đăng ký tại: https://sandbox.vnpayment.vn/
    |
    */
    'vnpay' => [
        'tmn_code' => env('VNPAY_TMN_CODE', 'TMNCODE'), // Mã website của merchant
        'hash_secret' => env('VNPAY_HASH_SECRET', 'HASHSECRET'), // Chuỗi bí mật
        'url' => env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
        'return_url' => env('VNPAY_RETURN_URL', 'http://localhost:8000/api/payments/vnpay/callback'),
        'version' => '2.1.0',
        'command' => 'pay',
        'currency_code' => 'VND',
        'locale' => 'vn',
        'environment' => env('VNPAY_ENVIRONMENT', 'sandbox'), // sandbox hoặc production
    ],

    /*
    |--------------------------------------------------------------------------
    | MoMo Configuration (Đã có sẵn)
    |--------------------------------------------------------------------------
    |
    | Cấu hình cho MoMo - giữ nguyên config hiện tại
    |
    */
    'momo' => [
        'app_info' => env('MOMO_APP_INFO', 'PolyFlix Payment'),
        'partner_code' => env('MOMO_PARTNER_CODE', 'MOMO'),
        'access_key' => env('MOMO_ACCESS_KEY', 'F8BBA842ECF85'),
        'secret_key' => env('MOMO_SECRET_KEY', 'K951B6PE1waDMi640xX08PD3vg6EkVlz'),
        'endpoint' => env('MOMO_ENDPOINT', 'https://test-payment.momo.vn/v2/gateway/api/create'),
        'redirect_url' => env('MOMO_REDIRECT_URL', 'http://localhost:8000/thanh-toan/ket-qua'),
        'ipn_url' => env('MOMO_IPN_URL', 'https://your-ngrok-url.ngrok.io/api/payments/momo/callback'),
        'extra_data' => '',
        'request_type' => 'payWithATM',
        'signature' => '',
        'environment' => env('MOMO_ENVIRONMENT', 'sandbox'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Ngrok Configuration
    |--------------------------------------------------------------------------
    |
    | Cấu hình cho Ngrok - tool tạo public URL cho localhost
    |
    */
    'ngrok' => [
        'enabled' => env('NGROK_ENABLED', false),
        'url' => env('NGROK_URL', 'https://your-domain.ngrok.io'),
        'auth_token' => env('NGROK_AUTH_TOKEN', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Transaction Settings
    |--------------------------------------------------------------------------
    |
    | Cài đặt chung cho giao dịch thanh toán
    |
    */
    'transaction' => [
        'timeout' => env('PAYMENT_TIMEOUT', 900), // Thời gian timeout (15 phút)
        'currency' => env('PAYMENT_CURRENCY', 'VND'),
        'prefix' => [
            'zalopay' => 'ZLP',
            'vnpay' => 'VNP',
            'momo' => 'MMO',
        ],
    ],
];
