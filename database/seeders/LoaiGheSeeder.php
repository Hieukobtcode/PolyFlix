<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LoaiGheSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('loai_ghes')->insert([
            [
                'id' => 10,
                'ten_loai_ghe' => 'Ghế thường',
                'chu_thich_mau_ghe' => '#ffffff',
                'mo_ta' => 'Ghế phổ thông không phụ thu',
                'phu_thu' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 11,
                'ten_loai_ghe' => 'Ghế vip',
                'chu_thich_mau_ghe' => '#ffcc00',
                'mo_ta' => 'Ghế VIP có phụ thu',
                'phu_thu' => 20000,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 12,
                'ten_loai_ghe' => 'Ghế đôi',
                'chu_thich_mau_ghe' => '#ff66cc',
                'mo_ta' => 'Ghế đôi cho cặp đôi, phụ thu cao hơn',
                'phu_thu' => 40000,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
