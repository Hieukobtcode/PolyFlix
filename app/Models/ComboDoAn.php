<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComboDoAn extends Model
{
      protected $table = 'combo_do_ans';

    protected $fillable = [
        'combo_id',
        'do_an_id',
    ];

    public function combo()
    {
        return $this->belongsTo(Combo::class);
    }

    public function doAn()
    {
        return $this->belongsTo(DoAn::class, 'do_an_id');
    }
}
