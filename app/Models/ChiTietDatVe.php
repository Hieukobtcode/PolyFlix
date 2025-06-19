<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChiTietDatVe extends Model
{
    protected $table = 'chi_tiet_dat_ves'; 

    protected $fillable = [
        'dat_ve_id',
        'ghe_id',
    ];

    public function datVe()
{
    return $this->belongsTo(DatVe::class, 'dat_ve_id');
}

    public function ghe()
    {
        return $this->belongsTo(GheNgoi::class, 'ghe_id');
    }
}
