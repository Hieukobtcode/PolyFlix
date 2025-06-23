<?php

namespace App\Http\Controllers\Client;

use App\Models\Phim;
use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PhimsController extends Controller
{
    public function phimDangChieu()
    {
        $phims = Phim::where('ngay_phat_hanh', '<=', now())
            ->where('ngay_ket_thuc', '>=', now())
            ->orderBy('ngay_phat_hanh', 'desc')
            ->get();

        $banners = Banner::where('trang_thai', 1)->orderBy('id', 'desc')->get();

        $tab = 'dang-chieu';

        return view('client.phim.phim-list', compact('phims', 'banners', 'tab'));
    }

    public function phimSapChieu()
    {
        $phims = Phim::where('ngay_phat_hanh', '>', now())
            ->orderBy('ngay_phat_hanh', 'asc')
            ->get();

        $banners = Banner::where('trang_thai', 1)->orderBy('id', 'desc')->get();

        $tab = 'sap-chieu';

        return view('client.phim.phim-list', compact('phims', 'banners', 'tab'));
    }
}
