<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhimRap extends Model
{
    protected $table = 'phim_raps';

    protected $fillable = [
        'phim_id',
        'rap_phim_id',
    ];

    public $timestamps = false;

    public function phim(): BelongsTo
    {
        return $this->belongsTo(Phim::class, 'phim_id');
    }

    public function rapPhim(): BelongsTo
    {
        return $this->belongsTo(RapPhim::class, 'rap_phim_id');
    }
}