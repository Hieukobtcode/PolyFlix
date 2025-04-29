<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TheLoaiPhim extends Model
{
    protected $table = 'the_loai_phims';

    protected $fillable = [
        'ten_the_loai',
        'mo_ta',
        'trang_thai',
    ];

    const CREATED_AT = 'create_at';
    const UPDATED_AT = 'update_at';

    public function phims(): BelongsToMany
    {
        return $this->belongsToMany(Phim::class, 'phim_the_loais', 'the_loai_phim_id', 'phim_id');
    }
}
