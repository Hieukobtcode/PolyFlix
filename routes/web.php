<?php

use App\Http\Controllers\Admin\ChiTietDatVeController;
use App\Http\Controllers\Admin\DatVeController;
use App\Http\Controllers\Client\DanhSachBaiVietController;
use App\Http\Controllers\Client\ThanhToanController;
use App\Http\Controllers\Client\TrangChuController;
use App\Http\Controllers\SeatLockController;
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
use App\Http\Controllers\Admin\KhuyenMaiController as AdminKhuyenMaiController;
use App\Http\Controllers\Admin\RequestController;
use App\Http\Controllers\Client\AIChatController;
use App\Http\Controllers\Client\LoginController;
use App\Http\Controllers\Client\ProfileController;

use App\Http\Controllers\Client\LichChieuController;
use App\Http\Controllers\Client\PhimsController;
use App\Http\Controllers\Client\LienHeController as ClientLienHeController;
use App\Http\Controllers\Client\TheLoaiController;
use App\Http\Controllers\Client\KhuyenMaiController;

Route::get('/', [TrangChuController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {

    Route::get('admin/dat-ve/{id}/print', [DatVeController::class, 'print'])->name('admin.dat-ve.print');
    //Đổi điểm
    Route::post('/doi-diem', [\App\Http\Controllers\Client\DatVeController::class, 'doiDiem'])->name('doi-diem');

    // Đặt vé client
    Route::get('/dat-ve', [\App\Http\Controllers\Client\DatVeController::class, 'indexDatVe'])->name('client.dat-ve');
    Route::post('/dat-ve', [\App\Http\Controllers\Client\DatVeController::class, 'store'])->name('client.dat-ve.store');
    Route::get('/dat-ve/ket-qua/{ma_ve}', [\App\Http\Controllers\Client\DatVeController::class, 'ketQua'])->name('client.dat-ve.ket-qua');
    Route::get('/chi-tiet-dat-ve/{id}', [ProfileController::class, 'chiTietVe'])->name('dat-ve.chi-tiet');
    Route::get('/chi-tiet-dat-ve/{id}/print', [ProfileController::class, 'printVe'])->name('dat-ve.print');

    // Thanh toán
    Route::get('/thanh-toan/{datVeId}', [ThanhToanController::class, 'index'])->name('client.thanh-toan.index');
    Route::post('/thanh-toan/xu-ly', [ThanhToanController::class, 'xuLyThanhToan'])->name('client.thanh-toan.xu-ly');
    Route::post('/thanh-toan/huy/{datVeId}', [ThanhToanController::class, 'huyThanhToan'])->name('client.thanh-toan.huy');
    Route::get('/thanh-toan/huy/{datVeId}', [ThanhToanController::class, 'huyThanhToan'])->name('client.thanh-toan.huy-get');


    Route::get('/zalopay/ketqua', [ThanhToanController::class, 'ketQuaThanhToan'])->name('zalopay.ketqua');

    // Profile
    Route::get('profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('profile', [ProfileController::class, 'updatePassword'])->name('updatePassword');
    Route::post('update-avatar', [ProfileController::class, 'updateAvatar'])->name('update.avatar');

    // Ghế đang được chọn
    Route::post('/chon-ghe', [\App\Http\Controllers\Client\DatVeController::class, 'chonGhe'])->name('client.ghe.chon');
    Route::post('/huy-chon-ghe', [\App\Http\Controllers\Client\DatVeController::class, 'huyChonGhe'])->name('client.ghe.huy');

    //Chat AI
    Route::post('/ai-chat', [AIChatController::class, 'chat']);
    Route::post('/ai-chat-reset', [AIChatController::class, 'reset']);

    // giữ ghế
    Route::post('/seat/lock', [SeatLockController::class, 'lock'])->name('seat.lock');
    Route::post('/seat/unlock', [SeatLockController::class, 'unlock'])->name('seat.unlock');
    Route::post('/seat/heartbeat', [SeatLockController::class, 'heartbeat'])->name('seat.heartbeat');
});

Route::get('/', [TrangChuController::class, 'index'])->name('home');
Route::get('/rap/{uuid}', [TrangChuController::class, 'showrap'])->name('showrap');
Route::get('/bai-viet', [DanhSachBaiVietController::class, 'index'])->name('client.bai-viet');
Route::get('/bai-viet/{uuid}', [DanhSachBaiVietController::class, 'show'])->name('show-bai-viet');

// Liên hệ
Route::get('/lien-he', [ClientLienHeController::class, 'index'])->name('client.lien-he');
Route::post('/lien-he', [ClientLienHeController::class, 'store'])->name('client.lien-he.store');

// Test route
Route::get('/test-route', function () {
    return 'Laravel routing is working!';
});

// Debug khuyến mãi
Route::get('/debug-khuyen-mai', function () {
    try {
        $khuyenMais = \App\Models\KhuyenMai::conHieuLuc()->get();
        return response()->json([
            'success' => true,
            'count' => $khuyenMais->count(),
            'data' => $khuyenMais->toArray()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});



// Khuyến mãi cho client - sử dụng tên route khác do vấn đề với server
Route::get('/promotions', [KhuyenMaiController::class, 'index'])->name('client.khuyen-mai.index');

// API endpoint cho AJAX requests
Route::get('/api/promotions', [KhuyenMaiController::class, 'apiIndex'])->name('api.promotions.index');

// Route alias cho /khuyen-mai
Route::get('/khuyen-mai', function () {
    return redirect('/promotions');
});
Route::get('/promotions/{id}', [KhuyenMaiController::class, 'show'])->name('client.khuyen-mai.show');
Route::post('/promotions/check-code', [KhuyenMaiController::class, 'checkCode'])->name('client.khuyen-mai.check-code');

// Demo tính năng khuyến mãi
Route::get('/promotion-demo', function () {
    $khuyenMais = \App\Models\KhuyenMai::conHieuLuc()->take(3)->get();
    return view('client.khuyen-mai.demo', compact('khuyenMais'));
})->name('promotion.demo');

// Debug view khuyến mãi
Route::get('/debug-promotions-view', function () {
    $khuyenMais = \App\Models\KhuyenMai::conHieuLuc()
        ->with(['chiNhanhs'])
        ->orderBy('ngay_bat_dau', 'desc')
        ->paginate(12);

    return view('debug.promotions', compact('khuyenMais'));
});

// Simple promotions view
Route::get('/promotions-simple', function () {
    $khuyenMais = \App\Models\KhuyenMai::conHieuLuc()
        ->with(['chiNhanhs'])
        ->orderBy('ngay_bat_dau', 'desc')
        ->paginate(12);

    return view('client.khuyen-mai.simple', compact('khuyenMais'));
});

// Navigation test page
Route::get('/test-navigation', function () {
    return view('test.navigation');
});

// SPA Test page
Route::get('/spa-test', function () {
    return view('client.khuyen-mai.spa-test');
})->name('spa.test');
Route::get('/phim-dang-chieu', [PhimsController::class, 'phimDangChieu'])->name('phim.dang-chieu');

// Lịch chiếu
Route::get('/lich-chieu', [LichChieuController::class, 'index'])->name('client.lich-chieu');

// Test route để debug
Route::get('/test-lich-chieu', function () {
    $today = \Carbon\Carbon::today();
    $suatChieus = \App\Models\SuatChieu::with(['phim', 'phongChieu.rapPhim'])
        ->where('ngay_bat_dau', '>=', $today)
        ->where('trang_thai', 'hoat_dong')
        ->orderBy('ngay_bat_dau')
        ->orderBy('bat_dau')
        ->get();

    return response()->json([
        'today' => $today->format('Y-m-d'),
        'count' => $suatChieus->count(),
        'sample' => $suatChieus->take(5)->map(function ($s) {
            return [
                'id' => $s->id,
                'phim' => $s->phim->ten_phim,
                'ngay' => $s->ngay_bat_dau,
                'gio' => $s->bat_dau,
                'rap' => $s->phongChieu->rapPhim->ten_rap ?? 'N/A'
            ];
        })
    ]);
});

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

// Đăng xuất
Route::post('dang-xuat', [AuthController::class, 'logout'])->name('logout');

// ========================================================================================================================================================


// Route mời quản lý chi nhánh/ rạp
Route::post('/gui-loi-moi', [InviteController::class, 'sendInvite'])->name('invite.send');
Route::get('/nhap-thong-tin', [InviteController::class, 'showForm'])->name('invite.form');
Route::post('/gui-thong-tin', [InviteController::class, 'submitForm'])->name('invite.submit');

Route::get('/admin/suat-chieu/theo-phong-va-ngay', [SuatChieuController::class, 'theoPhongVaNgay'])
    ->name('admin.suat-chieu.theo-phong-va-ngay');

//======================API DOANH THU=======================================
Route::get('/api/doanh-thu-{loai}', [ThongKeController::class, 'getDoanhThu'])->name('api.doanh-thu');
Route::get('/api/ty-le-lap-day-ghe', [ThongKeController::class, 'getTyLeLapDayGhe'])->name('api.ty-le-lap-day-ghe');
Route::get('/api/ty-le-doanh-thu-phim', [ThongKeController::class, 'getTyLeDoanhThuPhim'])->name('api.ty-le-doanh-thu-phim');
Route::get('/api/ty-le-suat-chieu', [ThongKeController::class, 'getTyLeSuatChieu'])->name('api.ty-le-suat-chieu');
Route::get('/api/ty-le-doanh-thu-phim', [ThongKeController::class, 'getTyLeDoanhThuPhim'])->name('api.ty-le-doanh-thu-phim');
//======================API DOANH THU=======================================

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
        Route::get('thong-ke-su-dung', [AdminKhuyenMaiController::class, 'thongKeSuDung'])->name('thong-ke-su-dung');
        Route::post('{khuyenMai}/assign-chi-nhanh', [AdminKhuyenMaiController::class, 'assignToChiNhanh'])->name('assign-chi-nhanh');
    });

    Route::resource('khuyen-mai', AdminKhuyenMaiController::class);

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
        Route::get('/', [ThongKeController::class, 'thongKeTongQuan'])->name('index');
        Route::get('doanh-thu', [ThongKeController::class, 'thongKeDoanhThu'])->name('doanh-thu');
        Route::get('ve', [ThongKeController::class, 've'])->name('ve');
        Route::get('suat-chieu', [ThongKeController::class, 'thongKeSuatChieu'])->name('suat-chieu');
        Route::get('do-an-combo', [ThongKeController::class, 'doAnCombo'])->name('do-an-combo');
        Route::get('dashboard', [ThongKeController::class, 'dashboard'])->name('dashboard');
        Route::get('phim', [ThongKeController::class, 'thongKePhim'])->name('phim');
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

    Route::post('suat-chieu/luu-suat-chieu', [SuatChieuController::class, 'luuSuatChieu'])->name('suat-chieu.luu-suat-chieu');
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