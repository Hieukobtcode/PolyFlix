<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Admin\LienHeController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\PhimController;
use App\Http\Controllers\Admin\VaiTroController;
use App\Http\Controllers\Admin\BaiVietController;
use App\Http\Controllers\Admin\ChiNhanhController;
use App\Http\Controllers\Admin\KhuyenMaiController;
use App\Http\Controllers\Admin\TheLoaiPhimController;

Route::get('/', function () {
    return view('welcome');
});

// Route tạm thời để kiểm tra dữ liệu
Route::get('/check-data', function () {
    $khuyenMais = DB::table('khuyen_mais')->get();
    $khuyenMaiChiNhanhs = DB::table('khuyen_mai_chi_nhanhs')->get();
    $lichSuSuDung = DB::table('lich_su_su_dung_khuyen_mais')->get();

    return [
        'khuyen_mais' => $khuyenMais,
        'khuyen_mai_chi_nhanhs' => $khuyenMaiChiNhanhs,
        'lich_su_su_dung' => $lichSuSuDung
    ];
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.lien-he.index');
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

    // Quản lý chi nhánh
    Route::resource('chi-nhanh', ChiNhanhController::class);

    // Quản lý vai trò
    Route::resource('vai-tro', VaiTroController::class);

    // Quản lý banners
    Route::resource('banners', BannerController::class);

    // Các chức năng bổ sung cho quản lý khuyến mãi
    Route::prefix('khuyen-mai')->name('khuyen-mai.')->group(function () {
        Route::post('{khuyenMai}/assign-chi-nhanh', [KhuyenMaiController::class, 'assignToChiNhanh'])->name('assign-chi-nhanh');
        Route::get('thong-ke-su-dung', [KhuyenMaiController::class, 'thongKeSuDung'])->name('thong-ke-su-dung');
    });

    // Quản lý khuyến mãi
    Route::resource('khuyen-mai', KhuyenMaiController::class);
});
