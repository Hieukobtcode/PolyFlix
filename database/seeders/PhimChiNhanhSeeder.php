<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhimChiNhanhSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy tất cả phim và chi nhánh
        $phims = DB::table('phims')->where('trang_thai', 1)->pluck('id');
        $chiNhanhs = DB::table('chi_nhanhs')->where('trang_thai', 'hoat_dong')->pluck('id');

        if ($phims->isEmpty() || $chiNhanhs->isEmpty()) {
            $this->command->info('Không có dữ liệu phim hoặc chi nhánh');
            return;
        }

        $data = [];

        // Liên kết mỗi phim với tất cả chi nhánh (hoặc random một số chi nhánh)
        foreach ($phims as $phimId) {
            // Chọn random 3-5 chi nhánh cho mỗi phim
            $randomChiNhanhs = $chiNhanhs->random(rand(3, min(5, $chiNhanhs->count())));

            foreach ($randomChiNhanhs as $chiNhanhId) {
                $data[] = [
                    'phim_id' => $phimId,
                    'chi_nhanh_id' => $chiNhanhId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert batch để tăng hiệu suất
        DB::table('phim_chi_nhanh')->insert($data);

        $this->command->info('Đã tạo ' . count($data) . ' liên kết phim-chi nhánh');
    }
}
