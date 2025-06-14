<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatVe extends Model
{
    use HasFactory;

    protected $table = 'dat_ves'; // tên bảng trong DB

    protected $fillable = [
        'nguoi_dung_id',
        'tong_tien',
        'khuyen_mai',
        'tong_tien_thanh_toan',
        'thoi_gian_dat',
        'phuong_thuc_thanh_toan',
        'ghi_chu',
        'ngay_cap_nhat',
        'phim_id'
    ];

    protected $dates = ['thoi_gian_dat', 'ngay_cap_nhat'];

    // Quan hệ: Một đơn đặt vé có nhiều chi tiết đặt vé (ghế)
    public function chiTietDatVes()
    {
        return $this->hasMany(ChiTietDatVe::class, 'dat_ve_id');
    }

    // Quan hệ: Đơn đặt vé thuộc về người dùng
    public function nguoiDung()
    {
        return $this->belongsTo(User::class, 'nguoi_dung_id');
    }

    public function phim()
    {
        return $this->belongsTo(Phim::class, 'phim_id');
    }
    public function rapPhim()
    {
        return $this->belongsTo(RapPhim::class, 'rap_phim_id');
    }
    public function chiNhanh() 
    {
        return $this->belongsTo(ChiNhanh::class, 'chi_nhanh_id');
    }


    

}
