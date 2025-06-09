<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class RapPhim extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rap_phims';

    protected $fillable = [
        'chi_nhanh_id',
        'ten_rap',
        'dia_chi',
        'trang_thai',
        'quan_ly_id',
    ];

    protected $dates = ['deleted_at'];


    // Quan hệ với chi nhánh
    public function chiNhanh()
    {
        return $this->belongsTo(ChiNhanh::class, 'chi_nhanh_id');
    }

    // Quan hệ với người quản lý (giả sử là model User)
    // public function quanLy()
    // {
    //     return $this->belongsTo(User::class, 'quan_ly_id');
    // }

    public function phongChieus()
    {
        return $this->hasMany(PhongChieu::class, 'rap_phim_id');
    }

    public function phims(): BelongsToMany
    {
        return $this->belongsToMany(Phim::class, 'phim_raps', 'rap_phim_id', 'phim_id');
    }
}