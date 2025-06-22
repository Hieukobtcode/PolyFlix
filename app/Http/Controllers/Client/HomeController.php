<?php

namespace App\Http\Controllers\Client;

use App\Models\Phim;
use App\Models\Banner;
use App\Models\KhuyenMai;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    //
      public function index()
    {
      $phims = Phim::where('trang_thai', 1)
    ->orderBy('ngay_phat_hanh', 'desc')
    ->get();

        $khuyenMais = KhuyenMai::where('trang_thai', 'hoat_dong')
            ->where('ngay_ket_thuc', '>=', now())
            ->orderBy('ngay_bat_dau', 'desc')
            ->get();

        return view('client.trang-chu', compact('banners', 'phims', 'khuyenMais'));
    }
}
