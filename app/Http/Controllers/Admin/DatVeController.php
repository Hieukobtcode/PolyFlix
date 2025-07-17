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

        $query = DatVe::with(['nguoiDung', 'suatChieu.phim', 'suatChieu.phongChieu.rapPhim.chiNhanh']);

        if (!empty($chiNhanhId)) {
            $query->whereHas('suatChieu.phongChieu.rapPhim.chiNhanh', function ($q) use ($chiNhanhId) {
                $q->where('chi_nhanhs.id', $chiNhanhId);
            });
        }

        if (!empty($rapId)) {
            $query->whereHas('suatChieu.phongChieu.rapPhim', function ($q) use ($rapId) {
                $q->where('rap_phims.id', $rapId);
            });
        }

        if (!empty($phimId)) {
            $query->whereHas('suatChieu.phim', function ($q) use ($phimId) {
                $q->where('phims.id', $phimId);
            });
        }

        $datVes = $query->orderBy('created_at', 'desc')->get();

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

    public function show(Request $request, $id = null, $ma_ve = null)
    {
        // Ưu tiên lấy từ query string nếu có
        $id = $id ?? $request->input('id');
        $ma_ve = $ma_ve ?? $request->input('ma_ve');

        // Nếu có ID thì tìm theo ID
        if ($id) {
            $datVe = DatVe::with([
                'nguoiDung',
                'suatChieu.phim',
                'suatChieu.phongChieu.rapPhim.chiNhanh',
                'gheNgois.loaiGhe',
                'combos.doAns'
            ])->findOrFail($id);

            // Nếu có mã vé thì kiểm tra trùng khớp
            if ($ma_ve && $datVe->ma_dat_ve !== $ma_ve) {
                abort(404, 'Mã vé không khớp');
            }
        }
        // Nếu không có ID thì thử tìm theo mã vé
        elseif ($ma_ve) {
            $datVe = DatVe::with([
                'nguoiDung',
                'suatChieu.phim',
                'suatChieu.phongChieu.rapPhim.chiNhanh',
                'gheNgois.loaiGhe',
                'combos.doAns'
            ])->where('ma_dat_ve', $ma_ve)->firstOrFail();
        } else {
            // Không đủ thông tin
            abort(404, 'Không có thông tin để hiển thị vé');
        }

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
    
    public function print($id)
    {
        $datVe = DatVe::with([
            'nguoiDung',
            'suatChieu.phim',
            'suatChieu.phongChieu.rapPhim',
            'gheNgois.loaiGhe',
            'combos.doAns'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('admin.dat-ve.print', compact('datVe'))->setPaper('a4');
        return $pdf->stream('ve_xem_phim_' . $datVe->ma_dat_ve . '.pdf');
    }
}
