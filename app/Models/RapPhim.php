<?php

namespace App\Models;

use App\Models\DoAn;
use App\Models\User;
use App\Models\Combo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RapPhim extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rap_phims';

    protected $fillable = [
        'chi_nhanh_id',
        'ten_rap',
        'dia_chi',
        'trang_thai',
        'phu_thu',
        'quan_ly_id',
    ];

    protected $dates = ['deleted_at'];


    // Quan hệ với chi nhánh
    public function chiNhanh()
    {
        return $this->belongsTo(ChiNhanh::class, 'chi_nhanh_id');
    }

    // Quan hệ với người quản lý 
    public function quanLy()
    {
        return $this->belongsTo(User::class, 'quan_ly_id');
    }

    public function phongChieus()
    {
        return $this->hasMany(PhongChieu::class, 'rap_phim_id');
    }

    public function phims(): BelongsToMany
    {
        return $this->belongsToMany(Phim::class, 'phim_raps', 'rap_phim_id', 'phim_id');
    }
    public function combos()
    {
        return $this->belongsToMany(Combo::class, 'rap_combo');
    }
    public function doAns()
    {
        return $this->belongsToMany(DoAn::class, 'rap_do_an');
    }
    public function users()
    {
        return $this->hasMany(User::class, 'rap_id');
    }

    // Quan hệ với khuyến mãi thông qua bảng trung gian
    public function khuyenMais()
    {
        return $this->belongsToMany(KhuyenMai::class, 'khuyen_mai_rap_phims', 'rap_phim_id', 'khuyen_mai_id')
            ->withTimestamps('created_at', 'updated_at');
    }
}
