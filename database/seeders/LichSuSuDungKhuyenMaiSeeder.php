<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LichSuSuDungKhuyenMaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy ID của các khuyến mãi đã tạo
        $khuyenMaiIds = DB::table('khuyen_mais')->pluck('id')->toArray();

        // Lấy ID của các người dùng đã tạo
        $userIds = DB::table('users')->pluck('id')->toArray();

        // Nếu có cả khuyến mãi và người dùng
        if (!empty($khuyenMaiIds) && !empty($userIds)) {
            $data = [];

            // Tạo dữ liệu lịch sử sử dụng khuyến mãi
            foreach ($khuyenMaiIds as $khuyenMaiId) {
                // Mỗi khuyến mãi được sử dụng bởi 2 người dùng ngẫu nhiên
                $randomUsers = array_rand(array_flip($userIds), min(2, count($userIds)));

                if (!is_array($randomUsers)) {
                    $randomUsers = [$randomUsers];
                }

                foreach ($randomUsers as $userId) {
                    $data[] = [
                        'khuyen_mai_id' => $khuyenMaiId,
                        'nguoi_dung_id' => $userId,
                        'thoi_gian_su_dung' => now()->subDays(rand(1, 30)),
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }

            DB::table('lich_su_su_dung_khuyen_mais')->insert($data);

            // Cập nhật số lần đã sử dụng cho mỗi khuyến mãi
            foreach ($khuyenMaiIds as $khuyenMaiId) {
                $count = DB::table('lich_su_su_dung_khuyen_mais')
                    ->where('khuyen_mai_id', $khuyenMaiId)
                    ->count();

                DB::table('khuyen_mais')
                    ->where('id', $khuyenMaiId)
                    ->update(['so_lan_da_su_dung' => $count]);
            }
        }
    }
}
