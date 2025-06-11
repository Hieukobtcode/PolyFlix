<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhimDinhDang extends Model
{
    protected $table = 'phim_dinh_dangs';

    protected $fillable = [
        'phim_id',
        'dinh_dang_phim_id',
    ];

    public $timestamps = false;

    public function phim(): BelongsTo
    {
        return $this->belongsTo(Phim::class, 'phim_id');
    }

    public function dinhDangPhim(): BelongsTo
    {
        return $this->belongsTo(DinhDangPhim::class, 'dinh_dang_phim_id');
    }
}