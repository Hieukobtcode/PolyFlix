<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class PhimPhuDe extends Model
{
    protected $table = 'phim_phu_des';

    protected $fillable = [
        'phim_id',
        'phu_de_phim_id',
    ];

    public $timestamps = false;

    public function phim(): BelongsTo
    {
        return $this->belongsTo(Phim::class, 'phim_id');
    }

    public function phuDePhim(): BelongsTo
    {
        return $this->belongsTo(PhuDePhim::class, 'phu_de_phim_id');
    }
}