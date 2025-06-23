<?php

return [
    'salt' => env('HASHIDS_SALT', 'polyflix_secret_key'),
    'length' => 16,
    'alphabet' => 'abcdefghijklmnopqrstuvwxyz1234567890',
];