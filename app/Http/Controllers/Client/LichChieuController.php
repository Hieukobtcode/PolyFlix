<?php

namespace App\Http\Controllers\Client;

use App\Models\Phim;
use App\Models\RapPhim;
use App\Models\SuatChieu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;


class LichChieuController extends Controller
{
    //
    public function index()
    {
        $today = Carbon::today();

        // Lấy danh sách phim có suất chiếu từ hôm nay trở đi
        $phims = Phim::whereHas('suatChieus', function ($query) use ($today) {
            $query->where('ngay_bat_dau', '>=', $today)
                ->where('trang_thai', 'hoat_dong');
        })->orderBy('ten_phim')->get();

        // Lấy danh sách rạp có suất chiếu từ hôm nay trở đi
        $raps = RapPhim::whereHas('phongChieus.suatChieus', function ($query) use ($today) {
            $query->where('ngay_bat_dau', '>=', $today)
                ->where('trang_thai', 'hoat_dong');
        })->orderBy('ten_rap')->get();

        // Lấy danh sách ngày từ hôm nay trở đi
        $ngayChieus = SuatChieu::select('ngay_bat_dau')
            ->where('ngay_bat_dau', '>=', $today)
            ->where('trang_thai', 'hoat_dong')
            ->distinct()
            ->orderBy('ngay_bat_dau')
            ->pluck('ngay_bat_dau');

        // Lấy suất chiếu từ hôm nay trở đi
        $suatChieus = SuatChieu::with(['phim', 'phongChieu.rapPhim'])
            ->where('ngay_bat_dau', '>=', $today)
            ->where('trang_thai', 'hoat_dong')
            ->orderBy('ngay_bat_dau')
            ->orderBy('bat_dau')
            ->get();

        return view('client.lich-chieu', compact('phims', 'raps', 'ngayChieus', 'suatChieus'));
    }
}
