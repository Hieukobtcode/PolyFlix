<?php

namespace App\Models;

use App\Models\LoaiGhe;
use App\Models\RapPhim;
use App\Models\LoaiPhong;
use App\Models\PhongChieu;
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
        return $this->belongsTo(PhongChieu::class, 'phong_chieu_id');
    }

    public function loaiGhe()
    {
        return $this->belongsTo(LoaiGhe::class, 'loai_ghe'); 
    }



}
