<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\DatVe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ThanhToanController extends Controller
{
    /**
     * Hiển thị trang thanh toán
     */
    public function index($datVeId)
    {
        // Kiểm tra user đã đăng nhập và vé thuộc về user này
        if (!Auth::check()) {
            return redirect()->route('login.form')->with('error', 'Vui lòng đăng nhập để thanh toán!');
        }

        $datVe = DatVe::with([
            'nguoiDung',
            'suatChieu.phim',
            'suatChieu.phongChieu.rapPhim.chiNhanh',
            'suatChieu.phongChieu.loaiPhong',
            'gheNgois.loaiGhe',
            'combos',
            'doAns'
        ])->where('id', $datVeId)
            ->where('user_id', Auth::id())
            ->where('trang_thai', 'Chờ thanh toán')
            ->firstOrFail();

        // Tính tổng tiền chi tiết
        $tongTienGhe = 0;
        $giaVeCoBan = 0; // Tạm thời set = 0 để debug vấn đề tính toán
        $phuThuRap = $datVe->suatChieu->phongChieu->rapPhim->phu_thu ?? 0;
        $phuThuLoaiPhong = $datVe->suatChieu->phongChieu->loaiPhong->phu_thu ?? 0;

        foreach ($datVe->gheNgois as $ghe) {
            $phuThuGhe = $ghe->loaiGhe->phu_thu ?? 0;

            // Giá cho 1 ghế = giá cơ bản + phụ thu loại phòng + phụ thu loại ghế
            $giaMotGhe = $giaVeCoBan + $phuThuLoaiPhong + $phuThuGhe;
            $tongTienGhe += $giaMotGhe;
        }

        // Cộng phụ thu rạp CHỈ 1 LẦN cho tất cả ghế
        $tongTienGhe += $phuThuRap;

        $tongTienCombo = 0;
        foreach ($datVe->combos as $combo) {
            $tongTienCombo += $combo->gia * $combo->pivot->so_luong;
        }

        $tongTienDoAn = 0;
        foreach ($datVe->doAns as $doAn) {
            $tongTienDoAn += $doAn->gia * $doAn->pivot->so_luong;
        }

        $tongThanhTien = $tongTienGhe + $tongTienCombo + $tongTienDoAn;

        return view('client.thanh-toan.index', compact(
            'datVe',
            'tongTienGhe',
            'tongTienCombo',
            'tongTienDoAn',
            'tongThanhTien'
        ));
    }

    /**
     * Xử lý thanh toán
     */
    public function xuLyThanhToan(Request $request)
    {
        $request->validate([
            'dat_ve_id' => 'required|exists:dat_ves,id',
            'phuong_thuc_tt' => 'required|in:momo,vnpay,zalopay,banking,cod',
        ]);

        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để thanh toán!'
            ], 401);
        }

        DB::beginTransaction();
        try {
            $datVe = DatVe::where('id', $request->dat_ve_id)
                ->where('user_id', Auth::id())
                ->where('trang_thai', 'Chờ thanh toán')
                ->firstOrFail();

            $phuongThucTt = $request->phuong_thuc_tt;

            // Cập nhật phương thức thanh toán
            $datVe->update([
                'phuong_thuc_tt' => $phuongThucTt
            ]);

            // Xử lý theo từng phương thức thanh toán
            switch ($phuongThucTt) {
                case 'momo':
                    return $this->thanhToanMomo($datVe);

                case 'vnpay':
                    return $this->thanhToanVnpay($datVe);

                case 'zalopay':
                    return $this->thanhToanZaloPay($datVe);

                case 'banking':
                    return $this->thanhToanBanking($datVe);

                case 'cod':
                    return $this->thanhToanTrucTiep($datVe);

                default:
                    throw new \Exception('Phương thức thanh toán không hợp lệ!');
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Lỗi xử lý thanh toán: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Thanh toán qua MoMo
     */
    private function thanhToanMomo($datVe)
    {
        // Sử dụng MoMo Sandbox thực tế
        Log::info('Xử lý thanh toán MoMo cho đặt vé: ' . $datVe->id);

        // Tính tổng tiền chính xác
        $tongTienGhe = 0;
        $giaVeCoBan = 0; // Tạm thời set = 0 để debug vấn đề tính toán
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

        $totalAmount = $tongTienGhe + $tongTienCombo + $tongTienDoAn;

        try {
            // Gọi MomoController để tạo payment
            $momoController = new \App\Http\Controllers\Client\MomoController();
            $momoResponse = $momoController->createPayment($datVe->id, $totalAmount);

            if (isset($momoResponse['payUrl']) && $momoResponse['resultCode'] == 0) {
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Đang chuyển đến cổng thanh toán MoMo...',
                    'payment_url' => $momoResponse['payUrl']
                ]);
            } else {
                throw new \Exception('Không thể tạo link thanh toán MoMo: ' . ($momoResponse['message'] ?? 'Lỗi không xác định'));
            }
        } catch (\Exception $e) {
            Log::error('Lỗi tạo thanh toán MoMo: ' . $e->getMessage());
            throw new \Exception('Có lỗi xảy ra khi kết nối tới MoMo. Vui lòng thử lại sau.');
        }
    }

    /**
     * Thanh toán qua VNPay
     */
    private function thanhToanVnpay($datVe)
    {
        // Mock thanh toán VNPay - thực tế cần tích hợp API VNPay
        Log::info('Xử lý thanh toán VNPay cho đặt vé: ' . $datVe->id);

        $paymentUrl = route('client.thanh-toan.callback', [
            'dat_ve_id' => $datVe->id,
            'method' => 'vnpay',
            'status' => 'success'
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Đang chuyển đến cổng thanh toán VNPay...',
            'payment_url' => $paymentUrl
        ]);
    }

    /**
     * Thanh toán qua ZaloPay
     */
    private function thanhToanZaloPay($datVe)
    {
        // Mock thanh toán ZaloPay - thực tế cần tích hợp API ZaloPay
        Log::info('Xử lý thanh toán ZaloPay cho đặt vé: ' . $datVe->id);

        $paymentUrl = route('client.thanh-toan.callback', [
            'dat_ve_id' => $datVe->id,
            'method' => 'zalopay',
            'status' => 'success'
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Đang chuyển đến cổng thanh toán ZaloPay...',
            'payment_url' => $paymentUrl
        ]);
    }

    /**
     * Thanh toán Banking
     */
    private function thanhToanBanking($datVe)
    {
        // Mock thanh toán Banking - hiển thị thông tin chuyển khoản
        Log::info('Xử lý thanh toán Banking cho đặt vé: ' . $datVe->id);

        // Tính lại tổng tiền để đảm bảo chính xác
        $tongTienGhe = 0;
        $giaVeCoBan = 0; // Tạm thời set = 0 để debug vấn đề tính toán
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

        $tongThanhTien = $tongTienGhe + $tongTienCombo + $tongTienDoAn;

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Vui lòng chuyển khoản theo thông tin bên dưới',
            'payment_method' => 'banking',
            'banking_info' => [
                'bank_name' => 'Ngân hàng Techcombank',
                'account_number' => '19036766589018',
                'account_name' => 'CONG TY TNHH POLYFLIX',
                'amount' => $tongThanhTien,
                'content' => 'POLYFLIX ' . $datVe->ma_dat_ve
            ]
        ]);
    }

    /**
     * Thanh toán trực tiếp (COD)
     */
    private function thanhToanTrucTiep($datVe)
    {
        // Thanh toán trực tiếp tại rạp
        Log::info('Xử lý thanh toán trực tiếp cho đặt vé: ' . $datVe->id);

        $datVe->update([
            'trang_thai' => 'Chờ thanh toán tại quầy'
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Đặt vé thành công! Vui lòng thanh toán tại quầy trước giờ chiếu.',
            'redirect_url' => route('client.dat-ve.ket-qua', $datVe->id)
        ]);
    }

    /**
     * Xử lý callback từ cổng thanh toán
     */
    public function callback(Request $request)
    {
        $datVeId = $request->get('dat_ve_id');
        $method = $request->get('method');
        $status = $request->get('status');

        try {
            $datVe = DatVe::findOrFail($datVeId);

            if ($status === 'success') {
                $datVe->update([
                    'trang_thai' => 'Đã thanh toán'
                ]);

                return redirect()->route('client.dat-ve.ket-qua', $datVe->id)
                    ->with('success', 'Thanh toán thành công!');
            } else {
                $datVe->update([
                    'trang_thai' => 'Thanh toán thất bại'
                ]);

                return redirect()->route('client.thanh-toan.index', $datVe->id)
                    ->with('error', 'Thanh toán thất bại! Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            Log::error('Lỗi callback thanh toán: ' . $e->getMessage());

            return redirect()->route('home')
                ->with('error', 'Có lỗi xảy ra trong quá trình thanh toán!');
        }
    }

    /**
     * Hủy thanh toán
     */
    public function huyThanhToan($datVeId)
    {
        try {
            $datVe = DatVe::where('id', $datVeId)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $datVe->update([
                'trang_thai' => 'Đã hủy'
            ]);

            return redirect()->route('home')
                ->with('success', 'Đã hủy đặt vé thành công!');
        } catch (\Exception $e) {
            Log::error('Lỗi hủy thanh toán: ' . $e->getMessage());

            return redirect()->route('home')
                ->with('error', 'Có lỗi xảy ra khi hủy đặt vé!');
        }
    }
}
