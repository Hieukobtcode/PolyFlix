<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KhuyenMai;
use App\Models\ChiNhanh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChiNhanhKhuyenMaiController extends Controller
{
    /**
     * Hiển thị danh sách khuyến mãi áp dụng cho chi nhánh của admin chi nhánh
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Kiểm tra xem user có phải admin chi nhánh không
        if ($user->vai_tro_id != 2) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        // Lấy chi nhánh mà admin này quản lý
        $chiNhanh = $user->chiNhanhDangQuanLy;

        if (!$chiNhanh) {
            return redirect()->back()->with('error', 'Bạn chưa được gán quản lý chi nhánh nào.');
        }

        // Lấy các khuyến mãi áp dụng cho chi nhánh này
        $query = $chiNhanh->khuyenMais()->with(['chiNhanhs']);

        // Bộ lọc theo trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Bộ lọc theo loại áp dụng
        if ($request->filled('ap_dung_cho')) {
            $query->where('ap_dung_cho', $request->ap_dung_cho);
        }

        // Bộ lọc theo thời gian
        if ($request->filled('thoi_gian')) {
            $now = now();
            switch ($request->thoi_gian) {
                case 'con_hieu_luc':
                    $query->where('ngay_bat_dau', '<=', $now)
                        ->where('ngay_ket_thuc', '>=', $now)
                        ->where('trang_thai', 'hoat_dong');
                    break;
                case 'sap_het_han':
                    $query->where('ngay_ket_thuc', '>=', $now)
                        ->where('ngay_ket_thuc', '<=', $now->addDays(7));
                    break;
                case 'da_het_han':
                    $query->where('ngay_ket_thuc', '<', $now);
                    break;
            }
        }

        // Tìm kiếm theo tên hoặc mã khuyến mãi
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ten', 'like', "%{$search}%")
                    ->orWhere('ma_khuyen_mai', 'like', "%{$search}%");
            });
        }

        $khuyenMais = $query->orderBy('ngay_bat_dau', 'desc')->paginate(10);

        return view('admin.chi-nhanh-khuyen-mai.index', compact('khuyenMais', 'chiNhanh'));
    }

    /**
     * Hiển thị chi tiết khuyến mãi
     */
    public function show($id)
    {
        $user = Auth::user();

        // Kiểm tra xem user có phải admin chi nhánh không
        if ($user->vai_tro_id != 2) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        // Lấy chi nhánh mà admin này quản lý
        $chiNhanh = $user->chiNhanhDangQuanLy;

        if (!$chiNhanh) {
            return redirect()->back()->with('error', 'Bạn chưa được gán quản lý chi nhánh nào.');
        }

        // Kiểm tra khuyến mãi có áp dụng cho chi nhánh này không
        $khuyenMai = $chiNhanh->khuyenMais()->with(['chiNhanhs'])->findOrFail($id);

        // Thống kê sử dụng khuyến mãi tại chi nhánh này
        $thongKeSuDung = $this->getThongKeSuDung($khuyenMai, $chiNhanh);

        return view('admin.chi-nhanh-khuyen-mai.show', compact('khuyenMai', 'chiNhanh', 'thongKeSuDung'));
    }

    /**
     * Thống kê sử dụng khuyến mãi tại chi nhánh
     */
    private function getThongKeSuDung($khuyenMai, $chiNhanh)
    {
        // Lấy số lượng vé đã sử dụng khuyến mãi tại chi nhánh này
        $soVeDaSuDung = \App\Models\DatVe::where('khuyen_mai_id', $khuyenMai->id)
            ->whereHas('suatChieu.phongChieu.rapPhim', function ($q) use ($chiNhanh) {
                $q->where('chi_nhanh_id', $chiNhanh->id);
            })
            ->count();

        // Tính tổng tiền đã giảm (ước tính dựa trên khuyến mãi)
        $datVes = \App\Models\DatVe::where('khuyen_mai_id', $khuyenMai->id)
            ->whereHas('suatChieu.phongChieu.rapPhim', function ($q) use ($chiNhanh) {
                $q->where('chi_nhanh_id', $chiNhanh->id);
            })
            ->get();

        $tongTienGiam = 0;
        foreach ($datVes as $datVe) {
            if ($khuyenMai->loai_giam_gia == 'phan_tram') {
                $tienGiam = ($datVe->tong_tien * $khuyenMai->gia_tri_giam) / 100;
                if ($khuyenMai->giam_toi_da && $tienGiam > $khuyenMai->giam_toi_da) {
                    $tienGiam = $khuyenMai->giam_toi_da;
                }
            } else {
                $tienGiam = $khuyenMai->gia_tri_giam;
            }
            $tongTienGiam += $tienGiam;
        }

        return [
            'so_ve_da_su_dung' => $soVeDaSuDung,
            'tong_tien_giam' => $tongTienGiam,
            'ty_le_su_dung' => $khuyenMai->so_lan_su_dung_toi_da > 0
                ? round(($soVeDaSuDung / $khuyenMai->so_lan_su_dung_toi_da) * 100, 2)
                : 0
        ];
    }

    /**
     * Báo cáo khuyến mãi theo chi nhánh
     */
    public function baoCao(Request $request)
    {
        $user = Auth::user();

        // Kiểm tra xem user có phải admin chi nhánh không
        if ($user->vai_tro_id != 2) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        // Lấy chi nhánh mà admin này quản lý
        $chiNhanh = $user->chiNhanhDangQuanLy;

        if (!$chiNhanh) {
            return redirect()->back()->with('error', 'Bạn chưa được gán quản lý chi nhánh nào.');
        }

        // Lấy khoảng thời gian báo cáo
        $tuNgay = $request->input('tu_ngay', now()->startOfMonth()->format('Y-m-d'));
        $denNgay = $request->input('den_ngay', now()->endOfMonth()->format('Y-m-d'));

        // Báo cáo tổng quan
        $tongQuan = [
            'tong_khuyen_mai' => $chiNhanh->khuyenMais()->count(),
            'khuyen_mai_dang_hoat_dong' => $chiNhanh->khuyenMais()
                ->where('trang_thai', 'hoat_dong')
                ->where('ngay_bat_dau', '<=', now())
                ->where('ngay_ket_thuc', '>=', now())
                ->count(),
            'tong_ve_su_dung' => \App\Models\DatVe::whereIn(
                'khuyen_mai_id',
                $chiNhanh->khuyenMais()->pluck('id')
            )
                ->whereHas('suatChieu.phongChieu.rapPhim', function ($q) use ($chiNhanh) {
                    $q->where('chi_nhanh_id', $chiNhanh->id);
                })
                ->whereBetween('created_at', [$tuNgay, $denNgay])
                ->count(),
            'tong_tien_giam' => $this->tinhTongTienGiam($chiNhanh, $tuNgay, $denNgay)
        ];

        // Báo cáo chi tiết theo từng khuyến mãi
        $baoCaoChiTiet = $chiNhanh->khuyenMais()
            ->with(['chiNhanhs'])
            ->get()
            ->map(function ($khuyenMai) use ($chiNhanh, $tuNgay, $denNgay) {
                $datVes = \App\Models\DatVe::where('khuyen_mai_id', $khuyenMai->id)
                    ->whereHas('suatChieu.phongChieu.rapPhim', function ($q) use ($chiNhanh) {
                        $q->where('chi_nhanh_id', $chiNhanh->id);
                    })
                    ->whereBetween('created_at', [$tuNgay, $denNgay])
                    ->get();

                $soVeSuDung = $datVes->count();
                $tienGiam = 0;

                foreach ($datVes as $datVe) {
                    if ($khuyenMai->loai_giam_gia == 'phan_tram') {
                        $giam = ($datVe->tong_tien * $khuyenMai->gia_tri_giam) / 100;
                        if ($khuyenMai->giam_toi_da && $giam > $khuyenMai->giam_toi_da) {
                            $giam = $khuyenMai->giam_toi_da;
                        }
                    } else {
                        $giam = $khuyenMai->gia_tri_giam;
                    }
                    $tienGiam += $giam;
                }

                return [
                    'khuyen_mai' => $khuyenMai,
                    'so_ve_su_dung' => $soVeSuDung,
                    'tien_giam' => $tienGiam,
                    'ty_le_su_dung' => $khuyenMai->so_lan_su_dung_toi_da > 0
                        ? round(($soVeSuDung / $khuyenMai->so_lan_su_dung_toi_da) * 100, 2)
                        : 0
                ];
            });

        return view('admin.chi-nhanh-khuyen-mai.bao-cao', compact(
            'chiNhanh',
            'tongQuan',
            'baoCaoChiTiet',
            'tuNgay',
            'denNgay'
        ));
    }

    /**
     * Tính tổng tiền giảm cho chi nhánh trong khoảng thời gian
     */
    private function tinhTongTienGiam($chiNhanh, $tuNgay, $denNgay)
    {
        $datVes = \App\Models\DatVe::whereIn('khuyen_mai_id', $chiNhanh->khuyenMais()->pluck('id'))
            ->with('khuyenMai')
            ->whereHas('suatChieu.phongChieu.rapPhim', function ($q) use ($chiNhanh) {
                $q->where('chi_nhanh_id', $chiNhanh->id);
            })
            ->whereBetween('created_at', [$tuNgay, $denNgay])
            ->get();

        $tongTienGiam = 0;
        foreach ($datVes as $datVe) {
            if ($datVe->khuyenMai) {
                if ($datVe->khuyenMai->loai_giam_gia == 'phan_tram') {
                    $giam = ($datVe->tong_tien * $datVe->khuyenMai->gia_tri_giam) / 100;
                    if ($datVe->khuyenMai->giam_toi_da && $giam > $datVe->khuyenMai->giam_toi_da) {
                        $giam = $datVe->khuyenMai->giam_toi_da;
                    }
                } else {
                    $giam = $datVe->khuyenMai->gia_tri_giam;
                }
                $tongTienGiam += $giam;
            }
        }

        return $tongTienGiam;
    }
}
