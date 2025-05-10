<?php

namespace App\Models;

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
}
