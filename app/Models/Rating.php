<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    //
    protected $fillable = ['user_id', 'dat_ve_id', 'phim_id', 'rating'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function phim()
    {
        return $this->belongsTo(Phim::class);
    }
}