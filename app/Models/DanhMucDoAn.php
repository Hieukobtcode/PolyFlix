<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanhMucDoAn extends Model
{
    protected $table = 'danh_muc_do_ans';

    protected $fillable = [
        'ten',
    ];

    public function doAns()
    {
        return $this->hasMany(DoAn::class, 'danh_muc_id');
    }
}
