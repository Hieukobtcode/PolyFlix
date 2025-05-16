<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LichSuSuDungKhuyenMai extends Model
{
    use HasFactory;

    protected $table = 'lich_su_su_dung_khuyen_mais';

    protected $fillable = [
        'khuyen_mai_id',
        'nguoi_dung_id',
        'thoi_gian_su_dung'
    ];

    protected $casts = [
        'thoi_gian_su_dung' => 'datetime'
    ];

    // Quan hệ với khuyến mãi
    public function khuyenMai()
    {
        return $this->belongsTo(KhuyenMai::class, 'khuyen_mai_id');
    }

    // Quan hệ với người dùng
    public function nguoiDung()
    {
        return $this->belongsTo(User::class, 'nguoi_dung_id');
    }
}
