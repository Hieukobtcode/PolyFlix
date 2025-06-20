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
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Gọi các seeder để tạo dữ liệu mẫu


        $this->call([
            KhuyenMaiSeeder::class,
            // BannerSeeder::class,
            // LoaiPhongSeeder::class,
            // CauHinhSeeder::class,
            // VaiTroSeeder::class,
            // PhanQuyenSeeder::class,
            // VaiTroPhanQuyenSeeder::class,
            // UserSeeder::class,
            // TheLoaiPhimSeeder::class,
            // DinhDangPhimSeeder::class,
            // PhuDePhimSeeder::class,
        ]);
    }
}