<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SuatChieuSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy dữ liệu cần thiết
        $phims = DB::table('phims')->where('trang_thai', 1)->get();
        $phongChieus = DB::table('phong_chieus')->where('status', 1)->get();

        if ($phims->isEmpty() || $phongChieus->isEmpty()) {
            $this->command->info('Không có dữ liệu phim hoặc phòng chiếu để tạo suất chiếu');
            return;
        }

        $phienBanPhims = ['long_tieng', 'phu_de'];
        $gioChieus = ['09:00', '12:00', '15:00', '18:00', '21:00'];

        // Tạo suất chiếu cho 7 ngày tới
        for ($i = 0; $i < 7; $i++) {
            $ngayChieu = Carbon::now()->addDays($i)->format('Y-m-d');

            foreach ($phims as $phim) {
                // Lấy random 2-3 phòng chiếu cho mỗi phim
                $randomPhongChieus = $phongChieus->random(rand(2, min(3, $phongChieus->count())));

                foreach ($randomPhongChieus as $phongChieu) {
                    // Tạo 2-3 suất chiếu cho mỗi phòng
                    $randomGioChieus = collect($gioChieus)->random(rand(2, 3));

                    foreach ($randomGioChieus as $gioChieu) {
                        $batDauTime = Carbon::parse($gioChieu);
                        $ketThucTime = $batDauTime->copy()->addMinutes($phim->thoi_luong + 15); // Thêm 15 phút nghỉ

                        DB::table('suat_chieus')->insert([
                            'phim_id' => $phim->id,
                            'phong_chieu_id' => $phongChieu->id,
                            'phien_ban_phim' => $phienBanPhims[array_rand($phienBanPhims)],
                            'ngay_bat_dau' => $ngayChieu,
                            'ngay_ket_thuc' => $ngayChieu, // Cùng ngày
                            'bat_dau' => $batDauTime->format('H:i:s'),
                            'ket_thuc' => $ketThucTime->format('H:i:s'),
                            'trang_thai' => 'hoat_dong',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }

        $this->command->info('Đã tạo suất chiếu thành công!');
    }
}
