<?php
namespace App\Models;

use App\Models\RapPhim;
use Illuminate\Database\Eloquent\Model;

class Combo extends Model
{
    protected $table = 'combos';

    protected $fillable = [
        'tieu_de',
        'noi_dung',
        'hinh_anh',
        'gia',
        'gia_combo',
        'trang_thai',
    ];

    public function doAns()
    {
        return $this->belongsToMany(DoAn::class, 'combo_do_ans')
            ->withPivot('so_luong')
            ->withTimestamps();
    }
    public function chiNhanhs()
    {
        return $this->belongsToMany(ChiNhanh::class, 'chi_nhanh_combo');
    }

    public function rapPhims()
    {
        return $this->belongsToMany(RapPhim::class, 'rap_combo');
    }

    public function datVes()
    {
        return $this->belongsToMany(DatVe::class, 'dat_ve_combo')
            ->withPivot('so_luong')
            ->withTimestamps();
    }
}
