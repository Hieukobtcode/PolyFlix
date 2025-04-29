<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhimTheLoai extends Model
{
    protected $table = 'phim_the_loais';

    protected $fillable = [
        'phim_id',
        'the_loai_phim_id',
    ];

    public $timestamps = false;

    public function phim(): BelongsTo
    {
        return $this->belongsTo(Phim::class, 'phim_id');
    }

    public function theLoaiPhim(): BelongsTo
    {
        return $this->belongsTo(TheLoaiPhim::class, 'the_loai_phim_id');
    }
}
