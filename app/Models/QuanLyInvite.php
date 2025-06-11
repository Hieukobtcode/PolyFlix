<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuanLyInvite extends Model
{
    //
    protected $fillable = ['email', 'token','chi_nhanh_id','rap_phim_id', 'loai_quan_ly', 'expires_at', 'used'];
    protected $casts = [
        'expires_at' => 'datetime',
        'used' => 'boolean',
    ];
}
