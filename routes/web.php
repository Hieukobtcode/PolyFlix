<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    });

    Route::resource('the-loai-phim', 'App\Http\Controllers\Admin\TheLoaiPhimController');
    Route::resource('phim', 'App\Http\Controllers\Admin\PhimController');
    
    // 👉 Route quản lý bài viết
    Route::resource('bai-viet', 'App\Http\Controllers\Admin\BaiVietController');
});
