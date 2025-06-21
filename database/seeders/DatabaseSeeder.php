<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\VaiTroSeeder;
use Database\Seeders\CauHinhSeeder;
use Database\Seeders\PhanQuyenSeeder;
use Database\Seeders\VaiTroPhanQuyenSeeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        $this->call([
            BaiVietSeeder::class,
            CauHinhSeeder::class,
            VaiTroSeeder::class,
            PhanQuyenSeeder::class,
            VaiTroPhanQuyenSeeder::class,
            BannerSeeder::class,
            KhuyenMaiSeeder::class,
            ChiNhanhSeeder::class,
            RapSeeder::class,
            LoaiGheSeeder::class,
            LoaiPhongSeeder::class,
            PhuDePhimSeeder::class,
            DinhDangPhimSeeder::class,
            LoaiPhongSeeder::class,
            CapBacTheSeeder::class,
            TheLoaiPhimSeeder::class,
            PhimSeeder::class,

        ]);
    }
}
