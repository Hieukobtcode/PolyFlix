<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rap extends Model
{
    use HasFactory;

    protected $table = 'raps';

    protected $fillable = [
        'khuyen_mai_id'
    ];

    // Quan hệ với khuyến mãi
    public function khuyenMai()
    {
        return $this->belongsTo(KhuyenMai::class, 'khuyen_mai_id');
    }
}
