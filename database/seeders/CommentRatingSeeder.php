<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Phim;
use App\Models\Comment;
use App\Models\Rating;
use App\Models\DatVe;

class CommentRatingSeeder extends Seeder
{
    public function run()
    {
        // Lấy một số user và phim để tạo data test
        $users = User::where('vai_tro_id', 4)->take(5)->get(); // Lấy 5 user khách hàng
        $phims = Phim::take(3)->get(); // Lấy 3 phim đầu tiên

        if ($users->isEmpty() || $phims->isEmpty()) {
            $this->command->info('Cần có ít nhất 5 user (vai_tro_id = 4) và 3 phim để tạo data test');
            return;
        }

        foreach ($phims as $phim) {
            foreach ($users as $user) {
                // Tạo vé đã thanh toán để user có thể comment
                $datVe = DatVe::create([
                    'nguoi_dung_id' => $user->id,
                    'phim_id' => $phim->id,
                    'suat_chieu_id' => 1, // Giả sử có suất chiếu ID = 1
                    'so_luong_ve' => 1,
                    'tong_tien' => 100000,
                    'trang_thai' => 'da_thanh_toan',
                    'ma_dat_ve' => 'TEST' . time() . $user->id . $phim->id,
                    'ngay_dat' => now(),
                ]);

                // Tạo rating (70% chance)
                if (rand(1, 10) <= 7) {
                    Rating::create([
                        'user_id' => $user->id,
                        'phim_id' => $phim->id,
                        'rating' => rand(3, 5), // Rating từ 3-5 sao
                    ]);
                }

                // Tạo comment (50% chance)
                if (rand(1, 10) <= 5) {
                    $comments = [
                        'Phim hay, diễn xuất tốt!',
                        'Cốt truyện hấp dẫn, đáng xem!',
                        'Hiệu ứng hình ảnh tuyệt vời.',
                        'Phim hay nhưng hơi dài.',
                        'Rất thích phim này, sẽ xem lại!',
                        'Diễn viên diễn xuất tự nhiên.',
                        'Phim có nhiều tình tiết bất ngờ.',
                        'Âm thanh và hình ảnh chất lượng cao.',
                        'Nội dung phù hợp với gia đình.',
                        'Phim để lại ấn tượng sâu sắc.'
                    ];

                    Comment::create([
                        'user_id' => $user->id,
                        'phim_id' => $phim->id,
                        'content' => $comments[array_rand($comments)],
                        'visible' => true,
                    ]);
                }
            }
        }

        $this->command->info('Đã tạo xong data test cho comments và ratings!');
    }
}
