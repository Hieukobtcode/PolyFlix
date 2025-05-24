<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class RapPhim extends Model
{
    //
    use HasFactory, SoftDeletes;

    protected $table = 'rap_phims'; // Tên bảng

    protected $fillable = [
        'chi_nhanh_id',
        'ten_rap',
        'dia_chi',
        'so_dien_thoai',
        'email',
        'trang_thai',      
    ];
    
       public function chiNhanh()
    {
        return $this->belongsTo(ChiNhanh::class, 'chi_nhanh_id');
    }

    public function phongChieus(){
        return $this->hasMany(PhongChieu::class,'rap_phim_id');
    }
    protected $dates = ['delete_at'];// đảm bảo laravel hiểu đây là kiểu ngày tháng

}
