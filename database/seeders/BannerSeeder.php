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
                'hinh_anh'    => 'banner/banner5.jpg',
                'trang_thai'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'hinh_anh'    => 'banner/banner6.jpg',
                'trang_thai'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'hinh_anh'    => 'banner/banner7.jpg',
                'trang_thai'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'hinh_anh'    => 'banner/banner8.jpg',
                'trang_thai'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'hinh_anh'    => 'banner/banner9.jpg',
                'trang_thai'  => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
