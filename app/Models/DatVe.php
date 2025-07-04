<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatVe extends Model
{
    use HasFactory;

    protected $table = 'dat_ves'; // tên bảng trong DB

    protected $fillable = [
        'ma_dat_ve',
        'user_id',
        'suat_chieu_id',
        'khuyen_mai_id',
        'tong_tien',
        'phuong_thuc_tt',
        'trang_thai',
        'ma_giao_dich',
        'ngay_thanh_toan',
        'ghi_chu',
    ];

    protected $dates = ['thoi_gian_dat', 'ngay_cap_nhat'];

    // // Quan hệ: Một đơn đặt vé có nhiều chi tiết đặt vé (ghế)
    // public function chiTietDatVes()
    // {
    //     return $this->hasMany(ChiTietDatVe::class, 'dat_ve_id');
    // }

    // Quan hệ: Đơn đặt vé thuộc về người dùng
    public function nguoiDung()
    {
        return $this->belongsTo(User::class, 'user_id');
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

    public function suatChieu()
    {
        return $this->belongsTo(SuatChieu::class, 'suat_chieu_id');
    }
    public function DoAn()
    {
        return $this->belongsTo(DoAn::class, 'do_an_id');
    }
    public function doAns()
    {
        return $this->belongsToMany(DoAn::class, 'dat_ve_do_an')
            ->withPivot('so_luong')
            ->withTimestamps();
    }
    public function combos()
    {
        return $this->belongsToMany(Combo::class, 'dat_ve_combo')
            ->withPivot('so_luong')
            ->withTimestamps();
    }
    public function gheNgois()
    {
        return $this->belongsToMany(GheNgoi::class, 'chi_tiet_dat_ves', 'dat_ve_id', 'ghe_id');
    }
}
