<?php

namespace App\Http\Controllers\Client;

use App\Models\KhuyenMai;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KhuyenMaiController extends Controller
{
    //
     public function index()
    {
        $khuyenMais = KhuyenMai::where('trang_thai', 'hoat_dong')
            ->where('ngay_ket_thuc', '>=', now())
            ->orderBy('ngay_bat_dau', 'desc')
            ->paginate(12); // phân trang cho đẹp

        return view('client.khuyen-mai.index', compact('khuyenMais'));
    }
}
