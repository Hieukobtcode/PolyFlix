<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class SuatChieu extends Model
{
    use HasFactory;

    protected $table = 'suat_chieus';

    protected $fillable = [
        'phim_id',
        'phong_chieu_id',
        'phien_ban_phim',
        'ngay_chieu',
        'bat_dau',
        'ket_thuc',
        'trang_thai',
    ];

    public function phim()
    {
        return $this->belongsTo(Phim::class);
    }

    public function phongChieu()
    {
        return $this->belongsTo(PhongChieu::class, 'phong_chieu_id');
    }

    public function chiNhanh()
    {
        return $this->belongsTo(ChiNhanh::class, 'chi_nhanh_id', 'id');
    }

    public function rapPhims()
    {
        return $this->belongsTo(RapPhim::class, 'phong_chieu_id', 'id');
    }

    public function datVes()
    {
        return $this->hasMany(DatVe::class, 'suat_chieu_id');
    }

    public function dinhDangPhim()
    {
        return $this->belongsTo(DinhDangPhim::class, 'phien_ban_phim', 'id');
    }

    public function chiNhanhs()
    {
        return $this->phim ? $this->phim->chiNhanhs() : collect();
    }

    public function getFormattedVersionAttribute()
    {
        $phienBanSlug = $this->phien_ban_phim;

        if (!$this->phim || !$this->phim->relationLoaded('dinhDangs') || !$this->phim->relationLoaded('phuDes')) {
            return 'Không rõ';
        }

        foreach ($this->phim->dinhDangs as $f) {
            foreach ($this->phim->phuDes as $s) {
                $slug = strtolower(Str::slug($f->ten_dinh_dang) . '-' . Str::slug($s->ten_phu_de));
                if ($slug === $phienBanSlug) {
                    return "{$f->ten_dinh_dang} – {$s->ten_phu_de}";
                }
            }
        }

        return 'Không rõ';
    }
}
