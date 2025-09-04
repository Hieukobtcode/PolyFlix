<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChiNhanh;
use App\Models\DatVe;
use App\Models\GheNgoi;
use App\Models\LienHe;
use App\Models\Phim;
use App\Models\PhongChieu;
use App\Models\RapPhim;
use App\Models\SuatChieu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ThongKeController extends Controller
{
    public function thongKeTongQuan(Request $request)
    {
        $user = Auth::user();
        $chiNhanhId = null;
        $rapId = null;

        // Kiểm tra vai trò và lấy chi_nhanh_id nếu là Admin Chi Nhánh (vai_tro_id = 2)
        if ($user->vaiTro && $user->vaiTro->id == 2) {
            $chiNhanhManaged = $user->chiNhanhDangQuanLy;
            if ($chiNhanhManaged) {
                $chiNhanhId = $chiNhanhManaged->id;
            }
        }

        // Kiểm tra vai trò và lấy rap_id nếu là Admin Rạp (vai_tro_id = 3)
        if ($user->vaiTro && $user->vaiTro->id == 3) {
            $rapManaged = $user->rapDangQuanLy;
            if ($rapManaged) {
                $rapId = $rapManaged->id;
                $chiNhanhId = $rapManaged->chi_nhanh_id; // Lấy chi nhánh của rạp
            }
        }

        // Lọc theo chi nhánh được chọn từ filter hoặc của quản lý
        $selectedChiNhanhId = $chiNhanhId ?? $request->branch_id;
        $selectedRapId = $rapId ?? $request->rap_id;

        // Query cơ bản cho chi nhánh
        $chiNhanhQuery = ChiNhanh::query();
        if ($selectedChiNhanhId) {
            $chiNhanhQuery->where('id', $selectedChiNhanhId);
        }

        // Query cơ sở cho DatVe để lọc theo chi nhánh và rạp
        $datVeQuery = DatVe::join('suat_chieus', 'dat_ves.suat_chieu_id', '=', 'suat_chieus.id')
            ->join('phong_chieus', 'suat_chieus.phong_chieu_id', '=', 'phong_chieus.id')
            ->join('rap_phims', 'phong_chieus.rap_phim_id', '=', 'rap_phims.id');

        // Lọc theo chi nhánh nếu có
        if ($selectedChiNhanhId) {
            $datVeQuery->where('rap_phims.chi_nhanh_id', $selectedChiNhanhId);
        } elseif ($chiNhanhId) {
            $datVeQuery->where('rap_phims.chi_nhanh_id', $chiNhanhId);
        }

        // Lọc theo rạp nếu có (cho Admin Rạp)
        if ($selectedRapId) {
            $datVeQuery->where('rap_phims.id', $selectedRapId);
        } elseif ($rapId) {
            $datVeQuery->where('rap_phims.id', $rapId);
        }

        // Đếm số lượng - phân biệt admin tổng, admin chi nhánh và admin rạp
        if ($rapId) {
            // Admin Rạp: thống kê trong phạm vi rạp
            $soVeDaBan = $datVeQuery->clone()->count();
            $soRaps = 1; // Admin rạp chỉ quản lý 1 rạp
            $soPhongChieus = PhongChieu::where('rap_phim_id', $rapId)->count();

            // Thêm số suất chiếu cho Admin Rạp
            $soSuatChieus = SuatChieu::join('phong_chieus', 'suat_chieus.phong_chieu_id', '=', 'phong_chieus.id')
                ->where('phong_chieus.rap_phim_id', $rapId)
                ->count();
        } elseif ($chiNhanhId) {
            // Admin Chi Nhánh: thống kê trong phạm vi chi nhánh
            $soVeDaBan = $datVeQuery->clone()->count();
            $soRaps = RapPhim::where('chi_nhanh_id', $chiNhanhId)->count();
            $soPhongChieus = PhongChieu::whereIn('rap_phim_id', RapPhim::where('chi_nhanh_id', $chiNhanhId)->pluck('id'))->count();
        } else {
            // Admin Tổng: thống kê toàn hệ thống
            $soChiNhanhs = ChiNhanh::count();
            $soRaps = RapPhim::count();
            $soPhongChieus = PhongChieu::count();
        }

        // Tính tổng doanh thu
        $tongDoanhThuHeThong = $datVeQuery->clone()->sum('dat_ves.tong_tien');

        // Lấy top 5 - phân biệt theo vai trò
        if ($rapId) {
            // Admin Rạp: Top 5 phim có doanh thu cao nhất trong rạp
            $top5Query = DatVe::join('suat_chieus', 'dat_ves.suat_chieu_id', '=', 'suat_chieus.id')
                ->join('phims', 'suat_chieus.phim_id', '=', 'phims.id')
                ->join('phong_chieus', 'suat_chieus.phong_chieu_id', '=', 'phong_chieus.id')
                ->join('rap_phims', 'phong_chieus.rap_phim_id', '=', 'rap_phims.id')
                ->where('rap_phims.id', $rapId)
                ->select(
                    'phims.id',
                    'phims.ten_phim as ten_item',
                    DB::raw('SUM(dat_ves.tong_tien) as tong_doanh_thu')
                )
                ->groupBy('phims.id', 'phims.ten_phim')
                ->orderByDesc('tong_doanh_thu')
                ->limit(5)
                ->get();
        } else {
            // Admin Chi Nhánh & Admin Tổng: Top 5 rạp có doanh thu cao nhất
            $top5Query = DatVe::join('suat_chieus', 'dat_ves.suat_chieu_id', '=', 'suat_chieus.id')
                ->join('phong_chieus', 'suat_chieus.phong_chieu_id', '=', 'phong_chieus.id')
                ->join('rap_phims', 'phong_chieus.rap_phim_id', '=', 'rap_phims.id');

            if ($selectedChiNhanhId) {
                $top5Query->where('rap_phims.chi_nhanh_id', $selectedChiNhanhId);
            } elseif ($chiNhanhId) {
                $top5Query->where('rap_phims.chi_nhanh_id', $chiNhanhId);
            }

            $top5Query = $top5Query->select(
                'rap_phims.id',
                'rap_phims.ten_rap as ten_item',
                DB::raw('SUM(dat_ves.tong_tien) as tong_doanh_thu')
            )
                ->groupBy('rap_phims.id', 'rap_phims.ten_rap')
                ->orderByDesc('tong_doanh_thu')
                ->limit(5)
                ->get();
        }

        $top5Query->transform(function ($item) use ($tongDoanhThuHeThong) {
            $item->phan_tram = $tongDoanhThuHeThong > 0
                ? round(($item->tong_doanh_thu / $tongDoanhThuHeThong) * 100, 2)
                : 0;
            return $item;
        });        // Lấy top 5 phim có doanh thu cao nhất
        $topDoanhThuPhimHeThong = $datVeQuery->clone() // Sử dụng lại query đã lọc
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

        // Tính tỷ lệ lấp đầy ghế (lọc theo chi nhánh)
        $queryGhe = GheNgoi::join('phong_chieus', 'ghe_ngois.phong_chieu_id', '=', 'phong_chieus.id')
            ->join('rap_phims', 'phong_chieus.rap_phim_id', '=', 'rap_phims.id');

        if ($selectedChiNhanhId) {
            $queryGhe->where('rap_phims.chi_nhanh_id', $selectedChiNhanhId);
        } elseif ($chiNhanhId) {
            $queryGhe->where('rap_phims.chi_nhanh_id', $chiNhanhId);
        }
        if ($request->rap_id) {
            $queryGhe->where('rap_phims.id', $request->rap_id);
        }

        $soGheDaDat = $queryGhe->clone()->where('ghe_ngois.trang_thai', 'da_dat')->count();
        $tongSoGhe = $queryGhe->clone()->count();
        $tyLeLapDayGhe = $tongSoGhe > 0 ? round(($soGheDaDat / $tongSoGhe) * 100) : 0;

        // Lấy danh sách chi nhánh và rạp cho bộ lọc  
        if ($rapId) {
            // Admin Rạp chỉ thấy rạp của mình
            $danhSachChiNhanh = ChiNhanh::where('id', $chiNhanhId)->get();
            $danhSachRap = RapPhim::where('id', $rapId)->get();
        } elseif ($chiNhanhId) {
            // Admin Chi Nhánh chỉ thấy chi nhánh và rạp của mình
            $danhSachChiNhanh = ChiNhanh::where('id', $chiNhanhId)->get();
            $danhSachRap = RapPhim::where('chi_nhanh_id', $chiNhanhId)->get();
        } else {
            // Admin Tổng thấy tất cả
            $danhSachChiNhanh = ChiNhanh::all();
            $danhSachRap = $selectedChiNhanhId ? RapPhim::where('chi_nhanh_id', $selectedChiNhanhId)->get() : RapPhim::all();
        }

        $compactData = [
            'soPhongChieus',
            'soRaps',
            'top5Query',
            'topDoanhThuPhimHeThong',
            'tyLeLapDayGhe',
            'danhSachChiNhanh',
            'danhSachRap',
            'rapId' // Thêm biến để view biết đang ở chế độ Admin Rạp
        ];

        // Thêm biến phù hợp theo vai trò
        if ($rapId) {
            // Admin Rạp: hiển thị số vé đã bán và số suất chiếu
            $compactData[] = 'soVeDaBan';
            $compactData[] = 'soSuatChieus';
        } elseif ($chiNhanhId) {
            // Admin Chi Nhánh: hiển thị số vé đã bán
            $compactData[] = 'soVeDaBan';
        } else {
            // Admin Tổng: hiển thị số chi nhánh
            $compactData[] = 'soChiNhanhs';
        }

        return view('admin.thong-ke.index', compact($compactData));
    }

    public function thongKeDoanhThu(Request $request)
    {
        $user = Auth::user();
        $chiNhanhId = null;
        $rapId = null;

        // Kiểm tra vai trò và lấy chi_nhanh_id nếu là Admin Chi Nhánh (vai_tro_id = 2)
        if ($user->vaiTro && $user->vaiTro->id == 2) {
            $chiNhanhManaged = $user->chiNhanhDangQuanLy;
            if ($chiNhanhManaged) {
                $chiNhanhId = $chiNhanhManaged->id;
            }
        }

        // Kiểm tra vai trò và lấy rap_id nếu là Admin Rạp (vai_tro_id = 3)
        if ($user->vaiTro && $user->vaiTro->id == 3) {
            $rapManaged = $user->rapDangQuanLy;
            if ($rapManaged) {
                $rapId = $rapManaged->id;
                $chiNhanhId = $rapManaged->chi_nhanh_id; // Lấy chi nhánh của rạp
            }
        }

        // Lấy tham số từ request, ưu tiên theo vai trò quản lý
        $branchId = $chiNhanhId ?? $request->input('branch_id');
        $selectedRapId = $rapId ?? $request->input('rap_id');
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
        if ($selectedRapId) {
            $query->where('rap_phims.id', $selectedRapId);
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
        if ($selectedRapId) {
            $top5Phim->where('rap_phims.id', $selectedRapId);
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

        // Lấy danh sách chi nhánh và rạp theo vai trò
        if ($rapId) {
            // Admin Rạp chỉ thấy rạp của mình
            $danhSachChiNhanh = ChiNhanh::where('id', $chiNhanhId)->get();
            $danhSachRap = RapPhim::where('id', $rapId)->get();
        } elseif ($chiNhanhId) {
            // Admin Chi Nhánh chỉ thấy chi nhánh và rạp của mình
            $danhSachChiNhanh = ChiNhanh::where('id', $chiNhanhId)->get();
            $danhSachRap = RapPhim::where('chi_nhanh_id', $chiNhanhId)->get();
        } else {
            // Admin Tổng thấy tất cả
            $danhSachChiNhanh = ChiNhanh::all();
            $danhSachRap = $branchId ? RapPhim::where('chi_nhanh_id', $branchId)->get() : RapPhim::all();
        }

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
        $user = Auth::user();
        $chiNhanhId = null;
        $rapId = null;

        // Kiểm tra vai trò và lấy chi_nhanh_id nếu là Admin Chi Nhánh (vai_tro_id = 2)
        if ($user->vaiTro && $user->vaiTro->id == 2) {
            $chiNhanhManaged = $user->chiNhanhDangQuanLy;
            if ($chiNhanhManaged) {
                $chiNhanhId = $chiNhanhManaged->id;
            }
        }

        // Kiểm tra vai trò và lấy rap_id nếu là Admin Rạp (vai_tro_id = 3)
        if ($user->vaiTro && $user->vaiTro->id == 3) {
            $rapManaged = $user->rapDangQuanLy;
            if ($rapManaged) {
                $rapId = $rapManaged->id;
                $chiNhanhId = $rapManaged->chi_nhanh_id; // Lấy chi nhánh của rạp
            }
        }

        // Lấy tham số từ request, ưu tiên theo vai trò quản lý
        $branchId = $chiNhanhId ?? $request->input('branch_id');
        $selectedRapId = $rapId ?? $request->input('rap_id');
        $tuNgay = $request->input('tu_ngay');
        $denNgay = $request->input('den_ngay');

        // Tổng số suất chiếu
        $querySuatChieu = SuatChieu::join('phong_chieus', 'suat_chieus.phong_chieu_id', '=', 'phong_chieus.id')
            ->join('rap_phims', 'phong_chieus.rap_phim_id', '=', 'rap_phims.id')
            ->join('chi_nhanhs', 'rap_phims.chi_nhanh_id', '=', 'chi_nhanhs.id');

        if ($branchId) {
            $querySuatChieu->where('chi_nhanhs.id', $branchId);
        }
        if ($selectedRapId) {
            $querySuatChieu->where('rap_phims.id', $selectedRapId);
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
        if ($selectedRapId) {
            $queryVe->where('rap_phims.id', $selectedRapId);
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
        if ($selectedRapId) {
            $top5SuatChieu->where('rap_phims.id', $selectedRapId);
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
        if ($selectedRapId) {
            $top5PhimSuatChieu->where('rap_phims.id', $selectedRapId);
        }
        if ($tuNgay && $denNgay) {
            $top5PhimSuatChieu->whereBetween('suat_chieus.bat_dau', [$tuNgay, $denNgay . ' 23:59:59']);
        }

        $top5PhimSuatChieu = $top5PhimSuatChieu->get();

        // Lấy danh sách chi nhánh và rạp theo vai trò
        if ($rapId) {
            // Admin Rạp chỉ thấy rạp của mình
            $danhSachChiNhanh = ChiNhanh::where('id', $chiNhanhId)->get();
            $danhSachRap = RapPhim::where('id', $rapId)->get();
        } elseif ($chiNhanhId) {
            // Admin Chi Nhánh chỉ thấy chi nhánh và rạp của mình
            $danhSachChiNhanh = ChiNhanh::where('id', $chiNhanhId)->get();
            $danhSachRap = RapPhim::where('chi_nhanh_id', $chiNhanhId)->get();
        } else {
            // Admin Tổng thấy tất cả
            $danhSachChiNhanh = ChiNhanh::all();
            $danhSachRap = $branchId ? RapPhim::where('chi_nhanh_id', $branchId)->get() : RapPhim::all();
        }

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
        $user = Auth::user();
        $chiNhanhId = null;

        if ($user->vaiTro && $user->vaiTro->id == 2) {
            $chiNhanhManaged = $user->chiNhanhDangQuanLy;
            if ($chiNhanhManaged) {
                $chiNhanhId = $chiNhanhManaged->id;
            }
        }

        $loai = $request->input('loai', 'week');
        $branchId = $chiNhanhId ?? $request->input('branch_id');
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
        $user = Auth::user();
        $chiNhanhId = null;

        if ($user->vaiTro && $user->vaiTro->id == 2) {
            $chiNhanhManaged = $user->chiNhanhDangQuanLy;
            if ($chiNhanhManaged) {
                $chiNhanhId = $chiNhanhManaged->id;
            }
        }

        $branchId = $chiNhanhId ?? $request->input('branch_id');
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
        $user = Auth::user();
        $chiNhanhId = null;

        if ($user->vaiTro && $user->vaiTro->id == 2) {
            $chiNhanhManaged = $user->chiNhanhDangQuanLy;
            if ($chiNhanhManaged) {
                $chiNhanhId = $chiNhanhManaged->id;
            }
        }

        $branchId = $chiNhanhId ?? $request->input('branch_id');
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
        $user = Auth::user();
        $chiNhanhId = null;

        if ($user->vaiTro && $user->vaiTro->id == 2) {
            $chiNhanhManaged = $user->chiNhanhDangQuanLy;
            if ($chiNhanhManaged) {
                $chiNhanhId = $chiNhanhManaged->id;
            }
        }

        $branchId = $chiNhanhId ?? $request->input('branch_id');
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

    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $chiNhanhId = null;

        // Kiểm tra vai trò và lấy chi_nhanh_id nếu là Admin Chi Nhánh (vai_tro_id = 2)
        if ($user->vaiTro && $user->vaiTro->id == 2) {
            $chiNhanhManaged = $user->chiNhanhDangQuanLy;
            if ($chiNhanhManaged) {
                $chiNhanhId = $chiNhanhManaged->id;
            }
        }

        // Lấy tham số từ request
        $tuNgay = $request->input('tu_ngay', now()->startOfMonth()->format('Y-m-d'));
        $denNgay = $request->input('den_ngay', now()->format('Y-m-d'));

        // Thống kê tổng quan
        $tongQuan = [];

        // Tổng chi nhánh (Admin Chi Nhánh chỉ thấy 1)
        $tongQuan['tong_chi_nhanh'] = $chiNhanhId ? 1 : ChiNhanh::count();

        // Tổng rạp (lọc theo chi nhánh nếu là Admin Chi Nhánh)
        if ($chiNhanhId) {
            $tongQuan['tong_rap'] = RapPhim::where('chi_nhanh_id', $chiNhanhId)->count();
        } else {
            $tongQuan['tong_rap'] = RapPhim::count();
        }

        // Tổng phim
        $tongQuan['tong_phim'] = Phim::count();
        $tongQuan['phim_dang_chieu'] = Phim::where('trang_thai', 'dang_chieu')->count();
        $tongQuan['phim_sap_chieu'] = Phim::where('trang_thai', 'sap_chieu')->count();

        // Thống kê liên hệ
        $tongQuan['tong_lien_he'] = \App\Models\LienHe::count();

        // Thống kê khuyến mãi  
        $tongQuan['tong_khuyen_mai'] = \App\Models\KhuyenMai::where('trang_thai', 'hoat_dong')->count();

        // Query cơ sở cho doanh thu (lọc theo chi nhánh nếu cần)
        $doanhThuQuery = DatVe::join('suat_chieus', 'dat_ves.suat_chieu_id', '=', 'suat_chieus.id')
            ->join('phong_chieus', 'suat_chieus.phong_chieu_id', '=', 'phong_chieus.id')
            ->join('rap_phims', 'phong_chieus.rap_phim_id', '=', 'rap_phims.id');

        if ($chiNhanhId) {
            $doanhThuQuery->where('rap_phims.chi_nhanh_id', $chiNhanhId);
        }

        if ($tuNgay && $denNgay) {
            $doanhThuQuery->whereBetween('suat_chieus.bat_dau', [$tuNgay, $denNgay . ' 23:59:59']);
        }

        // Doanh thu vé
        $tongQuan['doanh_thu_ve'] = $doanhThuQuery->clone()->sum('dat_ves.tong_tien');

        // Doanh thu combo - sử dụng bảng dat_ve_combo
        $comboQuery = DB::table('dat_ve_combo')
            ->join('dat_ves', 'dat_ve_combo.dat_ve_id', '=', 'dat_ves.id')
            ->join('combos', 'dat_ve_combo.combo_id', '=', 'combos.id')
            ->join('suat_chieus', 'dat_ves.suat_chieu_id', '=', 'suat_chieus.id')
            ->join('phong_chieus', 'suat_chieus.phong_chieu_id', '=', 'phong_chieus.id')
            ->join('rap_phims', 'phong_chieus.rap_phim_id', '=', 'rap_phims.id');

        if ($chiNhanhId) {
            $comboQuery->where('rap_phims.chi_nhanh_id', $chiNhanhId);
        }

        if ($tuNgay && $denNgay) {
            $comboQuery->whereBetween('suat_chieus.bat_dau', [$tuNgay, $denNgay . ' 23:59:59']);
        }

        $tongQuan['doanh_thu_combo'] = $comboQuery->selectRaw('SUM(combos.gia * dat_ve_combo.so_luong)')->value('SUM(combos.gia * dat_ve_combo.so_luong)') ?: 0;

        // Tổng doanh thu
        $tongQuan['tong_doanh_thu'] = $tongQuan['doanh_thu_ve'] + $tongQuan['doanh_thu_combo'];

        return view('admin.thong-ke.dashboard', compact('tongQuan', 'tuNgay', 'denNgay'));
    }

    public function ve(Request $request)
    {
        $user = Auth::user();

        // Không cho Admin Rạp truy cập trang Thống kê vé
        if ($user && $user->vai_tro_id == 3) {
            abort(403, 'Bạn không có quyền truy cập mục này.');
        }

        // Default bộ lọc
        $tuNgay = $request->input('tu_ngay');
        $denNgay = $request->input('den_ngay');
        $loaiThongKe = $request->input('loai_thong_ke', 'ngay'); // ngay | tuan | thang

        // Xác định giới hạn theo vai trò
        $chiNhanhId = null;
        $rapIdGioiHan = null;
        if ($user->vaiTro && $user->vaiTro->id == 2) { // Admin chi nhánh
            $managed = $user->chiNhanhDangQuanLy;
            if ($managed) {
                $chiNhanhId = $managed->id;
            }
        }
        if ($user->vaiTro && $user->vaiTro->id == 3) { // Admin rạp
            $rapManaged = $user->rapDangQuanLy;
            if ($rapManaged) {
                $rapIdGioiHan = $rapManaged->id;
                $chiNhanhId = $rapManaged->chi_nhanh_id; // Ngầm giới hạn theo chi nhánh của rạp
            }
        }

        // Nhận filter từ request nhưng tôn trọng giới hạn vai trò
        $chiNhanhId = $chiNhanhId ?? $request->input('chi_nhanh_id');
        $rapId = $rapIdGioiHan ?? $request->input('rap_id');

        // Query cơ sở cho thống kê vé
        $base = DatVe::join('suat_chieus', 'dat_ves.suat_chieu_id', '=', 'suat_chieus.id')
            ->join('phong_chieus', 'suat_chieus.phong_chieu_id', '=', 'phong_chieus.id')
            ->join('rap_phims', 'phong_chieus.rap_phim_id', '=', 'rap_phims.id')
            ->join('chi_nhanhs', 'rap_phims.chi_nhanh_id', '=', 'chi_nhanhs.id')
            ->join('phims', 'suat_chieus.phim_id', '=', 'phims.id');

        if ($chiNhanhId) {
            $base->where('chi_nhanhs.id', $chiNhanhId);
        }
        if ($rapId) {
            $base->where('rap_phims.id', $rapId);
        }
        if ($tuNgay && $denNgay) {
            $base->whereBetween('suat_chieus.bat_dau', [$tuNgay, $denNgay . ' 23:59:59']);
        }

        // Thống kê tổng quan
        $tongVeBan = (clone $base)->count();
        $soSuatChieu = (clone $base)->distinct('suat_chieus.id')->count('suat_chieus.id');
        // Không rõ tổng vé có thể bán theo schema, tạm thời dùng bằng tổng đã bán để tránh chia 0
        $tongVeCoTheBan = max($tongVeBan, $tongVeBan);
        $tyLeBanVe = $tongVeCoTheBan > 0 ? round($tongVeBan * 100 / $tongVeCoTheBan, 2) : 0;

        $veTongQuan = [
            'tong_ve_ban' => $tongVeBan,
            'tong_ve_co_the_ban' => $tongVeCoTheBan,
            'ty_le_ban_ve' => $tyLeBanVe,
            'so_suat_chieu' => $soSuatChieu,
        ];

        // Vé theo thời gian
        $format = match ($loaiThongKe) {
            'tuan' => '%x-%v', // ISO year-week
            'thang' => '%Y-%m',
            default => '%Y-%m-%d',
        };
        $veTheoThoiGianRows = (clone $base)
            ->selectRaw("DATE_FORMAT(suat_chieus.bat_dau, '{$format}') as label, COUNT(*) as so_ve_ban")
            ->groupBy('label')
            ->orderBy('label')
            ->get();
        $veTheoThoiGian = $veTheoThoiGianRows->map(fn($r) => [
            'label' => $r->label,
            'so_ve_ban' => (int) $r->so_ve_ban,
        ])->values();

        // Vé theo chi nhánh (chỉ meaningful với admin tổng)
        $veTheoChiNhanhRows = (clone $base)
            ->selectRaw('chi_nhanhs.id as chi_nhanh_id, chi_nhanhs.ten_chi_nhanh, COUNT(*) as so_ve_ban, COUNT(DISTINCT suat_chieus.id) as so_suat_chieu')
            ->groupBy('chi_nhanhs.id', 'chi_nhanhs.ten_chi_nhanh')
            ->orderBy('so_ve_ban', 'desc')
            ->get();
        $veTheoChiNhanh = $veTheoChiNhanhRows->map(fn($r) => [
            'chi_nhanh_id' => (int) $r->chi_nhanh_id,
            'ten_chi_nhanh' => $r->ten_chi_nhanh,
            'so_ve_ban' => (int) $r->so_ve_ban,
            'so_suat_chieu' => (int) $r->so_suat_chieu,
        ])->values();

        // Vé theo phim (top 10)
        $veTheoPhimRows = (clone $base)
            ->selectRaw('phims.id as phim_id, phims.ten_phim, COUNT(*) as so_ve_ban, COUNT(DISTINCT suat_chieus.id) as so_suat_chieu')
            ->groupBy('phims.id', 'phims.ten_phim')
            ->orderBy('so_ve_ban', 'desc')
            ->limit(10)
            ->get();
        $veTheoPhim = $veTheoPhimRows->map(fn($r) => [
            'phim_id' => (int) $r->phim_id,
            'ten_phim' => $r->ten_phim,
            'so_ve_ban' => (int) $r->so_ve_ban,
            'so_suat_chieu' => (int) $r->so_suat_chieu,
        ])->values();

        // Danh sách chọn lọc
        if ($user->vaiTro && $user->vaiTro->id == 1) { // Admin tổng
            $chiNhanhs = ChiNhanh::orderBy('ten_chi_nhanh')->get();
        } elseif ($chiNhanhId) { // Admin chi nhánh hoặc rạp
            $chiNhanhs = ChiNhanh::where('id', $chiNhanhId)->get();
        } else {
            $chiNhanhs = ChiNhanh::orderBy('ten_chi_nhanh')->get();
        }

        return view('admin.thong-ke.ve', [
            'veTongQuan' => $veTongQuan,
            'veTheoThoiGian' => $veTheoThoiGian,
            'veTheoChiNhanh' => $veTheoChiNhanh,
            'veTheoPhim' => $veTheoPhim,
            'chiNhanhs' => $chiNhanhs,
            'chiNhanhId' => $chiNhanhId,
            'tuNgay' => $tuNgay,
            'denNgay' => $denNgay,
            'loaiThongKe' => $loaiThongKe,
        ]);
    }

    public function doAnCombo(Request $request)
    {
        $user = Auth::user();
        $chiNhanhId = null;

        // Kiểm tra vai trò và lấy chi_nhanh_id nếu là Admin Chi Nhánh (vai_tro_id = 2)
        if ($user->vaiTro && $user->vaiTro->id == 2) {
            $chiNhanhManaged = $user->chiNhanhDangQuanLy;
            if ($chiNhanhManaged) {
                $chiNhanhId = $chiNhanhManaged->id;
            }
        }

        // Lấy tham số từ request, ưu tiên chi nhánh của quản lý
        $branchId = $chiNhanhId ?? $request->input('branch_id');
        $rapId = $request->input('rap_id');
        $tuNgay = $request->input('tu_ngay');
        $denNgay = $request->input('den_ngay');

        // Query cơ sở cho thống kê combo - sử dụng bảng dat_ve_combo
        $comboQuery = DB::table('dat_ve_combo')
            ->join('dat_ves', 'dat_ve_combo.dat_ve_id', '=', 'dat_ves.id')
            ->join('combos', 'dat_ve_combo.combo_id', '=', 'combos.id')
            ->join('suat_chieus', 'dat_ves.suat_chieu_id', '=', 'suat_chieus.id')
            ->join('phong_chieus', 'suat_chieus.phong_chieu_id', '=', 'phong_chieus.id')
            ->join('rap_phims', 'phong_chieus.rap_phim_id', '=', 'rap_phims.id')
            ->join('chi_nhanhs', 'rap_phims.chi_nhanh_id', '=', 'chi_nhanhs.id');

        if ($branchId) {
            $comboQuery->where('chi_nhanhs.id', $branchId);
        }
        if ($rapId) {
            $comboQuery->where('rap_phims.id', $rapId);
        }
        if ($tuNgay && $denNgay) {
            $comboQuery->whereBetween('suat_chieus.bat_dau', [$tuNgay, $denNgay . ' 23:59:59']);
        }

        $doanhThuCombo = $comboQuery->selectRaw('SUM(combos.gia * dat_ve_combo.so_luong)')->value('SUM(combos.gia * dat_ve_combo.so_luong)') ?: 0;

        $doAnComboTongQuan = [
            'tong_doanh_thu' => $doanhThuCombo,
            'doanh_thu_combo' => $doanhThuCombo,
        ];

        // Lấy danh sách chi nhánh và rạp cho bộ lọc
        $danhSachChiNhanh = $chiNhanhId ? ChiNhanh::where('id', $chiNhanhId)->get() : ChiNhanh::all();
        $danhSachRap = $branchId
            ? RapPhim::where('chi_nhanh_id', $branchId)->get()
            : ($chiNhanhId ? RapPhim::where('chi_nhanh_id', $chiNhanhId)->get() : RapPhim::all());

        return view('admin.thong-ke.do-an-combo', compact(
            'doAnComboTongQuan',
            'danhSachChiNhanh',
            'danhSachRap',
            'tuNgay',
            'denNgay'
        ));
    }

    public function thongKePhim(Request $request)
    {
        $user = Auth::user();
        $chiNhanhId = null;

        // Kiểm tra vai trò và lấy chi_nhanh_id nếu là Admin Chi Nhánh (vai_tro_id = 2)
        if ($user->vaiTro && $user->vaiTro->id == 2) {
            $chiNhanhManaged = $user->chiNhanhDangQuanLy;
            if ($chiNhanhManaged) {
                $chiNhanhId = $chiNhanhManaged->id;
            }
        }

        // Lấy tham số từ request, ưu tiên chi nhánh của quản lý
        $branchId = $chiNhanhId ?? $request->input('branch_id');
        $rapId = $request->input('rap_id');
        $tuNgay = $request->input('tu_ngay');
        $denNgay = $request->input('den_ngay');

        // Query để lấy thống kê phim theo chi nhánh
        $phimQuery = DatVe::join('suat_chieus', 'dat_ves.suat_chieu_id', '=', 'suat_chieus.id')
            ->join('phims', 'suat_chieus.phim_id', '=', 'phims.id')
            ->join('phong_chieus', 'suat_chieus.phong_chieu_id', '=', 'phong_chieus.id')
            ->join('rap_phims', 'phong_chieus.rap_phim_id', '=', 'rap_phims.id')
            ->join('chi_nhanhs', 'rap_phims.chi_nhanh_id', '=', 'chi_nhanhs.id');

        if ($branchId) {
            $phimQuery->where('chi_nhanhs.id', $branchId);
        }
        if ($rapId) {
            $phimQuery->where('rap_phims.id', $rapId);
        }
        if ($tuNgay && $denNgay) {
            $phimQuery->whereBetween('suat_chieus.bat_dau', [$tuNgay, $denNgay . ' 23:59:59']);
        }

        $topPhim = $phimQuery->clone()
            ->select(
                'phims.id',
                'phims.ten_phim',
                DB::raw('SUM(dat_ves.tong_tien) as tong_doanh_thu'),
                DB::raw('COUNT(dat_ves.id) as so_ve_ban')
            )
            ->groupBy('phims.id', 'phims.ten_phim')
            ->orderByDesc('tong_doanh_thu')
            ->limit(10)
            ->get();

        // Lấy danh sách chi nhánh và rạp cho bộ lọc
        $danhSachChiNhanh = $chiNhanhId ? ChiNhanh::where('id', $chiNhanhId)->get() : ChiNhanh::all();
        $danhSachRap = $branchId
            ? RapPhim::where('chi_nhanh_id', $branchId)->get()
            : ($chiNhanhId ? RapPhim::where('chi_nhanh_id', $chiNhanhId)->get() : RapPhim::all());

        return view('admin.thong-ke.phim', compact(
            'topPhim',
            'danhSachChiNhanh',
            'danhSachRap',
            'tuNgay',
            'denNgay'
        ));
    }

    public function lienHe(Request $request)
    {
        // Thống kê liên hệ không cần lọc theo chi nhánh vì liên hệ là chung cho toàn hệ thống
        $thongKeTheoTrangThai = [
            'chua_xu_ly' => \App\Models\LienHe::where('trang_thai', false)->count(),
            'da_xu_ly' => \App\Models\LienHe::where('trang_thai', true)->count(),
        ];

        return view('admin.thong-ke.lien-he', compact('thongKeTheoTrangThai'));
    }

    public function xuatBaoCao(Request $request)
    {
        $user = Auth::user();
        $chiNhanhId = null;

        // Kiểm tra vai trò và lấy chi_nhanh_id nếu là Admin Chi Nhánh (vai_tro_id = 2)
        if ($user->vaiTro && $user->vaiTro->id == 2) {
            $chiNhanhManaged = $user->chiNhanhDangQuanLy;
            if ($chiNhanhManaged) {
                $chiNhanhId = $chiNhanhManaged->id;
            }
        }

        $tuNgay = $request->input('tu_ngay', now()->startOfMonth()->format('Y-m-d'));
        $denNgay = $request->input('den_ngay', now()->format('Y-m-d'));

        // Lấy dữ liệu báo cáo chỉ cho chi nhánh của Admin Chi Nhánh
        $baoCao = [
            'tu_ngay' => $tuNgay,
            'den_ngay' => $denNgay,
            'chi_nhanh_id' => $chiNhanhId,
            'ten_chi_nhanh' => $chiNhanhId ? ChiNhanh::find($chiNhanhId)->ten_chi_nhanh : 'Toàn hệ thống'
        ];

        return view('admin.thong-ke.xuat-bao-cao', compact('baoCao'));
    }
}
