<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KhuyenMaiRapPhim extends Model
{
    use HasFactory;

    protected $table = 'khuyen_mai_rap_phims';

    protected $fillable = [
        'khuyen_mai_id',
        'rap_phim_id'
    ];

    // Quan hệ với khuyến mãi
    public function khuyenMai()
    {
        return $this->belongsTo(KhuyenMai::class, 'khuyen_mai_id');
    }

    // Quan hệ với rạp phim
    public function rapPhim()
    {
        return $this->belongsTo(RapPhim::class, 'rap_phim_id');
    }
}
