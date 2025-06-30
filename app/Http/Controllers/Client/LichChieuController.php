<?php

namespace App\Http\Controllers\Client;

use App\Models\Phim;
use App\Models\RapPhim;
use App\Models\SuatChieu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class LichChieuController extends Controller
{
    //
    public function index(){
        // Lấy danh sách phim
    // Lấy danh sách phim
    $phims = Phim::orderBy('ten_phim')->get();

    // Lấy danh sách rạp
    $raps = RapPhim::orderBy('ten_rap')->get();

    // Lấy danh sách ngày DISTINCT trong bảng suat_chieus
    $ngayChieus = SuatChieu::select('ngay_chieu')
        ->distinct()
        ->orderBy('ngay_chieu')
        ->pluck('ngay_chieu');

    // Lấy FULL tất cả suất chiếu (có thể eager load phim + phòng)
    $suatChieus = SuatChieu::with(['phim', 'phongChieu'])
        ->orderBy('ngay_chieu')
        ->orderBy('bat_dau')
        ->get();

    return view('client.lich-chieu', compact('phims', 'raps', 'ngayChieus', 'suatChieus'));

    }
    
}
