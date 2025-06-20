<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('banners')->insert([
            [
                'hinh_anh'    => '1215wx365h_6_.jpg',
                'trang_thai'  => 1,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'hinh_anh'    => 'logo1.png',
                'trang_thai'  => 1,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
           
        ]);
    }
}
