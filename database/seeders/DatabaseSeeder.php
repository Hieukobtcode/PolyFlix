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
            BaiVietSeeder::class,
            CauHinhSeeder::class,
            VaiTroSeeder::class,
            PhanQuyenSeeder::class,
            VaiTroPhanQuyenSeeder::class,
            UserSeeder::class,
            BaiVietSeeder::class,
            CauHinhSeeder::class,
            BannerSeeder::class,
            UserSeeder::class,
            KhuyenMaiSeeder::class,
            ChiNhanhSeeder::class,
            RapSeeder::class,
            LoaiGheSeeder::class,
            LoaiPhongSeeder::class,
            PhongChieuSeeder::class,
            GheNgoiSeeder::class,
            PhuDePhimSeeder::class,
            DinhDangPhimSeeder::class,
            // CapBacTheSeeder::class,
            TheLoaiPhimSeeder::class,
            PhimSeeder::class,
            SuatChieuSeeder::class,
            DoAnSeeder::class,
            ComboSeeder::class,
            DatVeSeeder::class,
            CommentSeeder::class,
            RatingSeeder::class,
            // php artisan db:seed --class=PhongChieuSeeder
        ]);
    }
}
