<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CapBacThe extends Model
{
    protected $table = 'cap_bac_thes';

    /**
     * Các thuộc tính có thể gán hàng loạt
     */
    protected $fillable = [
        'ten',
        'mo_ta',
        'tong_chi_tieu',
        'phan_tram_ve',
        'phan_tram_dich_vu',
        'is_default',
    ];

    /**
     * Các thuộc tính nên được ép kiểu
     */
    protected $casts = [
        'is_default' => 'boolean',
    ];
}
