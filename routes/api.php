<?php

use App\Http\Controllers\Client\ThanhToanController;
use Illuminate\Support\Facades\Route;


Route::post('/zalopay/callback', [ThanhToanController::class, 'callBack']);

