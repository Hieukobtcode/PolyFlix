<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
     protected $fillable = [
        'user_id',
        'phim_id',
        'content',
        'rating',
        'visible',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function phim()
    {
        return $this->belongsTo(Phim::class, 'phim_id');
    }
}
