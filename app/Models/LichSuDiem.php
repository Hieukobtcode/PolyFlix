<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LichSuDiem extends Model
{
    protected $table = 'lich_su_diem'; // chỉ định tên bảng

    protected $fillable = [
        'users_id',
        'thay_doi',
        'ly_do',
        'thoi_gian',
    ];

    // Quan hệ với user
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}