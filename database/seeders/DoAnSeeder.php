<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoAnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo danh mục đồ ăn trước
        $danhMucIds = [];

        // Kiểm tra và tạo danh mục đồ ăn
        $danhMucs = [
            ['ten' => 'Đồ ăn nhẹ'],
            ['ten' => 'Nước uống'],
            ['ten' => 'Kẹo - Bánh']
        ];

        foreach ($danhMucs as $dm) {
            $existing = DB::table('danh_muc_do_ans')->where('ten', $dm['ten'])->first();
            if (!$existing) {
                $danhMucIds[] = DB::table('danh_muc_do_ans')->insertGetId(array_merge($dm, [
                    'created_at' => now(),
                    'updated_at' => now()
                ]));
            } else {
                $danhMucIds[] = $existing->id;
            }
        }

        // Tạo đồ ăn
        $doAns = [
            // Đồ ăn nhẹ
            [
                'tieu_de' => 'Bỏng ngô caramel',
                'noi_dung' => 'Bỏng ngô thơm ngon với lớp caramel ngọt dịu',
                'hinh_anh' => 'do-an/popcorn-caramel.jpg',
                'gia' => 45000,
                'danh_muc_id' => $danhMucIds[0],
                'trang_thai' => 1
            ],
            [
                'tieu_de' => 'Bỏng ngô bơ',
                'noi_dung' => 'Bỏng ngô truyền thống với hương vị bơ thơm ngon',
                'hinh_anh' => 'do-an/popcorn-butter.jpg',
                'gia' => 40000,
                'danh_muc_id' => $danhMucIds[0],
                'trang_thai' => 1
            ],
            [
                'tieu_de' => 'Khoai tây chiên',
                'noi_dung' => 'Khoai tây chiên giòn rụm, ăn kèm tương cà',
                'hinh_anh' => 'do-an/french-fries.jpg',
                'gia' => 35000,
                'danh_muc_id' => $danhMucIds[0],
                'trang_thai' => 1
            ],
            [
                'tieu_de' => 'Bánh nachos',
                'noi_dung' => 'Bánh tortilla giòn với sốt phô mai nóng',
                'hinh_anh' => 'do-an/nachos.jpg',
                'gia' => 55000,
                'danh_muc_id' => $danhMucIds[0],
                'trang_thai' => 1
            ],

            // Nước uống
            [
                'tieu_de' => 'Coca Cola',
                'noi_dung' => 'Nước ngọt Coca Cola size L (32oz)',
                'hinh_anh' => 'do-an/coca-cola.jpg',
                'gia' => 25000,
                'danh_muc_id' => $danhMucIds[1],
                'trang_thai' => 1
            ],
            [
                'tieu_de' => 'Pepsi',
                'noi_dung' => 'Nước ngọt Pepsi size L (32oz)',
                'hinh_anh' => 'do-an/pepsi.jpg',
                'gia' => 25000,
                'danh_muc_id' => $danhMucIds[1],
                'trang_thai' => 1
            ],
            [
                'tieu_de' => 'Nước cam Tropicana',
                'noi_dung' => 'Nước cam nguyên chất Tropicana 330ml',
                'hinh_anh' => 'do-an/tropicana.jpg',
                'gia' => 30000,
                'danh_muc_id' => $danhMucIds[1],
                'trang_thai' => 1
            ],
            [
                'tieu_de' => 'Trà xanh C2',
                'noi_dung' => 'Trà xanh C2 chai 455ml',
                'hinh_anh' => 'do-an/c2-green-tea.jpg',
                'gia' => 20000,
                'danh_muc_id' => $danhMucIds[1],
                'trang_thai' => 1
            ],

            // Kẹo - Bánh
            [
                'tieu_de' => 'Kẹo M&Ms',
                'noi_dung' => 'Kẹo sô-cô-la M&Ms gói lớn',
                'hinh_anh' => 'do-an/mms.jpg',
                'gia' => 35000,
                'danh_muc_id' => $danhMucIds[2],
                'trang_thai' => 1
            ],
            [
                'tieu_de' => 'Skittles',
                'noi_dung' => 'Kẹo Skittles nhiều hương vị trái cây',
                'hinh_anh' => 'do-an/skittles.jpg',
                'gia' => 32000,
                'danh_muc_id' => $danhMucIds[2],
                'trang_thai' => 1
            ],
            [
                'tieu_de' => 'Bánh Oreo',
                'noi_dung' => 'Bánh quy Oreo kem vani gói gia đình',
                'hinh_anh' => 'do-an/oreo.jpg',
                'gia' => 28000,
                'danh_muc_id' => $danhMucIds[2],
                'trang_thai' => 1
            ]
        ];

        foreach ($doAns as $doAn) {
            $doAnId = DB::table('do_ans')->insertGetId(array_merge($doAn, [
                'created_at' => now(),
                'updated_at' => now()
            ]));

            // Liên kết đồ ăn với tất cả chi nhánh
            $chiNhanhIds = DB::table('chi_nhanhs')->pluck('id');
            foreach ($chiNhanhIds as $chiNhanhId) {
                DB::table('chi_nhanh_do_an')->insert([
                    'chi_nhanh_id' => $chiNhanhId,
                    'do_an_id' => $doAnId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        $this->command->info('Đã tạo thành công ' . count($doAns) . ' món đồ ăn!');
    }
}
