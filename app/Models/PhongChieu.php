<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhongChieu extends Model
{
    use HasFactory;

    protected $table = 'phong_chieus';

    protected $fillable = [
        'rap_phim_id',
        'ten_phong',
        'loai_phong_id',
        'status',
        'so_do_ghe_id',
        'so_ghe',
    ];

    public function rapPhim()
    {
        return $this->belongsTo(RapPhim::class, 'rap_phim_id');
    }

    public function loaiPhong()
    {
        return $this->belongsTo(loaiPhong::class, 'loai_phong_id');
    }

    public function soDoGhe()
    {
        return $this->hasOne(SoDoGhe::class, 'phong_chieu_id', 'id');
    }

    public function gheNgois()
    {
        return $this->hasMany(GheNgoi::class, 'phong_chieu_id');
    }

    public function suatChieus()
    {
        return $this->hasMany(SuatChieu::class);
    }

}