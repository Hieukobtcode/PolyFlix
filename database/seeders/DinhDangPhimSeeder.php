<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DinhDangPhim;

class DinhDangPhimSeeder extends Seeder
{
    public function run(): void
    {
        $dinhDangList = [
            [
                'ten_dinh_dang' => '2D',
                'mo_ta' => 'Định dạng tiêu chuẩn với hình ảnh hai chiều.',
                'trang_thai' => 1,
            ],
            [
                'ten_dinh_dang' => '3D',
                'mo_ta' => 'Định dạng ba chiều, sử dụng kính để tạo hiệu ứng nổi.',
                'trang_thai' => 1,
            ],
            [
                'ten_dinh_dang' => 'IMAX',
                'mo_ta' => 'Định dạng với màn hình cực lớn, âm thanh sống động.',
                'trang_thai' => 1,
            ],
            [
                'ten_dinh_dang' => '4DX',
                'mo_ta' => 'Tích hợp chuyển động ghế, hiệu ứng gió, mùi,...',
                'trang_thai' => 1,
            ],
            [
                'ten_dinh_dang' => 'HD',
                'mo_ta' => 'Hình ảnh chất lượng cao độ phân giải 720p trở lên.',
                'trang_thai' => 1,
            ],
            [
                'ten_dinh_dang' => 'Full HD',
                'mo_ta' => 'Độ phân giải 1080p cho hình ảnh sắc nét.',
                'trang_thai' => 1,
            ],
            [
                'ten_dinh_dang' => '4K',
                'mo_ta' => 'Siêu nét với độ phân giải 4K UHD.',
                'trang_thai' => 1,
            ],
        ];

        foreach ($dinhDangList as $item) {
            DinhDangPhim::create($item);
        }
    }
}