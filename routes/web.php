<?php

use App\Http\Controllers\Admin\DatVeController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ComboController;
use App\Http\Controllers\Admin\DanhMucDoAnController;
use App\Http\Controllers\Admin\DoAnController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\PhimController;
use App\Http\Controllers\Admin\InviteController;
use App\Http\Controllers\Admin\LienHeController;
use App\Http\Controllers\Admin\VaiTroController;
use App\Http\Controllers\Admin\BaiVietController;
use App\Http\Controllers\Admin\CauHinhController;
use App\Http\Controllers\Admin\PhuDePhimController;
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
use App\Http\Controllers\Admin\ThongKeController;
use App\Http\Controllers\Admin\PhongChieuController;
use App\Http\Controllers\Admin\TheLoaiPhimController;
use App\Http\Controllers\Admin\DinhDangPhimController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\GiaVeController;

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
        return redirect()->route('admin.thong-ke.index');
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

    // Quản lý loại phòng
    Route::resource('loai-phong', LoaiPhongController::class);
    // Quản lý phụ đề phim
    Route::resource('phu-de-phim', PhuDePhimController::class);

    // Quản lý phim và chức năng xóa mềm

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

    // Quản lý khuyến mãi
    Route::prefix('khuyen-mai')->name('khuyen-mai.')->group(function () {
        Route::get('thong-ke-su-dung', [KhuyenMaiController::class, 'thongKeSuDung'])->name('thong-ke-su-dung');
        Route::post('{khuyenMai}/assign-chi-nhanh', [KhuyenMaiController::class, 'assignToChiNhanh'])->name('assign-chi-nhanh');
    });
    Route::resource('khuyen-mai', KhuyenMaiController::class);

    // Quản lý rạp phim
    Route::resource('rap-phim', RapphimController::class);

    // Quản lý cấu hình
    Route::resource('cau-hinh', CauHinhController::class);

    // Quản lý ghế ngồi
    Route::resource('ghe-ngoi', GheNgoiController::class);

    // Quản lý phòng chiếu
    Route::resource('phong-chieu', PhongChieuController::class);

    // Quản lý loại ghế
    Route::resource('loai-ghe', LoaiGheController::class);

    // Quản lý sơ đồ ghế
    Route::resource('so-do-ghe', SoDoGheController::class);

    // Quản lý cấp bậc thẻ
    Route::resource('cap-bac-the', CapBacTheController::class);

    // Quản lý suất chiếu
    Route::resource('suat-chieu', SuatChieuController::class);

    // Quản lý combo
    Route::resource('combos', ComboController::class);

    // Quản lý danh mục đồ ăn
    Route::resource('danh-muc-do-an', DanhMucDoAnController::class);

    // Quản lý đồ ăn
    Route::resource('do-an', DoAnController::class);

    // Thống kê
    Route::prefix('thong-ke')->name('thong-ke.')->group(function () {
        Route::get('/', [ThongKeController::class, 'index'])->name('index');
        Route::get('dashboard', [ThongKeController::class, 'dashboard'])->name('dashboard');
        Route::get('phim', [ThongKeController::class, 'phim'])->name('phim');
        Route::get('lien-he', [ThongKeController::class, 'lienHe'])->name('lien-he');
        Route::get('xuat-bao-cao', [ThongKeController::class, 'xuatBaoCao'])->name('xuat-bao-cao');
    });

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
    Route::get('/suat-chieu/theo-phong-ngay', [SuatChieuController::class, 'theoPhongVaNgay'])->name('suat-chieu.api_phong_ngay');
    Route::resource('suat-chieu', SuatChieuController::class);

    Route::resource('combos', ComboController::class);
    Route::resource('do-an', DoAnController::class);
    Route::resource('danh-muc-do-an', DanhMucDoAnController::class);

    Route::get('requests', [RequestController::class, 'index'])->name('requests.index');
    Route::post('requests/{id}/approve', [RequestController::class, 'approve'])->name('requests.approve');
    Route::delete('requests/{id}', [RequestController::class, 'reject'])->name('requests.reject');

    Route::resource('dat-ves', DatVeController::class);

    Route::get('gia-ve', [GiaVeController::class, 'index'])->name('gia-ve.index');
    Route::post('gia-ve/cap-nhat', [GiaVeController::class, 'updateGiaVe'])->name('gia-ve.cap-nhat');

    // Quản lý bình luận & đánh giá
    Route::prefix('comments')->name('comments.')->group(function () {
        // Giao diện quản lý bình luận
        Route::get('/', [CommentController::class, 'index'])->name('index');

        // Giao diện chi tiết bình luận theo phim
        Route::get('{phim}', [CommentController::class, 'show'])->name('show');
        // Giao diện trả lời bình luận
        Route::post('{id}/reply', [CommentController::class, 'reply'])->name('reply');

        // Ẩn bình luận
        Route::post('{id}/hide', [CommentController::class, 'hide'])->name('hide');

        // Hiện lại bình luận
        Route::post('{id}/unhide', [CommentController::class, 'unhide'])->name('unhide');

        // Xóa bình luận
        Route::delete('{id}', [CommentController::class, 'destroy'])->name('destroy');
    });
});
