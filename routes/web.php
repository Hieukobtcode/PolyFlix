<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LienHeController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.lien-he.index');
    })->name('admin.dashboard');

    // Quản lý liên hệ
    Route::resource('lien-he', LienHeController::class)->names([
        'index' => 'admin.lien-he.index',
        'create' => 'admin.lien-he.create',
        'store' => 'admin.lien-he.store',
        'show' => 'admin.lien-he.show',
        'edit' => 'admin.lien-he.edit',
        'update' => 'admin.lien-he.update',
        'destroy' => 'admin.lien-he.destroy',
    ]);

    // Các chức năng bổ sung cho quản lý liên hệ
    Route::prefix('lien-he')->name('admin.lien-he.')->group(function () {
        // Dashboard thống kê
        Route::get('dashboard', [LienHeController::class, 'dashboard'])->name('dashboard');

        // Xuất dữ liệu
        Route::get('export', [LienHeController::class, 'export'])->name('export');

        // Thêm ghi chú
        Route::post('{lienHe}/notes', [LienHeController::class, 'addNote'])->name('add-note');

        // Cập nhật trạng thái nhanh
        Route::patch('{lienHe}/status', [LienHeController::class, 'updateStatus'])->name('update-status');

        // Gửi email phản hồi
        Route::post('{lienHe}/send-email', [LienHeController::class, 'sendEmail'])->name('send-email');

        // Xử lý hàng loạt
        Route::post('bulk-action', [LienHeController::class, 'bulkAction'])->name('bulk-action');
    });
});
