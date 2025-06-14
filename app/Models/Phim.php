<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Phim extends Model
{
    use SoftDeletes;
    protected $table = 'phims';

    protected $fillable = [
        'ten_phim',
        'mo_ta',
        'dao_dien',
        'dien_vien',
        'thoi_luong',
        'ngay_phat_hanh',
        'ngay_ket_thuc',
        'trailer',
        'poster',
        'ngon_ngu',
        'quoc_gia',
        'do_tuoi',
        'trang_thai',
    ];

    protected $casts = [
        'ngay_phat_hanh' => 'date',
        'ngay_ket_thuc' => 'date',
        'thoi_luong' => 'integer',
    ];

    const CREATED_AT = 'create_at';
    const UPDATED_AT = 'update_at';

    public function theLoais(): BelongsToMany
    {
        return $this->belongsToMany(TheLoaiPhim::class, 'phim_the_loais', 'phim_id', 'the_loai_phim_id');
    }

    public function dinhDangs(): BelongsToMany
    {
        return $this->belongsToMany(DinhDangPhim::class, 'phim_dinh_dangs', 'phim_id', 'dinh_dang_phim_id');
    }

    public function chiNhanhs(): BelongsToMany
    {
        return $this->belongsToMany(ChiNhanh::class, 'phim_chi_nhanhs', 'phim_id', 'chi_nhanh_id');
    }

    public function rapPhims(): BelongsToMany
    {
        return $this->belongsToMany(RapPhim::class, 'phim_raps', 'phim_id', 'rap_phim_id');
    }

    public function suatChieus()
    {
        return $this->hasMany(SuatChieu::class);
    }

       public function datVes()
    {
        return $this->hasMany(DatVe::class, 'phim_id'); 
    }
}