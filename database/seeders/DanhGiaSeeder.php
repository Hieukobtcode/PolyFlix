<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DanhGiaSeeder extends Seeder
{
    public function run()
    {
            DB::table('ratings')->insert([
                'user_id'    =>18,      
                'phim_id'    =>1,     
                'rating'     => rand(1, 5),     
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
                'updated_at' => Carbon::now(),
            ]);
    }
}
