<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChiNhanh;
use App\Models\DatVe;
use App\Models\GheNgoi;
use App\Models\Phim;
use App\Models\PhongChieu;
use App\Models\RapPhim;
use App\Models\SuatChieu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ThongKeController extends Controller
{
    public function thongKeTongQuan(Request $request)
    {
        // Đếm số lượng chi nhánh, rạp, phòng chiếu, người dùng
        $soChiNhanhs = ChiNhanh::count();
        $soRaps = RapPhim::count();
        $soPhongChieus = PhongChieu::count();
        $soNguoiDungs = User::count();

        // Tính tổng doanh thu hệ thống
        $tongDoanhThuHeThong = DatVe::sum('tong_tien');

        // Lấy top 5 chi nhánh có doanh thu cao nhất
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

        // Lấy top 5 phim có doanh thu cao nhất
        $topDoanhThuPhimHeThong = DatVe::join('suat_chieus', 'dat_ves.suat_chieu_id', '=', 'suat_chieus.id')
            ->join('phims', 'suat_chieus.phim_id', '=', 'phims.id')
            ->select(
                'phims.id',
                'phims.ten_phim',
                DB::raw('SUM(dat_ves.tong_tien) as tong_doanh_thu')
            )
            ->groupBy('phims.id', 'phims.ten_phim')
            ->orderByDesc('tong_doanh_thu')
            ->limit(5)
            ->get();

        $topDoanhThuPhimHeThong->transform(function ($item) use ($tongDoanhThuHeThong) {
            $item->phan_tram = $tongDoanhThuHeThong > 0
                ? round(($item->tong_doanh_thu / $tongDoanhThuHeThong) * 100, 2)
                : 0;
            return $item;
        });

        // Tính tỷ lệ lấp đầy ghế
        $queryGhe = GheNgoi::join('phong_chieus', 'ghe_ngois.phong_chieu_id', '=', 'phong_chieus.id')
            ->join('rap_phims', 'phong_chieus.rap_phim_id', '=', 'rap_phims.id')
            ->join('chi_nhanhs', 'rap_phims.chi_nhanh_id', '=', 'chi_nhanhs.id');

        if ($request->branch_id) {
            $queryGhe->where('chi_nhanhs.id', $request->branch_id);
        }
        if ($request->rap_id) {
            $queryGhe->where('rap_phims.id', $request->rap_id);
        }

        $soGheDaDat = $queryGhe->where('ghe_ngois.trang_thai', 'da_dat')->count();
        $tongSoGhe = $queryGhe->count();
        $tyLeLapDayGhe = $tongSoGhe > 0 ? round(($soGheDaDat / $tongSoGhe) * 100) : 0;

        // Lấy danh sách chi nhánh và rạp
        $danhSachChiNhanh = ChiNhanh::all();
        $danhSachRap = $request->branch_id
            ? RapPhim::where('chi_nhanh_id', $request->branch_id)->get()
            : RapPhim::all();

        return view('admin.thong-ke.index', compact(
            'soChiNhanhs',
            'soRaps',
            'soPhongChieus',
            'soNguoiDungs',
            'top5ChiNhanh',
            'topDoanhThuPhimHeThong',
            'tyLeLapDayGhe',
            'danhSachChiNhanh',
            'danhSachRap'
        ));
    }

    public function thongKeDoanhThu(Request $request)
    {
        // Lấy tham số từ request
        $branchId = $request->input('branch_id');
        $rapId = $request->input('rap_id');
        $tuNgay = $request->input('tu_ngay');
        $denNgay = $request->input('den_ngay');

        // Tính tổng doanh thu
        $query = DatVe::join('suat_chieus', 'dat_ves.suat_chieu_id', '=', 'suat_chieus.id')
            ->join('phong_chieus', 'suat_chieus.phong_chieu_id', '=', 'phong_chieus.id')
            ->join('rap_phims', 'phong_chieus.rap_phim_id', '=', 'rap_phims.id')
            ->join('chi_nhanhs', 'rap_phims.chi_nhanh_id', '=', 'chi_nhanhs.id');

        if ($branchId) {
            $query->where('chi_nhanhs.id', $branchId);
        }
        if ($rapId) {
            $query->where('rap_phims.id', $rapId);
        }
        if ($tuNgay && $denNgay) {
            $query->whereBetween('dat_ves.created_at', [$tuNgay, $denNgay . ' 23:59:59']);
        }

        $tongDoanhThu = $query->sum('dat_ves.tong_tien');

        // Lấy top 5 phim có doanh thu cao nhất
        $top5Phim = DatVe::join('suat_chieus', 'dat_ves.suat_chieu_id', '=', 'suat_chieus.id')
            ->join('phims', 'suat_chieus.phim_id', '=', 'phims.id')
            ->join('phong_chieus', 'suat_chieus.phong_chieu_id', '=', 'phong_chieus.id')
            ->join('rap_phims', 'phong_chieus.rap_phim_id', '=', 'rap_phims.id')
            ->join('chi_nhanhs', 'rap_phims.chi_nhanh_id', '=', 'chi_nhanhs.id')
            ->select(
                'phims.id',
                'phims.ten_phim',
                DB::raw('SUM(dat_ves.tong_tien) as tong_doanh_thu')
            )
            ->groupBy('phims.id', 'phims.ten_phim')
            ->orderByDesc('tong_doanh_thu')
            ->limit(5);

        if ($branchId) {
            $top5Phim->where('chi_nhanhs.id', $branchId);
        }
        if ($rapId) {
            $top5Phim->where('rap_phims.id', $rapId);
        }
        if ($tuNgay && $denNgay) {
            $top5Phim->whereBetween('dat_ves.created_at', [$tuNgay, $denNgay . ' 23:59:59']);
        }

        $top5Phim = $top5Phim->get();

        $top5Phim->transform(function ($item) use ($tongDoanhThu) {
            $item->phan_tram = $tongDoanhThu > 0
                ? round(($item->tong_doanh_thu / $tongDoanhThu) * 100, 2)
                : 0;
            return $item;
        });

        // Lấy danh sách chi nhánh và rạp
        $danhSachChiNhanh = ChiNhanh::all();
        $danhSachRap = $request->branch_id
            ? RapPhim::where('chi_nhanh_id', $request->branch_id)->get()
            : RapPhim::all();

        return view('admin.thong-ke.doanh-thu', compact(
            'tongDoanhThu',
            'top5Phim',
            'danhSachChiNhanh',
            'danhSachRap',
            'tuNgay',
            'denNgay'
        ));
    }

    public function thongKeSuatChieu(Request $request)
    {
        // Lấy tham số từ request
        $branchId = $request->input('branch_id');
        $rapId = $request->input('rap_id');
        $tuNgay = $request->input('tu_ngay');
        $denNgay = $request->input('den_ngay');

        // Tổng số suất chiếu
        $querySuatChieu = SuatChieu::join('phong_chieus', 'suat_chieus.phong_chieu_id', '=', 'phong_chieus.id')
            ->join('rap_phims', 'phong_chieus.rap_phim_id', '=', 'rap_phims.id')
            ->join('chi_nhanhs', 'rap_phims.chi_nhanh_id', '=', 'chi_nhanhs.id');

        if ($branchId) {
            $querySuatChieu->where('chi_nhanhs.id', $branchId);
        }
        if ($rapId) {
            $querySuatChieu->where('rap_phims.id', $rapId);
        }
        if ($tuNgay && $denNgay) {
            $querySuatChieu->whereBetween('suat_chieus.bat_dau', [$tuNgay, $denNgay . ' 23:59:59']);
        }

        $tongSuatChieu = $querySuatChieu->count();

        // Tổng số vé bán được
        $queryVe = DatVe::join('suat_chieus', 'dat_ves.suat_chieu_id', '=', 'suat_chieus.id')
            ->join('phong_chieus', 'suat_chieus.phong_chieu_id', '=', 'phong_chieus.id')
            ->join('rap_phims', 'phong_chieus.rap_phim_id', '=', 'rap_phims.id')
            ->join('chi_nhanhs', 'rap_phims.chi_nhanh_id', '=', 'chi_nhanhs.id');

        if ($branchId) {
            $queryVe->where('chi_nhanhs.id', $branchId);
        }
        if ($rapId) {
            $queryVe->where('rap_phims.id', $rapId);
        }
        if ($tuNgay && $denNgay) {
            $queryVe->whereBetween('suat_chieus.bat_dau', [$tuNgay, $denNgay . ' 23:59:59']);
        }

        $tongVeBan = $queryVe->count();
        $tongDoanhThu = $queryVe->sum('dat_ves.tong_tien');

        // Tỷ lệ lấp đầy ghế trung bình
        $queryGhe = GheNgoi::join('phong_chieus', 'ghe_ngois.phong_chieu_id', '=', 'phong_chieus.id')
            ->join('rap_phims', 'phong_chieus.rap_phim_id', '=', 'rap_phims.id')
            ->join('chi_nhanhs', 'rap_phims.chi_nhanh_id', '=', 'chi_nhanhs.id')
            ->join('suat_chieus', 'phong_chieus.id', '=', 'suat_chieus.phong_chieu_id');

        if ($branchId) {
            $queryGhe->where('chi_nhanhs.id', $branchId);
        }
        if ($rapId) {
            $queryGhe->where('rap_phims.id', $rapId);
        }
        if ($tuNgay && $denNgay) {
            $queryGhe->whereBetween('suat_chieus.bat_dau', [$tuNgay, $denNgay . ' 23:59:59']);
        }

        $soGheDaDat = $queryGhe->where('ghe_ngois.trang_thai', 'da_dat')->count();
        $tongSoGhe = $queryGhe->count();
        $tyLeLapDayGhe = $tongSoGhe > 0 ? round(($soGheDaDat / $tongSoGhe) * 100) : 0;

        // Top 5 suất chiếu có doanh thu cao nhất
        $top5SuatChieu = DatVe::join('suat_chieus', 'dat_ves.suat_chieu_id', '=', 'suat_chieus.id')
            ->join('phong_chieus', 'suat_chieus.phong_chieu_id', '=', 'phong_chieus.id')
            ->join('rap_phims', 'phong_chieus.rap_phim_id', '=', 'rap_phims.id')
            ->join('chi_nhanhs', 'rap_phims.chi_nhanh_id', '=', 'chi_nhanhs.id')
            ->join('phims', 'suat_chieus.phim_id', '=', 'phims.id')
            ->select(
                'suat_chieus.id',
                'phims.ten_phim',
                'suat_chieus.bat_dau',
                'phong_chieus.ten_phong',
                'rap_phims.ten_rap',
                DB::raw('SUM(dat_ves.tong_tien) as tong_doanh_thu'),
                DB::raw('COUNT(dat_ves.id) as so_ve_ban')
            )
            ->groupBy('suat_chieus.id', 'phims.ten_phim', 'suat_chieus.bat_dau', 'phong_chieus.ten_phong', 'rap_phims.ten_rap')
            ->orderByDesc('tong_doanh_thu')
            ->limit(5);

        if ($branchId) {
            $top5SuatChieu->where('chi_nhanhs.id', $branchId);
        }
        if ($rapId) {
            $top5SuatChieu->where('rap_phims.id', $rapId);
        }
        if ($tuNgay && $denNgay) {
            $top5SuatChieu->whereBetween('suat_chieus.bat_dau', [$tuNgay, $denNgay . ' 23:59:59']);
        }

        $top5SuatChieu = $top5SuatChieu->get();

        // Top 5 phim có số suất chiếu nhiều nhất
        $top5PhimSuatChieu = SuatChieu::join('phims', 'suat_chieus.phim_id', '=', 'phims.id')
            ->join('phong_chieus', 'suat_chieus.phong_chieu_id', '=', 'phong_chieus.id')
            ->join('rap_phims', 'phong_chieus.rap_phim_id', '=', 'rap_phims.id')
            ->join('chi_nhanhs', 'rap_phims.chi_nhanh_id', '=', 'chi_nhanhs.id')
            ->select(
                'phims.id',
                'phims.ten_phim',
                DB::raw('COUNT(suat_chieus.id) as so_suat_chieu')
            )
            ->groupBy('phims.id', 'phims.ten_phim')
            ->orderByDesc('so_suat_chieu')
            ->limit(5);

        if ($branchId) {
            $top5PhimSuatChieu->where('chi_nhanhs.id', $branchId);
        }
        if ($rapId) {
            $top5PhimSuatChieu->where('rap_phims.id', $rapId);
        }
        if ($tuNgay && $denNgay) {
            $top5PhimSuatChieu->whereBetween('suat_chieus.bat_dau', [$tuNgay, $denNgay . ' 23:59:59']);
        }

        $top5PhimSuatChieu = $top5PhimSuatChieu->get();

        // Lấy danh sách chi nhánh và rạp
        $danhSachChiNhanh = ChiNhanh::all();
        $danhSachRap = $request->branch_id
            ? RapPhim::where('chi_nhanh_id', $request->branch_id)->get()
            : RapPhim::all();

        return view('admin.thong-ke.suat-chieu', compact(
            'tongSuatChieu',
            'tongVeBan',
            'tongDoanhThu',
            'tyLeLapDayGhe',
            'top5SuatChieu',
            'top5PhimSuatChieu',
            'danhSachChiNhanh',
            'danhSachRap',
            'tuNgay',
            'denNgay'
        ));
    }

    public function getDoanhThu(Request $request)
    {
        $loai = $request->input('loai', 'week');
        $branchId = $request->input('branch_id');
        $rapId = $request->input('rap_id');
        $tuNgay = $request->input('tu_ngay');
        $denNgay = $request->input('den_ngay');

        $query = DatVe::join('suat_chieus', 'dat_ves.suat_chieu_id', '=', 'suat_chieus.id')
            ->join('phong_chieus', 'suat_chieus.phong_chieu_id', '=', 'phong_chieus.id')
            ->join('rap_phims', 'phong_chieus.rap_phim_id', '=', 'rap_phims.id')
            ->join('chi_nhanhs', 'rap_phims.chi_nhanh_id', '=', 'chi_nhanhs.id');

        if ($branchId) {
            $query->where('chi_nhanhs.id', $branchId);
        }
        if ($rapId) {
            $query->where('rap_phims.id', $rapId);
        }
        if ($tuNgay && $denNgay) {
            $query->whereBetween('suat_chieus.bat_dau', [$tuNgay, $denNgay . ' 23:59:59']);
        }

        if ($loai === 'week') {
            $query->select(
                DB::raw('SUM(tong_tien) as doanh_thu'),
                DB::raw('COUNT(dat_ves.id) as so_ve'),
                DB::raw('YEARWEEK(suat_chieus.bat_dau, 1) as tuan')
            )
                ->groupBy('tuan')
                ->orderBy('tuan', 'desc')
                ->limit(4);
        } elseif ($loai === 'month') {
            $query->select(
                DB::raw('SUM(tong_tien) as doanh_thu'),
                DB::raw('COUNT(dat_ves.id) as so_ve'),
                DB::raw('DATE_FORMAT(suat_chieus.bat_dau, "%Y-%m") as thang')
            )
                ->groupBy('thang')
                ->orderBy('thang', 'desc')
                ->limit(12);
        } else {
            $query->select(
                DB::raw('SUM(tong_tien) as doanh_thu'),
                DB::raw('COUNT(dat_ves.id) as so_ve'),
                DB::raw('DATE(suat_chieus.bat_dau) as ngay')
            )
                ->groupBy('ngay')
                ->orderBy('ngay', 'desc');
        }

        $data = $query->get();

        if ($data->isEmpty()) {
            return response()->json([
                'labels' => [],
                'values' => [],
                'so_ve' => [],
                'message' => 'Không có dữ liệu vé bán'
            ]);
        }

        $labels = [];
        $values = [];
        $soVe = [];

        if ($loai === 'week') {
            $data->each(function ($item) use (&$labels, &$values, &$soVe) {
                $labels[] = "Tuần {$item->tuan}";
                $values[] = $item->doanh_thu;
                $soVe[] = $item->so_ve;
            });
        } elseif ($loai === 'month') {
            $data->each(function ($item) use (&$labels, &$values, &$soVe) {
                $labels[] = "Tháng {$item->thang}";
                $values[] = $item->doanh_thu;
                $soVe[] = $item->so_ve;
            });
        } else {
            $data->each(function ($item) use (&$labels, &$values, &$soVe) {
                $labels[] = $item->ngay;
                $values[] = $item->doanh_thu;
                $soVe[] = $item->so_ve;
            });
        }

        return response()->json([
            'labels' => array_reverse($labels),
            'values' => array_reverse($values),
            'so_ve' => array_reverse($soVe)
        ]);
    }

    public function getTyLeDoanhThuPhim(Request $request)
    {
        $branchId = $request->input('branch_id');
        $rapId = $request->input('rap_id');
        $tuNgay = $request->input('tu_ngay');
        $denNgay = $request->input('den_ngay');

        $query = DatVe::join('suat_chieus', 'dat_ves.suat_chieu_id', '=', 'suat_chieus.id')
            ->join('phims', 'suat_chieus.phim_id', '=', 'phims.id')
            ->join('phong_chieus', 'suat_chieus.phong_chieu_id', '=', 'phong_chieus.id')
            ->join('rap_phims', 'phong_chieus.rap_phim_id', '=', 'rap_phims.id')
            ->join('chi_nhanhs', 'rap_phims.chi_nhanh_id', '=', 'chi_nhanhs.id')
            ->select(
                'phims.ten_phim',
                DB::raw('SUM(dat_ves.tong_tien) as tong_doanh_thu')
            )
            ->groupBy('phims.ten_phim')
            ->orderByDesc('tong_doanh_thu')
            ->limit(5);

        if ($branchId) {
            $query->where('chi_nhanhs.id', $branchId);
        }
        if ($rapId) {
            $query->where('rap_phims.id', $rapId);
        }
        if ($tuNgay && $denNgay) {
            $query->whereBetween('suat_chieus.bat_dau', [$tuNgay, $denNgay . ' 23:59:59']);
        }

        $data = $query->get();

        if ($data->isEmpty()) {
            return response()->json([
                'labels' => [],
                'values' => [],
                'message' => 'Không có dữ liệu doanh thu phim'
            ]);
        }

        $labels = $data->pluck('ten_phim')->toArray();
        $values = $data->pluck('tong_doanh_thu')->toArray();

        return response()->json([
            'labels' => $labels,
            'values' => $values
        ]);
    }

    public function getTyLeSuatChieu(Request $request)
    {
        $branchId = $request->input('branch_id');
        $rapId = $request->input('rap_id');
        $tuNgay = $request->input('tu_ngay');
        $denNgay = $request->input('den_ngay');

        $query = SuatChieu::join('phong_chieus', 'suat_chieus.phong_chieu_id', '=', 'phong_chieus.id')
            ->join('rap_phims', 'phong_chieus.rap_phim_id', '=', 'rap_phims.id')
            ->join('chi_nhanhs', 'rap_phims.chi_nhanh_id', '=', 'chi_nhanhs.id')
            ->select(
                DB::raw('CASE 
                    WHEN suat_chieus.bat_dau > NOW() THEN "Sắp chiếu"
                    WHEN suat_chieus.ket_thuc < NOW() THEN "Đã kết thúc"
                    ELSE "Đang chiếu"
                END as trang_thai'),
                DB::raw('COUNT(suat_chieus.id) as so_suat_chieu')
            )
            ->groupBy('trang_thai');

        if ($branchId) {
            $query->where('chi_nhanhs.id', $branchId);
        }
        if ($rapId) {
            $query->where('rap_phims.id', $rapId);
        }
        if ($tuNgay && $denNgay) {
            $query->whereBetween('suat_chieus.bat_dau', [$tuNgay, $denNgay . ' 23:59:59']);
        }

        $data = $query->get();

        if ($data->isEmpty()) {
            return response()->json([
                'labels' => [],
                'values' => [],
                'message' => 'Không có dữ liệu suất chiếu'
            ]);
        }

        $labels = $data->pluck('trang_thai')->toArray();
        $values = $data->pluck('so_suat_chieu')->toArray();

        return response()->json([
            'labels' => $labels,
            'values' => $values
        ]);
    }

    public function getTyLeLapDayGhe(Request $request)
    {
        $branchId = $request->input('branch_id');
        $rapId = $request->input('rap_id');
        $tuNgay = $request->input('tu_ngay');
        $denNgay = $request->input('den_ngay');

        $query = GheNgoi::join('phong_chieus', 'ghe_ngois.phong_chieu_id', '=', 'phong_chieus.id')
            ->join('rap_phims', 'phong_chieus.rap_phim_id', '=', 'rap_phims.id')
            ->join('chi_nhanhs', 'rap_phims.chi_nhanh_id', '=', 'chi_nhanhs.id')
            ->join('suat_chieus', 'phong_chieus.id', '=', 'suat_chieus.phong_chieu_id');

        if ($branchId) {
            $query->where('chi_nhanhs.id', $branchId);
        }
        if ($rapId) {
            $query->where('rap_phims.id', $rapId);
        }
        if ($tuNgay && $denNgay) {
            $query->whereBetween('suat_chieus.bat_dau', [$tuNgay, $denNgay . ' 23:59:59']);
        }

        $soGheDaDat = $query->where('ghe_ngois.trang_thai', 'da_dat')->count();
        $tongSoGhe = $query->count();
        $tyLeLapDayGhe = $tongSoGhe > 0 ? round(($soGheDaDat / $tongSoGhe) * 100) : 0;

        return response()->json([
            'tyLeLapDayGhe' => $tyLeLapDayGhe
        ]);
    }
}
