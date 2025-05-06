<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LienHeController;
use App\Http\Controllers\Admin\TheLoaiPhimController;
use App\Http\Controllers\Admin\PhimController;
use App\Http\Controllers\Admin\BaiVietController;
use App\Http\Controllers\Admin\ChiNhanhController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function () {
    // Trang dashboard của admin
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

    // Quản lý thể loại phim
    Route::resource('the-loai-phim', TheLoaiPhimController::class);

    // Các chức năng xóa mềm cho quản lý phim
    Route::prefix('phim')->name('phim.')->group(function () {
        Route::get('trash', [PhimController::class, 'trash'])->name('trash');
        Route::patch('{phim}/restore', [PhimController::class, 'restore'])->name('restore');
        Route::delete('{phim}/force-delete', [PhimController::class, 'forceDelete'])->name('force-delete');
    });

    // Quản lý phim
    Route::resource('phim', PhimController::class);

    // Quản lý bài viết
    Route::resource('bai-viet', BaiVietController::class);

    // Quản lý chi nhánh
    Route::resource('chi-nhanh', ChiNhanhController::class);
});
