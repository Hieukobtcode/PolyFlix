<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy ID của các khuyến mãi đã tạo
        $khuyenMaiIds = DB::table('khuyen_mais')->pluck('id')->toArray();

        if (!empty($khuyenMaiIds)) {
            $data = [];

            // Tạo dữ liệu rạp
            foreach ($khuyenMaiIds as $khuyenMaiId) {
                $data[] = [
                    'khuyen_mai_id' => $khuyenMaiId,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            DB::table('raps')->insert($data);
        }
    }
}
