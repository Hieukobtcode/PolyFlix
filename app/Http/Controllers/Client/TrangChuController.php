<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\BaiViet;
use App\Models\Banner;
use App\Models\Comment;
use App\Models\Phim;
use App\Models\RapPhim;
use App\Models\Rating;
use App\Models\ChiNhanh;
use App\Models\SuatChieu;
use Illuminate\Http\Request;
use Hashids\Hashids;
use Carbon\Carbon;
use App\Helpers\IdFormatter;

class TrangChuController extends Controller
{
    /**
     * Trang chủ
     */
    public function index(Request $request)
    {
        $banners = Banner::where('trang_thai', 1)->orderBy('id', 'desc')->get();
        $ratings = Rating::all();

        $baiViet = BaiViet::where('status', '!=', 'draft')
            ->orderBy('ngay_tao', 'desc')
            ->limit(4)
            ->get();

        $phims = Phim::whereHas('comments')
            ->with(['comments.user'])
            ->latest()
            ->take(3)
            ->get();

        $phims->each(function ($phim) {
            $latestComments = $phim->comments->sortByDesc('created_at')->take(3);
            $phim->setRelation('comments', $latestComments);
        });

        // Xác định tab phim đang chọn
        $tab = $request->get('tab', 'dang-chieu');

        if ($tab === 'sap-chieu') {
            $allPhims = Phim::where('ngay_phat_hanh', '>', now())
                ->orderBy('ngay_phat_hanh', 'asc')
                ->take(8)
                ->get();
        } else {
            $allPhims = Phim::where('ngay_phat_hanh', '<=', now())
                ->where('ngay_ket_thuc', '>=', now())
                ->orderBy('ngay_phat_hanh', 'desc')
                ->take(8)
                ->get();
        }

        return view('client.trang-chu', compact('phims', 'ratings', 'baiViet', 'banners', 'allPhims', 'tab'));
    }

    public function getChiNhanhs()
    {
        $chiNhanhs = ChiNhanh::where('trang_thai', 1)
            ->select('id', 'ten_chi_nhanh')
            ->orderBy('ten_chi_nhanh')
            ->get();

        return response()->json($chiNhanhs);
    }

    public function getPhimsByChiNhanh(Request $request)
    {
        $chiNhanhId = $request->input('chi_nhanh_id');

        $phims = Phim::whereHas('chiNhanhs', function ($query) use ($chiNhanhId) {
            $query->where('chi_nhanh_id', $chiNhanhId);
        })
            ->where('trang_thai', 1)
            ->where('ngay_ket_thuc', '>=', Carbon::now())
            ->select('id', 'ten_phim', 'poster')
            ->orderBy('ten_phim')
            ->get();

        return response()->json($phims);
    }

    public function getNgayChieuByPhim(Request $request)
    {
        $phimId = $request->input('phim_id');
        $chiNhanhId = $request->input('chi_nhanh_id');

        $ngayChieus = SuatChieu::join('phong_chieus', 'suat_chieus.phong_chieu_id', '=', 'phong_chieus.id')
            ->join('rap_phims', 'phong_chieus.rap_phim_id', '=', 'rap_phims.id')
            ->where('suat_chieus.phim_id', $phimId)
            ->where('rap_phims.chi_nhanh_id', $chiNhanhId)
            ->where('suat_chieus.ngay_chieu', '>=', Carbon::today())
            ->where('suat_chieus.trang_thai', 'hoat_dong')
            ->select('suat_chieus.ngay_chieu')
            ->distinct()
            ->orderBy('suat_chieus.ngay_chieu')
            ->get()
            ->map(function ($item) {
                return [
                    'ngay_chieu' => $item->ngay_chieu,
                    'ngay_hien_thi' => Carbon::parse($item->ngay_chieu)->locale('vi')->isoFormat('dddd - DD/MM')
                ];
            });

        return response()->json($ngayChieus);
    }

    public function getSuatChieuByNgay(Request $request)
    {
        $phimId = $request->input('phim_id');
        $chiNhanhId = $request->input('chi_nhanh_id');
        $ngayChieu = $request->input('ngay_chieu');

        $suatChieus = SuatChieu::with(['phongChieu.rapPhim', 'phongChieu.loaiPhong'])
            ->join('phong_chieus', 'suat_chieus.phong_chieu_id', '=', 'phong_chieus.id')
            ->join('rap_phims', 'phong_chieus.rap_phim_id', '=', 'rap_phims.id')
            ->where('suat_chieus.phim_id', $phimId)
            ->where('rap_phims.chi_nhanh_id', $chiNhanhId)
            ->where('suat_chieus.ngay_chieu', $ngayChieu)
            ->where('suat_chieus.trang_thai', 'hoat_dong')
            ->select('suat_chieus.*')
            ->orderBy('suat_chieus.bat_dau')
            ->get()
            ->map(function ($suatChieu) {
                return [
                    'id' => $suatChieu->id,
                    'bat_dau' => Carbon::parse($suatChieu->bat_dau)->format('H:i'),
                    'ket_thuc' => Carbon::parse($suatChieu->ket_thuc)->format('H:i'),
                    'phien_ban_phim' => $suatChieu->phien_ban_phim,
                    'phong_chieu' => $suatChieu->phongChieu->ten_phong,
                    'loai_phong' => $suatChieu->phongChieu->loaiPhong->ten_loai_phong ?? 'Standard',
                    'hien_thi' => Carbon::parse($suatChieu->bat_dau)->format('H:i') . ' - ' . $suatChieu->phien_ban_phim
                ];
            });

        return response()->json($suatChieus);
    }

    public function loadPhimTab(Request $request)
    {
        $tab = $request->get('tab', 'dang-chieu');

        if ($tab === 'sap-chieu') {
            $allPhims = Phim::where('ngay_phat_hanh', '>', now())
                ->orderBy('ngay_phat_hanh', 'asc')
                ->take(8)
                ->get();
        } else {
            $allPhims = Phim::where('ngay_phat_hanh', '<=', now())
                ->where('ngay_ket_thuc', '>=', now())
                ->orderBy('ngay_phat_hanh', 'desc')
                ->take(8)
                ->get();
        }

        return response()->json([
            'phims' => $allPhims
        ]);
    }

    public function showRap($uuid, Request $request)
    {
        $id = IdFormatter::deuuidify($uuid);
        if (!$id) {
            abort(404, 'Mã không hợp lệ');
        }

        $rap = RapPhim::findOrFail($id);

        $phimDangChieu = Phim::where('trang_thai', 'đang chiếu')
            ->whereHas('rapPhims', function ($query) use ($rap) {
                $query->where('rap_phim_id', $rap->id);
            })
            ->get();

        $phimSapChieu = Phim::where('trang_thai', 'sắp chiếu')
            ->whereHas('rapPhims', function ($query) use ($rap) {
                $query->where('rap_phim_id', $rap->id);
            })
            ->get();

        $today = Carbon::today();
        $threeDaysLater = $today->copy()->addDays(3);

        $suatChieuTheoPhim = SuatChieu::whereIn('phim_id', $phimDangChieu->pluck('id'))
            ->whereBetween('ngay_chieu', [$today, $threeDaysLater])
            ->whereHas('phongChieu', function ($query) use ($rap) {
                $query->where('rap_phim_id', $rap->id);
            })
            ->with(['phongChieu.loaiPhong'])
            ->orderBy('bat_dau')
            ->get()
            ->groupBy('phim_id');

        $phimCoSuatDacBiet = collect();

        foreach ($phimSapChieu as $phim) {
            $suatDacBiet = SuatChieu::where('phim_id', $phim->id)
                ->whereHas('phongChieu', function ($query) use ($rap) {
                    $query->where('rap_phim_id', $rap->id);
                })
                ->with(['phongChieu.loaiPhong'])
                ->get()
                ->filter(function ($suat) use ($phim) {
                    return $phim->ngay_phat_hanh &&
                        Carbon::parse($suat->ngay_chieu)->lt(Carbon::parse($phim->ngay_phat_hanh));
                });

            if ($suatDacBiet->isNotEmpty()) {
                $phim->setRelation('suatChieus', $suatDacBiet);
                $phimCoSuatDacBiet->push($phim);
            }
        }

        return view('client.show-rap', compact(
            'rap',
            'phimDangChieu',
            'phimSapChieu',
            'suatChieuTheoPhim',
            'phimCoSuatDacBiet'
        ));
    }
}