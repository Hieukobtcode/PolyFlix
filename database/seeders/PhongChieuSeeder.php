<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PhongChieuSeeder extends Seeder
{
    public function run(): void
    {
        $rapPhims = DB::table('rap_phims')->get();
        $loaiPhongs = DB::table('loai_phongs')->get();

        foreach ($rapPhims as $rap) {
            for ($i = 1; $i <= 3; $i++) {
                // Chọn random 1 loại phòng
                $loai = $loaiPhongs->random();

                $tenPhong = Str::upper(Str::random(1)) . rand(1, 20) . ' - ' . $loai->ten_loai_phong;

                DB::table('phong_chieus')->insert([
                    'rap_phim_id'    => $rap->id,
                    'ten_phong'      => $tenPhong,
                    'loai_phong_id'  => $loai->id,
                    'so_do_ghe_id'   => null,
                    'status'         => 1,
                    'so_ghe'         => null,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }
        }
    }
}
