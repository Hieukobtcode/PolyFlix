<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LoaiPhongSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('loai_phongs')->insert([
            [
                'ten_loai_phong' => '2D',
                'mo_ta' => 'Loại phòng phổ biến nhất, ghế ngồi tiêu chuẩn, âm thanh cơ bản',
                'phu_thu' => 0,
                'create_at' => Carbon::now(),
                'update_at' => Carbon::now(),
            ],
            [
                'ten_loai_phong' => '3D',
                'mo_ta' => 'Trang bị kính 3D, âm thanh sống động',
                'phu_thu' => 30000,
                'create_at' => Carbon::now(),
                'update_at' => Carbon::now(),
            ],
            [
                'ten_loai_phong' => '4DX',
                'mo_ta' => 'Tích hợp hiệu ứng rung, mùi hương, nước, gió... đồng bộ với phim.',
                'phu_thu' => 50000,
                'create_at' => Carbon::now(),
                'update_at' => Carbon::now(),
            ],
            [
                'ten_loai_phong' => 'Gold Class',
                'mo_ta' => 'Ghế đôi sofa, phục vụ đồ ăn tận nơi, phòng riêng biệt.',
                'phu_thu' => 70000,
                'create_at' => Carbon::now(),
                'update_at' => Carbon::now(),
            ],
            [
                'ten_loai_phong' => 'Couple',
                'mo_ta' => 'Ghế đôi không có vách ngăn ở lưng ghế, không gian riêng tư.',
                'phu_thu' =>110000,
                'create_at' => Carbon::now(),
                'update_at' => Carbon::now(),
            ],
        ]);
    }
}
