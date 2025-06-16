<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DatVesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('dat_ves')->insert([
            [
                'nguoi_dung_id' => 2,
                'tong_tien' => 150000,
                'khuyen_mai' => 20000,
                'tong_tien_thanh_toan' => 130000,
                'thoi_gian_dat' => Carbon::now()->subDays(1),
                'phuong_thuc_thanh_toan' => 'zalo_pay',
                'ghi_chu' => 'Đặt vé xem phim Spider-Man',
                'ngay_cap_nhat' => Carbon::now(),
                'phim_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
       
        ]);
    }
}
