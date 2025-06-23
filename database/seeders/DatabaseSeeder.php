<?php

namespace Database\Seeders;

use App\Models\Rating;
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
            UserSeeder::class,
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
            PhongChieuSeeder::class,
            PhuDePhimSeeder::class,
            DinhDangPhimSeeder::class,
            LoaiPhongSeeder::class,
            CapBacTheSeeder::class,
            TheLoaiPhimSeeder::class,
            PhimSeeder::class,
            CommentSeeder::class,
            RatingSeeder::class,
        ]);
    }
}
