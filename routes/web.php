<?php

use App\Http\Controllers\Admin\ChiTietDatVeController;
use App\Http\Controllers\Admin\DatVeController;
use App\Http\Controllers\Client\DanhSachBaiVietController;
use App\Http\Controllers\Client\ThanhToanController;
use App\Http\Controllers\Client\TrangChuController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CapBacTheController;
use App\Http\Controllers\Admin\ChiNhanhController;
use App\Http\Controllers\Admin\ComboController;
use App\Http\Controllers\Admin\DanhMucDoAnController;
use App\Http\Controllers\Admin\DoAnController;
use App\Http\Controllers\Admin\PhimController;
use App\Http\Controllers\Admin\InviteController;
use App\Http\Controllers\Admin\LienHeController;
use App\Http\Controllers\Admin\BaiVietController;
use App\Http\Controllers\Admin\CauHinhController;
use App\Http\Controllers\Admin\DinhDangPhimController;
use App\Http\Controllers\Admin\PhuDePhimController;
use App\Http\Controllers\Admin\GheNgoiController;
use App\Http\Controllers\Admin\LoaiGheController;
use App\Http\Controllers\Admin\LoaiPhongController;
use App\Http\Controllers\Admin\PhanQuyenController;
use App\Http\Controllers\Admin\PhongChieuController;
use App\Http\Controllers\Admin\RapphimController;
use App\Http\Controllers\Admin\SoDoGheController;
use App\Http\Controllers\Admin\SuatChieuController;
use App\Http\Controllers\Admin\TheLoaiPhimController;
use App\Http\Controllers\Admin\ThongKeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VaiTroController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\GiaVeController;
use App\Http\Controllers\Admin\RequestController;
use App\Http\Controllers\Client\LoginController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Client\KhuyenMaiController;
use App\Http\Controllers\Client\PhimsController;
use App\Http\Controllers\Client\LienHeController as ClientLienHeController;
use App\Http\Controllers\Client\TheLoaiController;

Route::get('/', [TrangChuController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    // Đặt vé client
    Route::get('/dat-ve', [\App\Http\Controllers\Client\DatVeController::class, 'indexDatVe'])->name('client.dat-ve');
    Route::post('/dat-ve', [\App\Http\Controllers\Client\DatVeController::class, 'store'])->name('client.dat-ve.store');
    Route::get('/dat-ve/ket-qua/{id}', [\App\Http\Controllers\Client\DatVeController::class, 'ketQua'])->name('client.dat-ve.ket-qua');

    // Thanh toán
    Route::get('/thanh-toan/{datVeId}', [ThanhToanController::class, 'index'])->name('client.thanh-toan.index');
    Route::post('/thanh-toan/xu-ly', [ThanhToanController::class, 'xuLyThanhToan'])->name('client.thanh-toan.xu-ly');
    Route::post('/thanh-toan/huy/{datVeId}', [ThanhToanController::class, 'huyThanhToan'])->name('client.thanh-toan.huy');
    Route::get('/check-payment-status', [ThanhToanController::class, 'kiemTraTrangThai']);
});

// Payment callback - MUST be outside auth middleware for external services
Route::get('/thanh-toan/callback', [ThanhToanController::class, 'callback'])->name('client.thanh-toan.callback');

// MoMo callback routes (both GET and POST)
Route::get('/thanh-toan/momo/callback', [\App\Http\Controllers\Client\MomoController::class, 'callback'])->name('client.thanh-toan.momo.callback');
Route::post('/thanh-toan/momo/callback', [\App\Http\Controllers\Client\MomoController::class, 'callback']);

// MoMo test routes (for development/testing)
Route::get('/test-momo', function () {
    return view('momo-test');
})->name('momo.test');

// Shortcut cho test MoMo
Route::get('/momo', function () {
    return view('momo-test');
});

Route::post('/test-momo/create', [\App\Http\Controllers\Client\MomoController::class, 'createTestPayment'])->name('momo.test.create');
Route::get('/test-momo/callback', [\App\Http\Controllers\Client\MomoController::class, 'testCallback'])->name('momo.test.callback');

// Ghế đang được chọn
Route::post('/chon-ghe', [\App\Http\Controllers\Client\DatVeController::class, 'chonGhe'])->name('client.ghe.chon');
Route::post('/huy-chon-ghe', [\App\Http\Controllers\Client\DatVeController::class, 'huyChonGhe'])->name('client.ghe.huy');

Route::get('/', [TrangChuController::class, 'index'])->name('home');
Route::get('/rap/{uuid}', [TrangChuController::class, 'showrap'])->name('showrap');
Route::get('/bai-viet', [DanhSachBaiVietController::class, 'index'])->name('client.bai-viet');
Route::get('/bai-viet/{uuid}', [DanhSachBaiVietController::class, 'show'])->name('show-bai-viet');

// Liên hệ
Route::get('/lien-he', [ClientLienHeController::class, 'index'])->name('client.lien-he');
Route::post('/lien-he', [ClientLienHeController::class, 'store'])->name('client.lien-he.store');

// Profile
Route::get('profile', [ProfileController::class, 'index'])->name('profile');
Route::post('profile', [ProfileController::class, 'updatePassword'])->name('updatePassword');
Route::post('update-avatar', [ProfileController::class, 'updateAvatar'])->name('update.avatar');


Route::get('/client.khuyen-mai', [KhuyenMaiController::class, 'index'])->name('khuyen-mai.index');
Route::get('/phim-dang-chieu', [PhimsController::class, 'phimDangChieu'])->name('phim.dang-chieu');

//Phim
Route::get('/phim-sap-chieu', [PhimsController::class, 'phimSapChieu'])->name('phim.sap-chieu');

// Route load phim cho tab (AJAX)
Route::get('/phim-tab', [TrangChuController::class, 'loadPhimTab'])->name('client.load-phim-tab');

// routes/web.php (hoặc api.php nếu gọi bằng API)
Route::get('/phim/{id}/lich-chieu', [PhimsController::class, 'loadLichChieu'])->name('phim.load-lich-chieu');

// Chi tiết phim
Route::get('phim/{ten_phim}', [PhimsController::class, 'show'])->name('phim.chi-tiet');


//Thể loại
Route::get('/the-loai/{id}', [App\Http\Controllers\Client\TheLoaiController::class, 'show'])
    ->name('theloai.show');

// Route::prefix('client')->name('client.')->group(function () {
//     // Trang chủ client
//     Route::get('/', [HomeController::class, 'index'])->name('home');

//     // Trang khuyến mãi
//     Route::get('/khuyen-mai', [KhuyenMaiController::class, 'index'])->name('khuyen-mai');
// // 
// });

//Quen mk
Route::get('forgot-pass', [AuthController::class, 'forgotPassForm'])->name('forgot-form');
Route::post('forgot-pass', [AuthController::class, 'forgotPass'])->name('forgot-pass');


// Đăng nhập (chung)
Route::get('dang-nhap', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('dang-nhap', [AuthController::class, 'login'])->name('login');


// Đăng ký (client)
Route::post('dang-ky', [AuthController::class, 'register'])->name('register');

Route::get('xac-thuc-email', [AuthController::class, 'showVerifyForm'])->name('verify.form');

Route::post('xac-thuc-email', [AuthController::class, 'verifyOtp'])->name('verify.submit');

// GOOGLE
Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('google.callback');

// FACEBOOK
Route::get('/auth/facebook', [SocialAuthController::class, 'redirectToFacebook'])->name('facebook.redirect');
Route::get('/auth/facebook/callback', [SocialAuthController::class, 'handleFacebookCallback'])->name('facebook.callback');

// Đăng xuất
Route::post('dang-xuat', [AuthController::class, 'logout'])->name('logout');


// ========================================================================================================================================================


// Route mời quản lý chi nhánh/ rạp
Route::post('/gui-loi-moi', [InviteController::class, 'sendInvite'])->name('invite.send');
Route::get('/nhap-thong-tin', [InviteController::class, 'showForm'])->name('invite.form');
Route::post('/gui-thong-tin', [InviteController::class, 'submitForm'])->name('invite.submit');

Route::get('/suat-chieu/theo-phong-ngay', [SuatChieuController::class, 'theoPhongVaNgay'])
    ->name('admin.suat-chieu.theo-phong-ngay');

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
        Route::get('doanh-thu', [ThongKeController::class, 'doanhThu'])->name('doanh-thu');
        Route::get('ve', [ThongKeController::class, 've'])->name('ve');
        Route::get('suat-chieu', [ThongKeController::class, 'suatChieu'])->name('suat-chieu');
        Route::get('do-an-combo', [ThongKeController::class, 'doAnCombo'])->name('do-an-combo');
        Route::get('dashboard', [ThongKeController::class, 'dashboard'])->name('dashboard');
        Route::get('phim', [ThongKeController::class, 'phim'])->name('phim');
        Route::get('lien-he', [ThongKeController::class, 'lienHe'])->name('lien-he');
        Route::get('xuat-bao-cao', [ThongKeController::class, 'xuatBaoCao'])->name('xuat-bao-cao');
    });

    Route::resource('loai-phong', LoaiPhongController::class);
    Route::resource('rap-phim', RapphimController::class);

    Route::get('cau-hinh-settings', [CauHinhController::class, 'index'])->name('cau-hinh-settings.index');
    Route::get('cau-hinh-settings/edit', [CauHinhController::class, 'edit'])->name('cau-hinh-settings.edit');
    Route::post('cau-hinh-settings/update', [CauHinhController::class, 'update'])->name('cau-hinh-settings.update');

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

    Route::get('requests', [RequestController::class, 'index'])->name('requests.index');
    Route::post('requests/{id}/approve', [RequestController::class, 'approve'])->name('requests.approve');
    Route::delete('requests/{id}', [RequestController::class, 'reject'])->name('requests.reject');

    // =========================================================================

    // Đặt vé
    Route::resource('dat-ves', DatVeController::class)->except(['show']);

    Route::get('/dat-ve', [DatVeController::class, 'show'])->name('dat-ve.show');

    // ============================================================================
    //gửi email
    Route::get('dat-ve/{id}/gui-email', [DatVeController::class, 'guiVe'])->name('dat_ve.gui_email');

    // =============================================================================

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
    // Hủy lời mời quản lý
    Route::post('invite/cancel', [InviteController::class, 'cancel'])->name('invite.cancel');
});
