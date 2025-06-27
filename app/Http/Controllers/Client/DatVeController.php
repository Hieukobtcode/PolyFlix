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
use Illuminate\Support\Facades\Log;
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
            ->orderBy('hang')
            ->orderBy('cot')
            ->get()
            ->map(function ($ghe) use ($gheDaDat) {
                $ghe->da_dat = in_array($ghe->id, $gheDaDat);
                return $ghe;
            });

        // Lấy danh sách đồ ăn và combo
        $doAns = DoAn::whereHas('chiNhanhs', function ($query) use ($suatChieu) {
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
        Log::info('=== BẮT ĐẦU ĐẶT VÉ ===');
        Log::info('Request data:', $request->all());

        $request->validate([
            'suat_chieu_id' => 'required|exists:suat_chieus,id',
            'ghe_ids' => 'required|array|min:1',
            'ghe_ids.*' => 'exists:ghe_ngois,id',
            'do_an' => 'nullable|array',
            'combo' => 'nullable|array',
        ]);

        if (!Auth::check()) {
            Log::error('User chưa đăng nhập');
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để đặt vé!'
            ], 401);
        }

        Log::info('User đã đăng nhập:', ['user_id' => Auth::id()]);

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
            Log::info('Tổng tiền tính được:', ['tong_tien' => $tongTien]);

            // Tạo đơn đặt vé
            $datVe = DatVe::create([
                'ma_dat_ve' => 'DV' . time() . rand(100, 999),
                'user_id' => Auth::id(),
                'suat_chieu_id' => $request->suat_chieu_id,
                'tong_tien' => $tongTien,
                'phuong_thuc_tt' => 'Chưa chọn',
                'trang_thai' => 'Chờ thanh toán'
            ]);
            Log::info('Đã tạo đặt vé:', ['dat_ve_id' => $datVe->id]);

            // Tạo chi tiết đặt vé (ghế)
            foreach ($request->ghe_ids as $gheId) {
                $ghe = GheNgoi::with('loaiGhe')->find($gheId);
                $phuThuGhe = ($ghe->loaiGhe->phu_thu ?? 0);
                $phuThuRap = $suatChieu->phongChieu->rapPhim->phu_thu ?? 0;
                $giaVe = $phuThuGhe + $phuThuRap;

                $chiTiet = ChiTietDatVe::create([
                    'dat_ve_id' => $datVe->id,
                    'ghe_id' => $gheId,
                    'gia_ve' => $giaVe
                ]);
                Log::info('Đã tạo chi tiết đặt vé:', ['chi_tiet_id' => $chiTiet->id, 'ghe_id' => $gheId, 'gia_ve' => $giaVe]);
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
            Log::info('=== ĐẶT VÉ THÀNH CÔNG ===', ['dat_ve_id' => $datVe->id]);

            return response()->json([
                'success' => true,
                'message' => 'Đặt vé thành công!',
                'dat_ve_id' => $datVe->id
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('=== LỖI ĐẶT VÉ ===', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Hiển thị trang kết quả đặt vé
     */
    public function ketQua($id)
    {
        // Kiểm tra user đã đăng nhập và vé thuộc về user này
        if (!Auth::check()) {
            return redirect()->route('home')->with('error', 'Vui lòng đăng nhập để xem vé!');
        }

        $datVe = DatVe::with([
            'nguoiDung',
            'suatChieu.phim',
            'suatChieu.phongChieu.rapPhim.chiNhanh',
            'gheNgois.loaiGhe',
            'combos.doAns'
        ])->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('client.dat-ve.ket-qua', compact('datVe'));
    }

    /**
     * Tính tổng tiền đặt vé
     */
    private function tinhTongTien($request)
    {
        $tongTien = 0;

        // Tính tiền ghế
        $gheIds = $request->ghe_ids;
        $suatChieu = SuatChieu::find($request->suat_chieu_id);
        $phuThuRap = $suatChieu->phongChieu->rapPhim->phu_thu ?? 0;

        foreach ($gheIds as $gheId) {
            $ghe = GheNgoi::with('loaiGhe')->find($gheId);
            $phuThuGhe = $ghe->loaiGhe->phu_thu ?? 0;
            $tongTien += $phuThuGhe;
        }

        // Thêm phụ thu rạp
        $tongTien += $phuThuRap;

        // Tính tiền đồ ăn
        if ($request->do_an) {
            foreach ($request->do_an as $doAnId => $soLuong) {
                if ($soLuong > 0) {
                    $giaDoAn = DoAn::find($doAnId)->gia ?? 0;
                    $tongTien += $giaDoAn * $soLuong;
                }
            }
        }

        // Tính tiền combo
        if ($request->combo) {
            foreach ($request->combo as $comboId => $soLuong) {
                if ($soLuong > 0) {
                    $giaCombo = Combo::find($comboId)->gia ?? 0;
                    $tongTien += $giaCombo * $soLuong;
                }
            }
        }

        return $tongTien;
    }
}
