<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GheNgoi extends Model
{
    protected $table = 'ghe_ngois';
    public $timestamps = true;
    protected $fillable = [
        'phong_chieu_id',
        'loai_ghe',
        'hang',
        'cot',
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
