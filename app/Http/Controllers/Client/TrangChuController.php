<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\BaiViet;
use App\Models\Banner;
use App\Models\Comment;
use App\Models\Phim;
use App\Models\RapPhim;
use App\Models\Rating;
use App\Models\SuatChieu;
use Illuminate\Http\Request;
use Hashids\Hashids;
use Carbon\Carbon;
use App\Helpers\IdFormatter;


class TrangChuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::where('trang_thai', 1)->orderBy('id', 'desc')->get();

        $phims = Phim::whereHas('comments')
            ->with(['comments.user'])
            ->latest()
            ->take(3)
            ->get();

        $phims->each(function ($phim) {
            $latestComments = $phim->comments->sortByDesc('created_at')->take(3);
            $phim->setRelation('comments', $latestComments);
        });

        $ratings = Rating::all();

        $baiViet = BaiViet::where('status', '!=', 'draft')
            ->orderBy('ngay_tao', 'desc')
            ->limit(4)
            ->get();

        $rapPhims = RapPhim::all()->groupBy('chi_nhanh_id');

        return view('client.trang-chu', compact('phims', 'ratings', 'baiViet', 'banners', 'rapPhims'));
    }

    public function showRap($uuid, Request $request)
    {
        $id = IdFormatter::deuuidify($uuid);
        if (!$id) {
            abort(404, 'Mã không hợp lệ');
        }

        // Lấy dữ liệu rạp phim
        $rap = RapPhim::findOrFail($id);
        $rapPhims = RapPhim::all()->groupBy('chi_nhanh_id');

        // Lấy phim đang chiếu tại rạp này
        $phimDangChieu = Phim::where('trang_thai', 'đang chiếu')
            ->whereHas('rapPhims', function ($query) use ($rap) {
                $query->where('rap_phim_id', $rap->id);
            })
            ->get();

        // Lấy phim sắp chiếu tại rạp này
        $phimSapChieu = Phim::where('trang_thai', 'sắp chiếu')
            ->whereHas('rapPhims', function ($query) use ($rap) {
                $query->where('rap_phim_id', $rap->id);
            })
            ->get();

        $today = Carbon::today();
        $threeDaysLater = $today->copy()->addDays(3);

        // Lấy suất chiếu cho các phim đang chiếu trong 3 ngày tới
        $suatChieuTheoPhim = SuatChieu::whereIn('phim_id', $phimDangChieu->pluck('id'))
            ->whereBetween('ngay_chieu', [$today, $threeDaysLater])
            ->whereHas('phongChieu', function ($query) use ($rap) {
                $query->where('rap_phim_id', $rap->id);
            })
            ->with(['phongChieu.loaiPhong'])
            ->orderBy('bat_dau')
            ->get()
            ->groupBy('phim_id');

        // ✅ Tìm các phim có suất chiếu đặc biệt (trước ngày phát hành)
        $phimCoSuatDacBiet = collect();

        foreach ($phimSapChieu as $phim) {
            // Truy vấn suất chiếu thủ công để đảm bảo đúng rạp & ngày chiếu
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
                // Gán dữ liệu vào quan hệ để view có thể dùng $item->suatChieus
                $phim->setRelation('suatChieus', $suatDacBiet);
                $phimCoSuatDacBiet->push($phim);
            }
        }

        return view('client.show-rap', compact(
            'rap',
            'rapPhims',
            'phimDangChieu',
            'phimSapChieu',
            'suatChieuTheoPhim',
            'phimCoSuatDacBiet'
        ));
    }
}