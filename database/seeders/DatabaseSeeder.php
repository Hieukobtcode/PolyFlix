<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
<<<<<<< HEAD
        
       $this->call([
        BannerSeeder::class,
        DatVesTableSeeder::class, // <-- thêm dòng này
    ]);
=======
        $this->call([
            BannerSeeder::class,
            CauHinhSeeder::class,
            PhanQuyenSeeder::class,
            UserSeeder::class,
        ]);
>>>>>>> 46d6e9d9a7b665af7ad487198df802ef758e171b
    }
}
