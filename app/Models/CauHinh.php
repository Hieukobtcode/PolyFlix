<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CauHinh extends Model
{
    protected $table = 'cau_hinhs'; // Tên bảng trong database

    protected $fillable = [
        'id',
        'ten_website',
        'ten_thuong_hieu',
        'khau_hieu',
        'so_dien_thoai',
        'email',
        'dia_chi',
        'giay_phep_kinh_doanh',
        'thoi_gian_lam_viec',
        'link_facebook',
        'link_youtube',
        'ban_quyen',
        'logo',
        'anh_chinh_sach_bao_mat',
        'anh_dieu_khoan_dich_vu',
        'anh_gioi_thieu',
    ];
}
