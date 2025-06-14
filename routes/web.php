<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ComboController;
use App\Http\Controllers\Admin\DanhMucDoAnController;
use App\Http\Controllers\Admin\DoAnController;
use App\Http\Controllers\Admin\PhimController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\InviteController;
use App\Http\Controllers\Admin\LienHeController;
use App\Http\Controllers\Admin\VaiTroController;
use App\Http\Controllers\Admin\BaiVietController;
use App\Http\Controllers\Admin\CauHinhController;
use App\Http\Controllers\Admin\GheNgoiController;
use App\Http\Controllers\Admin\LoaiGheController;
use App\Http\Controllers\Admin\RapphimController;
use App\Http\Controllers\Admin\RequestController;
use App\Http\Controllers\Admin\SoDoGheController;
use App\Http\Controllers\Admin\ChiNhanhController;
use App\Http\Controllers\Admin\CapBacTheController;
use App\Http\Controllers\Admin\KhuyenMaiController;
use App\Http\Controllers\Admin\LoaiPhongController;
use App\Http\Controllers\Admin\PhanQuyenController;
use App\Http\Controllers\Admin\SuatChieuController;
use App\Http\Controllers\Admin\PhongChieuController;
use App\Http\Controllers\Admin\TheLoaiPhimController;
use App\Http\Controllers\Admin\DinhDangPhimController;
use App\Http\Controllers\SocialAuthController;

// Trang welcome
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Đăng ký (client)
Route::get('dang-ky', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('dang-ky', [AuthController::class, 'register'])->name('register');

// Đăng nhập (chung)
Route::get('dang-nhap', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('dang-nhap', [AuthController::class, 'login'])->name('login');

// GOOGLE
Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('google.callback');

// FACEBOOK
Route::get('/auth/facebook', [SocialAuthController::class, 'redirectToFacebook'])->name('facebook.redirect');
Route::get('/auth/facebook/callback', [SocialAuthController::class, 'handleFacebookCallback'])->name('facebook.callback');

// Đăng xuất
Route::post('dang-xuat', [AuthController::class, 'logout'])->name('logout');

// Route tạm kiểm tra dữ liệu
Route::get('/check-data', function () {
    return [
        'khuyen_mais' => DB::table('khuyen_mais')->get(),
        'khuyen_mai_chi_nhanhs' => DB::table('khuyen_mai_chi_nhanhs')->get(),
        'lich_su_su_dung' => DB::table('lich_su_su_dung_khuyen_mais')->get(),
    ];
});

// Route mời quản lý chi nhánh/ rạp
Route::post('/gui-loi-moi', [InviteController::class, 'sendInvite'])->name('invite.send');
Route::get('/nhap-thong-tin', [InviteController::class, 'showForm'])->name('invite.form');
Route::post('/gui-thong-tin', [InviteController::class, 'submitForm'])->name('invite.submit');

// Group route cho admin
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin.access', 'permission.check'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.lien-he.index');
    })->name('dashboard');

    Route::prefix('lien-he')->name('lien-he.')->group(function () {
        Route::get('dashboard', [LienHeController::class, 'dashboard'])->name('dashboard');
        Route::get('export', [LienHeController::class, 'export'])->name('export');
        Route::post('{lienHe}/notes', [LienHeController::class, 'addNote'])->name('add-note');
        Route::patch('{lienHe}/status', [LienHeController::class, 'updateStatus'])->name('update-status');
        Route::post('{lienHe}/send-email', [LienHeController::class, 'sendEmail'])->name('send-email');
        Route::post('bulk-action', [LienHeController::class, 'bulkAction'])->name('bulk-action');
    });
    Route::resource('lien-he', LienHeController::class)->names('lien-he');

    Route::resource('the-loai-phim', TheLoaiPhimController::class);
    Route::resource('dinh-dang-phim', DinhDangPhimController::class);

    Route::prefix('phim')->name('phim.')->group(function () {
        Route::get('trash', [PhimController::class, 'trash'])->name('trash');
        Route::patch('{phim}/restore', [PhimController::class, 'restore'])->name('restore');
        Route::delete('{phim}/force-delete', [PhimController::class, 'forceDelete'])->name('force-delete');
    });
    Route::resource('phim', PhimController::class);

    Route::resource('bai-viet', BaiVietController::class);
    Route::resource('chi-nhanh', ChiNhanhController::class);
    Route::resource('vai-tro', VaiTroController::class);
    Route::resource('phan-quyen', PhanQuyenController::class);
    Route::resource('users', UserController::class);
    Route::resource('banners', BannerController::class);
    Route::resource('loai-phong', LoaiPhongController::class);
    Route::resource('rap-phim', RapphimController::class);

    Route::get('cau-hinh', [CauHinhController::class, 'index'])->name('cau-hinh.index');
    Route::get('cau-hinh/edit', [CauHinhController::class, 'edit'])->name('cau-hinh.edit');
    Route::post('cau-hinh/update', [CauHinhController::class, 'update'])->name('cau-hinh.update');

    Route::resource('phong-chieu', PhongChieuController::class);
    Route::resource('loai-ghe', LoaiGheController::class);
    Route::resource('so-do-ghe', SoDoGheController::class);
    Route::resource('ghe-ngoi', GheNgoiController::class);
    Route::post('ghe-ngoi/updateSeat', [GheNgoiController::class, 'updateSeat'])->name('ghe-ngoi.updateSeat');

    Route::resource('cap-bac-the', CapBacTheController::class);
    Route::put('cap-bac-the/{capBacThe}/set-default', [CapBacTheController::class, 'setDefault'])->name('cap-bac-the.set-default');

    Route::prefix('khuyen-mai')->name('khuyen-mai.')->group(function () {
        Route::post('{khuyenMai}/assign-chi-nhanh', [KhuyenMaiController::class, 'assignToChiNhanh'])->name('assign-chi-nhanh');
        Route::get('thong-ke-su-dung', [KhuyenMaiController::class, 'thongKeSuDung'])->name('thong-ke-su-dung');
    });
    Route::resource('khuyen-mai', KhuyenMaiController::class);

    Route::post('suat-chieu/bulk-delete', [SuatChieuController::class, 'bulkDelete'])->name('suat-chieu.bulk-delete');
    Route::post('suat-chieu/bulk-toggle-status', [SuatChieuController::class, 'bulkToggleStatus'])->name('suat-chieu.bulk-toggle-status');
    Route::post('suat-chieu/{suatChieu}/toggle-status', [SuatChieuController::class, 'toggleStatus']);
    Route::resource('suat-chieu', SuatChieuController::class);

    Route::resource('combos', ComboController::class);
    Route::resource('do-an', DoAnController::class);
    Route::resource('danh-muc-do-an', DanhMucDoAnController::class);

    Route::get('requests', [RequestController::class, 'index'])->name('requests.index');
    Route::post('requests/{id}/approve', [RequestController::class, 'approve'])->name('requests.approve');
    Route::delete('requests/{id}', [RequestController::class, 'reject'])->name('requests.reject');
});
