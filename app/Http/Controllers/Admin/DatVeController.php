<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DatVe;
use App\Models\ChiNhanh;
use App\Models\DoAn;
use App\Models\RapPhim;
use App\Models\Phim;
use DNS1D;
use App\Mail\GuiVeXemPhim;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
class DatVeController extends Controller
{
    public function index(Request $request)
    {
        $phimId = $request->input('phim');
        $rapId = $request->input('rap');
        $chiNhanhId = $request->input('chi_nhanh');

        $query = DatVe::with(['nguoiDung', 'phim.rapPhims.chiNhanh', 'suatChieu']);

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
            'datVes',
            'chiNhanhs',
            'dsPhim',
            'phimId',
            'rapId',
            'chiNhanhId'
        ));

    }

    public function show($id)
    {
        $datVe = DatVe::with(['nguoiDung', 'suatChieu.phim', 'combos'])->findOrFail($id);
        return view('admin.dat-ve.show', compact('datVe'));
    }
    public function guiVe($datVeId)
    {
        $datVe = DatVe::with(['nguoiDung', 'suatChieu.phim', 'combos.doAns'])->findOrFail($datVeId);

        // Tạo barcode base64
        $barcodeUrl = 'data:image/png;base64,' . DNS1D::getBarcodePNG($datVe->ma_dat_ve, 'C128', 2, 60);

        Mail::to($datVe->nguoiDung->email)
            ->send(new GuiVeXemPhim($datVe, $barcodeUrl)); 

        return back()->with('success', 'Đã gửi vé về email người dùng!');
    }
}
