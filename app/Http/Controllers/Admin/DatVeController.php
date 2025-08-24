<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DatVe;
use App\Models\ChiNhanh;
use App\Models\DoAn;
use App\Models\RapPhim;
use App\Models\Phim;
use App\Models\CauHinh;
use DNS1D;
use App\Mail\GuiVeXemPhim;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DatVeController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $phimId = $request->input('phim');
        $rapId = $request->input('rap');
        $chiNhanhId = $request->input('chi_nhanh');

        // Query cơ bản
        $query = DatVe::with([
            'nguoiDung',
            'suatChieu.phim',
            'suatChieu.phongChieu.rapPhim.chiNhanh'
        ]);

        // ===== PHÂN QUYỀN =====
        if ($user->vai_tro_id == 2 && $user->chiNhanhDangQuanLy) {
            // Admin chi nhánh: chỉ xem vé thuộc chi nhánh đó
            $query->whereHas('suatChieu.phongChieu.rapPhim', function ($q) use ($user) {
                $q->where('chi_nhanh_id', $user->chiNhanhDangQuanLy->id);
            });
        }

        if ($user->vai_tro_id == 3 && $user->rapPhimDangQuanLy) {
            $query->whereHas('suatChieu.phongChieu', function ($q) use ($user) {
                $q->where('rap_phim_id', $user->rapPhimDangQuanLy->id);
            });
        }

        if ($user->vai_tro_id == 4 && $user->rapLamViec) {
            $query->whereHas('suatChieu.phongChieu', function ($q) use ($user) {
                $q->where('rap_phim_id', $user->rapLamViec->id);
            });
        }

        // ===== BỘ LỌC =====
        if ($user->vai_tro_id == 1 && !empty($chiNhanhId)) {
            // Admin tổng: có quyền lọc theo chi nhánh
            $query->whereHas('suatChieu.phongChieu.rapPhim.chiNhanh', function ($q) use ($chiNhanhId) {
                $q->where('id', $chiNhanhId);
            });
        }

        if (in_array($user->vai_tro_id, [1, 2]) && !empty($rapId)) {
            // Admin tổng hoặc Admin chi nhánh: có quyền lọc theo rạp
            $query->whereHas('suatChieu.phongChieu.rapPhim', function ($q) use ($rapId) {
                $q->where('id', $rapId);
            });
        }

        if (!empty($phimId)) {
            $query->whereHas('suatChieu.phim', function ($q) use ($phimId) {
                $q->where('id', $phimId);
            });
        }

        // Lấy kết quả
        $datVes = $query->orderBy('created_at', 'desc')->paginate(10);;

        // ===== CHI NHÁNH / RẠP HIỂN THỊ TRONG VIEW =====
        if ($user->vai_tro_id == 1) {
            // Admin tổng: lấy tất cả chi nhánh và rạp
            $chiNhanhs = ChiNhanh::with('rapPhims')->get();
        } elseif ($user->vai_tro_id == 2 && $user->chiNhanhDangQuanLy) {
            $chiNhanhs = collect([$user->chiNhanhDangQuanLy->load('rapPhims')]);
        } elseif ($user->vai_tro_id == 3 && $user->rapPhimDangQuanLy) {
            $rap = $user->rapPhimDangQuanLy;
            $chiNhanh = $rap->chiNhanh;
            $chiNhanh->setRelation('rapPhims', collect([$rap]));
            $chiNhanhs = collect([$chiNhanh]);
        } elseif ($user->vai_tro_id == 4 && $user->rapLamViec) {
            $rap = $user->rapLamViec;
            $chiNhanh = $rap->chiNhanh;
            $chiNhanh->setRelation('rapPhims', collect([$rap]));
            $chiNhanhs = collect([$chiNhanh]);
        } else {
            $chiNhanhs = collect();
        }

        $dsPhim = Phim::all();

        return view('admin.dat-ve.index', compact(
            'datVes',
            'chiNhanhs',
            'dsPhim',
            'phimId',
            'rapId',
            'chiNhanhId',
            'user'
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

        // ✅ Kiểm tra quyền quản lý theo vai trò
        $user = auth()->user();
        $rapPhim   = $datVe->suatChieu->phongChieu->rapPhim;
        $chiNhanh  = $rapPhim->chiNhanh;

        // Nếu là admin chi nhánh
        if ($user->vai_tro_id == 2) {
            if ($chiNhanh->quan_ly_id != $user->id) {
                return back()
                    ->with('error', 'Bạn không có quyền xem vé của chi nhánh này.');
            }
        }

        // Nếu là admin rạp
        if ($user->vai_tro_id == 3) {
            if ($rapPhim->quan_ly_id != $user->id) {
                return back()
                    ->with('error', 'Bạn không có quyền xem vé của rạp này.');
            }
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
            'suatChieu.phongChieu.rapPhim.chiNhanh',
            'gheNgois.loaiGhe',
            'combos.doAns',
            'doAns'
        ])->findOrFail($id);

        // Tính tổng tiền
        $phuThuRap = $datVe->suatChieu->phongChieu->rapPhim->phu_thu ?? 0;
        $tongTienGhe = $datVe->gheNgois->sum(function ($ghe) use ($phuThuRap) {
            return ($ghe->loaiGhe->phu_thu ?? 0) + $phuThuRap;
        });

        $tongTienCombo = $datVe->combos->sum(function ($combo) {
            return ($combo->gia ?? 0) * ($combo->pivot->so_luong ?? 1);
        });

        $tongTienDoAn = $datVe->doAns->sum(function ($doAn) {
            return ($doAn->gia ?? 0) * ($doAn->pivot->so_luong ?? 1);
        });

        $tongThanhTien = $tongTienGhe + $tongTienCombo + $tongTienDoAn;

        // Tạo mã vạch và lưu thành file PNG
        $barcodeData = DNS1D::getBarcodePNG($datVe->ma_dat_ve, 'C128', 2, 60);
        $barcodeFileName = 'barcode_' . $datVe->ma_dat_ve . '.png';
        $barcodePath = public_path('temp/' . $barcodeFileName);

        // Đảm bảo thư mục temp tồn tại
        if (!Storage::exists('public/temp')) {
            Storage::makeDirectory('public/temp');
        }

        // Lưu mã vạch vào file
        file_put_contents($barcodePath, base64_decode($barcodeData));

        // ✅ Lấy cấu hình
        $cauHinh = CauHinh::first();
        // Tải view
        $pdf = Pdf::loadView('admin.dat-ve.print', compact('datVe', 'tongTienGhe', 'tongTienCombo', 'tongTienDoAn', 'tongThanhTien', 'barcodeFileName', 'cauHinh'))
            ->setPaper('a4')
            ->setOption('enable-local-file-access', true)
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true)
            ->setOption('default_font', 'DejaVu Sans')
            ->setOption('dpi', 150)
            ->setOption('isPhpEnabled', true)
            ->setOption('isFontSubsettingEnabled', true)
            ->setOption('enable_html5_parser', true);

        // Xóa file mã vạch sau khi tạo PDF
        register_shutdown_function(function () use ($barcodePath) {
            if (file_exists($barcodePath)) {
                unlink($barcodePath);
            }
        });

        return $pdf->stream('ve_xem_phim_' . $datVe->ma_dat_ve . '.pdf');
    }
}