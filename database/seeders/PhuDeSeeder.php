<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PhuDeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('phu_de_phim')->insert([
            [
                'ten_phu_de' => 'Lồng tiếng Việt',
                'mo_ta'      => 'Phụ đề tiếng Việt cho phim',
                'trang_thai' => 1,
                'create_at'  => now(),
                'update_at'  => now(),
            ],
            [
                'ten_phu_de' => 'Phụ đề tiếng Việt',
                'mo_ta'      => 'Phụ đề tiếng Việt cho phim',
                'trang_thai' => 1,
                'create_at'  => now(),
                'update_at'  => now(),
            ],
            [
                'ten_phu_de' => 'Phụ đề tiếng Anh',
                'mo_ta'      => 'English subtitles',
                'trang_thai' => 1,
                'create_at'  => now(),
                'update_at'  => now(),
            ]
        ]);
    }
}
