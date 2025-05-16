<?php

namespace App\Models;

use App\Models\ChiNhanh;
use App\Models\KhuyenMaiChiNhanh;
use App\Models\LichSuSuDungKhuyenMai;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KhuyenMai extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'khuyen_mais';

    protected $fillable = [
        'ma_khuyen_mai',
        'ten',
        'mo_ta',
        'loai_giam_gia',
        'gia_tri_giam',
        'giam_toi_da',
        'ap_dung_cho',
        'don_toi_thieu',
        'ngay_bat_dau',
        'ngay_ket_thuc',
        'so_lan_su_dung_toi_da',
        'so_lan_da_su_dung',
        'trang_thai'
    ];

    protected $casts = [
        'gia_tri_giam' => 'decimal:2',
        'giam_toi_da' => 'decimal:2',
        'don_toi_thieu' => 'decimal:2',
        'so_lan_su_dung_toi_da' => 'integer',
        'so_lan_da_su_dung' => 'integer',
        'ngay_bat_dau' => 'datetime',
        'ngay_ket_thuc' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Định nghĩa tên cột created_at và updated_at tùy chỉnh
    const CREATED_AT = 'create_at';
    const UPDATED_AT = 'update_at';

    // Quan hệ với chi nhánh thông qua bảng trung gian
    public function chiNhanhs()
    {
        return $this->belongsToMany(ChiNhanh::class, 'khuyen_mai_chi_nhanhs', 'khuyen_mai_id', 'chi_nhanh_id')
            ->withTimestamps('created_at', 'updated_at');
    }

    // Quan hệ với lịch sử sử dụng khuyến mãi
    public function lichSuSuDung()
    {
        return $this->hasMany(LichSuSuDungKhuyenMai::class, 'khuyen_mai_id');
    }

    // Quan hệ với bảng trung gian khuyến mãi chi nhánh
    public function khuyenMaiChiNhanhs()
    {
        return $this->hasMany(KhuyenMaiChiNhanh::class, 'khuyen_mai_id');
    }

    // Quan hệ với rạp
    public function raps()
    {
        return $this->hasMany(Rap::class, 'khuyen_mai_id');
    }

    // Scope để lọc khuyến mãi theo trạng thái
    public function scopeTrangThai($query, $trangThai)
    {
        return $query->where('trang_thai', $trangThai);
    }

    // Scope để lọc khuyến mãi còn hiệu lực
    public function scopeConHieuLuc($query)
    {
        return $query->where('ngay_ket_thuc', '>=', now())
            ->where('ngay_bat_dau', '<=', now())
            ->where('trang_thai', 'hoat_dong');
    }

    // Scope để lọc khuyến mãi theo loại áp dụng
    public function scopeApDungCho($query, $loai)
    {
        return $query->where('ap_dung_cho', $loai);
    }
}
