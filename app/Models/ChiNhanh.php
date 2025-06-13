<?php

namespace App\Models;

use App\Models\KhuyenMai;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
}
