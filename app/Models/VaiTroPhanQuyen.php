<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VaiTroPhanQuyen extends Model
{
    use HasFactory;

    protected $table = 'vai_tro_phan_quyens';

    protected $fillable = ['vai_tro_id', 'phan_quyen_id'];

    public function vaiTro()
    {
        return $this->belongsTo(VaiTro::class, 'vai_tro_id');
    }

    public function phanQuyen()
    {
        return $this->belongsTo(PhanQuyen::class, 'phan_quyen_id');
    }
}
