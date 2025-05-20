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

    // Quan hệ nhiều user thuộc 1 vai trò
    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }

    // Quan hệ nhiều-nhiều với phân quyền
    public function phanQuyens()
    {
        return $this->belongsToMany(PhanQuyen::class, 'vai_tro_phan_quyens', 'vai_tro_id', 'phan_quyen_id');
    }
}
