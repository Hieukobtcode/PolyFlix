<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminRequest extends Model
{
    protected $fillable = [
        'name',
        'original_email',
        'ngay_sinh',
        'avatar',
        'dia_chi',
        'so_dien_thoai',
        'chi_nhanh_id',
        'rap_phim_id',
        'approved',
        'approved_at',
    ];

    protected $casts = [
        'approved' => 'boolean',
        'approved_at' => 'datetime',
        'ngay_sinh' => 'date',
    ];
    public function chiNhanh()
    {
        return $this->belongsTo(ChiNhanh::class, 'chi_nhanh_id');
    }
    public function rapPhim()
    {
        return $this->belongsTo(RapPhim::class, 'rap_phim_id');
    }
}
