<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LienHeController;
use App\Http\Controllers\Admin\TheLoaiPhimController;
use App\Http\Controllers\Admin\PhimController;
use App\Http\Controllers\Admin\BaiVietController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Quản lý liên hệ
    Route::resource('lien-he', LienHeController::class)->names([
        'index' => 'lien-he.index',
        'create' => 'lien-he.create',
        'store' => 'lien-he.store',
        'show' => 'lien-he.show',
        'edit' => 'lien-he.edit',
        'update' => 'lien-he.update',
        'destroy' => 'lien-he.destroy',
    ]);

    // Các chức năng bổ sung cho quản lý liên hệ
    Route::prefix('lien-he')->name('lien-he.')->group(function () {
        Route::get('dashboard', [LienHeController::class, 'dashboard'])->name('dashboard');
        Route::get('export', [LienHeController::class, 'export'])->name('export');
        Route::post('{lienHe}/notes', [LienHeController::class, 'addNote'])->name('add-note');
        Route::patch('{lienHe}/status', [LienHeController::class, 'updateStatus'])->name('update-status');
        Route::post('{lienHe}/send-email', [LienHeController::class, 'sendEmail'])->name('send-email');
        Route::post('bulk-action', [LienHeController::class, 'bulkAction'])->name('bulk-action');
    });

    // Quản lý thể loại phim & phim
    Route::resource('the-loai-phim', TheLoaiPhimController::class);
    Route::resource('phim', PhimController::class);

    // Quản lý bài viết
    Route::resource('bai-viet', BaiVietController::class);
});
