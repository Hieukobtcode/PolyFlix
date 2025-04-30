<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LienHeNote extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lien_he_notes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lien_he_id',
        'noi_dung',
        'nguoi_tao',
    ];

    /**
     * Get the formatted created_at attribute.
     *
     * @return string
     */
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('d/m/Y H:i:s');
    }

    /**
     * Get the contact that owns the note.
     */
    public function lienHe()
    {
        return $this->belongsTo(LienHe::class, 'lien_he_id');
    }
}
