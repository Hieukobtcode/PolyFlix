<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KhuyenMaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('khuyen_mais')->insert([
            [
                'ma_khuyen_mai' => 'KM001',
                'ten' => 'Giảm giá 10% vé xem phim',
                'mo_ta' => 'Giảm giá 10% cho tất cả các vé xem phim',
                'loai_giam_gia' => 'phan_tram',
                'gia_tri_giam' => 10.00,
                'giam_toi_da' => 50000.00,
                'ap_dung_cho' => 've',
                'don_toi_thieu' => 0.00,
                'ngay_bat_dau' => now(),
                'ngay_ket_thuc' => now()->addMonths(1),
                'so_lan_su_dung_toi_da' => 100,
                'so_lan_da_su_dung' => 0,
                'trang_thai' => 'hoat_dong',
                'create_at' => now(),
                'update_at' => now()
            ],
            [
                'ma_khuyen_mai' => 'KM002',
                'ten' => 'Giảm 20.000đ đồ ăn',
                'mo_ta' => 'Giảm 20.000đ cho hóa đơn đồ ăn từ 100.000đ',
                'loai_giam_gia' => 'tien',
                'gia_tri_giam' => 20000.00,
                'giam_toi_da' => null,
                'ap_dung_cho' => 'do_an',
                'don_toi_thieu' => 100000.00,
                'ngay_bat_dau' => now(),
                'ngay_ket_thuc' => now()->addMonths(2),
                'so_lan_su_dung_toi_da' => 200,
                'so_lan_da_su_dung' => 0,
                'trang_thai' => 'hoat_dong',
                'create_at' => now(),
                'update_at' => now()
            ],
            [
                'ma_khuyen_mai' => 'KM003',
                'ten' => 'Giảm 15% tất cả',
                'mo_ta' => 'Giảm 15% cho tất cả dịch vụ',
                'loai_giam_gia' => 'phan_tram',
                'gia_tri_giam' => 15.00,
                'giam_toi_da' => 100000.00,
                'ap_dung_cho' => 'tat_ca',
                'don_toi_thieu' => 200000.00,
                'ngay_bat_dau' => now(),
                'ngay_ket_thuc' => now()->addMonths(1),
                'so_lan_su_dung_toi_da' => 50,
                'so_lan_da_su_dung' => 0,
                'trang_thai' => 'hoat_dong',
                'create_at' => now(),
                'update_at' => now()
            ]
        ]);
    }
}
