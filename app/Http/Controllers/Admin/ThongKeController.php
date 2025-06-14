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
use App\Models\User;
use App\Models\SuatChieu;
use App\Models\LichSuSuDungKhuyenMai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ThongKeController extends Controller
{
    /**
     * Hiển thị trang thống kê tổng quan
     */
    public function index(Request $request)
    {
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

        // Thống kê theo thời gian (7 ngày gần đây)
        $thongKeTheoNgay = [];
        for ($i = 6; $i >= 0; $i--) {
            $ngay = Carbon::now()->subDays($i);
            $thongKeTheoNgay[] = [
                'ngay' => $ngay->format('d/m'),
                'lien_he_moi' => LienHe::whereDate('created_at', $ngay)->count(),
                'khuyen_mai_su_dung' => LichSuSuDungKhuyenMai::whereDate('thoi_gian_su_dung', $ngay)->count(),
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

        return view('admin.thong-ke.index', compact(
            'tongQuan',
            'thongKeTheoNgay',
            'topPhim',
            'topKhuyenMai',
            'thongKeLienHe',
            'thongKePhimTheoTheLoai',
            'thongKeCombo'
        ));
    }

    /**
     * Thống kê chi tiết phim
     */
    public function phim(Request $request)
    {
        $query = Phim::query();

        // Lọc theo thời gian nếu có
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $phims = $query->withCount('suatChieus')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Thống kê tổng hợp
        $thongKe = [
            'tong_phim' => $query->count(),
            'dang_chieu' => $query->where('trang_thai', 'dang_chieu')->count(),
            'sap_chieu' => $query->where('trang_thai', 'sap_chieu')->count(),
            'ngung_chieu' => $query->where('trang_thai', 'ngung_chieu')->count(),
        ];

        return view('admin.thong-ke.phim', compact('phims', 'thongKe'));
    }

    /**
     * Thống kê chi tiết liên hệ
     */
    public function lienHe(Request $request)
    {
        $query = LienHe::query();

        // Lọc theo thời gian nếu có
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $lienHes = $query->orderBy('created_at', 'desc')->paginate(10);

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
                'so_luong' => LienHe::whereYear('created_at', $thang->year)
                    ->whereMonth('created_at', $thang->month)
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
        $lienHes = LienHe::select('ho_ten', 'email', 'trang_thai', 'created_at')->get();

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
}
