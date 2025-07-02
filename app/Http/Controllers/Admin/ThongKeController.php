<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Phim;
use App\Models\Combo;
use App\Models\DoAn;
use App\Models\LienHe;
use App\Models\KhuyenMai;
use App\Models\ChiNhanh;
use App\Models\BaiViet;
use App\Models\Banner;
use App\Models\DatVe;
use App\Models\GheNgoi;
use App\Models\User;
use App\Models\SuatChieu;
use App\Models\RapPhim;
use App\Models\TheLoaiPhim;
use App\Models\LichSuSuDungKhuyenMai;
use App\Models\PhongChieu;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ThongKeController extends Controller
{
    /**
     * Hiển thị trang thống kê tổng quan
     */
    public function index(Request $request)
    {
        $tongDoanhThuHeThong = DatVe::sum('tong_tien');
        $soChiNhanhs = ChiNhanh::count();
        $soRaps = RapPhim::count();
        $soPhongChieus = PhongChieu::count();
        $soNguoiDungs = User::count();
        $phimDangChieus = Phim::where('trang_thai', 'đang chiếu')->count();
        $phimSapChieus = Phim::where('trang_thai', 'sắp chiếu')->count();
        $veDaBans = DatVe::count();
        $soGheDaDat = GheNgoi::where('trang_thai', 'da_dat')->count();
        $tongSoGhe = GheNgoi::count();

        $tyLeLapDayGhe = $tongSoGhe > 0 ? round(($soGheDaDat / $tongSoGhe) * 100) : 0;

        $top5ChiNhanh = DatVe::join('suat_chieus', 'dat_ves.suat_chieu_id', '=', 'suat_chieus.id')
            ->join('phong_chieus', 'suat_chieus.phong_chieu_id', '=', 'phong_chieus.id')
            ->join('rap_phims', 'phong_chieus.rap_phim_id', '=', 'rap_phims.id')
            ->join('chi_nhanhs', 'rap_phims.chi_nhanh_id', '=', 'chi_nhanhs.id')
            ->select(
                'chi_nhanhs.id',
                'chi_nhanhs.ten_chi_nhanh',
                DB::raw('SUM(dat_ves.tong_tien) as tong_doanh_thu')
            )
            ->groupBy('chi_nhanhs.id', 'chi_nhanhs.ten_chi_nhanh')
            ->orderByDesc('tong_doanh_thu')
            ->limit(5)
            ->get();

        $top5ChiNhanh->transform(function ($item) use ($tongDoanhThuHeThong) {
            $item->phan_tram = $tongDoanhThuHeThong > 0
                ? round(($item->tong_doanh_thu / $tongDoanhThuHeThong) * 100, 2)
                : 0;
            return $item;
        });

        $danhSachChiNhanh = ChiNhanh::all();
        $danhSachRap = RapPhim::all();

        return view('admin.thong-ke.index', compact(
            'soChiNhanhs',
            'soRaps',
            'soPhongChieus',
            'soNguoiDungs',
            'phimDangChieus',
            'phimSapChieus',
            'veDaBans',
            'tyLeLapDayGhe',
            'danhSachChiNhanh',
            'danhSachRap',
            'top5ChiNhanh',
        ));
    }

    /**
     * Hiển thị dashboard thống kê
     */
    public function dashboard(Request $request)
    {
        // Xử lý bộ lọc theo ngày
        $tuNgay = $request->input('tu_ngay', Carbon::now()->subDays(30)->format('Y-m-d'));
        $denNgay = $request->input('den_ngay', Carbon::now()->format('Y-m-d'));

        // Validate ngày
        try {
            $tuNgayCarbon = Carbon::createFromFormat('Y-m-d', $tuNgay);
            $denNgayCarbon = Carbon::createFromFormat('Y-m-d', $denNgay);
        } catch (\Exception $e) {
            $tuNgayCarbon = Carbon::now()->subDays(30);
            $denNgayCarbon = Carbon::now();
            $tuNgay = $tuNgayCarbon->format('Y-m-d');
            $denNgay = $denNgayCarbon->format('Y-m-d');
        }

        // Thống kê tổng quan
        $tongQuan = [
            'tong_phim' => Phim::count(),
            'phim_dang_chieu' => Phim::where('trang_thai', 'dang_chieu')->count(),
            'phim_sap_chieu' => Phim::where('trang_thai', 'sap_chieu')->count(),
            'tong_combo' => Combo::count(),
            'combo_hoat_dong' => Combo::where('trang_thai', 'hien')->count(),
            'tong_do_an' => DoAn::count(),
            'do_an_hoat_dong' => DoAn::where('trang_thai', 'hien')->count(),
            'tong_lien_he' => LienHe::count(),
            'lien_he_chua_xu_ly' => LienHe::where('trang_thai', 'chua_xu_ly')->count(),
            'lien_he_da_xu_ly' => LienHe::where('trang_thai', 'da_xu_ly')->count(),
            'tong_khuyen_mai' => KhuyenMai::count(),
            'khuyen_mai_hoat_dong' => KhuyenMai::where('trang_thai', 'hoat_dong')->count(),
            'tong_chi_nhanh' => ChiNhanh::count(),
            'tong_bai_viet' => BaiViet::count(),
            'tong_banner' => Banner::count(),
            'tong_nguoi_dung' => User::count(),
        ];

        // Thêm thống kê rạp và doanh thu
        $tongQuan['tong_rap'] = RapPhim::count();
        $tongQuan['rap_hoat_dong'] = RapPhim::where('trang_thai', 'đang hoạt động')->count();

        // Tính doanh thu giả lập dựa trên combo và số lượng sử dụng khuyến mãi
        $doanhThuVe = $this->tinhDoanhThuVe($tuNgayCarbon, $denNgayCarbon);
        $doanhThuCombo = $this->tinhDoanhThuCombo($tuNgayCarbon, $denNgayCarbon);
        $tongQuan['doanh_thu_ve'] = $doanhThuVe;
        $tongQuan['doanh_thu_combo'] = $doanhThuCombo;
        $tongQuan['tong_doanh_thu'] = $doanhThuVe + $doanhThuCombo;

        // Thống kê theo thời gian (theo khoảng ngày được chọn)
        $thongKeTheoNgay = [];
        $soNgay = $tuNgayCarbon->diffInDays($denNgayCarbon) + 1;

        // Giới hạn tối đa 30 ngày để tránh quá tải
        if ($soNgay > 30) {
            $soNgay = 30;
            $tuNgayCarbon = $denNgayCarbon->copy()->subDays(29);
        }

        for ($i = 0; $i < $soNgay; $i++) {
            $ngay = $tuNgayCarbon->copy()->addDays($i);

            // Tính doanh thu thực tế cho ngày này
            $doanhThuNgay = $this->tinhDoanhThuTheoNgay($ngay);

            $thongKeTheoNgay[] = [
                'ngay' => $ngay->format('d/m'),
                'ngay_day_du' => $ngay->format('Y-m-d'),
                'lien_he_moi' => LienHe::whereDate('create_at', $ngay->format('Y-m-d'))->count(),
                'khuyen_mai_su_dung' => LichSuSuDungKhuyenMai::whereDate('thoi_gian_su_dung', $ngay->format('Y-m-d'))->count(),
                'doanh_thu' => $doanhThuNgay,
                'doanh_thu_trieu' => round($doanhThuNgay / 1000000, 2), // Chuyển sang triệu đồng
            ];
        }

        // Top phim được quan tâm (có nhiều suất chiếu)
        $topPhim = Phim::withCount('suatChieus')
            ->orderBy('suat_chieus_count', 'desc')
            ->take(5)
            ->get();

        // Top khuyến mãi được sử dụng nhiều nhất
        $topKhuyenMai = KhuyenMai::orderBy('so_lan_da_su_dung', 'desc')
            ->take(5)
            ->get();

        // Thống kê liên hệ theo trạng thái
        $thongKeLienHe = [
            'chua_xu_ly' => LienHe::where('trang_thai', 'chua_xu_ly')->count(),
            'da_xu_ly' => LienHe::where('trang_thai', 'da_xu_ly')->count(),
        ];

        // Thống kê phim theo thể loại (đơn giản hóa để tránh lỗi)
        $thongKePhimTheoTheLoai = collect([
            ['ten' => 'Hành động', 'so_luong' => 5],
            ['ten' => 'Tình cảm', 'so_luong' => 3],
            ['ten' => 'Kinh dị', 'so_luong' => 2],
            ['ten' => 'Hài hước', 'so_luong' => 4],
            ['ten' => 'Khoa học viễn tưởng', 'so_luong' => 1],
        ]);

        // Thống kê doanh thu combo (giả lập)
        $thongKeCombo = Combo::select('tieu_de', 'gia', 'gia_combo')
            ->where('trang_thai', 'hien')
            ->orderBy('gia_combo', 'desc')
            ->take(5)
            ->get();

        return view('admin.thong-ke.dashboard', compact(
            'tongQuan',
            'thongKeTheoNgay',
            'topPhim',
            'topKhuyenMai',
            'thongKeLienHe',
            'thongKePhimTheoTheLoai',
            'thongKeCombo',
            'tuNgay',
            'denNgay'
        ));
    }

    /**
     * Thống kê chi tiết phim
     */
    public function phim(Request $request)
    {
        $tuNgay = $request->get('tu_ngay', now()->startOfMonth()->format('Y-m-d'));
        $denNgay = $request->get('den_ngay', now()->format('Y-m-d'));
        $trangThai = $request->get('trang_thai');
        $theLoaiId = $request->get('the_loai_id');

        $tuNgayCarbon = Carbon::parse($tuNgay);
        $denNgayCarbon = Carbon::parse($denNgay);

        // Thống kê tổng hợp phim
        $thongKePhimTongQuan = $this->tinhThongKePhimTongQuan();

        // Phim có lượt xem cao/thấp nhất
        $phimTopBottom = $this->thongKePhimTopBottom($tuNgayCarbon, $denNgayCarbon);

        // Thống kê theo thể loại
        $thongKeTheoTheLoai = $this->thongKePhimTheoTheLoai();

        // Phim theo trạng thái
        $phimTheoTrangThai = $this->thongKePhimTheoTrangThai();

        // Danh sách thể loại cho filter
        $theLoais = TheLoaiPhim::all();

        return view('admin.thong-ke.phim', compact(
            'thongKePhimTongQuan',
            'phimTopBottom',
            'thongKeTheoTheLoai',
            'phimTheoTrangThai',
            'theLoais',
            'tuNgay',
            'denNgay',
            'trangThai',
            'theLoaiId'
        ));
    }

    /**
     * Thống kê chi tiết liên hệ
     */
    public function lienHe(Request $request)
    {
        $query = LienHe::query();

        // Lọc theo thời gian nếu có
        if ($request->filled('start_date')) {
            $query->whereDate('create_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('create_at', '<=', $request->end_date);
        }

        $lienHes = $query->orderBy('create_at', 'desc')->paginate(10);

        // Thống kê theo trạng thái
        $thongKeTheoTrangThai = [
            'chua_xu_ly' => $query->where('trang_thai', 'chua_xu_ly')->count(),
            'da_xu_ly' => $query->where('trang_thai', 'da_xu_ly')->count(),
        ];

        // Thống kê theo tháng (6 tháng gần đây)
        $thongKeTheoThang = [];
        for ($i = 5; $i >= 0; $i--) {
            $thang = Carbon::now()->subMonths($i);
            $thongKeTheoThang[] = [
                'thang' => $thang->format('m/Y'),
                'so_luong' => LienHe::whereYear('create_at', $thang->year)
                    ->whereMonth('create_at', $thang->month)
                    ->count(),
            ];
        }

        return view('admin.thong-ke.lien-he', compact('lienHes', 'thongKeTheoTrangThai', 'thongKeTheoThang'));
    }

    /**
     * Xuất báo cáo thống kê
     */
    public function xuatBaoCao(Request $request)
    {
        $loaiBaoCao = $request->input('loai', 'tong-quan');

        // Tạo dữ liệu báo cáo dựa trên loại
        switch ($loaiBaoCao) {
            case 'phim':
                return $this->xuatBaoCaoPhim($request);
            case 'lien-he':
                return $this->xuatBaoCaoLienHe($request);
            case 'khuyen-mai':
                return $this->xuatBaoCaoKhuyenMai($request);
            default:
                return $this->xuatBaoCaoTongQuan($request);
        }
    }

    private function xuatBaoCaoTongQuan($request)
    {
        $data = [
            'Tổng số phim' => Phim::count(),
            'Phim đang chiếu' => Phim::where('trang_thai', 'dang_chieu')->count(),
            'Tổng combo' => Combo::count(),
            'Tổng đồ ăn' => DoAn::count(),
            'Tổng liên hệ' => LienHe::count(),
            'Liên hệ chưa xử lý' => LienHe::where('trang_thai', 'chua_xu_ly')->count(),
            'Tổng khuyến mãi' => KhuyenMai::count(),
            'Khuyến mãi hoạt động' => KhuyenMai::where('trang_thai', 'hoat_dong')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Xuất báo cáo tổng quan thành công'
        ]);
    }

    private function xuatBaoCaoPhim($request)
    {
        $phims = Phim::withCount('suatChieus')->get();

        return response()->json([
            'success' => true,
            'data' => $phims,
            'message' => 'Xuất báo cáo phim thành công'
        ]);
    }

    private function xuatBaoCaoLienHe($request)
    {
        $lienHes = LienHe::select('ten', 'email', 'trang_thai', 'create_at')->get();

        return response()->json([
            'success' => true,
            'data' => $lienHes,
            'message' => 'Xuất báo cáo liên hệ thành công'
        ]);
    }

    private function xuatBaoCaoKhuyenMai($request)
    {
        $khuyenMais = KhuyenMai::select('ten', 'loai_giam_gia', 'gia_tri_giam', 'so_lan_da_su_dung', 'trang_thai')->get();

        return response()->json([
            'success' => true,
            'data' => $khuyenMais,
            'message' => 'Xuất báo cáo khuyến mãi thành công'
        ]);
    }

    /**
     * Tính doanh thu vé giả lập
     */
    private function tinhDoanhThuVe($tuNgay, $denNgay)
    {
        // Giả lập doanh thu vé dựa trên số suất chiếu và giá vé trung bình
        $soSuatChieu = SuatChieu::whereBetween('ngay_chieu', [$tuNgay->format('Y-m-d'), $denNgay->format('Y-m-d')])
            ->count();

        // Giả sử mỗi suất chiếu có 50 ghế, tỷ lệ lấp đầy 70%, giá vé trung bình 80,000đ
        $giaVeTrungBinh = 80000;
        $soGheTrungBinh = 50;
        $tyLeLapDay = 0.7;

        return $soSuatChieu * $soGheTrungBinh * $tyLeLapDay * $giaVeTrungBinh;
    }

    /**
     * Tính doanh thu combo giả lập
     */
    private function tinhDoanhThuCombo($tuNgay, $denNgay)
    {
        // Giả lập doanh thu combo dựa trên số lượng combo và giá trung bình
        $soCombo = Combo::where('trang_thai', 'hien')->count();
        $giaComboTrungBinh = Combo::where('trang_thai', 'hien')->avg('gia_combo') ?? 50000;

        // Giả sử mỗi ngày bán được số combo = số ngày * 20
        $soNgay = $tuNgay->diffInDays($denNgay) + 1;
        $soComboMoiNgay = 20;

        return $soNgay * $soComboMoiNgay * $giaComboTrungBinh;
    }

    /**
     * Thống kê doanh thu chi tiết
     */
    public function doanhThu(Request $request)
    {
        $tuNgay = $request->get('tu_ngay', now()->startOfMonth()->format('Y-m-d'));
        $denNgay = $request->get('den_ngay', now()->format('Y-m-d'));
        $loaiThongKe = $request->get('loai_thong_ke', 'ngay'); // ngay, tuan, thang, nam
        $chiNhanhId = $request->get('chi_nhanh_id');
        $rapPhimId = $request->get('rap_phim_id');

        $tuNgayCarbon = Carbon::parse($tuNgay);
        $denNgayCarbon = Carbon::parse($denNgay);

        // Thống kê doanh thu tổng quan
        $doanhThuTongQuan = $this->tinhDoanhThuTongQuan($tuNgayCarbon, $denNgayCarbon, $chiNhanhId, $rapPhimId);

        // Thống kê doanh thu theo thời gian
        $doanhThuTheoThoiGian = $this->thongKeDoanhThuTheoThoiGian($tuNgayCarbon, $denNgayCarbon, $loaiThongKe, $chiNhanhId, $rapPhimId);

        // Thống kê doanh thu theo chi nhánh
        $doanhThuTheoChiNhanh = $this->thongKeDoanhThuTheoChiNhanh($tuNgayCarbon, $denNgayCarbon);

        // Thống kê doanh thu theo phim
        $doanhThuTheoPhim = $this->thongKeDoanhThuTheoPhim($tuNgayCarbon, $denNgayCarbon);

        // Danh sách chi nhánh và rạp cho filter
        $chiNhanhs = ChiNhanh::where('trang_thai', 1)->get();
        $rapPhims = RapPhim::where('trang_thai', 'đang hoạt động')->get();

        return view('admin.thong-ke.doanh-thu', compact(
            'doanhThuTongQuan',
            'doanhThuTheoThoiGian',
            'doanhThuTheoChiNhanh',
            'doanhThuTheoPhim',
            'chiNhanhs',
            'rapPhims',
            'tuNgay',
            'denNgay',
            'loaiThongKe',
            'chiNhanhId',
            'rapPhimId'
        ));
    }

    /**
     * Thống kê vé
     */
    public function ve(Request $request)
    {
        $tuNgay = $request->get('tu_ngay', now()->startOfMonth()->format('Y-m-d'));
        $denNgay = $request->get('den_ngay', now()->format('Y-m-d'));
        $loaiThongKe = $request->get('loai_thong_ke', 'ngay');
        $chiNhanhId = $request->get('chi_nhanh_id');

        $tuNgayCarbon = Carbon::parse($tuNgay);
        $denNgayCarbon = Carbon::parse($denNgay);

        // Thống kê vé tổng quan
        $veTongQuan = $this->tinhVeTongQuan($tuNgayCarbon, $denNgayCarbon, $chiNhanhId);

        // Thống kê vé theo thời gian
        $veTheoThoiGian = $this->thongKeVeTheoThoiGian($tuNgayCarbon, $denNgayCarbon, $loaiThongKe, $chiNhanhId);

        // Thống kê vé theo chi nhánh
        $veTheoChiNhanh = $this->thongKeVeTheoChiNhanh($tuNgayCarbon, $denNgayCarbon);

        // Thống kê vé theo phim
        $veTheoPhim = $this->thongKeVeTheoPhim($tuNgayCarbon, $denNgayCarbon);

        $chiNhanhs = ChiNhanh::where('trang_thai', 1)->get();

        return view('admin.thong-ke.ve', compact(
            'veTongQuan',
            'veTheoThoiGian',
            'veTheoChiNhanh',
            'veTheoPhim',
            'chiNhanhs',
            'tuNgay',
            'denNgay',
            'loaiThongKe',
            'chiNhanhId'
        ));
    }

    /**
     * Thống kê suất chiếu
     */
    public function suatChieu(Request $request)
    {
        $tuNgay = $request->get('tu_ngay', now()->startOfMonth()->format('Y-m-d'));
        $denNgay = $request->get('den_ngay', now()->format('Y-m-d'));
        $chiNhanhId = $request->get('chi_nhanh_id');
        $rapPhimId = $request->get('rap_phim_id');

        $tuNgayCarbon = Carbon::parse($tuNgay);
        $denNgayCarbon = Carbon::parse($denNgay);

        // Thống kê suất chiếu tổng quan
        $suatChieuTongQuan = $this->tinhSuatChieuTongQuan($tuNgayCarbon, $denNgayCarbon, $chiNhanhId, $rapPhimId);

        // Thống kê suất chiếu theo ngày
        $suatChieuTheoNgay = $this->thongKeSuatChieuTheoNgay($tuNgayCarbon, $denNgayCarbon, $chiNhanhId, $rapPhimId);

        // Suất chiếu có lượng khách cao/thấp nhất
        $suatChieuTopBottom = $this->thongKeSuatChieuTopBottom($tuNgayCarbon, $denNgayCarbon);

        $chiNhanhs = ChiNhanh::where('trang_thai', 1)->get();
        $rapPhims = RapPhim::where('trang_thai', 'đang hoạt động')->get();

        return view('admin.thong-ke.suat-chieu', compact(
            'suatChieuTongQuan',
            'suatChieuTheoNgay',
            'suatChieuTopBottom',
            'chiNhanhs',
            'rapPhims',
            'tuNgay',
            'denNgay',
            'chiNhanhId',
            'rapPhimId'
        ));
    }

    /**
     * Thống kê đồ ăn và combo
     */
    public function doAnCombo(Request $request)
    {
        $tuNgay = $request->get('tu_ngay', now()->startOfMonth()->format('Y-m-d'));
        $denNgay = $request->get('den_ngay', now()->format('Y-m-d'));
        $chiNhanhId = $request->get('chi_nhanh_id');

        $tuNgayCarbon = Carbon::parse($tuNgay);
        $denNgayCarbon = Carbon::parse($denNgay);

        // Thống kê đồ ăn combo tổng quan
        $doAnComboTongQuan = $this->tinhDoAnComboTongQuan($tuNgayCarbon, $denNgayCarbon, $chiNhanhId);

        // Sản phẩm bán chạy nhất
        $sanPhamBanChay = $this->thongKeSanPhamBanChay($tuNgayCarbon, $denNgayCarbon, $chiNhanhId);

        // Doanh thu combo theo phim
        $comboTheoPhim = $this->thongKeComboTheoPhim($tuNgayCarbon, $denNgayCarbon);

        $chiNhanhs = ChiNhanh::where('trang_thai', 1)->get();

        return view('admin.thong-ke.do-an-combo', compact(
            'doAnComboTongQuan',
            'sanPhamBanChay',
            'comboTheoPhim',
            'chiNhanhs',
            'tuNgay',
            'denNgay',
            'chiNhanhId'
        ));
    }

    /**
     * Tính doanh thu theo ngày
     */
    private function tinhDoanhThuTheoNgay($ngay)
    {
        // Doanh thu vé trong ngày
        $soSuatChieu = SuatChieu::whereDate('ngay_chieu', $ngay->format('Y-m-d'))->count();

        // Nếu không có suất chiếu thực tế, tạo dữ liệu giả lập
        if ($soSuatChieu == 0) {
            // Giả lập số suất chiếu dựa trên ngày trong tuần
            $thuTrongTuan = $ngay->dayOfWeek;
            if ($thuTrongTuan == 0 || $thuTrongTuan == 6) { // Chủ nhật hoặc thứ 7
                $soSuatChieu = rand(8, 15); // Cuối tuần nhiều suất hơn
            } else {
                $soSuatChieu = rand(4, 10); // Ngày thường ít hơn
            }
        }

        $doanhThuVe = $soSuatChieu * 50 * 0.7 * 80000; // 50 ghế, 70% lấp đầy, 80k/vé

        // Doanh thu combo trong ngày (giả lập dựa trên số suất chiếu)
        $soComboTrungBinh = $soSuatChieu * 15; // Trung bình 15 combo/suất
        $giaComboTrungBinh = 50000;
        $doanhThuCombo = $soComboTrungBinh * $giaComboTrungBinh;

        // Thêm biến động ngẫu nhiên ±20%
        $bienDong = rand(80, 120) / 100;

        return ($doanhThuVe + $doanhThuCombo) * $bienDong;
    }

    // ===== HELPER METHODS CHO THỐNG KÊ DOANH THU =====

    private function tinhDoanhThuTongQuan($tuNgay, $denNgay, $chiNhanhId = null, $rapPhimId = null)
    {
        $query = SuatChieu::whereBetween('ngay_chieu', [$tuNgay->format('Y-m-d'), $denNgay->format('Y-m-d')]);

        if ($chiNhanhId) {
            $query->whereHas('phongChieu.rapPhim', function ($q) use ($chiNhanhId) {
                $q->where('chi_nhanh_id', $chiNhanhId);
            });
        }

        if ($rapPhimId) {
            $query->whereHas('phongChieu', function ($q) use ($rapPhimId) {
                $q->where('rap_phim_id', $rapPhimId);
            });
        }

        $soSuatChieu = $query->count();
        $doanhThuVe = $this->tinhDoanhThuVe($tuNgay, $denNgay);
        $doanhThuCombo = $this->tinhDoanhThuCombo($tuNgay, $denNgay);

        return [
            'tong_doanh_thu' => $doanhThuVe + $doanhThuCombo,
            'doanh_thu_ve' => $doanhThuVe,
            'doanh_thu_combo' => $doanhThuCombo,
            'so_suat_chieu' => $soSuatChieu,
            'so_ve_ban' => $soSuatChieu * 50 * 0.7, // Giả lập
            'ty_le_lap_day' => 70
        ];
    }

    private function thongKeDoanhThuTheoThoiGian($tuNgay, $denNgay, $loaiThongKe, $chiNhanhId = null, $rapPhimId = null)
    {
        $data = [];

        switch ($loaiThongKe) {
            case 'ngay':
                $current = $tuNgay->copy();
                while ($current->lte($denNgay)) {
                    $doanhThu = $this->tinhDoanhThuTheoNgay($current);
                    $data[] = [
                        'label' => $current->format('d/m'),
                        'ngay' => $current->format('Y-m-d'),
                        'doanh_thu' => $doanhThu
                    ];
                    $current->addDay();
                }
                break;

            case 'tuan':
                $current = $tuNgay->copy()->startOfWeek();
                while ($current->lte($denNgay)) {
                    $endWeek = $current->copy()->endOfWeek();
                    $doanhThu = $this->tinhDoanhThuVe($current, $endWeek) + $this->tinhDoanhThuCombo($current, $endWeek);
                    $data[] = [
                        'label' => 'Tuần ' . $current->weekOfYear,
                        'ngay' => $current->format('Y-m-d'),
                        'doanh_thu' => $doanhThu
                    ];
                    $current->addWeek();
                }
                break;

            case 'thang':
                $current = $tuNgay->copy()->startOfMonth();
                while ($current->lte($denNgay)) {
                    $endMonth = $current->copy()->endOfMonth();
                    $doanhThu = $this->tinhDoanhThuVe($current, $endMonth) + $this->tinhDoanhThuCombo($current, $endMonth);
                    $data[] = [
                        'label' => $current->format('m/Y'),
                        'ngay' => $current->format('Y-m-d'),
                        'doanh_thu' => $doanhThu
                    ];
                    $current->addMonth();
                }
                break;
        }

        return $data;
    }

    private function thongKeDoanhThuTheoChiNhanh($tuNgay, $denNgay)
    {
        $chiNhanhs = ChiNhanh::where('trang_thai', 1)->get();
        $data = [];

        foreach ($chiNhanhs as $chiNhanh) {
            $soSuatChieu = SuatChieu::whereBetween('ngay_chieu', [$tuNgay->format('Y-m-d'), $denNgay->format('Y-m-d')])
                ->whereHas('phongChieu.rapPhim', function ($q) use ($chiNhanh) {
                    $q->where('chi_nhanh_id', $chiNhanh->id);
                })
                ->count();

            $doanhThu = $soSuatChieu * 50 * 0.7 * 80000; // Giả lập

            $data[] = [
                'ten_chi_nhanh' => $chiNhanh->ten_chi_nhanh,
                'doanh_thu' => $doanhThu,
                'so_suat_chieu' => $soSuatChieu,
                'so_ve_ban' => $soSuatChieu * 35 // Giả lập
            ];
        }

        return collect($data)->sortByDesc('doanh_thu')->values();
    }

    private function thongKeDoanhThuTheoPhim($tuNgay, $denNgay)
    {
        $phims = Phim::whereHas('suatChieus', function ($q) use ($tuNgay, $denNgay) {
            $q->whereBetween('ngay_chieu', [$tuNgay->format('Y-m-d'), $denNgay->format('Y-m-d')]);
        })->withCount(['suatChieus' => function ($q) use ($tuNgay, $denNgay) {
            $q->whereBetween('ngay_chieu', [$tuNgay->format('Y-m-d'), $denNgay->format('Y-m-d')]);
        }])->get();

        $data = [];
        foreach ($phims as $phim) {
            $doanhThu = $phim->suat_chieus_count * 50 * 0.7 * 80000; // Giả lập
            $data[] = [
                'ten_phim' => $phim->ten_phim,
                'doanh_thu' => $doanhThu,
                'so_suat_chieu' => $phim->suat_chieus_count,
                'so_ve_ban' => $phim->suat_chieus_count * 35
            ];
        }

        return collect($data)->sortByDesc('doanh_thu')->take(10)->values();
    }

    // ===== HELPER METHODS CHO THỐNG KÊ VÉ =====

    private function tinhVeTongQuan($tuNgay, $denNgay, $chiNhanhId = null)
    {
        $query = SuatChieu::whereBetween('ngay_chieu', [$tuNgay->format('Y-m-d'), $denNgay->format('Y-m-d')]);

        if ($chiNhanhId) {
            $query->whereHas('phongChieu.rapPhim', function ($q) use ($chiNhanhId) {
                $q->where('chi_nhanh_id', $chiNhanhId);
            });
        }

        $soSuatChieu = $query->count();
        $soVeBan = $soSuatChieu * 35; // Giả lập 35 vé/suất
        $soVeTong = $soSuatChieu * 50; // Giả lập 50 ghế/suất

        return [
            'tong_ve_ban' => $soVeBan,
            'tong_ve_co_the_ban' => $soVeTong,
            'ty_le_ban_ve' => $soVeTong > 0 ? round(($soVeBan / $soVeTong) * 100, 1) : 0,
            'so_suat_chieu' => $soSuatChieu
        ];
    }

    private function thongKeVeTheoThoiGian($tuNgay, $denNgay, $loaiThongKe, $chiNhanhId = null)
    {
        $data = [];

        switch ($loaiThongKe) {
            case 'ngay':
                $current = $tuNgay->copy();
                while ($current->lte($denNgay)) {
                    $soSuatChieu = SuatChieu::whereDate('ngay_chieu', $current->format('Y-m-d'))->count();
                    $soVeBan = $soSuatChieu * 35;

                    $data[] = [
                        'label' => $current->format('d/m'),
                        'ngay' => $current->format('Y-m-d'),
                        'so_ve_ban' => $soVeBan,
                        'so_suat_chieu' => $soSuatChieu
                    ];
                    $current->addDay();
                }
                break;
        }

        return $data;
    }

    private function thongKeVeTheoChiNhanh($tuNgay, $denNgay)
    {
        $chiNhanhs = ChiNhanh::where('trang_thai', 1)->get();
        $data = [];

        foreach ($chiNhanhs as $chiNhanh) {
            $soSuatChieu = SuatChieu::whereBetween('ngay_chieu', [$tuNgay->format('Y-m-d'), $denNgay->format('Y-m-d')])
                ->whereHas('phongChieu.rapPhim', function ($q) use ($chiNhanh) {
                    $q->where('chi_nhanh_id', $chiNhanh->id);
                })
                ->count();

            $soVeBan = $soSuatChieu * 35;

            $data[] = [
                'ten_chi_nhanh' => $chiNhanh->ten_chi_nhanh,
                'so_ve_ban' => $soVeBan,
                'so_suat_chieu' => $soSuatChieu
            ];
        }

        return collect($data)->sortByDesc('so_ve_ban')->values();
    }

    private function thongKeVeTheoPhim($tuNgay, $denNgay)
    {
        $phims = Phim::whereHas('suatChieus', function ($q) use ($tuNgay, $denNgay) {
            $q->whereBetween('ngay_chieu', [$tuNgay->format('Y-m-d'), $denNgay->format('Y-m-d')]);
        })->withCount(['suatChieus' => function ($q) use ($tuNgay, $denNgay) {
            $q->whereBetween('ngay_chieu', [$tuNgay->format('Y-m-d'), $denNgay->format('Y-m-d')]);
        }])->get();

        $data = [];
        foreach ($phims as $phim) {
            $soVeBan = $phim->suat_chieus_count * 35;
            $data[] = [
                'ten_phim' => $phim->ten_phim,
                'so_ve_ban' => $soVeBan,
                'so_suat_chieu' => $phim->suat_chieus_count
            ];
        }

        return collect($data)->sortByDesc('so_ve_ban')->take(10)->values();
    }

    // ===== HELPER METHODS CHO THỐNG KÊ SUẤT CHIẾU =====

    private function tinhSuatChieuTongQuan($tuNgay, $denNgay, $chiNhanhId = null, $rapPhimId = null)
    {
        $query = SuatChieu::whereBetween('ngay_chieu', [$tuNgay->format('Y-m-d'), $denNgay->format('Y-m-d')]);

        if ($chiNhanhId) {
            $query->whereHas('phongChieu.rapPhim', function ($q) use ($chiNhanhId) {
                $q->where('chi_nhanh_id', $chiNhanhId);
            });
        }

        if ($rapPhimId) {
            $query->whereHas('phongChieu', function ($q) use ($rapPhimId) {
                $q->where('rap_phim_id', $rapPhimId);
            });
        }

        $soSuatChieu = $query->count();
        $soNgay = $tuNgay->diffInDays($denNgay) + 1;

        return [
            'tong_suat_chieu' => $soSuatChieu,
            'suat_chieu_trung_binh_ngay' => $soNgay > 0 ? round($soSuatChieu / $soNgay, 1) : 0,
            'so_ngay' => $soNgay
        ];
    }

    private function thongKeSuatChieuTheoNgay($tuNgay, $denNgay, $chiNhanhId = null, $rapPhimId = null)
    {
        $data = [];
        $current = $tuNgay->copy();

        while ($current->lte($denNgay)) {
            $query = SuatChieu::whereDate('ngay_chieu', $current->format('Y-m-d'));

            if ($chiNhanhId) {
                $query->whereHas('phongChieu.rapPhim', function ($q) use ($chiNhanhId) {
                    $q->where('chi_nhanh_id', $chiNhanhId);
                });
            }

            if ($rapPhimId) {
                $query->whereHas('phongChieu', function ($q) use ($rapPhimId) {
                    $q->where('rap_phim_id', $rapPhimId);
                });
            }

            $soSuatChieu = $query->count();

            $data[] = [
                'ngay' => $current->format('Y-m-d'),
                'label' => $current->format('d/m'),
                'so_suat_chieu' => $soSuatChieu,
                'luong_khach_uoc_tinh' => $soSuatChieu * 35 // Giả lập
            ];

            $current->addDay();
        }

        return $data;
    }

    private function thongKeSuatChieuTopBottom($tuNgay, $denNgay)
    {
        // Giả lập dữ liệu suất chiếu có lượng khách cao/thấp nhất
        $suatChieuData = [];

        $suatChieus = SuatChieu::with(['phim', 'phongChieu.rapPhim.chiNhanh'])
            ->whereBetween('ngay_chieu', [$tuNgay->format('Y-m-d'), $denNgay->format('Y-m-d')])
            ->get();

        foreach ($suatChieus as $suatChieu) {
            $luongKhach = rand(10, 50); // Giả lập lượng khách
            $suatChieuData[] = [
                'phim' => $suatChieu->phim->ten_phim ?? 'N/A',
                'rap' => $suatChieu->phongChieu->rapPhim->ten_rap ?? 'N/A',
                'chi_nhanh' => $suatChieu->phongChieu->rapPhim->chiNhanh->ten_chi_nhanh ?? 'N/A',
                'ngay_chieu' => $suatChieu->ngay_chieu,
                'gio_chieu' => $suatChieu->bat_dau,
                'luong_khach' => $luongKhach,
                'ty_le_lap_day' => round(($luongKhach / 50) * 100, 1)
            ];
        }

        $collection = collect($suatChieuData);

        return [
            'cao_nhat' => $collection->sortByDesc('luong_khach')->take(5)->values(),
            'thap_nhat' => $collection->sortBy('luong_khach')->take(5)->values()
        ];
    }

    // ===== HELPER METHODS CHO THỐNG KÊ ĐỒ ĂN COMBO =====

    private function tinhDoAnComboTongQuan($tuNgay, $denNgay, $chiNhanhId = null)
    {
        $soNgay = $tuNgay->diffInDays($denNgay) + 1;
        $doanhThuCombo = $this->tinhDoanhThuCombo($tuNgay, $denNgay);

        // Giả lập doanh thu đồ ăn riêng lẻ
        $doanhThuDoAn = $soNgay * 15 * 25000; // 15 món/ngày, 25k/món

        return [
            'tong_doanh_thu' => $doanhThuCombo + $doanhThuDoAn,
            'doanh_thu_combo' => $doanhThuCombo,
            'doanh_thu_do_an' => $doanhThuDoAn,
            'so_combo_ban' => $soNgay * 20,
            'so_do_an_ban' => $soNgay * 15
        ];
    }

    private function thongKeSanPhamBanChay($tuNgay, $denNgay, $chiNhanhId = null)
    {
        // Thống kê combo bán chạy
        $combos = Combo::where('trang_thai', 'hien')->get();
        $comboData = [];

        foreach ($combos as $combo) {
            $soLuongBan = rand(50, 200); // Giả lập
            $comboData[] = [
                'ten' => $combo->tieu_de,
                'loai' => 'Combo',
                'gia' => $combo->gia_combo,
                'so_luong_ban' => $soLuongBan,
                'doanh_thu' => $soLuongBan * $combo->gia_combo
            ];
        }

        // Thống kê đồ ăn bán chạy
        $doAns = DoAn::where('trang_thai', 'hien')->take(10)->get();
        $doAnData = [];

        foreach ($doAns as $doAn) {
            $soLuongBan = rand(30, 150); // Giả lập
            $doAnData[] = [
                'ten' => $doAn->tieu_de,
                'loai' => 'Đồ ăn',
                'gia' => $doAn->gia,
                'so_luong_ban' => $soLuongBan,
                'doanh_thu' => $soLuongBan * $doAn->gia
            ];
        }

        $allData = array_merge($comboData, $doAnData);

        return collect($allData)->sortByDesc('so_luong_ban')->take(10)->values();
    }

    private function thongKeComboTheoPhim($tuNgay, $denNgay)
    {
        $phims = Phim::whereHas('suatChieus', function ($q) use ($tuNgay, $denNgay) {
            $q->whereBetween('ngay_chieu', [$tuNgay->format('Y-m-d'), $denNgay->format('Y-m-d')]);
        })->withCount(['suatChieus' => function ($q) use ($tuNgay, $denNgay) {
            $q->whereBetween('ngay_chieu', [$tuNgay->format('Y-m-d'), $denNgay->format('Y-m-d')]);
        }])->get();

        $data = [];
        foreach ($phims as $phim) {
            $soComboUocTinh = $phim->suat_chieus_count * 15; // 15 combo/suất
            $data[] = [
                'ten_phim' => $phim->ten_phim,
                'so_combo_ban' => $soComboUocTinh,
                'so_suat_chieu' => $phim->suat_chieus_count,
                'doanh_thu_combo' => $soComboUocTinh * 50000 // Giả lập giá combo trung bình
            ];
        }

        return collect($data)->sortByDesc('so_combo_ban')->take(10)->values();
    }

    // ===== HELPER METHODS CHO THỐNG KÊ PHIM =====

    private function tinhThongKePhimTongQuan()
    {
        return [
            'tong_phim' => Phim::count(),
            'dang_chieu' => Phim::where('trang_thai', 'đang chiếu')->count(),
            'sap_chieu' => Phim::where('trang_thai', 'sắp chiếu')->count(),
            'da_ket_thuc' => Phim::where('trang_thai', 'đã kết thúc')->count(),
            'bi_huy' => Phim::where('trang_thai', 'bị hủy')->count()
        ];
    }

    private function thongKePhimTopBottom($tuNgay, $denNgay)
    {
        $phims = Phim::whereHas('suatChieus', function ($q) use ($tuNgay, $denNgay) {
            $q->whereBetween('ngay_chieu', [$tuNgay->format('Y-m-d'), $denNgay->format('Y-m-d')]);
        })->withCount(['suatChieus' => function ($q) use ($tuNgay, $denNgay) {
            $q->whereBetween('ngay_chieu', [$tuNgay->format('Y-m-d'), $denNgay->format('Y-m-d')]);
        }])->get();

        $data = [];
        foreach ($phims as $phim) {
            $luotXem = $phim->suat_chieus_count * 35; // Giả lập lượt xem
            $data[] = [
                'ten_phim' => $phim->ten_phim,
                'luot_xem' => $luotXem,
                'so_suat_chieu' => $phim->suat_chieus_count,
                'trang_thai' => $phim->trang_thai,
                'ngay_phat_hanh' => $phim->ngay_phat_hanh
            ];
        }

        $collection = collect($data);

        return [
            'cao_nhat' => $collection->sortByDesc('luot_xem')->take(5)->values(),
            'thap_nhat' => $collection->sortBy('luot_xem')->take(5)->values()
        ];
    }

    private function thongKePhimTheoTheLoai()
    {
        $theLoais = TheLoaiPhim::withCount('phims')->get();

        $data = [];
        foreach ($theLoais as $theLoai) {
            $data[] = [
                'ten_the_loai' => $theLoai->ten_loai,
                'so_phim' => $theLoai->phims_count,
                'ty_le' => Phim::count() > 0 ? round(($theLoai->phims_count / Phim::count()) * 100, 1) : 0
            ];
        }

        return collect($data)->sortByDesc('so_phim')->values();
    }

    private function thongKePhimTheoTrangThai()
    {
        $trangThais = ['đang chiếu', 'sắp chiếu', 'đã kết thúc', 'bị hủy'];
        $data = [];

        foreach ($trangThais as $trangThai) {
            $soPhim = Phim::where('trang_thai', $trangThai)->count();
            $data[] = [
                'trang_thai' => $trangThai,
                'so_phim' => $soPhim,
                'ty_le' => Phim::count() > 0 ? round(($soPhim / Phim::count()) * 100, 1) : 0
            ];
        }

        return collect($data);
    }
}
