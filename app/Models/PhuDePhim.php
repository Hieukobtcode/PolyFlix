<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PhuDePhim extends Model
{
    protected $table = 'phu_de_phims';

    protected $fillable = [
        'ten_phu_de',
        'mo_ta',
        'trang_thai',
    ];

    const CREATED_AT = 'create_at';
    const UPDATED_AT = 'update_at';

    public function phims(): BelongsToMany
    {
        return $this->belongsToMany(Phim::class, 'phim_phu_des', 'phu_de_phim_id', 'phim_id');
    }
}