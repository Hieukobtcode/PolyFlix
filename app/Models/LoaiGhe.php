<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoaiGhe extends Model
{
    use HasFactory;

    protected $table = 'loai_ghes';

    protected $fillable = [
        'ten_loai_ghe',
        'mo_ta',
        'phu_thu',
    ];

     public function gheNgois()
    {
        return $this->hasMany(GheNgoi::class);
    }
    
}
