<?php

namespace App\Models;

use App\Models\User;
use App\Models\KhuyenMai;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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

    // Quan hệ với khuyến mãi thông qua bảng trung gian
    public function khuyenMais()
    {
        return $this->belongsToMany(KhuyenMai::class, 'khuyen_mai_chi_nhanhs', 'chi_nhanh_id', 'khuyen_mai_id')
            ->withTimestamps('created_at', 'updated_at');
    }
    public function rapPhims()
    {
        return $this->hasMany(RapPhim::class, 'chi_nhanh_id');
    }

    public function phims(): BelongsToMany
    {
        return $this->belongsToMany(Phim::class, 'phim_chi_nhanhs', 'chi_nhanh_id', 'phim_id');
    }

    public function doAns()
    {
        return $this->belongsToMany(DoAn::class, 'chi_nhanh_do_an');
    }

    public function combos()
    {
        return $this->belongsToMany(Combo::class, 'chi_nhanh_combo');
    }

    public function quanLy()
    {
        return $this->belongsTo(User::class, 'quan_ly_id');
    }
}


