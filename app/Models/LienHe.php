<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class LienHe extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lien_hes';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ten',
        'email',
        'so_dien_thoai',
        'noi_dung',
        'trang_thai',
        'muc_do_uu_tien',
        'nguon_goc',
        'phan_loai',
        'ghi_chu_noi_bo',
        'nguoi_phu_trach',
        'ngay_hen',
        'da_phan_hoi',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'trang_thai' => false,
        'muc_do_uu_tien' => 'binh_thuong',
        'da_phan_hoi' => false,
        'phan_loai' => 'Phân loại',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'create_at';

    /**
     * Get the name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'update_at';

    /**
     * Scope a query to filter contacts by search term.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $term
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch(Builder $query, $term)
    {
        if ($term) {
            return $query->where(function ($query) use ($term) {
                $query->where('ten', 'LIKE', "%{$term}%")
                    ->orWhere('email', 'LIKE', "%{$term}%")
                    ->orWhere('so_dien_thoai', 'LIKE', "%{$term}%")
                    ->orWhere('noi_dung', 'LIKE', "%{$term}%");
            });
        }

        return $query;
    }

    /**
     * Scope a query to filter contacts by status.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  bool|null  $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatus(Builder $query, $status)
    {
        if (!is_null($status)) {
            return $query->where('trang_thai', $status);
        }

        return $query;
    }

    /**
     * Scope a query to filter contacts by priority.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string|null  $priority
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePriority(Builder $query, $priority)
    {
        if ($priority) {
            return $query->where('muc_do_uu_tien', $priority);
        }

        return $query;
    }

    /**
     * Scope a query to filter contacts by category.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string|null  $category
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCategory(Builder $query, $category)
    {
        if ($category) {
            return $query->where('phan_loai', $category);
        }

        return $query;
    }

    /**
     * Scope a query to filter contacts by date range.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string|null  $from
     * @param  string|null  $to
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDateRange(Builder $query, $from, $to)
    {
        if ($from && $to) {
            return $query->whereBetween('create_at', [$from, $to]);
        }

        if ($from) {
            return $query->where('create_at', '>=', $from);
        }

        if ($to) {
            return $query->where('create_at', '<=', $to);
        }

        return $query;
    }

    /**
     * Get the formatted priority.
     *
     * @return string
     */
    public function getPriorityLabelAttribute()
    {
        return match ($this->muc_do_uu_tien) {
            'cao' => 'Cao',
            'thap' => 'Thấp',
            default => 'Bình thường',
        };
    }

    /**
     * Get the priority badge class.
     *
     * @return string
     */
    public function getPriorityBadgeAttribute()
    {
        return match ($this->muc_do_uu_tien) {
            'cao' => 'bg-danger',
            'thap' => 'bg-info',
            default => 'bg-warning',
        };
    }

    /**
     * Get the formatted create_at attribute.
     *
     * @return string
     */
    public function getFormattedCreateAtAttribute()
    {
        return date('d/m/Y H:i:s', strtotime($this->create_at));
    }

    /**
     * Get the formatted update_at attribute.
     *
     * @return string
     */
    public function getFormattedUpdateAtAttribute()
    {
        return date('d/m/Y H:i:s', strtotime($this->update_at));
    }

    /**
     * Get all the activity logs for this contact.
     */
    public function activityLogs()
    {
        return $this->hasMany(LienHeActivityLog::class, 'lien_he_id');
    }

    /**
     * Get all the notes for this contact.
     */
    public function notes()
    {
        return $this->hasMany(LienHeNote::class, 'lien_he_id');
    }
}
