<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\SuatChieu;
use App\Models\GheNgoi;
use App\Models\DoAn;
use App\Models\Combo;
use App\Models\DatVe;
use App\Models\ChiTietDatVe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatVeController extends Controller
{
    /**
     * Hiển thị trang đặt vé chi tiết
     */
    public function index(Request $request)
    {
        $suatChieuId = $request->input('suat_chieu_id');
        
        if (!$suatChieuId) {
            return redirect()->route('home')->with('error', 'Vui lòng chọn suất chiếu!');
        }

        // Lấy thông tin suất chiếu
        $suatChieu = SuatChieu::with([
            'phim',
            'phongChieu.rapPhim.chiNhanh',
            'phongChieu.loaiPhong',
            'phongChieu.gheNgois.loaiGhe'
        ])->findOrFail($suatChieuId);

        // Kiểm tra suất chiếu còn hiệu lực
        $now = Carbon::now();
        $ngayGioChieu = Carbon::parse($suatChieu->ngay_chieu . ' ' . $suatChieu->bat_dau);
        
        if ($ngayGioChieu->isPast()) {
            return redirect()->route('home')->with('error', 'Suất chiếu đã qua. Vui lòng chọn suất chiếu khác!');
        }

        // Lấy danh sách ghế đã đặt
        $gheDaDat = DB::table('chi_tiet_dat_ves')
            ->join('dat_ves', 'chi_tiet_dat_ves.dat_ve_id', '=', 'dat_ves.id')
            ->where('dat_ves.suat_chieu_id', $suatChieuId)
            ->whereIn('dat_ves.trang_thai', ['Đã thanh toán', 'Chờ thanh toán'])
            ->pluck('chi_tiet_dat_ves.ghe_id')
            ->toArray();

        // Lấy danh sách ghế theo phòng chiếu
        $gheNgois = $suatChieu->phongChieu->gheNgois()
            ->with('loaiGhe')
            ->orderBy('hang_ghe')
            ->orderBy('so_ghe')
            ->get()
            ->map(function($ghe) use ($gheDaDat) {
                $ghe->da_dat = in_array($ghe->id, $gheDaDat);
                return $ghe;
            });

        // Lấy danh sách đồ ăn và combo
        $doAns = DoAn::whereHas('chiNhanhs', function($query) use ($suatChieu) {
                $query->where('chi_nhanh_id', $suatChieu->phongChieu->rapPhim->chi_nhanh_id);
            })
            ->where('trang_thai', 1)
            ->with('danhMuc')
            ->get();

        $combos = Combo::where('trang_thai', 1)
            ->with('doAns')
            ->get();

        return view('client.dat-ve.index', compact(
            'suatChieu', 
            'gheNgois', 
            'doAns', 
            'combos'
        ));
    }

    /**
     * Xử lý đặt vé
     */
    public function store(Request $request)
    {
        $request->validate([
            'suat_chieu_id' => 'required|exists:suat_chieus,id',
            'ghe_ids' => 'required|array|min:1',
            'ghe_ids.*' => 'exists:ghe_ngois,id',
            'do_an' => 'nullable|array',
            'combo' => 'nullable|array',
        ]);

        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để đặt vé!'
            ], 401);
        }

        DB::beginTransaction();
        try {
            $suatChieu = SuatChieu::findOrFail($request->suat_chieu_id);
            
            // Kiểm tra ghế còn trống
            $gheDaDat = DB::table('chi_tiet_dat_ves')
                ->join('dat_ves', 'chi_tiet_dat_ves.dat_ve_id', '=', 'dat_ves.id')
                ->where('dat_ves.suat_chieu_id', $request->suat_chieu_id)
                ->whereIn('dat_ves.trang_thai', ['Đã thanh toán', 'Chờ thanh toán'])
                ->whereIn('chi_tiet_dat_ves.ghe_id', $request->ghe_ids)
                ->exists();

            if ($gheDaDat) {
                throw new \Exception('Một số ghế đã được đặt. Vui lòng chọn ghế khác!');
            }

            // Tính tổng tiền
            $tongTien = $this->tinhTongTien($request);

            // Tạo đơn đặt vé
            $datVe = DatVe::create([
                'user_id' => Auth::id(),
                'suat_chieu_id' => $request->suat_chieu_id,
                'tong_tien' => $tongTien,
                'phuong_thuc_tt' => 'Chưa chọn',
                'trang_thai' => 'Chờ thanh toán'
            ]);

            // Tạo chi tiết đặt vé (ghế)
            foreach ($request->ghe_ids as $gheId) {
                ChiTietDatVe::create([
                    'dat_ve_id' => $datVe->id,
                    'ghe_id' => $gheId
                ]);
            }

            // Thêm đồ ăn nếu có
            if ($request->do_an) {
                foreach ($request->do_an as $doAnId => $soLuong) {
                    if ($soLuong > 0) {
                        $datVe->doAns()->attach($doAnId, ['so_luong' => $soLuong]);
                    }
                }
            }

            // Thêm combo nếu có
            if ($request->combo) {
                foreach ($request->combo as $comboId => $soLuong) {
                    if ($soLuong > 0) {
                        $datVe->combos()->attach($comboId, ['so_luong' => $soLuong]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đặt vé thành công!',
                'dat_ve_id' => $datVe->id
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Tính tổng tiền đặt vé
     */
    private function tinhTongTien($request)
    {
        $tongTien = 0;

        // Tính tiền ghế
        $gheIds = $request->ghe_ids;
        $giaGhe = DB::table('ghe_ngois')
            ->join('loai_ghes', 'ghe_ngois.loai_ghe_id', '=', 'loai_ghes.id')
            ->whereIn('ghe_ngois.id', $gheIds)
            ->sum('loai_ghes.gia');
        
        $tongTien += $giaGhe;

        // Tính tiền đồ ăn
        if ($request->do_an) {
            foreach ($request->do_an as $doAnId => $soLuong) {
                if ($soLuong > 0) {
                    $giaDoAn = DoAn::find($doAnId)->gia;
                    $tongTien += $giaDoAn * $soLuong;
                }
            }
        }

        // Tính tiền combo
        if ($request->combo) {
            foreach ($request->combo as $comboId => $soLuong) {
                if ($soLuong > 0) {
                    $giaCombo = Combo::find($comboId)->gia;
                    $tongTien += $giaCombo * $soLuong;
                }
            }
        }

        return $tongTien;
    }
}
