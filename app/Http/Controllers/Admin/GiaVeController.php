<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChiNhanh;
use App\Models\LoaiGhe;
use App\Models\LoaiPhong;
use App\Models\RapPhim;
use Illuminate\Http\Request;

class GiaVeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chiNhanhs = ChiNhanh::all();
        $rapPhims = RapPhim::all();
        $loaiGhes = LoaiGhe::all();
        $loaiPhongs = LoaiPhong::all();

        return view('admin.gia-ve.index', compact(
            'chiNhanhs',
            'rapPhims',
            'loaiGhes',
            'loaiPhongs'
        ));
    }

    public function updateGiaVe(Request $request)
    {
        if ($request->has('gia_ghe')) {
            foreach ($request->input('gia_ghe') as $gheId => $phuThu) {
                LoaiGhe::where('id', $gheId)->update([
                    'phu_thu' => (int)$phuThu
                ]);
            }
        }

        if ($request->has('phu_thu_phong')) {
            foreach ($request->input('phu_thu_phong') as $phongId => $phuThu) {
                LoaiPhong::where('id', $phongId)->update([
                    'phu_thu' => (int)$phuThu
                ]);
            }
        }

        if ($request->has('phu_thu_rap')) {
            foreach ($request->input('phu_thu_rap') as $rapId => $phuThu) {
                RapPhim::where('id', $rapId)->update([
                    'phu_thu' => (int)$phuThu
                ]);
            }
        }

        return redirect()->route('admin.gia-ve.index')->with('success', 'Cập nhật giá vé thành công.');
    }
}
