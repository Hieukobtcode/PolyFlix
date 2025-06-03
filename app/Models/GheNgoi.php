<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GheNgoi extends Model
{
    protected $table = 'ghe_ngois';

    protected $fillable = [
        'phong_chieu_id',
        'loai_ghe_id',
        'so_hang',
        'so_cot',
        'ma_ghe',
        'trang_thai',
    ];

    public function phongChieu()
    {
        return $this->belongsTo(PhongChieu::class);
    }

    public function loaiGhe()
    {
        return $this->belongsTo(LoaiGhe::class);
    }
}
