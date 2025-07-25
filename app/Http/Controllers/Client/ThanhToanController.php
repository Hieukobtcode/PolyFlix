<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\GuiVeXemPhim;
use App\Models\CapBacThe;
use App\Models\DatVe;
use App\Models\LichSuDiem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class ThanhToanController extends Controller
{
    /**
     * Hiển thị trang thanh toán
     */
    public function index($datVeId)
    {
        // Kiểm tra người dùng đã đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login.form')->with('error', 'Vui lòng đăng nhập để thanh toán!');
        }

        // Lấy thông tin đặt vé, kèm các quan hệ cần thiết
        $datVe = DatVe::with([
            'nguoiDung',
            'suatChieu.phim',
            'suatChieu.phongChieu.rapPhim.chiNhanh',
            'suatChieu.phongChieu.loaiPhong',
            'gheNgois.loaiGhe',
            'combos',
            'doAns'
        ])
            ->where('id', $datVeId)
            ->where('user_id', Auth::id())
            ->where('trang_thai', 'Chờ thanh toán')
            ->firstOrFail();

        foreach ($datVe->gheNgois as $ghe) {
            if ($ghe->trang_thai === 'da_dat') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Một hoặc nhiều ghế đã được đặt. Vui lòng chọn lại ghế khác.'
                ]);
            }
        }

        // --- TÍNH TIỀN GHẾ ---
        $tongTienGhe = 0;
        $giaVeCoBan = 0; // Tạm thời set 0, sau có thể cấu hình từ DB hoặc biến cấu hình

        // Phụ thu theo rạp và loại phòng
        $phuThuRap = $datVe->suatChieu->phongChieu->rapPhim->phu_thu ?? 0;
        $phuThuLoaiPhong = $datVe->suatChieu->phongChieu->loaiPhong->phu_thu ?? 0;

        // Duyệt qua từng ghế được chọn
        foreach ($datVe->gheNgois as $ghe) {
            $phuThuGhe = $ghe->loaiGhe->phu_thu ?? 0;

            // Tính giá cho từng ghế
            $giaMotGhe = $giaVeCoBan + $phuThuLoaiPhong + $phuThuGhe;

            $tongTienGhe += $giaMotGhe;
        }

        // Phụ thu rạp chỉ tính một lần
        $tongTienGhe += $phuThuRap;

        // --- TÍNH TIỀN COMBO ---
        $tongTienCombo = 0;
        foreach ($datVe->combos as $combo) {
            $tongTienCombo += $combo->gia * $combo->pivot->so_luong;
        }

        // --- TÍNH TIỀN ĐỒ ĂN ---
        $tongTienDoAn = 0;
        foreach ($datVe->doAns as $doAn) {
            $tongTienDoAn += $doAn->gia * $doAn->pivot->so_luong;
        }

        // --- TỔNG THANH TOÁN ---
        $tongThanhTien = $datVe->tong_tien;

        // Trả về view thanh toán với các dữ liệu cần thiết
        return view('client.thanh-toan.index', compact(
            'datVe',
            'tongTienGhe',
            'tongTienCombo',
            'tongTienDoAn',
            'tongThanhTien'
        ));
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
                                Log::info("Đã cập nhật trạng thái ghế: " . $ghe->ma_ghe);
                                $ghe->trang_thai = 'da_dat';
                                $ghe->save();
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
}
