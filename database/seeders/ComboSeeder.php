<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComboSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy ID của các đồ ăn để tạo combo
        $doAnIds = DB::table('do_ans')->pluck('id')->toArray();

        if (empty($doAnIds)) {
            $this->command->warn('Không tìm thấy đồ ăn nào. Vui lòng chạy DoAnSeeder trước!');
            return;
        }

        $combos = [
            [
                'tieu_de' => 'Combo Couple',
                'noi_dung' => '2 bỏng ngô + 2 nước ngọt size L - Dành cho cặp đôi xem phim',
                'hinh_anh' => 'combo/combo-couple.jpg',
                'gia' => 120000,
                'trang_thai' => 1
            ],
            [
                'tieu_de' => 'Combo Family',
                'noi_dung' => '1 bỏng ngô lớn + 4 nước ngọt + kẹo - Dành cho gia đình 4 người',
                'hinh_anh' => 'combo/combo-family.jpg',
                'gia' => 180000,
                'trang_thai' => 1
            ],
            [
                'tieu_de' => 'Combo Solo',
                'noi_dung' => '1 bỏng ngô nhỏ + 1 nước ngọt + kẹo - Dành cho 1 người',
                'hinh_anh' => 'combo/combo-solo.jpg',
                'gia' => 65000,
                'trang_thai' => 1
            ],
            [
                'tieu_de' => 'Combo Premium',
                'noi_dung' => '1 bỏng ngô caramel + 2 nước cam + nachos - Combo cao cấp',
                'hinh_anh' => 'combo/combo-premium.jpg',
                'gia' => 150000,
                'trang_thai' => 1
            ],
            [
                'tieu_de' => 'Combo Student',
                'noi_dung' => '1 bỏng ngô + 1 nước ngọt + bánh quy - Combo tiết kiệm cho sinh viên',
                'hinh_anh' => 'combo/combo-student.jpg',
                'gia' => 55000,
                'trang_thai' => 1
            ],
            [
                'tieu_de' => 'Combo Deluxe',
                'noi_dung' => '2 bỏng ngô lớn + 3 nước ngọt + khoai tây chiên + kẹo - Combo sang trọng',
                'hinh_anh' => 'combo/combo-deluxe.jpg',
                'gia' => 220000,
                'trang_thai' => 1
            ]
        ];

        foreach ($combos as $combo) {
            $comboId = DB::table('combos')->insertGetId(array_merge($combo, [
                'created_at' => now(),
                'updated_at' => now()
            ]));

            // Liên kết combo với một số đồ ăn ngẫu nhiên
            $randomDoAnIds = array_rand(array_flip($doAnIds), min(3, count($doAnIds)));
            if (!is_array($randomDoAnIds)) {
                $randomDoAnIds = [$randomDoAnIds];
            }

            foreach ($randomDoAnIds as $doAnId) {
                DB::table('combo_do_ans')->insert([
                    'combo_id' => $comboId,
                    'do_an_id' => $doAnId,
                    'so_luong' => rand(1, 2),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Bỏ qua liên kết combo với chi nhánh vì table structure khác
            // $chiNhanhIds = DB::table('chi_nhanhs')->pluck('id');
            // foreach ($chiNhanhIds as $chiNhanhId) {
            //     DB::table('chi_nhanh_combo')->insert([
            //         'chi_nhanh_id' => $chiNhanhId,
            //         'combo_id' => $comboId,
            //         'created_at' => now(),
            //         'updated_at' => now()
            //     ]);
            // }
        }

        $this->command->info('Đã tạo thành công ' . count($combos) . ' combo!');
    }
}
