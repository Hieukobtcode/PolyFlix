<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'cap_bac_id',
        'name',
        'rap_id',
        'email',
        'password',
        'avatar',
        'vai_tro_id',
        'dia_chi',
        'so_dien_thoai',
        'diem',
        'trang_thai',
        'hoat_dong',
        'ngay_sinh',
        'email_verified_at'
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'hoat_dong' => 'boolean',
        ];
    }

    public function vaiTro()
    {
        return $this->belongsTo(VaiTro::class, 'vai_tro_id');
    }

    public function phanQuyens()
    {
        return $this->vaiTro ? $this->vaiTro->phanQuyens() : collect();
    }

    public function coQuyen($slug)
    {
        return $this->phanQuyens()->contains('slug', $slug);
    }

    public function datVes()
    {
        return $this->hasMany(DatVe::class, 'nguoi_dung_id');
    }
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function lichSuDiem()
    {
        return $this->hasMany(LichSuDiem::class, 'users_id');
    }

    // User là quản lý của 1 chi nhánh
    public function chiNhanhDangQuanLy()
    {
        return $this->hasOne(ChiNhanh::class, 'quan_ly_id');
    }

    
    public function rapPhim()
    {
        return $this->belongsTo(RapPhim::class, 'rap_id');
    }

}