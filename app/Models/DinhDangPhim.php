<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DinhDangPhim extends Model
{
    protected $table = 'dinh_dang_phims';

    protected $fillable = [
        'ten_dinh_dang',
        'mo_ta',
        'trang_thai',
    ];

    const CREATED_AT = 'create_at';
    const UPDATED_AT = 'update_at';

    public function phims(): BelongsToMany
    {
        return $this->belongsToMany(Phim::class, 'phim_dinh_dangs', 'dinh_dang_phim_id', 'phim_id');
    }
}