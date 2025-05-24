<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoDoGhe extends Model
{
    protected $table = 'so_do_ghes';

    protected $fillable = [
        'ten_so_do',
        'cau_truc_ghe',
        'so_hang_thuong',
        'so_hang_vip',
        'so_hang_doi',
        'mo_ta',
        'trang_thai',
    ];

    protected $casts = [
        'cau_truc_ghe' => 'array',
        'trang_thai'   => 'boolean',
    ];

    public function phongChieus()
    {
        return $this->hasMany(PhongChieu::class);
    }
}
