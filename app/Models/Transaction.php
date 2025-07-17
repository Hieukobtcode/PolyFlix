<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code',
        'dat_ve_id',
        'user_id',
        'payment_method',
        'amount',
        'currency',
        'gateway_transaction_id',
        'gateway_order_id',
        'gateway_response',
        'status',
        'payment_url',
        'paid_at',
        'note'
    ];

    protected $casts = [
        'gateway_response' => 'array',
        'paid_at' => 'datetime',
        'amount' => 'decimal:2'
    ];

    /**
     * Relationship với bảng dat_ves
     */
    public function datVe(): BelongsTo
    {
        return $this->belongsTo(DatVe::class, 'dat_ve_id');
    }

    /**
     * Relationship với bảng users
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope để lọc theo trạng thái
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope để lọc theo phương thức thanh toán
     */
    public function scopePaymentMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    /**
     * Tạo mã giao dịch duy nhất
     */
    public static function generateTransactionCode($prefix = 'TXN')
    {
        do {
            $code = $prefix . date('YmdHis') . rand(1000, 9999);
        } while (self::where('transaction_code', $code)->exists());

        return $code;
    }

    /**
     * Kiểm tra giao dịch có thành công không
     */
    public function isSuccess(): bool
    {
        return $this->status === 'success';
    }

    /**
     * Kiểm tra giao dịch có thất bại không
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Kiểm tra giao dịch có đang chờ không
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}
