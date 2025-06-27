<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PhuDePhim;

class PhuDePhimSeeder extends Seeder
{
    public function run(): void
    {
        $phuDeList = [
            [
                'ten_phu_de' => 'Tiếng Việt',
                'mo_ta' => 'Phụ đề dịch sang tiếng Việt.',
                'trang_thai' => 1,
            ],
            [
                'ten_phu_de' => 'Tiếng Anh',
                'mo_ta' => 'Phụ đề tiếng Anh dành cho người học hoặc người nước ngoài.',
                'trang_thai' => 1,
            ],
            [
                'ten_phu_de' => 'Song ngữ Việt - Anh',
                'mo_ta' => 'Phụ đề song ngữ hiển thị cả tiếng Việt và tiếng Anh.',
                'trang_thai' => 1,
            ],
            [
                'ten_phu_de' => 'Tiếng Nhật',
                'mo_ta' => 'Phụ đề dịch sang tiếng Nhật.',
                'trang_thai' => 1,
            ],
            [
                'ten_phu_de' => 'Lồng Tiếng',
                'mo_ta' => 'Phim gốc không có phụ đề.',
                'trang_thai' => 1,
            ],
        ];

        foreach ($phuDeList as $item) {
            PhuDePhim::create($item);
        }
    }
}