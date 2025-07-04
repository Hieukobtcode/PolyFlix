<?php

namespace App\Http\Controllers\Client;

use Carbon\Carbon;
use App\Models\DoAn;
use App\Models\Combo;
use App\Models\DatVe;
use App\Models\GheNgoi;
use App\Models\SuatChieu;
use App\Events\GheBiHuyChon;
use App\Models\ChiTietDatVe;
use Illuminate\Http\Request;
use App\Events\GheDangDuocChon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

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

        // Lấy thông tin suất chiếu với các mối quan hệ cần thiết
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
            ->with(['loaiGhe'])
            ->orderBy('hang')
            ->orderBy('cot')
            ->get()
            ->map(function ($ghe) use ($gheDaDat, $suatChieu) {
                $ghe->da_dat = in_array($ghe->id, $gheDaDat);
                $ghe->phu_thu_loai_phong = optional($suatChieu->phongChieu->loaiPhong)->phu_thu ?? 0;
                $ghe->phu_thu_loai_ghe = optional($ghe->loaiGhe)->phu_thu ?? 0;
                $ghe->phu_thu_rap_phim = optional($suatChieu->phongChieu->rapPhim)->phu_thu ?? 0;

                $userIdDangChon = Cache::get("ghe_dang_chon_{$ghe->id}");
                $ghe->dang_duoc_chon = $userIdDangChon && $userIdDangChon != Auth::id();

                return $ghe;
            });

        // Hủy ghế tạm thời do user hiện tại giữ nhưng F5 lại
        foreach ($gheNgois as $ghe) {
            $userIdDangChon = Cache::get("ghe_dang_chon_{$ghe->id}");
            if ($userIdDangChon == Auth::id()) {
                Cache::forget("ghe_dang_chon_{$ghe->id}");
                event(new GheBiHuyChon($ghe->id, $userIdDangChon));
            }
        }

        // Lấy danh sách loại ghế để lấy màu
        $loaiGhes = \App\Models\LoaiGhe::all();

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
            'loaiGhes',
            'doAns',
            'combos'
        ));
    }

    // xử lý chọn ghế
    public function chonGhe(Request $request)
    {
        $request->validate([
            'ghe_id' => 'required|exists:ghe_ngois,id',
        ]);

        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Bạn cần đăng nhập để chọn ghế'], 401);
        }

        $gheId = $request->ghe_id;

        // Emit event
        event(new GheDangDuocChon($gheId, Auth::id()));

        // giữ ghế
        Cache::put("ghe_dang_chon_{$gheId}", Auth::id(), now()->addMinutes(2));

        return response()->json(['success' => true]);
    }
    // xử lý hủy chọn ghế
    public function huyChonGhe(Request $request)
    {
        $request->validate([
            'ghe_id' => 'required|exists:ghe_ngois,id',
        ]);

        $gheId = $request->ghe_id;
        $userId = Auth::id();

        if (Cache::get("ghe_dang_chon_{$gheId}") == $userId) {
            Cache::forget("ghe_dang_chon_{$gheId}");
            event(new GheBiHuyChon($gheId, $userId));
        }

        return response()->json(['success' => true]);
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
            $suatChieu = SuatChieu::with(['phongChieu.rapPhim', 'phongChieu.loaiPhong'])->findOrFail($request->suat_chieu_id);

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
                $phuThuLoaiPhong = $suatChieu->phongChieu->loaiPhong->phu_thu ?? 0;
                $phuThuRap = $suatChieu->phongChieu->rapPhim->phu_thu ?? 0;

                // Sử dụng logic giống tinhTongTien()
                $giaVeCoBan = 0; // Temporary 0 để test
                $giaVe = $giaVeCoBan + $phuThuLoaiPhong + $phuThuGhe;
                // Không cộng phụ thu rạp vào từng ghế vì đã cộng 1 lần trong tinhTongTien()

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
                'message' => 'Đặt vé thành công! Chuyển đến trang thanh toán...',
                'dat_ve_id' => $datVe->id,
                'redirect_url' => route('client.thanh-toan.index', $datVe->id)
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

        // Lấy thông tin suất chiếu
        $suatChieu = SuatChieu::with(['phongChieu.rapPhim', 'phongChieu.loaiPhong'])->find($request->suat_chieu_id);
        $phuThuRap = $suatChieu->phongChieu->rapPhim->phu_thu ?? 0;
        $phuThuLoaiPhong = $suatChieu->phongChieu->loaiPhong->phu_thu ?? 0;

        // Giá vé cơ bản (có thể lấy từ cấu hình hoặc mặc định)
        $giaVeCoBan = 0; // Temporary 0 để test

        // Tính tiền ghế
        $gheIds = $request->ghe_ids;
        foreach ($gheIds as $gheId) {
            $ghe = GheNgoi::with('loaiGhe')->find($gheId);
            $phuThuGhe = $ghe->loaiGhe->phu_thu ?? 0;

            // Giá cho 1 ghế = giá cơ bản + phụ thu loại phòng + phụ thu loại ghế
            $giaMotGhe = $giaVeCoBan + $phuThuLoaiPhong + $phuThuGhe;
            $tongTien += $giaMotGhe;
        }

        // Cộng phụ thu rạp CHỈ 1 LẦN cho tất cả ghế
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
