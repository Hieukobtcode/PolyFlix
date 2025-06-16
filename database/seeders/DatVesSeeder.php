<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DatVe;
use App\Models\Combo;
use Carbon\Carbon;

class DatVesSeeder extends Seeder
{

    public function run(): void
{
    $datVe = DatVe::create([
        'user_id' => 2,
        'ma_dat_ve' => str_pad(mt_rand(0, 9999999999), 10, '0', STR_PAD_LEFT),
        'suat_chieu_id' => 1,
        'tong_tien' => 150000,
        'phuong_thuc_tt' => 'zalo_pay',
        'trang_thai' => 'Chờ thanh toán',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Gán ngẫu nhiên 2 combo vào đơn đặt vé
    $comboIds = Combo::inRandomOrder()->take(2)->pluck('id');

    foreach ($comboIds as $id) {
        $datVe->combos()->attach($id, ['so_luong' => rand(1, 3)]);
    }
}
}