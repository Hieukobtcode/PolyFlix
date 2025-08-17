<?php

use App\Http\Controllers\Client\ThanhToanController;
use Illuminate\Support\Facades\Route;


Route::post('/zalopay/callback', [ThanhToanController::class, 'callBack']);

// API kiểm tra mã khuyến mãi
Route::post('/check-promotion', [\App\Http\Controllers\Client\KhuyenMaiController::class, 'checkCode']);
