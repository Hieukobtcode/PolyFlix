<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TheLoaiPhim;

class TheLoaiPhimSeeder extends Seeder
{
    public function run(): void
    {
        $theLoaiList = [
            [
                'ten_the_loai' => 'Hành động',
                'mo_ta' => 'Phim có nhiều pha hành động, chiến đấu căng thẳng và hồi hộp.',
                'trang_thai' => 1,
            ],
            [
                'ten_the_loai' => 'Hài hước',
                'mo_ta' => 'Phim mang tính giải trí cao, gây cười và thư giãn.',
                'trang_thai' => 1,
            ],
            [
                'ten_the_loai' => 'Tình cảm',
                'mo_ta' => 'Phim xoay quanh các mối quan hệ yêu đương, cảm xúc.',
                'trang_thai' => 1,
            ],
            [
                'ten_the_loai' => 'Kinh dị',
                'mo_ta' => 'Phim với nội dung rùng rợn, gây ám ảnh và hồi hộp.',
                'trang_thai' => 1,
            ],
            [
                'ten_the_loai' => 'Khoa học viễn tưởng',
                'mo_ta' => 'Phim lấy bối cảnh tương lai, công nghệ hoặc vũ trụ.',
                'trang_thai' => 1,
            ],
            [
                'ten_the_loai' => 'Hoạt hình',
                'mo_ta' => 'Phim được sản xuất bằng kỹ thuật hoạt hình, dành cho mọi lứa tuổi.',
                'trang_thai' => 1,
            ],
            [
                'ten_the_loai' => 'Phiêu lưu',
                'mo_ta' => 'Phim về hành trình khám phá, trải nghiệm, mạo hiểm.',
                'trang_thai' => 1,
            ],
            [
                'ten_the_loai' => 'Tâm lý',
                'mo_ta' => 'Phim khai thác chiều sâu nội tâm, cảm xúc nhân vật.',
                'trang_thai' => 1,
            ],
            [
                'ten_the_loai' => 'Chiến tranh',
                'mo_ta' => 'Phim phản ánh chiến tranh, lịch sử và lòng yêu nước.',
                'trang_thai' => 1,
            ],
            [
                'ten_the_loai' => 'Âm nhạc',
                'mo_ta' => 'Phim có yếu tố ca hát, âm nhạc làm chủ đề trung tâm.',
                'trang_thai' => 1,
            ],
        ];

        foreach ($theLoaiList as $item) {
            TheLoaiPhim::create($item);
        }

        
    }
}