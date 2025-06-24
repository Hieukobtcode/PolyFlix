<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
       

            Comment::create([
                'user_id'    => 1,
                'phim_id'    => 4,
                'content'    => 'Phim quá xuất sắc!',
                'visible'    => 1,
                'reply'      => null,
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
                'updated_at' => Carbon::now(),
            ]);

            Comment::create([
                'user_id'    => 2,
                'phim_id'    => 5,
                'content'    => 'Cốt truyện hấp dẫn và diễn viên diễn rất tốt.',
                'visible'    => 1,
                'reply'      => null,
                'created_at' => Carbon::now()->subDays(rand(0, 30)),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
