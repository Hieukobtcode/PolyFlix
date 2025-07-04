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

        // ✅ Lấy danh sách ghế và đánh dấu trạng thái
        $gheNgois = $suatChieu->phongChieu->gheNgois()
            ->with(['loaiGhe'])
            ->orderBy('hang')
            ->orderBy('cot')
            ->get()
            ->map(function ($ghe) use ($gheDaDat) {
                $ghe->da_dat = in_array($ghe->id, $gheDaDat);
                $ghe->phu_thu_loai_phong = optional($ghe->phongChieu->loaiPhong)->phu_thu ?? 0;
                $ghe->phu_thu_loai_ghe = optional($ghe->loaiGhe)->phu_thu ?? 0;
                $ghe->phu_thu_rap_phim = optional($ghe->phongChieu->rapPhim)->phu_thu ?? 0;

                // ✅ Nếu ghế đang được giữ bởi người khác
                $ghe->dang_duoc_chon = $ghe->trang_thai === 'da_chon' && $ghe->dang_chon_user_id !== Auth::id();

                return $ghe;
            });

        //  Hủy ghế tạm thời mà user hiện tại đang giữ khi F5
        foreach ($gheNgois as $ghe) {
            if ($ghe->trang_thai === 'da_chon' && $ghe->dang_chon_user_id === Auth::id()) {
                $ghe->trang_thai = 'trong';
                $ghe->dang_chon_user_id = null;

                //  Bỏ những attribute không có trong DB
                unset(
                    $ghe->da_dat,
                    $ghe->phu_thu_loai_phong,
                    $ghe->phu_thu_loai_ghe,
                    $ghe->phu_thu_rap_phim,
                    $ghe->dang_duoc_chon
                );

                $ghe->save();

                // Broadcast realtime
                event(new GheBiHuyChon($ghe->id, Auth::id()));
            }
        }

        // Lấy danh sách loại ghế
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

        $gheId = $request->ghe_id;
        $userId = Auth::id();

        try {
            DB::beginTransaction();

            // Lock hàng ghế lại để tránh race condition
            $ghe = GheNgoi::lockForUpdate()->find($gheId);

            if (!$ghe) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Ghế không tồn tại!',
                ], 404);
            }

            // Nếu ghế đã được chọn bởi người khác
            if ($ghe->trang_thai === 'da_chon' && $ghe->dang_chon_user_id !== $userId) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Ghế này đã có người khác chọn!',
                ], 409);
            }

            // Cập nhật trạng thái ghế
            $ghe->trang_thai = 'da_chon';
            $ghe->dang_chon_user_id = $userId;
            $ghe->save();

            // Gửi event realtime
            event(new GheDangDuocChon($gheId, $userId));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đã chọn ghế thành công!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server: ' . $e->getMessage(),
            ], 500);
        }
    }

    // xử lý hủy chọn ghế
    public function huyChonGhe(Request $request)
    {
        $request->validate([
            'ghe_id' => 'required|exists:ghe_ngois,id',
        ]);

        $gheId = $request->ghe_id;
        $userId = Auth::id();

        $ghe = GheNgoi::lockForUpdate()->find($gheId);

        if ($ghe->trang_thai === 'da_chon' && $ghe->dang_chon_user_id === $userId) {
            $ghe->trang_thai = 'trong';
            $ghe->dang_chon_user_id = null;
            $ghe->save();

            event(new GheBiHuyChon($gheId, $userId));
        }

        return response()->json(['success' => true]);
    }

    /**
     * Hiển thị trang đặt vé chi tiết
     */
    public function indexDatVe(Request $request)
    {
        $params = $request->input('params');

        if (!$params) {
            return redirect()->route('home')->with('error', 'Không tìm thấy thông tin đặt vé!');
        }

        try {
            // Giải mã params
            $decodedParams = base64_decode($params);
            list($phimId, $suatChieuId) = explode('-', $decodedParams);

            // Lấy thông tin suất chiếu
            $suatChieu = SuatChieu::with([
                'phim',
                'phongChieu.rapPhim.chiNhanh',
                'phongChieu.loaiPhong',
                'phongChieu.gheNgois.loaiGhe'
            ])
                ->where('id', $suatChieuId)
                ->where('phim_id', $phimId)
                ->first();

            if (!$suatChieu) {
                return redirect()->route('home')->with('error', 'Không tìm thấy suất chiếu!');
            }

            // Kiểm tra suất chiếu còn hiệu lực
            $now = Carbon::now();
            $ngayGioChieu = Carbon::parse($suatChieu->ngay_chieu . ' ' . $suatChieu->bat_dau);

            if ($ngayGioChieu->isPast()) {
                return redirect()->route('home')->with('error', 'Suất chiếu đã qua. Vui lòng chọn suất chiếu khác!');
            }

            // Lấy danh sách ghế đã đặt
            $gheDaDat = DB::table('chi_tiet_dat_ves')
                ->join('dat_ves', 'chi_tiet_dat_ves.dat_ve_id', '=', 'dat_ves.id')
                ->where('dat_ves.suat_chieu_id', $suatChieu->id)
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

                    \Log::info('Ghe ID: ' . $ghe->id . ', LoaiGhe: ' . ($ghe->loaiGhe ? $ghe->loaiGhe->ten_loai_ghe : 'null') .
                        ', PhuThuLoaiPhong: ' . $ghe->phu_thu_loai_phong .
                        ', PhuThuLoaiGhe: ' . $ghe->phu_thu_loai_ghe .
                        ', PhuThuRapPhim: ' . $ghe->phu_thu_rap_phim);

                    return $ghe;
                });

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
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Đã có lỗi xảy ra. Vui lòng thử lại!');
        }
    }

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
