<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\ChiNhanh;

class ChiNhanhSeeder extends Seeder
{
    public function run(): void
    {
        $chiNhanhs = [
            [
                'id' => 1,
                'ten_chi_nhanh' => 'Chi nhánh Hà Nội',
                'dia_chi' => 'Hà Nội - Việt Nam',
                'quan_ly_id' => null,
                'trang_thai' => 1,
            ],
            [
                'id' => 2,
                'ten_chi_nhanh' => 'Chi nhánh TP.HCM',
                'dia_chi' => 'TP.HCM - Việt Nam',
                'quan_ly_id' => null,
                'trang_thai' => 1,
            ],
            [
                'id' => 3,
                'ten_chi_nhanh' => 'Chi nhánh Đà Nẵng',
                'dia_chi' => 'Đà Nẵng - Việt Nam',
                'quan_ly_id' => null,
                'trang_thai' => 1,
            ],
            [
                'id' => 4,
                'ten_chi_nhanh' => 'Chi nhánh Hải Phòng',
                'dia_chi' => 'Hải Phòng - Việt Nam',
                'quan_ly_id' => null,
                'trang_thai' => 1,
            ],
            [
                'id' => 5,
                'ten_chi_nhanh' => 'Chi nhánh Cần Thơ',
                'dia_chi' => 'Cần Thơ - Việt Nam',
                'quan_ly_id' => null,
                'trang_thai' => 1,
            ],
            [
                'id' => 6,
                'ten_chi_nhanh' => 'Chi nhánh Quảng Ninh',
                'dia_chi' => 'Quảng Ninh - Việt Nam',
                'quan_ly_id' => null,
                'trang_thai' => 1,
            ],
            [
                'id' => 7,
                'ten_chi_nhanh' => 'Chi nhánh Đà Lạt',
                'dia_chi' => 'Đà Lạt - Việt Nam',
                'quan_ly_id' => null,
                'trang_thai' => 1,
            ],
            [
                'id' => 8,
                'ten_chi_nhanh' => 'Chi nhánh Nha Trang',
                'dia_chi' => 'Nha Trang - Việt Nam',
                'quan_ly_id' => null,
                'trang_thai' => 1,
            ],
            [
                'id' => 9,
                'ten_chi_nhanh' => 'Chi nhánh Nghệ An',
                'dia_chi' => 'Nghệ An - Việt Nam',
                'quan_ly_id' => null,
                'trang_thai' => 1,
            ],
            [
                'id' => 10,
                'ten_chi_nhanh' => 'Chi nhánh Bình Dương',
                'dia_chi' => 'Bình Dương - Việt Nam',
                'quan_ly_id' => null,
                'trang_thai' => 1,
            ],
        ];

        foreach ($chiNhanhs as $chiNhanh) {
            ChiNhanh::updateOrCreate(
                ['id' => $chiNhanh['id']],
                $chiNhanh
            );
        }

        DB::statement('ALTER TABLE chi_nhanhs AUTO_INCREMENT =11;');
    }
}