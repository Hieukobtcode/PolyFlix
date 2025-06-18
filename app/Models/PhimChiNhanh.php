<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhimChiNhanh extends Model
{
    protected $table = 'phim_chi_nhanhs';

    protected $fillable = [
        'phim_id',
        'chi_nhanh_id',
    ];

    public $timestamps = false;

    public function phim(): BelongsTo
    {
        return $this->belongsTo(Phim::class, 'phim_id');
    }

    public function chiNhanh(): BelongsTo
    {
        return $this->belongsTo(ChiNhanh::class, 'chi_nhanh_id');
    }
    
}