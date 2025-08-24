<?php

namespace App\Http\Controllers\Client;

use Exception;
use App\Models\DatVe;
use App\Models\CapBacThe;
use App\Mail\GuiVeXemPhim;
use App\Models\LichSuDiem;
use Illuminate\Http\Request;
use App\Models\GheNgoiSuatChieu;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;


class ThanhToanController extends Controller
{
    /**
     * Hiển thị trang thanh toán
     */

    public function index($datVeId)
    {
        if (!Auth::check()) {
            return redirect()->route('login.form')->with('error', 'Vui lòng đăng nhập để thanh toán!');
        }
        $query = DatVe::with([
            'nguoiDung',
            'suatChieu.phim',
            'suatChieu.phongChieu.rapPhim.chiNhanh',
            'suatChieu.phongChieu.loaiPhong',
            'gheNgois.loaiGhe',
            'combos',
            'doAns'
        ])
            ->where('id', $datVeId)
            ->where('trang_thai', 'Chờ thanh toán');

        if (Auth::user()->vai_tro_id != 4) {
            $query->where('user_id', Auth::id());
        }

        $datVe = $query->firstOrFail();

        /**
         * Cập nhật trạng thái ghế trong bảng ghe_ngoi_suat_chieus
         * Nếu đã tồn tại thì update, chưa có thì insert
         */
        foreach ($datVe->gheNgois as $ghe) {
            GheNgoiSuatChieu::updateOrCreate(
                [
                    'ghe_ngoi_id'   => $ghe->id,
                    'suat_chieu_id' => $datVe->suatChieu->id
                ],
                [
                    'trang_thai' => 'da_chon'
                ]
            );
        }

        // --- TÍNH TIỀN GHẾ ---
        $tongTienGhe = 0;
        $giaVeCoBan = 0;

        $phuThuRap = $datVe->suatChieu->phongChieu->rapPhim->phu_thu ?? 0;
        $phuThuLoaiPhong = $datVe->suatChieu->phongChieu->loaiPhong->phu_thu ?? 0;

        foreach ($datVe->gheNgois as $ghe) {
            $phuThuGhe = $ghe->loaiGhe->phu_thu ?? 0;
            $giaMotGhe = $giaVeCoBan + $phuThuLoaiPhong + $phuThuGhe;
            $tongTienGhe += $giaMotGhe;
        }

        $tongTienGhe += $phuThuRap;

        $tongTienCombo = 0;
        foreach ($datVe->combos as $combo) {
            $tongTienCombo += $combo->gia * $combo->pivot->so_luong;
        }

        $tongTienDoAn = 0;
        foreach ($datVe->doAns as $doAn) {
            $tongTienDoAn += $doAn->gia * $doAn->pivot->so_luong;
        }

        $tongThanhTien = $datVe->tong_tien;

        return view('client.thanh-toan.index', compact(
            'datVe',
            'tongTienGhe',
            'tongTienCombo',
            'tongTienDoAn',
            'tongThanhTien'
        ));
    }

    public function xuLyThanhToanTienMat(Request $request)
    {
        $request->validate([
            'dat_ve_id' => 'required|exists:dat_ves,id',
        ]);

        $datVe = DatVe::with([
            'nguoiDung',
            'suatChieu.phim',
            'suatChieu.phongChieu.rapPhim.chiNhanh',
            'chiTietDatVes.ghe'
        ])
            ->where('id', $request->dat_ve_id)
            ->where('trang_thai', 'Chờ thanh toán')
            ->firstOrFail();

        foreach ($datVe->gheNgois as $ghe) {
            if ($ghe->trang_thai === 'da_dat') {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Một hoặc nhiều ghế đã được đặt. Vui lòng chọn lại ghế khác.'
                ]);
            }
        }

        try {
            // ✅ 1. Cập nhật trạng thái vé
            $datVe->trang_thai = 'Đã thanh toán';
            $datVe->phuong_thuc_tt = "Tiền mặt";
            $datVe->ngay_thanh_toan = now();
            $datVe->save();

            // ✅ 2. Cập nhật trạng thái ghế
            foreach ($datVe->chiTietDatVes as $chiTiet) {
                $ghe = $chiTiet->ghe;
                if ($ghe) {
                    GheNgoiSuatChieu::where('ghe_ngoi_id', $ghe->id)
                        ->where('suat_chieu_id', $datVe->suat_chieu_id)
                        ->update(['trang_thai' => 'da_dat']);
                }
            }

            // ✅ 3. Gửi mail vé có barcode
            if ($datVe->nguoiDung && $datVe->nguoiDung->email) {
                try {
                    $barcode = new \Milon\Barcode\DNS1D();
                    $barcodeUrl = 'data:image/png;base64,' . $barcode->getBarcodePNG($datVe->ma_dat_ve, 'C128', 2, 60);
                    Mail::to($datVe->nguoiDung->email)->send(new GuiVeXemPhim($datVe, $barcodeUrl));
                } catch (Exception $e) {
                    Log::error('Gửi mail thất bại: ' . $e->getMessage());
                }
            }

            // ✅ 4. Cộng điểm thành viên
            try {
                $nguoiDung = $datVe->nguoiDung;

                if ($nguoiDung && $nguoiDung->cap_bac_id) {
                    // Lấy cấp bậc theo ID từ người dùng
                    $capBac = CapBacThe::find($nguoiDung->cap_bac_id);

                    if ($capBac) {
                        // Tính điểm dựa trên phần trăm vé
                        $tongTien   = $datVe->tong_tien;
                        $phanTramVe = $capBac->phan_tram_ve;
                        $diemCong   = round($tongTien * $phanTramVe / 100);

                        if ($diemCong > 0) {
                            // Cộng điểm vào người dùng
                            $nguoiDung->diem += $diemCong;
                            $nguoiDung->save();

                            // Ghi vào lịch sử điểm
                            LichSuDiem::create([
                                'users_id' => $nguoiDung->id,
                                'thay_doi' => $diemCong,
                                'ly_do'    => 'Cộng điểm từ đơn đặt vé #' . $datVe->ma_dat_ve,
                                'thoi_gian' => now(),
                            ]);

                            Log::info("Đã cộng điểm cho user ID {$nguoiDung->id}, số điểm cộng: {$diemCong}");

                            // ========= Cập nhật cấp bậc mới theo tổng chi tiêu =========
                            $tongTienChiTieu = DatVe::where('user_id', $nguoiDung->id)->sum('tong_tien');
                            Log::info("Tổng chi tiêu của user {$nguoiDung->id}: {$tongTienChiTieu}");

                            $capBacMoi = CapBacThe::where('tong_chi_tieu', '<=', $tongTienChiTieu)
                                ->orderByDesc('tong_chi_tieu')
                                ->first();

                            if ($capBacMoi && $capBacMoi->id !== $nguoiDung->cap_bac_id) {
                                $nguoiDung->cap_bac_id = $capBacMoi->id;
                                $nguoiDung->save();

                                Log::info("Đã cập nhật cấp bậc mới cho user ID {$nguoiDung->id}: {$capBacMoi->ten}");
                            }
                        }
                    } else {
                        Log::warning("Không tìm thấy cấp bậc ID: {$nguoiDung->cap_bac_id}");
                    }
                }
            } catch (Exception $e) {
                Log::error('Lỗi khi cộng điểm: ' . $e->getMessage());
            }

            // return response()->json([
            //     'status' => 'success',
            //     'message' => 'Thanh toán tiền mặt thành công!'
            // ]);
            return redirect()->route('home')->with('success', 'Đặt vé thành công! Vé đã được gửi vào email của bạn.');
        } catch (\Exception $e) {
            Log::error('Thanh toán tiền mặt lỗi: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }




    public function xuLyThanhToan(Request $request)
    {
        $request->validate([
            'dat_ve_id' => 'required|exists:dat_ves,id',
            'phuong_thuc_tt' => 'required|in:zalopay',
        ]);

        $datVe = DatVe::with([
            'nguoiDung',
            'suatChieu.phim',
            'suatChieu.phongChieu.rapPhim.chiNhanh',
        ])
            ->where('id', $request->dat_ve_id)
            ->where('user_id', Auth::id())
            ->where('trang_thai', 'Chờ thanh toán')
            ->firstOrFail();

        foreach ($datVe->gheNgois as $ghe) {
            if ($ghe->trang_thai === 'da_dat') {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Một hoặc nhiều ghế đã được đặt. Vui lòng chọn lại ghế khác.'
                ]);
            }
        }

        // $giaVeCoBan = 0;
        // $phuThuRap = $datVe->suatChieu->phongChieu->rapPhim->phu_thu ?? 0;
        // $phuThuLoaiPhong = $datVe->suatChieu->phongChieu->loaiPhong->phu_thu ?? 0;

        // $tongTienGhe = 0;
        // foreach ($datVe->gheNgois as $ghe) {
        //     $phuThuGhe = $ghe->loaiGhe->phu_thu ?? 0;
        //     $giaMotGhe = $giaVeCoBan + $phuThuLoaiPhong + $phuThuGhe;
        //     $tongTienGhe += $giaMotGhe;
        // }
        // $tongTienGhe += $phuThuRap;

        // $tongTienCombo = 0;
        // foreach ($datVe->combos as $combo) {
        //     $tongTienCombo += $combo->gia * $combo->pivot->so_luong;
        // }

        // $tongTienDoAn = 0;
        // foreach ($datVe->doAns as $doAn) {
        //     $tongTienDoAn += $doAn->gia * $doAn->pivot->so_luong;
        // }

        $tongThanhTien = intval($datVe->tong_tien);

        $embedData = [
            'dat_ve_id' => $datVe->id,
            'nguoi_dung_id' => $datVe->user_id,
            'ten_phim' => $datVe->suatChieu->phim->ten_phim,
            'rap' => $datVe->suatChieu->phongChieu->rapPhim->ten_rap,
            'chi_nhanh' => $datVe->suatChieu->phongChieu->rapPhim->chiNhanh->ten_chi_nhanh ?? '',
            'suat_chieu' => $datVe->suatChieu->bat_dau,
            'tong_tien' => $tongThanhTien,
            'redirecturl' => 'http://127.0.0.1:8000/zalopay/ketqua'
        ];

        $config = [
            "app_id" => 2553,
            "key1" => "PcY4iZIKFCIdgZvA6ueMcMHHUbRLYjPL",
            "key2" => "kLtgPl8HHhfvMuDHPwKfgfsY4Ydm9eIz",
            "endpoint" => "https://sb-openapi.zalopay.vn/v2/create"
        ];

        $transID = rand(100000, 999999);
        $orderID = date("ymd") . "_" . $transID;

        $order = [
            "app_id" => $config["app_id"],
            "app_trans_id" => $orderID,
            "app_time" => round(microtime(true) * 1000),
            "app_user" => $datVe->nguoiDung->id ?? 'guest',
            "item" => json_encode([]),
            "embed_data" => json_encode($embedData),
            "amount" => (int) $tongThanhTien,
            "description" => "Thanh toán vé xem phim - Đơn #$orderID",
            "bank_code" => "",
            "callback_url" => "https://poetic-adder-polite.ngrok-free.app/api/zalopay/callback",
        ];

        $dataMac = implode("|", [
            $order["app_id"],
            $order["app_trans_id"],
            $order["app_user"],
            $order["amount"],
            $order["app_time"],
            $order["embed_data"],
            $order["item"]
        ]);
        $order["mac"] = hash_hmac("sha256", $dataMac, $config["key1"]);

        $context = stream_context_create([
            "http" => [
                "header" => "Content-type: application/x-www-form-urlencoded\r\n",
                "method" => "POST",
                "content" => http_build_query($order),
            ]
        ]);

        $response = file_get_contents($config["endpoint"], false, $context);
        $result = json_decode($response, true);
        Log::info('ZaloPay response: ' . $response);

        if (isset($result['return_code']) && $result['return_code'] == 1) {
            return response()->json([
                'status' => 'success',
                'redirect_url' => $result['order_url']
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Không thể tạo đơn hàng ZaloPay. Vui lòng thử lại.'
            ]);
        }
    }


    public function callBack(Request $request)
    {
        $result = [];

        try {
            $key2 = "kLtgPl8HHhfvMuDHPwKfgfsY4Ydm9eIz";
            $postdata = file_get_contents('php://input');

            Log::info('ZaloPay callback - Raw body:', ['body' => $postdata]);

            $postdatajson = json_decode($postdata, true);

            if (!isset($postdatajson["data"], $postdatajson["mac"])) {
                Log::warning('ZaloPay callback - Thiếu trường dữ liệu', ['postdatajson' => $postdatajson]);

                $result["return_code"] = -1;
                $result["return_message"] = "Thiếu dữ liệu trong callback";
                echo json_encode($result);
                return;
            }

            $mac = hash_hmac("sha256", $postdatajson["data"], $key2);
            $requestmac = $postdatajson["mac"];

            if (strcmp($mac, $requestmac) != 0) {
                Log::error('ZaloPay callback - MAC không khớp', [
                    'expected_mac' => $mac,
                    'received_mac' => $requestmac
                ]);

                $result["return_code"] = -1;
                $result["return_message"] = "MAC không khớp";
            } else {
                $datajson = json_decode($postdatajson["data"], true);
                Log::info('ZaloPay callback - Dữ liệu đã giải mã:', $datajson);

                $embedData = json_decode($datajson["embed_data"], true);
                $datVeId = $embedData["dat_ve_id"] ?? null;

                if ($datVeId) {
                    $datVe = DatVe::with('nguoiDung', 'chiTietDatVes')->where('id', $datVeId)->first();

                    if ($datVe) {
                        $datVe->trang_thai = 'Đã thanh toán';
                        $datVe->phuong_thuc_tt = "ZaloPay";
                        $datVe->ngay_thanh_toan = now();
                        $datVe->save();

                        foreach ($datVe->chiTietDatVes as $chiTiet) {
                            $ghe = $chiTiet->ghe;
                            if ($ghe) {
                                Log::info("Đã cập nhật trạng thái ghế (theo suất): " . $ghe->ma_ghe);

                                // Cập nhật trạng thái trong bảng ghe_ngoi_suat_chieu
                                GheNgoiSuatChieu::where('ghe_ngoi_id', $ghe->id)
                                    ->where('suat_chieu_id', $datVe->suat_chieu_id)
                                    ->update(['trang_thai' => 'da_dat']);
                            }
                        }

                        // Gửi mail xác nhận cho người dùng
                        if ($datVe->nguoiDung && $datVe->nguoiDung->email) {
                            try {
                                $barcode = new \Milon\Barcode\DNS1D();
                                $barcodeUrl = 'data:image/png;base64,' . $barcode->getBarcodePNG($datVe->ma_dat_ve, 'C128', 2, 60);
                                Mail::to($datVe->nguoiDung->email)->send(new GuiVeXemPhim($datVe, $barcodeUrl));
                                Log::info('Đã gửi email xác nhận thanh toán thành công cho: ' . $datVe->nguoiDung->email);
                            } catch (Exception $e) {
                                Log::error('Gửi mail thất bại: ' . $e->getMessage());
                            }

                            try {
                                $nguoiDung = $datVe->nguoiDung;

                                if ($nguoiDung && $nguoiDung->cap_bac_id) {
                                    // Lấy cấp bậc theo ID từ người dùng

                                    $capBac = CapBacThe::find($nguoiDung->cap_bac_id);

                                    if ($capBac) {
                                        // Tính điểm dựa trên phần trăm vé
                                        $tongTien = $datVe->tong_tien;
                                        $phanTramVe = $capBac->phan_tram_ve;
                                        $diemCong = round($tongTien * $phanTramVe / 100);

                                        if ($diemCong > 0) {
                                            // Cộng điểm vào người dùng
                                            $nguoiDung->diem += $diemCong;
                                            $nguoiDung->save();

                                            // Ghi vào lịch sử điểm
                                            LichSuDiem::create([
                                                'users_id' => $nguoiDung->id,
                                                'thay_doi' => $diemCong,
                                                'ly_do' => 'Cộng điểm từ đơn đặt vé #' . $datVe->ma_dat_ve,
                                                'thoi_gian' => now(),
                                            ]);

                                            Log::info('Đã cộng điểm cho người dùng ID ' . $nguoiDung->id . ', số điểm: ' . $diemCong);

                                            // =========

                                            $tongTienChiTieu = DatVe::where('user_id', $nguoiDung->id)->sum('tong_tien');
                                            Log::info('tong chi tieu:' . $tongTienChiTieu);
                                            $capBacMoi = CapBacThe::where('tong_chi_tieu', '<=', $tongTienChiTieu)
                                                ->orderByDesc('tong_chi_tieu')
                                                ->first();

                                            if ($capBacMoi && $capBacMoi->id !== $nguoiDung->cap_bac_id) {
                                                $nguoiDung->cap_bac_id = $capBacMoi->id;
                                                $nguoiDung->save();

                                                Log::info("Đã cập nhật cấp bậc mới cho người dùng ID {$nguoiDung->id}: {$capBacMoi->ten}");
                                            }
                                        }
                                    } else {
                                        Log::warning('Không tìm thấy cấp bậc ID: ' . $nguoiDung->cap_bac_id);
                                    }
                                }
                            } catch (Exception $e) {
                                Log::error('Lỗi khi cộng điểm: ' . $e->getMessage());
                            }
                        }

                        // ========================================

                        Log::info('ZaloPay callback - Cập nhật trạng thái đơn đặt vé thành công', [
                            'dat_ve_id' => $datVeId
                        ]);
                    } else {
                        Log::warning('ZaloPay callback - Không tìm thấy đơn đặt vé phù hợp', [
                            'dat_ve_id' => $datVeId
                        ]);
                    }
                } else {
                    Log::warning('ZaloPay callback - Không có dat_ve_id trong embedData', ['embedData' => $embedData]);
                }

                $result["return_code"] = 1;
                $result["return_message"] = "success";
            }
        } catch (Exception $e) {
            Log::error('ZaloPay callback - Exception xảy ra', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $result["return_code"] = 0;
            $result["return_message"] = $e->getMessage();
        }

        echo json_encode($result);
    }


    public function ketQuaThanhToan(Request $request)
    {
        return redirect()->route('home')->with('success', 'Đặt vé thành công! Vé sẽ được gửi qua email.');
    }

    /**
     * Hủy thanh toán và mở khóa ghế
     */
    public function huyThanhToan($datVeId)
    {
        if (!Auth::check()) {
            return redirect()->route('login.form')->with('error', 'Vui lòng đăng nhập!');
        }

        $datVe = DatVe::with([
            'gheNgois',
            'suatChieu'
        ])
            ->where('id', $datVeId)
            ->where('user_id', Auth::id())
            ->where('trang_thai', 'Chờ thanh toán')
            ->first();

        if (!$datVe) {
            return redirect()->route('home')->with('error', 'Không tìm thấy đơn đặt vé hoặc đơn đã được xử lý.');
        }

        try {
            // Cập nhật trạng thái đơn đặt vé thành "Đã hủy"
            $datVe->update([
                'trang_thai' => 'Đã hủy',
                'ghi_chu' => 'Hủy đơn vào lúc: ' . now()->format('d/m/Y H:i:s')
            ]);

            // Mở khóa tất cả ghế trong đơn đặt vé
            foreach ($datVe->gheNgois as $ghe) {
                try {
                    DB::statement("
                UPDATE ghe_ngoi_suat_chieu
                SET trang_thai = 'trong', user_id = NULL
                WHERE ghe_ngoi_id = ? AND suat_chieu_id = ?
            ", [
                        // dùng pivot nếu là belongsToMany
                        $ghe->pivot->ghe_ngoi_id ?? $ghe->id,
                        $datVe->suat_chieu_id
                    ]);
                } catch (\Exception $e) {
                    Log::warning('Không thể mở khóa ghế ID: ' . ($ghe->pivot->ghe_ngoi_id ?? $ghe->id) . ' - ' . $e->getMessage());
                }
            }

            return redirect()->route('home')->with('success', 'Đã hủy đơn đặt vé thành công. Ghế đã được mở khóa.');
        } catch (Exception $e) {
            Log::error('Lỗi khi hủy thanh toán: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi hủy đơn đặt vé. Vui lòng thử lại.');
        }
    }
}
