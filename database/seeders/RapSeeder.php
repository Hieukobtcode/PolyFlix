<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class RapSeeder extends Seeder
{
    public function run(): void
    {
        $raps = [
            // Hà Nội
            ['chi_nhanh_id' => 1, 'ten_rap' => 'PolyFlix Bà Triệu', 'dia_chi' => '191 Bà Triệu, Hai Bà Trưng, Hà Nội'],
            ['chi_nhanh_id' => 1, 'ten_rap' => 'PolyFlix Long Biên', 'dia_chi' => 'Aeon Mall Long Biên, Hà Nội'],
            ['chi_nhanh_id' => 1, 'ten_rap' => 'PolyFlix Royal City', 'dia_chi' => '72A Nguyễn Trãi, Thanh Xuân, Hà Nội'],

            // TP.HCM
            ['chi_nhanh_id' => 2, 'ten_rap' => 'PolyFlix Vivo City', 'dia_chi' => '1058 Nguyễn Văn Linh, Q.7, TP.HCM'],
            ['chi_nhanh_id' => 2, 'ten_rap' => 'PolyFlix Giga Mall', 'dia_chi' => '240-242 Phạm Văn Đồng, Thủ Đức, TP.HCM'],
            ['chi_nhanh_id' => 2, 'ten_rap' => 'PolyFlix Crescent Mall', 'dia_chi' => '101 Tôn Dật Tiên, Q.7, TP.HCM'],

            // Đà Nẵng
            ['chi_nhanh_id' => 3, 'ten_rap' => 'PolyFlix Vincom Ngô Quyền', 'dia_chi' => '910A Ngô Quyền, Sơn Trà, Đà Nẵng'],
            ['chi_nhanh_id' => 3, 'ten_rap' => 'PolyFlix Lotte Mart', 'dia_chi' => '6 Nại Nam, Hải Châu, Đà Nẵng'],
            ['chi_nhanh_id' => 3, 'ten_rap' => 'PolyFlix Indochina Tower', 'dia_chi' => '74 Bạch Đằng, Hải Châu, Đà Nẵng'],

            // Hải Phòng
            ['chi_nhanh_id' => 4, 'ten_rap' => 'PolyFlix Vincom Lê Thánh Tông', 'dia_chi' => 'Lê Thánh Tông, Ngô Quyền, Hải Phòng'],
            ['chi_nhanh_id' => 4, 'ten_rap' => 'PolyFlix TD Plaza', 'dia_chi' => '20A Lê Hồng Phong, Ngô Quyền, Hải Phòng'],
            ['chi_nhanh_id' => 4, 'ten_rap' => 'PolyFlix Cát Bi', 'dia_chi' => 'Sân bay Cát Bi, Hải Phòng'],

            // Cần Thơ
            ['chi_nhanh_id' => 5, 'ten_rap' => 'PolyFlix Vincom Xuân Khánh', 'dia_chi' => '209 30/4, Ninh Kiều, Cần Thơ'],
            ['chi_nhanh_id' => 5, 'ten_rap' => 'PolyFlix Sense City', 'dia_chi' => '1 Hòa Bình, Ninh Kiều, Cần Thơ'],
            ['chi_nhanh_id' => 5, 'ten_rap' => 'PolyFlix Lotte Mart', 'dia_chi' => '84 Mậu Thân, Ninh Kiều, Cần Thơ'],

            // Quảng Ninh
            ['chi_nhanh_id' => 6, 'ten_rap' => 'PolyFlix Hạ Long', 'dia_chi' => 'Vincom Plaza Hạ Long, Quảng Ninh'],
            ['chi_nhanh_id' => 6, 'ten_rap' => 'PolyFlix Cẩm Phả', 'dia_chi' => 'Số 5 Trần Phú, Cẩm Phả, Quảng Ninh'],
            ['chi_nhanh_id' => 6, 'ten_rap' => 'PolyFlix Móng Cái', 'dia_chi' => 'Trần Phú, Móng Cái, Quảng Ninh'],

            // Đà Lạt
            ['chi_nhanh_id' => 7, 'ten_rap' => 'PolyFlix Chợ Đà Lạt', 'dia_chi' => 'Nguyễn Thị Minh Khai, Đà Lạt'],
            ['chi_nhanh_id' => 7, 'ten_rap' => 'PolyFlix Hồ Xuân Hương', 'dia_chi' => 'Đường Trần Quốc Toản, Đà Lạt'],
            ['chi_nhanh_id' => 7, 'ten_rap' => 'PolyFlix Lâm Viên', 'dia_chi' => 'Nguyễn Văn Cừ, Đà Lạt'],

            // Nha Trang
            ['chi_nhanh_id' => 8, 'ten_rap' => 'PolyFlix Trần Phú', 'dia_chi' => '76 Trần Phú, Nha Trang'],
            ['chi_nhanh_id' => 8, 'ten_rap' => 'PolyFlix Vincom Lê Thánh Tôn', 'dia_chi' => 'Vincom Plaza, Lê Thánh Tôn, Nha Trang'],
            ['chi_nhanh_id' => 8, 'ten_rap' => 'PolyFlix Nha Trang Center', 'dia_chi' => '20 Trần Phú, Nha Trang'],

            // Nghệ An
            ['chi_nhanh_id' => 9, 'ten_rap' => 'PolyFlix Vinh Plaza', 'dia_chi' => 'Lê Mao, TP. Vinh, Nghệ An'],
            ['chi_nhanh_id' => 9, 'ten_rap' => 'PolyFlix Big C Vinh', 'dia_chi' => 'Quang Trung, TP. Vinh, Nghệ An'],
            ['chi_nhanh_id' => 9, 'ten_rap' => 'PolyFlix Nguyễn Sỹ Sách', 'dia_chi' => 'Nguyễn Sỹ Sách, Vinh, Nghệ An'],

            // Bình Dương
            ['chi_nhanh_id' => 10, 'ten_rap' => 'PolyFlix Aeon Mall BD', 'dia_chi' => 'Aeon Mall, Bình Dương'],
            ['chi_nhanh_id' => 10, 'ten_rap' => 'PolyFlix Thủ Dầu Một', 'dia_chi' => 'Q.Liên Hợp, Thủ Dầu Một, Bình Dương'],
            ['chi_nhanh_id' => 10, 'ten_rap' => 'PolyFlix VSIP', 'dia_chi' => 'KCN VSIP, Thuận An, Bình Dương'],
        ];

        $insertData = [];
        $id = 1;

        foreach ($raps as $rap) {
            $insertData[] = [
                'id' => $id++,
                'chi_nhanh_id' => $rap['chi_nhanh_id'],
                'quan_ly_id' => null,
                'ten_rap' => $rap['ten_rap'],
                'dia_chi' => $rap['dia_chi'],
                'trang_thai' => 1,
                'phu_thu' => rand(5000, 20000),
                'deleted_at' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('rap_phims')->insert($insertData);
        DB::statement('ALTER TABLE raps AUTO_INCREMENT = ' . ($id + 1) . ';');
    }
}
