<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VaiTro;

class VaiTroSeeder extends Seeder
{
    public function run(): void
    {
        VaiTro::insert([
            [
                'id' => 1,
                'ten' => 'SupperAdmin',
                'mo_ta' => 'Tài khoản quản trị cao nhất, có toàn quyền trong hệ thống.'
            ],
            [
                'id' => 2,
                'ten' => 'Admin Chi Nhánh',
                'mo_ta' => 'Quản lý toàn bộ hoạt động của một chi nhánh cụ thể.'
            ],
            [
                'id' => 3,
                'ten' => 'Admin Rạp',
                'mo_ta' => 'Quản lý một rạp phim trong chi nhánh.'
            ],
            [
                'id' => 4,
                'ten' => 'Nhân Viên',
                'mo_ta' => 'Nhân viên thực hiện các tác vụ hỗ trợ, bán vé, phục vụ khách.'
            ],
            [
                'id' => 5,
                'ten' => 'Khách Hàng',
                'mo_ta' => 'Người dùng hệ thống, có thể đặt vé, xem lịch chiếu, đánh giá phim.'
            ],
        ]);
    }
}