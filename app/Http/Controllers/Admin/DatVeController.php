<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DatVe;
use App\Models\ChiNhanh;
use App\Models\RapPhim;
use App\Models\Phim;

class DatVeController extends Controller
{
    public function index(Request $request)
{
    $phimId = $request->input('phim');
    $rapId = $request->input('rap');
    $chiNhanhId = $request->input('chi_nhanh');

    $query = DatVe::with(['nguoiDung', 'phim.rapPhims.chiNhanh']);

    if (!empty($chiNhanhId)) {
        $query->whereHas('phim.rapPhims.chiNhanh', function ($q) use ($chiNhanhId) {
            $q->where('chi_nhanhs.id', $chiNhanhId);
        });
    }

    if (!empty($rapId)) {
        $query->whereHas('phim.rapPhims', function ($q) use ($rapId) {
            $q->where('rap_phims.id', $rapId);
        });
    }

    if (!empty($phimId)) {
        $query->where('phim_id', $phimId);
    }

    $datVes = $query->get();

    $chiNhanhs = ChiNhanh::with('rapPhims')->get();
    $dsPhim = Phim::all();

    return view('admin.dat-ve.index', compact(
        'datVes', 'chiNhanhs', 'dsPhim',
        'phimId', 'rapId', 'chiNhanhId'
    ));
}

    public function show($id)
    {
        $datVe = DatVe::with(['nguoiDung', 'phim'])->findOrFail($id);

        return view('admin.dat-ve.show', compact('datVe'));
    }
}
