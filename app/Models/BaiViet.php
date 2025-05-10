<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaiViet extends Model
{
    protected $table = 'bai_viets'; // tên bảng trong database

    public $timestamps = false; // vì bảng không có cột created_at, updated_at

    protected $fillable = [
        'tieu_de',
        'noi_dung',
        'hinh_anh',
        'ngay_tao',
        'ngay_cap_nhat',
        'status',
    ];
}
