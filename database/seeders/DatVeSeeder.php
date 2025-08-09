<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\SuatChieu;
use App\Models\GheNgoi;
use App\Models\Combo;
use App\Models\DoAn;
use App\Models\KhuyenMai;

class DatVeSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy dữ liệu cần thiết
        $users = User::where('vai_tro_id', '!=', 1)->get(); // Không lấy admin
        $suatChieus = SuatChieu::with(['phongChieu'])->get();
        $combos = Combo::where('trang_thai', 1)->get();
        $doAns = DoAn::where('trang_thai', 1)->get();
        $khuyenMais = KhuyenMai::where('trang_thai', 'hoat_dong')
            ->where('ngay_bat_dau', '<=', now())
            ->where('ngay_ket_thuc', '>=', now())
            ->get();

        if ($users->isEmpty() || $suatChieus->isEmpty()) {
            $this->command->info('Không có dữ liệu user hoặc suất chiếu để tạo đơn hàng');
            return;
        }

        $trangThais = ['Đã thanh toán', 'Chờ thanh toán', 'Đã hủy', 'Đã xuất vé'];
        $phuongThucTTs = ['ZaloPay', 'VNPay', 'MoMo', 'Banking', 'COD'];

        $soLuongDonHang = 100; // Tạo 100 đơn hàng
        $donHangDaTao = 0;

        for ($i = 0; $i < $soLuongDonHang; $i++) {
            try {
                // Chọn ngẫu nhiên user và suất chiếu
                $user = $users->random();
                $suatChieu = $suatChieus->random();

                // Lấy ghế của phòng chiếu này
                $gheNgois = GheNgoi::where('phong_chieu_id', $suatChieu->phong_chieu_id)
                    ->where('trang_thai', 'trong')
                    ->get();

                if ($gheNgois->isEmpty()) {
                    continue; // Bỏ qua nếu không có ghế trống
                }

                // Chọn ngẫu nhiên 1-4 ghế
                $soGhe = rand(1, min(4, $gheNgois->count()));
                $gheChon = $gheNgois->random($soGhe);

                // Tính thời gian đặt vé (trong 30 ngày gần đây)
                $ngayDat = Carbon::now()->subDays(rand(0, 30));

                // Tạo mã đặt vé
                $maDatVe = 'DV' . str_pad($i + 1, 6, '0', STR_PAD_LEFT);

                // Chọn khuyến mãi (30% có khuyến mãi)
                $khuyenMai = null;
                if (rand(1, 100) <= 30 && !$khuyenMais->isEmpty()) {
                    $khuyenMai = $khuyenMais->random();
                }

                // Tính giá vé cơ bản
                $giaVeCoBan = 75000; // Giá vé cơ bản
                $tongTienVe = 0;

                foreach ($gheChon as $ghe) {
                    // Tính phụ thu theo loại ghế
                    $phuThu = 0;
                    if ($ghe->loai_ghe === 'VIP') {
                        $phuThu = 20000;
                    } elseif ($ghe->loai_ghe === 'Couple') {
                        $phuThu = 30000;
                    }
                    $tongTienVe += $giaVeCoBan + $phuThu;
                }

                // Tính tiền combo/đồ ăn (50% đơn hàng có combo/đồ ăn)
                $tongTienCombo = 0;
                $combosChon = collect();
                $doAnsChon = collect();

                if (rand(1, 100) <= 50) {
                    // 30% chọn combo, 20% chọn đồ ăn lẻ
                    if (rand(1, 100) <= 60 && !$combos->isEmpty()) {
                        // Chọn combo
                        $combo = $combos->random();
                        $soLuongCombo = rand(1, 2);
                        $combosChon->push([
                            'combo' => $combo,
                            'so_luong' => $soLuongCombo
                        ]);
                        $tongTienCombo += $combo->gia * $soLuongCombo;
                    } else if (!$doAns->isEmpty()) {
                        // Chọn đồ ăn lẻ
                        $soMonAn = rand(1, 3);
                        $doAnChon = $doAns->random(min($soMonAn, $doAns->count()));
                        foreach ($doAnChon as $doAn) {
                            $soLuong = rand(1, 2);
                            $doAnsChon->push([
                                'do_an' => $doAn,
                                'so_luong' => $soLuong
                            ]);
                            $tongTienCombo += $doAn->gia * $soLuong;
                        }
                    }
                }

                // Tính tổng tiền trước giảm giá
                $tongTienTruocGiam = $tongTienVe + $tongTienCombo;

                // Áp dụng khuyến mãi
                $tienGiam = 0;
                if ($khuyenMai) {
                    if ($khuyenMai->loai_khuyen_mai === 'phan_tram') {
                        $tienGiam = $tongTienTruocGiam * ($khuyenMai->gia_tri / 100);
                        $tienGiam = min($tienGiam, $khuyenMai->gia_tri_toi_da ?? $tienGiam);
                    } else {
                        $tienGiam = $khuyenMai->gia_tri;
                    }
                }

                $tongTien = $tongTienTruocGiam - $tienGiam;

                // Chọn trạng thái và phương thức thanh toán
                $trangThai = $trangThais[array_rand($trangThais)];
                $phuongThucTT = $phuongThucTTs[array_rand($phuongThucTTs)];

                // Tạo thông tin thanh toán
                $maGiaoDich = null;
                $ngayThanhToan = null;
                $ghiChu = null;

                if ($trangThai === 'Đã thanh toán' || $trangThai === 'Đã xuất vé') {
                    $maGiaoDich = 'TXN' . time() . rand(1000, 9999);
                    $ngayThanhToan = $ngayDat->addMinutes(rand(5, 60));
                    $ghiChu = 'Thanh toán thành công qua ' . $phuongThucTT;
                } elseif ($trangThai === 'Đã hủy') {
                    $ghiChu = 'Đơn hàng bị hủy do khách hàng không thanh toán';
                }

                // Tạo đơn đặt vé
                $datVeId = DB::table('dat_ves')->insertGetId([
                    'ma_dat_ve' => $maDatVe,
                    'user_id' => $user->id,
                    'suat_chieu_id' => $suatChieu->id,
                    'khuyen_mai_id' => $khuyenMai ? $khuyenMai->id : null,
                    'tong_tien' => $tongTien,
                    'phuong_thuc_tt' => $phuongThucTT,
                    'trang_thai' => $trangThai,
                    'ma_giao_dich' => $maGiaoDich,
                    'ngay_thanh_toan' => $ngayThanhToan,
                    'ghi_chu' => $ghiChu,
                    'created_at' => $ngayDat,
                    'updated_at' => $ngayDat,
                ]);

                // Tạo chi tiết đặt vé (ghế)
                foreach ($gheChon as $ghe) {
                    $phuThu = 0;
                    if ($ghe->loai_ghe === 'VIP') {
                        $phuThu = 20000;
                    } elseif ($ghe->loai_ghe === 'Couple') {
                        $phuThu = 30000;
                    }

                    DB::table('chi_tiet_dat_ves')->insert([
                        'dat_ve_id' => $datVeId,
                        'ghe_id' => $ghe->id,
                        'gia_ve' => $giaVeCoBan + $phuThu,
                    ]);

                    // Cập nhật trạng thái ghế nếu đã thanh toán
                    if ($trangThai === 'Đã thanh toán' || $trangThai === 'Đã xuất vé') {
                        DB::table('ghe_ngois')->where('id', $ghe->id)->update([
                            'trang_thai' => 'da_dat'
                        ]);
                    }
                }

                // Tạo chi tiết combo
                foreach ($combosChon as $comboData) {
                    DB::table('dat_ve_combo')->insert([
                        'dat_ve_id' => $datVeId,
                        'combo_id' => $comboData['combo']->id,
                        'so_luong' => $comboData['so_luong'],
                        'created_at' => $ngayDat,
                        'updated_at' => $ngayDat,
                    ]);
                }

                // Tạo chi tiết đồ ăn
                foreach ($doAnsChon as $doAnData) {
                    DB::table('dat_ve_do_an')->insert([
                        'dat_ve_id' => $datVeId,
                        'do_an_id' => $doAnData['do_an']->id,
                        'so_luong' => $doAnData['so_luong'],
                        'created_at' => $ngayDat,
                        'updated_at' => $ngayDat,
                    ]);
                }

                $donHangDaTao++;
            } catch (\Exception $e) {
                $this->command->warn("Lỗi tạo đơn hàng $i: " . $e->getMessage());
                continue;
            }
        }

        $this->command->info("Đã tạo thành công $donHangDaTao đơn hàng!");
    }
}
