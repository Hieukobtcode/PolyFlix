<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CauHinh;

class CauHinhSeeder extends Seeder
{
    public function run(): void
    {
        CauHinh::truncate(); // Xoá hết để tránh trùng

        CauHinh::create([
            'ten_website' => 'PolyTech',
            'ten_thuong_hieu' => 'PolyTech Co.',
            'khau_hieu' => 'Đồng hành cùng công nghệ',
            'so_dien_thoai' => '0123 456 789',
            'email' => 'lienhe@polytech.vn',
            'dia_chi' => '123 Đường Công Nghệ, Q.1, TP.HCM',
            'giay_phep_kinh_doanh' => 'GP123456/2024/TPHCM',
            'thoi_gian_lam_viec' => 'T2 - T7: 08:00 - 17:00',
            'link_facebook' => 'https://facebook.com/polytech',
            'link_youtube' => 'https://youtube.com/polytech',
            'ban_quyen' => '© 2025 PolyTech. All rights reserved.',
            'logo' => 'logos/logo.png',
            'anh_chinh_sach_bao_mat' => 'uploads/chinh-sach.jpg',
            'anh_dieu_khoan_dich_vu' => 'uploads/dieu-khoan.jpg',
            'anh_gioi_thieu' => 'uploads/gioi-thieu.jpg',
        ]);
    }
}
