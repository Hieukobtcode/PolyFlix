<?php

namespace App\Models;

use App\Models\User;
use App\Models\GheNgoi;
use App\Models\SuatChieu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GheNgoiSuatChieu extends Model
{
    //
    use HasFactory;

    protected $table = 'ghe_ngoi_suat_chieu';

    protected $fillable = [
        'ghe_ngoi_id',
        'suat_chieu_id',
        'trang_thai',
        'user_id',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    // Helpers
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isLocked() // còn đang giữ
    {
        return $this->trang_thai === 'da_chon' && !$this->isExpired();
    }

    public function isBooked()
    {
        return $this->trang_thai === 'da_dat';
    }

    // ======================
    // Relationships
    // ======================

    public function gheNgoi()
    {
        return $this->belongsTo(GheNgoi::class, 'ghe_ngoi_id');
    }

    public function suatChieu()
    {
        return $this->belongsTo(SuatChieu::class, 'suat_chieu_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ======================
    // Helpers
    // ======================

    public function isAvailable()
    {
        return $this->trang_thai === 'trong';
    }

    public function isDangChon()
    {
        return $this->trang_thai === 'da_chon';
    }

    public function isDaDat()
    {
        return $this->trang_thai === 'da_dat';
    }
}
