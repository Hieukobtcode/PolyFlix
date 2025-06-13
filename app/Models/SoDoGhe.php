<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoDoGhe extends Model
{
    protected $table = 'so_do_ghes';

    protected $fillable = [
        'cau_truc_ghe',
        'so_hang_thuong',
        'so_hang_vip',
        'so_hang_doi',
        'mo_ta',
        'trang_thai'
    ];

    protected $casts = [
        'cau_truc_ghe' => 'array',
    ];

    public function phongChieu()
    {
        return $this->belongsTo(PhongChieu::class, 'phong_chieu_id', 'id');
    }
}
