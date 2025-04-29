<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Phim extends Model
{
    protected $table = 'phims';

    protected $fillable = [
        'ten_phim',
        'mo_ta',
        'dao_dien',
        'dien_vien',
        'thoi_luong',
        'ngay_phat_hanh',
        'trailer',
        'poster',
        'ngon_ngu',
        'quoc_gia',
        'do_tuoi',
        'trang_thai',
    ];

    protected $casts = [
        'ngay_phat_hanh' => 'date',
        'thoi_luong' => 'integer',
    ];

    const CREATED_AT = 'create_at';
    const UPDATED_AT = 'update_at';

    public function theLoais(): BelongsToMany
    {
        return $this->belongsToMany(TheLoaiPhim::class, 'phim_the_loais', 'phim_id', 'the_loai_phim_id');
    }
}
