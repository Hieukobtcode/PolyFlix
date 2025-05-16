<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KhuyenMaiChiNhanh extends Model
{
    use HasFactory;

    protected $table = 'khuyen_mai_chi_nhanhs';

    // Định nghĩa tên cột created_at và updated_at mặc định
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'khuyen_mai_id',
        'chi_nhanh_id'
    ];

    // Quan hệ với khuyến mãi
    public function khuyenMai()
    {
        return $this->belongsTo(KhuyenMai::class, 'khuyen_mai_id');
    }

    // Quan hệ với chi nhánh
    public function chiNhanh()
    {
        return $this->belongsTo(ChiNhanh::class, 'chi_nhanh_id');
    }
}
