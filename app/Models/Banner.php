<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Banner extends Model
{
    use HasFactory, SoftDeletes;
    // quy dinh model nay thao tac vowis bang nao
    protected $table = 'banners';
    // cac truowng trong bang deu phai dua vao fillable 
    protected $fillable = [
        
        'hinh_anh',
        'duong_dan',
        'vi_tri',
        'trang_thai'
    ];
    protected $dates = ['delete_at'];// đảm bảo laravel hiểu đây là kiểu ngày tháng

}
