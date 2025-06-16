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
                'ten_loai_phong' => 'Phòng 2D',
                'mo_ta' => 'Loại phòng phổ biến nhất, ghế ngồi tiêu chuẩn, âm thanh cơ bản',
                'phu_thu' => 0,
                'create_at' => Carbon::now(),
                'update_at' => Carbon::now(),
            ],
            [
                'ten_loai_phong' => 'Phòng 3D',
                'mo_ta' => 'Trang bị kính 3D, âm thanh sống động',
                'phu_thu' => 30000,
                'create_at' => Carbon::now(),
                'update_at' => Carbon::now(),
            ],
            [
                'ten_loai_phong' => ' Phòng 4DX',
                'mo_ta' => 'Tích hợp hiệu ứng rung, mùi hương, nước, gió... đồng bộ với phim.',
                'phu_thu' => 50000,
                'create_at' => Carbon::now(),
                'update_at' => Carbon::now(),
            ],
            [
                'ten_loai_phong' => 'Phòng Gold Class',
                'mo_ta' => 'Ghế đôi sofa, phục vụ đồ ăn tận nơi, phòng riêng biệt.',
                'phu_thu' => 70000,
                'create_at' => Carbon::now(),
                'update_at' => Carbon::now(),
            ],
            [
                'ten_loai_phong' => 'Phòng Couple',
                'mo_ta' => 'Ghế đôi không có vách ngăn ở lưng ghế, không gian riêng tư.',
                'phu_thu' =>110000,
                'create_at' => Carbon::now(),
                'update_at' => Carbon::now(),
            ],
            [
                'ten_loai_phong' => 'Phòng Dolby Atmos ',
                'mo_ta' => 'Âm thanh đa chiều, hình ảnh HDR siêu nét.',
                'phu_thu' =>130000,
                'create_at' => Carbon::now(),
                'update_at' => Carbon::now(),
            ],
            [
                'ten_loai_phong' => 'Phòng Onyx',
                'mo_ta' => 'Màn hình LED thay cho máy chiếu, độ tương phản cao, không bị chói.',
                'phu_thu' =>150000,
                'create_at' => Carbon::now(),
                'update_at' => Carbon::now(),
            ],
            [
                'ten_loai_phong' => 'Phòng KIDS',
                'mo_ta' => ' Thiết kế dễ thương, màu sắc vui nhộn, có khu vui chơi.',
                'phu_thu' =>170000,
                'create_at' => Carbon::now(),
                'update_at' => Carbon::now(),
            ],
            [
                'ten_loai_phong' => ' Phòng Premium L’amour ',
                'mo_ta' => 'Có giường nằm đôi, mền gối đầy đủ, phục vụ đồ ăn.',
                'phu_thu' =>190000,
                'create_at' => Carbon::now(),
                'update_at' => Carbon::now(),
            ],
        ]);
    }
}
