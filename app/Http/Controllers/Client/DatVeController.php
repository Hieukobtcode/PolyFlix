<?php

namespace App\Http\Controllers\Client;

use Exception;
use Carbon\Carbon;
use App\Models\DoAn;
use App\Models\Combo;
use App\Models\DatVe;
use App\Models\KhuyenMai;
use App\Models\GheNgoi;
use App\Models\SuatChieu;
use App\Models\LichSuDiem;
use App\Events\GheBiHuyChon;
use App\Models\ChiTietDatVe;
use Illuminate\Http\Request;
use App\Events\GheDangDuocChon;
use App\Models\GheNgoiSuatChieu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DatVeController extends Controller
{



    // xử lý chọn ghế
    public function chonGhe(Request $request)
    {
        // $request->validate([
        //     'ghe_id' => 'required|exists:ghe_ngois,id',
        // ]);

        // $gheId = $request->ghe_id;
        // $userId = Auth::id();

        // try {
        //     DB::beginTransaction();

        //     // Lock hàng ghế lại để tránh race condition
        //     $ghe = GheNgoi::lockForUpdate()->find($gheId);

        //     if (!$ghe) {
        //         DB::rollBack();
        //         return response()->json([
        //             'success' => false,
        //             'message' => 'Ghế không tồn tại!',
        //         ], 404);
        //     }

        //     // Nếu ghế đã được chọn bởi người khác
        //     if ($ghe->trang_thai === 'da_chon' && $ghe->dang_chon_user_id !== $userId) {
        //         DB::rollBack();
        //         return response()->json([
        //             'success' => false,
        //             'message' => 'Ghế này đã có người khác chọn!',
        //         ], 409);
        //     }

        //     // Cập nhật trạng thái ghế
        //     $ghe->trang_thai = 'da_chon';
        //     $ghe->dang_chon_user_id = $userId;
        //     $ghe->save();

        //     // Gửi event realtime
        //     event(new GheDangDuocChon($gheId, $userId));

        //     DB::commit();

        //     return response()->json([
        //         'success' => true,
        //         'message' => 'Đã chọn ghế thành công!',
        //     ]);
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Lỗi server: ' . $e->getMessage(),
        //     ], 500);
        // }
    }

    // xử lý hủy chọn ghế
    public function huyChonGhe(Request $request)
    {
        // $request->validate([
        //     'ghe_id' => 'required|exists:ghe_ngois,id',
        // ]);

        // $gheId = $request->ghe_id;
        // $userId = Auth::id();

        // $ghe = GheNgoi::lockForUpdate()->find($gheId);

        // if ($ghe->trang_thai === 'da_chon' && $ghe->dang_chon_user_id === $userId) {
        //     $ghe->trang_thai = 'trong';
        //     $ghe->dang_chon_user_id = null;
        //     $ghe->save();

        //     event(new GheBiHuyChon($gheId, $userId));
        // }

        // return response()->json(['success' => true]);
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

            if ($suatChieu->ngay_bat_dau && $suatChieu->bat_dau) {
                $ngayGioChieu = Carbon::parse($suatChieu->ngay_bat_dau . ' ' . $suatChieu->bat_dau);
                if ($ngayGioChieu->isPast()) {
                    return redirect()->back()->with('error', 'Suất chiếu đã qua. Vui lòng chọn suất chiếu khác!');
                }
            } else {
                return redirect()->back()->with('error', 'Suất chiếu không hợp lệ!');
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
                    // Lưu trạng thái gốc của ghế
                    $ghe->trang_thai_mac_dinh = $ghe->trang_thai;

                    // Lấy trạng thái ghế theo suất chiếu
                    $pivot = \App\Models\GheNgoiSuatChieu::where('ghe_ngoi_id', $ghe->id)
                        ->where('suat_chieu_id', $suatChieu->id)
                        ->first();

                    $ghe->trang_thai_theo_suat = $pivot?->trang_thai ?? 'trong';

                    // Đã đặt hay chưa (theo vé đã bán)
                    $ghe->da_dat = in_array($ghe->id, $gheDaDat);

                    // Phụ thu
                    $ghe->phu_thu_loai_phong = optional($suatChieu->phongChieu->loaiPhong)->phu_thu ?? 0;
                    $ghe->phu_thu_loai_ghe = optional($ghe->loaiGhe)->phu_thu ?? 0;
                    $ghe->phu_thu_rap_phim = optional($suatChieu->phongChieu->rapPhim)->phu_thu ?? 0;

                    // Debug log để xem dữ liệu
                    Log::info(
                        'Ghế ID: ' . $ghe->id .
                            ', Mặc định: ' . $ghe->trang_thai_mac_dinh .
                            ', Theo suất: ' . $ghe->trang_thai_theo_suat .
                            ', Đã đặt: ' . ($ghe->da_dat ? 'Yes' : 'No')
                    );

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
            'ma_khuyen_mai' => 'nullable|string',
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

        $diemSuDung = (int) $request->input('diem_su_dung');

        $user = Auth::user();

        if ($user->diem >= $diemSuDung) {
            $user->diem -= $diemSuDung;
            $user->save();
        }



        try {
            $suatChieu = SuatChieu::with(['phongChieu.rapPhim', 'phongChieu.loaiPhong'])
                ->findOrFail($request->suat_chieu_id);

            // Lấy thông tin trạng thái các ghế theo suất chiếu
            $trangThaiGhe = GheNgoiSuatChieu::whereIn('ghe_ngoi_id', $request->ghe_ids)
                ->where('suat_chieu_id', $request->suat_chieu_id)
                ->pluck('trang_thai', 'ghe_ngoi_id');

            // Lấy tên ghế để báo lỗi
            $tenGheMap = DB::table('ghe_ngois')
                ->whereIn('id', $request->ghe_ids)
                ->pluck('ma_ghe', 'id');

            // Kiểm tra từng ghế
            foreach ($trangThaiGhe as $gheId => $trangThai) {
                if ($trangThai === 'da_chon') {
                    throw new Exception("Ghế {$tenGheMap[$gheId]} đã được người khác chọn, vui lòng chọn ghế khác!");
                }
                if ($trangThai === 'da_dat') {
                    throw new \Exception("Ghế {$tenGheMap[$gheId]} đã được đặt, vui lòng chọn ghế khác!");
                }
            }

            // Tính tổng tiền với khuyến mãi
            $tinhToan = $this->tinhTongTien($request);
            $tongTien = $tinhToan['tong_tien'];
            $giaTriGiam = $tinhToan['giam_gia'];
            Log::info('Tổng tiền tính được:', [
                'tong_tien_goc' => $tinhToan['tong_tien_goc'],
                'giam_gia' => $giaTriGiam,
                'tong_tien' => $tongTien
            ]);

            // Lấy thông tin khuyến mãi nếu có
            $khuyenMaiId = null;
            if ($request->filled('ma_khuyen_mai')) {
                $khuyenMai = KhuyenMai::where('ma_khuyen_mai', $request->ma_khuyen_mai)
                    ->conHieuLuc()
                    ->first();
                if ($khuyenMai && $giaTriGiam > 0) {
                    $khuyenMaiId = $khuyenMai->id;
                }
            }

            // Tạo đơn đặt vé
            $datVe = DatVe::create([
                'ma_dat_ve' => time() . rand(100, 999),
                'user_id' => Auth::id(),
                'suat_chieu_id' => $request->suat_chieu_id,
                'khuyen_mai_id' => $khuyenMaiId,
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

    public function ketQua($maDatVe)
    {
        // Kiểm tra user đã đăng nhập
        if (!Auth::check()) {
            return redirect()->route('home')->with('error', 'Vui lòng đăng nhập để xem vé!');
        }

        $datVe = DatVe::with([
            'nguoiDung',
            'suatChieu.phim',
            'suatChieu.phongChieu.rapPhim.chiNhanh',
            'gheNgois.loaiGhe',
            'combos.doAns'
        ])
            ->where('ma_dat_ve', $maDatVe)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('client.dat-ve.ket-qua', compact('datVe'));
    }


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

        // Áp dụng khuyến mãi nếu có
        $giaTriGiam = 0;
        if ($request->filled('ma_khuyen_mai')) {
            $khuyenMai = KhuyenMai::where('ma_khuyen_mai', $request->ma_khuyen_mai)
                ->conHieuLuc()
                ->first();

            if ($khuyenMai) {
                // Kiểm tra đơn tối thiểu
                if ($tongTien >= ($khuyenMai->don_toi_thieu ?? 0)) {
                    // Kiểm tra số lần sử dụng
                    if (!$khuyenMai->so_lan_su_dung_toi_da || $khuyenMai->so_lan_da_su_dung < $khuyenMai->so_lan_su_dung_toi_da) {
                        // Tính giảm giá
                        if ($khuyenMai->loai_giam_gia === 'phan_tram') {
                            $giaTriGiam = ($tongTien * $khuyenMai->gia_tri_giam) / 100;
                            if ($khuyenMai->giam_toi_da > 0 && $giaTriGiam > $khuyenMai->giam_toi_da) {
                                $giaTriGiam = $khuyenMai->giam_toi_da;
                            }
                        } else {
                            $giaTriGiam = $khuyenMai->gia_tri_giam;
                        }
                    }
                }
            }
        }

        return [
            'tong_tien_goc' => $tongTien,
            'giam_gia' => $giaTriGiam,
            'tong_tien' => $tongTien - $giaTriGiam
        ];
    }

    public function doiDiem(Request $request)
    {
        $soDiem = (int) $request->input('so_diem');
        $soTien = (int) $request->input('so_tien');
        $user = Auth::user();

        if ($soDiem < 1000 || $soDiem > $user->diem) {
            return response()->json(['message' => 'Số điểm không hợp lệ'], 400);
        }

        return response()->json(['message' => 'Đổi điểm thành công']);
    }
}
