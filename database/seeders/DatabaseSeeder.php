<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\VaiTroSeeder;
use Database\Seeders\CauHinhSeeder;
use Database\Seeders\PhanQuyenSeeder;
use Database\Seeders\VaiTroPhanQuyenSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Gọi các seeder để tạo dữ liệu mẫu
        

        $this->call([
            CauHinhSeeder::class,
            VaiTroSeeder::class,
            PhanQuyenSeeder::class,
            VaiTroPhanQuyenSeeder::class,
            UserSeeder::class,
        ]);
    }
}
