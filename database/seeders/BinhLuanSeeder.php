<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BinhLuanSeeder extends Seeder
{
    public function run()
    {
        foreach (range(1, 10) as $i) {
            DB::table('comments')->insert([
                'user_id'    => 18,
                'phim_id'    => 1,
                'content'    => 'Nội dung bình luận mẫu số ' . $i,
                'visible'    => rand(0, 1),
                'reply'      => rand(0, 1) ? 'Phản hồi cho bình luận ' . $i : null,
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
