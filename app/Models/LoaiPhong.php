<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoaiPhong extends Model
{
    protected $table = 'loai_phongs';

    protected $fillable = [
        'ten_loai_phong',
        'mo_ta',
        'create_at',
        'update_at',
    ];

    public function phongChieus(){
        return $this->hasMany(PhongChieu::class,'loai_phong_id');
    }

    public $timestamps = true; // bật tính năng timestamps

    const CREATED_AT = 'create_at';  // đổi tên cột created_at thành create_at
    const UPDATED_AT = 'update_at';  // đổi tên cột updated_at thành update_at
}
