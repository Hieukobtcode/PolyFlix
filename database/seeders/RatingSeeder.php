<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rating;
use Illuminate\Support\Carbon;

class RatingSeeder extends Seeder
{
    public function run(): void
    {
        Rating::create([
            'user_id'    => 1,
            'phim_id'    => 4,
            'rating'     => 5, 
            'created_at' => Carbon::now()->subDays(rand(0, 30)),
            'updated_at' => Carbon::now(),
        ]);

        Rating::create([
            'user_id'    => 2,
            'phim_id'    => 5,
            'rating'     => 4, 
            'created_at' => Carbon::now()->subDays(rand(0, 30)),
            'updated_at' => Carbon::now(),
        ]);
    }
}
