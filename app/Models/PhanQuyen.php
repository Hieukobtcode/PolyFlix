<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PhanQuyen extends Model
{
    use HasFactory;

    protected $table = 'phan_quyens';

    protected $fillable = ['ten', 'slug'];

    // Quan hệ nhiều-nhiều với vai trò
    public function vaiTros()
    {
        return $this->belongsToMany(VaiTro::class, 'vai_tro_phan_quyens', 'phan_quyen_id', 'vai_tro_id');
    }
}
