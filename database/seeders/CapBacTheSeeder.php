<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CapBacTheSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('cap_bac_thes')->insert([
            [
                'ten'                => 'Đồng',
                'mo_ta'              => 'Cấp mặc định ban đầu',
                'tong_chi_tieu'      => 0,
                'phan_tram_ve'       => 0,
                'is_default'         => 1,
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
            [
                'ten'                => 'Bạc',
                'mo_ta'              => 'Ưu đãi khi mua từ 20 vé',
                'tong_chi_tieu'      => 1000000,
                'phan_tram_ve'       => 5,
                'is_default'         => 0,
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
            [
                'ten'                => 'Vàng',
                'mo_ta'              => 'Ưu đãi khi mua từ 50 vé',
                'tong_chi_tieu'      => 5000000,
                'phan_tram_ve'       => 10,
                'is_default'         => 0,
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
            [
                'ten'                => 'Kim Cương',
                'mo_ta'              => 'Khách hàng thân thiết nhất',
                'tong_chi_tieu'      => 10000000,
                'phan_tram_ve'       => 15,
                'is_default'         => 0,
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
        ]);
    }
}
