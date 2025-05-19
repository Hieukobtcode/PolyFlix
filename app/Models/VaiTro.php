<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VaiTro extends Model
{
    //
    use HasFactory;

    protected $table = 'vai_tros';  // Tên bảng
    protected $fillable = ['ten', 'mo_ta'];  // gán giá trị các trường
}
