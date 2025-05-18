<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoDoGhe extends Model
{
    protected $table = 'so_do_ghes';

    protected $fillable = [
        'ten_so_do',
        'so_hang',
        'so_cot',
        'cau_truc',
    ];

    protected $casts = [
        'cau_truc' => 'array',
    ];

    public function phongChieus()
    {
        return $this->hasMany(PhongChieu::class);
    }
}
