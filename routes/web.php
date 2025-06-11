<?php

use App\Http\Controllers\Admin\ComboController;
use App\Http\Controllers\Admin\DanhMucDoAnController;
use App\Http\Controllers\Admin\DoAnController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\PhimController;
use App\Http\Controllers\Admin\LienHeController;
use App\Http\Controllers\Admin\VaiTroController;
use App\Http\Controllers\Admin\BaiVietController;
use App\Http\Controllers\Admin\ChiNhanhController;
use App\Http\Controllers\Admin\KhuyenMaiController;
use App\Http\Controllers\Admin\TheLoaiPhimController;
use App\Http\Controllers\Admin\LoaiPhongController;
use App\Http\Controllers\Admin\RapphimController;
use App\Http\Controllers\Admin\CauHinhController;
use App\Http\Controllers\Admin\DinhDangPhimController;
use App\Http\Controllers\Admin\GheNgoiController;
use App\Http\Controllers\Admin\PhongChieuController;
use App\Http\Controllers\Admin\LoaiGheController;
use App\Http\Controllers\Admin\SoDoGheController;
use App\Http\Controllers\Admin\CapBacTheController;
use App\Http\Controllers\Admin\PhanQuyenController;
use App\Http\Controllers\Admin\SuatChieuController;

// Trang welcome
Route::get('/', function () {
    return view('welcome');
});



// Route tạm kiểm tra dữ liệu
Route::get('/check-data', function () {
    return [
        'khuyen_mais' => DB::table('khuyen_mais')->get(),
        'khuyen_mai_chi_nhanhs' => DB::table('khuyen_mai_chi_nhanhs')->get(),
        'lich_su_su_dung' => DB::table('lich_su_su_dung_khuyen_mais')->get(),
    ];
});

// Group route cho admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.lien-he.index');
    })->name('dashboard');

    // Quản lý liên hệ
    Route::prefix('lien-he')->name('lien-he.')->group(function () {
        Route::get('dashboard', [LienHeController::class, 'dashboard'])->name('dashboard');
        Route::get('export', [LienHeController::class, 'export'])->name('export');
        Route::post('{lienHe}/notes', [LienHeController::class, 'addNote'])->name('add-note');
        Route::patch('{lienHe}/status', [LienHeController::class, 'updateStatus'])->name('update-status');
        Route::post('{lienHe}/send-email', [LienHeController::class, 'sendEmail'])->name('send-email');
        Route::post('bulk-action', [LienHeController::class, 'bulkAction'])->name('bulk-action');
    });
    Route::resource('lien-he', LienHeController::class)->names('lien-he');

    // Quản lý thể loại phim
    Route::resource('the-loai-phim', TheLoaiPhimController::class);

    // Quản lý định dạng phim
    Route::resource('dinh-dang-phim', DinhDangPhimController::class);

    // Quản lý phim và chức năng xóa mềm
    Route::prefix('phim')->name('phim.')->group(function () {
        Route::get('trash', [PhimController::class, 'trash'])->name('trash');
        Route::patch('{phim}/restore', [PhimController::class, 'restore'])->name('restore');
        Route::delete('{phim}/force-delete', [PhimController::class, 'forceDelete'])->name('force-delete');
    });
    Route::resource('phim', PhimController::class);

    // Quản lý bài viết
    Route::resource('bai-viet', BaiVietController::class);

    // Quản lý chi nhánh
    Route::resource('chi-nhanh', ChiNhanhController::class);

    // Quản lý vai trò
    Route::resource('vai-tro', VaiTroController::class);

    // Quản lý phân quyền
    Route::resource('phan-quyen', PhanQuyenController::class);

    // Quản lý banners
    Route::resource('banners', BannerController::class);
});
