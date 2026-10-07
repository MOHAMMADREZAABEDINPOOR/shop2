<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'payment_status',
        'shipping_status',
        'currency',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'shipping_cost',
        'grand_total',
        'coupon_code',
        'coupon_discount',
        'shipping_address_snapshot',
        'billing_address_snapshot',
        'shipping_method',
        'payment_method',
        'notes',
        'tracking_number',
        'paid_at',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'grand_total' => 'decimal:2',
            'coupon_discount' => 'decimal:2',
            'shipping_address_snapshot' => 'array',
            'billing_address_snapshot' => 'array',
            'paid_at' => 'datetime',
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function latestPayment(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'awaiting_payment', 'paid', 'processing']);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => __('در انتظار پرداخت'),
            'awaiting_payment' => __('در حال پرداخت'),
            'paid' => __('پرداخت شده'),
            'processing' => __('در حال پردازش'),
            'packed' => __('بسته‌بندی شده'),
            'shipped' => __('تحویل به پست/پیک'),
            'delivered' => __('تحویل داده شده'),
            'cancelled' => __('لغو شده'),
            'returned' => __('مرجوع شده'),
            'refunded' => __('استرداد وجه'),
            default => $this->status,
        };
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            'pending' => __('در انتظار پرداخت'),
            'paid' => __('پرداخت موفق'),
            'failed' => __('پرداخت ناموفق'),
            'refunded' => __('مسترد شده'),
            default => $this->payment_status,
        };
    }

    public function scopePaid(Builder $query): Builder
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }
}
