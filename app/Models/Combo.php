<?php
namespace App\Models;

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
        return $this->belongsToMany(DoAn::class, 'combo_do_ans', 'combo_id', 'do_an_id');
    }
}
