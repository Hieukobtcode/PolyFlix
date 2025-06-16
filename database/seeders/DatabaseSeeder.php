<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
   
    public function run(): void
    {
      
        $this->call([
            LoaiGheSeeder::class,
            PhanQuyenSeeder::class,
            // UserSeeder::class,
            // CauHinhSeeder::class,
            // BannerSeeder::class,
        ]);
    }
}
