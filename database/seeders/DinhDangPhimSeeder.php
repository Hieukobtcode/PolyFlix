<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DinhDangPhimSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('dinh_dang_phims')->insert([
            [
                'ten_dinh_dang' => '2D',
                'mo_ta'         => 'Định dạng hình ảnh tiêu chuẩn',
                'trang_thai'    => 1,
                'create_at'    => now(),
                'update_at'    => now(),
            ],
            [
                'ten_dinh_dang' => '3D',
                'mo_ta'         => 'Hiệu ứng hình ảnh 3 chiều sống động',
                'trang_thai'    => 1,
                'create_at'    => now(),
                'update_at'    => now(),
            ],
            [
                'ten_dinh_dang' => 'IMAX',
                'mo_ta'         => 'Màn hình lớn, âm thanh chất lượng cao',
                'trang_thai'    => 1,
                'create_at'    => now(),
                'update_at'    => now(),
            ],
            [
                'ten_dinh_dang' => '4DX',
                'mo_ta'         => 'Hiệu ứng ghế rung, gió, nước,...',
                'trang_thai'    => 1,
                'create_at'    => now(),
                'update_at'    => now(),
            ],
        ]);
    }
}
