<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\SiteSetting;

class PricingService
{
    /**
     * Calculate all prices for a given cart and optional coupon.
     *
     * @return array{
     *     subtotal: float,
     *     discount: float,
     *     tax: float,
     *     shipping: float,
     *     grand_total: float,
     *     coupon_code: ?string,
     *     is_free_shipping: bool
     * }
     */
    public function calculateCartTotals(Cart $cart, ?Coupon $coupon = null): array
    {
        $subtotal = 0.0;

        foreach ($cart->items as $item) {
            $unitPrice = $item->variant
                ? $item->variant->effective_price
                : $item->product->effective_price;

            $subtotal += ($unitPrice * $item->quantity);
        }

        $discount = 0.0;
        $couponCode = null;

        if ($coupon) {
            $validation = $coupon->isValid($cart->user, $subtotal);
            if ($validation['valid']) {
                $discount = $coupon->calculateDiscount($subtotal);
                $couponCode = $coupon->code;
            }
        }

        $taxRate = (float) SiteSetting::get('tax_rate', 0.0); // e.g. 0.09 or 0.10
        $taxableAmount = max(0, $subtotal - $discount);
        $tax = round($taxableAmount * $taxRate, 2);

        $freeShippingThreshold = (float) SiteSetting::get('free_shipping_threshold', 10000000); // 10,000,000 IRR / 1,000,000 Toman
        $standardShippingCost = (float) SiteSetting::get('shipping_cost', 450000); // 450,000 IRR / 45,000 Toman

        if ($subtotal >= $freeShippingThreshold || $subtotal == 0) {
            $shipping = 0.0;
            $isFreeShipping = true;
        } else {
            $shipping = $standardShippingCost;
            $isFreeShipping = false;
        }

        $grandTotal = max(0, round($subtotal - $discount + $tax + $shipping, 2));

        return [
            'subtotal' => round($subtotal, 2),
            'discount' => round($discount, 2),
            'tax' => round($tax, 2),
            'shipping' => round($shipping, 2),
            'grand_total' => $grandTotal,
            'coupon_code' => $couponCode,
            'is_free_shipping' => $isFreeShipping,
        ];
    }
}
