<?php

return [
    'salt' => env('HASHIDS_SALT', 'polyflix_secret_key'), // Salt để mã hóa riêng biệt
    'length' => 10, // Độ dài tối thiểu chuỗi hashid
    'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890', // Ký tự cho chuỗi
];
