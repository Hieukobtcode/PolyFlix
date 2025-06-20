<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
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
                'ma_khuyen_mai'           => 'KM10VE',
                'ten'                     => 'Giảm 10% vé xem phim',
                'mo_ta'                   => 'Khuyến mãi giảm giá 10% cho vé xem phim.',
                'loai_giam_gia'           => 'phan_tram',
                'gia_tri_giam'            => 100,
                'giam_toi_da'             => 50000,
                'ap_dung_cho'             => 've',
                'don_toi_thieu'           => 100000,
                'ngay_bat_dau'            => Carbon::now()->subDays(5),
                'ngay_ket_thuc'           => Carbon::now()->addDays(10),
                'so_lan_su_dung_toi_da'   => 100,
                'so_lan_da_su_dung'       => 0,
                'trang_thai'              => 'hoat_dong',
                'create_at'               => Carbon::now(),
                'update_at'               => Carbon::now(),
            ],
            [
                'ma_khuyen_mai'           => 'KM50DOAN',
                'ten'                     => 'Giảm 50K đồ ăn',
                'mo_ta'                   => 'Khuyến mãi giảm 50K cho đồ ăn khi mua từ 200K.',
                'loai_giam_gia'           => 'tien',
                'gia_tri_giam'            => 5000,
                'giam_toi_da'             => null,
                'ap_dung_cho'             => 'do_an',
                'don_toi_thieu'           => 200000,
                'ngay_bat_dau'            => Carbon::now(),
                'ngay_ket_thuc'           => Carbon::now()->addDays(15),
                'so_lan_su_dung_toi_da'   => 50,
                'so_lan_da_su_dung'       => 0,
                'trang_thai'              => 'hoat_dong',
                'create_at'               => Carbon::now(),
                'update_at'               => Carbon::now(),
            ],
            [
                'ma_khuyen_mai'           => 'KMTONGHOP',
                'ten'                     => 'Giảm 20% toàn bộ',
                'mo_ta'                   => 'Khuyến mãi giảm 20% cho cả vé và đồ ăn.',
                'loai_giam_gia'           => 'phan_tram',
                'gia_tri_giam'            => 200,
                'giam_toi_da'             => 100000,
                'ap_dung_cho'             => 'tat_ca',
                'don_toi_thieu'           => 150000,
                'ngay_bat_dau'            => Carbon::now()->subDays(2),
                'ngay_ket_thuc'           => Carbon::now()->addDays(7),
                'so_lan_su_dung_toi_da'   => null,
                'so_lan_da_su_dung'       => 0,
                'trang_thai'              => 'tam_dung',
                'create_at'               => Carbon::now(),
                'update_at'               => Carbon::now(),
            ],
        ]);
    }
}
