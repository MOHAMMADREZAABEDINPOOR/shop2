<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CouponService
{
    /**
     * Validate coupon code for a given cart or user.
     *
     * @return array{valid: bool, message: string, coupon: ?Coupon}
     */
    public function validateCoupon(string $code, ?User $user, float $subtotal, ?Cart $cart = null): array
    {
        $coupon = Coupon::where('code', strtoupper(trim($code)))->first();

        if (! $coupon) {
            return [
                'valid' => false,
                'message' => 'کد تخفیف وارد شده یافت نشد.',
                'coupon' => null,
            ];
        }

        $check = $coupon->isValid($user, $subtotal);
        if (! $check['valid']) {
            return [
                'valid' => false,
                'message' => $check['message'],
                'coupon' => null,
            ];
        }

        // Check category restrictions if specified
        if ($cart && ! empty($coupon->restricted_to_categories)) {
            $cartCategoryIds = $cart->items->pluck('product.category_id')->filter()->unique()->toArray();
            $matches = array_intersect($cartCategoryIds, $coupon->restricted_to_categories);
            if (empty($matches)) {
                return [
                    'valid' => false,
                    'message' => 'این کد تخفیف برای دسته‌بندی اقلام سبد خرید شما معتبر نمی‌باشد.',
                    'coupon' => null,
                ];
            }
        }

        // Check product restrictions if specified
        if ($cart && ! empty($coupon->restricted_to_products)) {
            $cartProductIds = $cart->items->pluck('product_id')->unique()->toArray();
            $matches = array_intersect($cartProductIds, $coupon->restricted_to_products);
            if (empty($matches)) {
                return [
                    'valid' => false,
                    'message' => 'این کد تخفیف برای محصولات موجود در سبد خرید شما معتبر نمی‌باشد.',
                    'coupon' => null,
                ];
            }
        }

        return [
            'valid' => true,
            'message' => 'کد تخفیف با موفقیت اعمال شد.',
            'coupon' => $coupon,
        ];
    }

    /**
     * Record usage after successful order creation/payment.
     */
    public function recordUsage(Coupon $coupon, User $user, Order $order, float $discountAmount): void
    {
        DB::transaction(function () use ($coupon, $user, $order, $discountAmount) {
            $coupon->increment('usage_count');

            CouponUsage::create([
                'coupon_id' => $coupon->id,
                'user_id' => $user->id,
                'order_id' => $order->id,
                'discount_amount' => $discountAmount,
            ]);
        });
    }
}
