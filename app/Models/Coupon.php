<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'minimum_order_amount',
        'maximum_discount_amount',
        'usage_limit',
        'usage_count',
        'per_user_limit',
        'starts_at',
        'expires_at',
        'is_active',
        'restricted_to_categories',
        'restricted_to_products',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'minimum_order_amount' => 'decimal:2',
            'maximum_discount_amount' => 'decimal:2',
            'usage_limit' => 'integer',
            'usage_count' => 'integer',
            'per_user_limit' => 'integer',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
            'restricted_to_categories' => 'array',
            'restricted_to_products' => 'array',
        ];
    }

    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
            });
    }

    public function isValid(?User $user = null, float $subtotal = 0): array
    {
        if (! $this->is_active) {
            return ['valid' => false, 'message' => __('این کد تخفیف فعال نیست.')];
        }

        $now = now();
        if ($this->starts_at && $now->lt($this->starts_at)) {
            return ['valid' => false, 'message' => __('مهلت استفاده از این کد تخفیف هنوز آغاز نشده است.')];
        }

        if ($this->expires_at && $now->gt($this->expires_at)) {
            return ['valid' => false, 'message' => __('این کد تخفیف منقضی شده است.')];
        }

        if ($this->usage_limit !== null && $this->usage_count >= $this->usage_limit) {
            return ['valid' => false, 'message' => __('ظرفیت استفاده از این کد تخفیف به پایان رسیده است.')];
        }

        if ($this->minimum_order_amount !== null && $subtotal < $this->minimum_order_amount) {
            return [
                'valid' => false,
                'message' => __('حداقل مبلغ سفارش برای استفاده از این کد :amount تومان می‌باشد.', ['amount' => format_price($this->minimum_order_amount)]),
            ];
        }

        if ($user) {
            $userUsageCount = $this->usages()->where('user_id', $user->id)->count();
            if ($userUsageCount >= $this->per_user_limit) {
                return ['valid' => false, 'message' => __('شما پیش از این از حداکثر سهمیه این کد تخفیف استفاده کرده‌اید.')];
            }
        }

        return ['valid' => true, 'message' => __('کد تخفیف معتبر است.')];
    }

    public function calculateDiscount(float $subtotal): float
    {
        if ($this->type === 'percentage') {
            $discount = ($subtotal * $this->value) / 100;
            if ($this->maximum_discount_amount !== null && $discount > $this->maximum_discount_amount) {
                $discount = (float) $this->maximum_discount_amount;
            }

            return round($discount, 2);
        }

        // fixed discount
        return round(min($subtotal, (float) $this->value), 2);
    }
}
