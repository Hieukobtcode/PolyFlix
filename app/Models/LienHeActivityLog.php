<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LienHeActivityLog extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lien_he_activity_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lien_he_id',
        'hanh_dong',
        'mo_ta',
        'nguoi_thuc_hien',
        'du_lieu_cu',
        'du_lieu_moi',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'du_lieu_cu' => 'array',
        'du_lieu_moi' => 'array',
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
     * Get the contact that owns the activity log.
     */
    public function lienHe()
    {
        return $this->belongsTo(LienHe::class, 'lien_he_id');
    }
}