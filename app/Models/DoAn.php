<?php
namespace App\Models;

use App\Models\RapPhim;
use Illuminate\Database\Eloquent\Model;

class DoAn extends Model
{
    protected $table = 'do_ans'; // nếu bạn đặt tên bảng là `do_ans`

    protected $fillable = [
        'tieu_de',
        'noi_dung',
        'hinh_anh',
        'gia',
        'trang_thai',
        'danh_muc_id',
        'chi_nhanh_id',
    ];

    public function danhMuc()
    {
        return $this->belongsTo(DanhMucDoAn::class, 'danh_muc_id');
    }

    public function combos()
    {
        return $this->belongsToMany(Combo::class, 'combo_do_ans', 'do_an_id', 'combo_id');
    }
    public function chiNhanhs()
    {
        return $this->belongsToMany(ChiNhanh::class, 'chi_nhanh_do_an');
    }
    public function rapPhims()
    {
        return $this->belongsToMany(RapPhim::class, 'rap_do_an');
    }

    public function datVes()
    {
        return $this->belongsToMany(DatVe::class, 'dat_ve_do_an')
            ->withPivot('so_luong')
            ->withTimestamps();
    }
}
