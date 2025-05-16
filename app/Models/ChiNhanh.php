<?php

namespace App\Models;

use App\Models\KhuyenMai;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiNhanh extends Model
{
    use HasFactory;

    protected $table = 'chi_nhanhs'; // Tên bảng

    protected $fillable = [
        'ten_chi_nhanh',
        'dia_chi',
        'quan_ly_id',
        'trang_thai',
    ];

    // Quan hệ với khuyến mãi thông qua bảng trung gian
    public function khuyenMais()
    {
        return $this->belongsToMany(KhuyenMai::class, 'khuyen_mai_chi_nhanhs', 'chi_nhanh_id', 'khuyen_mai_id')
            ->withTimestamps('created_at', 'updated_at');
    }
}
