<?php
namespace App\Models;

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
    ];

    public function danhMuc()
    {
        return $this->belongsTo(DanhMucDoAn::class, 'danh_muc_id');
    }

    public function combos()
    {
        return $this->belongsToMany(Combo::class, 'combo_do_ans', 'do_an_id', 'combo_id');
    }
}
