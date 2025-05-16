<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KhuyenMaiChiNhanhSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy ID của các khuyến mãi đã tạo
        $khuyenMaiIds = DB::table('khuyen_mais')->pluck('id')->toArray();

        // Lấy ID của các chi nhánh đã tạo
        $chiNhanhIds = DB::table('chi_nhanhs')->pluck('id')->toArray();

        // Nếu có cả khuyến mãi và chi nhánh
        if (!empty($khuyenMaiIds) && !empty($chiNhanhIds)) {
            $data = [];

            // Tạo dữ liệu khuyến mãi chi nhánh
            foreach ($khuyenMaiIds as $khuyenMaiId) {
                // Mỗi khuyến mãi áp dụng cho 2 chi nhánh ngẫu nhiên
                $randomChiNhanhs = array_rand(array_flip($chiNhanhIds), min(2, count($chiNhanhIds)));

                if (!is_array($randomChiNhanhs)) {
                    $randomChiNhanhs = [$randomChiNhanhs];
                }

                foreach ($randomChiNhanhs as $chiNhanhId) {
                    $data[] = [
                        'khuyen_mai_id' => $khuyenMaiId,
                        'chi_nhanh_id' => $chiNhanhId,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }

            DB::table('khuyen_mai_chi_nhanhs')->insert($data);
        }
    }
}
