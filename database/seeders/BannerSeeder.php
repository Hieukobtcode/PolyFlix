<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('banners')->insert([
            [
                'hinh_anh'    => 'banner/28-year-later-2048_1750407231251.jpg',
                'trang_thai'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'hinh_anh'    => 'banner/980x448_3_1.png',
                'trang_thai'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'hinh_anh'    => 'banner/980x448_290.jpg',
                'trang_thai'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'hinh_anh'    => 'banner/bi-kip-luyen-rong-2048_1749195168873.jpg',
                'trang_thai'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'hinh_anh'    => 'banner/ut-lan-2048_1750048449368.jpg',
                'trang_thai'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
