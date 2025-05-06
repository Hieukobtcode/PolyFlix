<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('banners')->insert([
            [
                'hinh_anh' => 'https://intphcm.com/data/upload/mau-banner-quan-ao.jpg',
                'trang_thai' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'hinh_anh' => 'https://thietkewebchuyen.com/wp-content/uploads/thiet-ke-banner-website-anh-bia-Facebook-shop-quan-ao-nam-nu-2.jpg',
                 'trang_thai' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
