<?php

return [
    'merchant_id' => env('NIUBIZ_ENV') === 'produccion' ? env('NIUBIZ_MERCHANT_ID_PROD') : env('NIUBIZ_MERCHANT_ID_DEV'),
    'access_key' => env('NIUBIZ_ENV') === 'produccion' ? env('NIUBIZ_ACCESS_KEY_PROD') : env('NIUBIZ_ACCESS_KEY_DEV'),
    'secret_key' => env('NIUBIZ_ENV') === 'produccion' ? env('NIUBIZ_SECRET_KEY_PROD') : env('NIUBIZ_SECRET_KEY_DEV'),
    'base_url' => env('NIUBIZ_ENV') === 'produccion' ? env('NIUBIZ_BASE_URL_PROD') : env('NIUBIZ_BASE_URL_DEV'),

    'user' => env('NIUBIZ_USER'),
    'password' => env('NIUBIZ_PASSWORD'),
    'security_url' => env('NIUBIZ_SECURITY_URL'),
    'session_url' => env('NIUBIZ_SESSION_URL'),
    'authorization_url' => env('NIUBIZ_AUTHORIZATION_URL'),
    'merchantt_id' => env('NIUBIZ_MERCHANT_ID'),
    'js_url' => env('NIUBIZ_JS_URL'),
];
