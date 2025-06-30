<?php

namespace App\Http\Controllers\Client;

use App\Models\Phim;
use App\Models\Banner;
use App\Models\Rating;
use App\Models\BaiViet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\IdFormatter;


class PhimsController extends Controller
{
    public function phimDangChieu()
    {
        $phims = Phim::with(['comments.user']) 
    ->where('ngay_phat_hanh', '<=', now())
    ->where('ngay_ket_thuc', '>=', now())
    ->orderBy('ngay_phat_hanh', 'desc')
    ->get();
            $ratings = Rating::all();

        $banners = Banner::where('trang_thai', 1)->orderBy('id', 'desc')->get();

        $tab = 'dang-chieu';
         $baiViet = BaiViet::where('status', '!=', 'draft')
            ->orderBy('ngay_tao', 'desc')
            ->limit(4)
            ->get();

        return view('client.phim.phim-list', compact('phims', 'ratings', 'banners', 'tab', 'baiViet'));
    }

    public function phimSapChieu()
    {
        $phims = Phim::with(['comments.user']) 
    ->where('ngay_phat_hanh', '>', now())
    ->orderBy('ngay_phat_hanh', 'asc')
    ->get();
            $ratings = Rating::all();

        $banners = Banner::where('trang_thai', 1)->orderBy('id', 'desc')->get();

        $tab = 'sap-chieu';
         $baiViet = BaiViet::where('status', '!=', 'draft')
            ->orderBy('ngay_tao', 'desc')
            ->limit(4)
            ->get();

        return view('client.phim.phim-list', compact('phims', 'ratings', 'banners', 'tab', 'baiViet'));
    }
}
