<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutService
{
    public function __construct(
        protected PricingService $pricingService,
        protected InventoryService $inventoryService,
        protected CartService $cartService
    ) {}

    /**
     * Process checkout and create an order with comprehensive snapshots.
     *
     * @throws Exception
     */
    public function createOrderFromCart(
        Cart $cart,
        User $user,
        Address $address,
        string $shippingMethod = 'standard',
        string $paymentMethod = 'test_gateway',
        ?string $notes = null
    ): Order {
        if ($cart->items->isEmpty()) {
            throw new Exception('سبد خرید شما خالی است.');
        }

        // Validate stock for all items prior to order generation
        foreach ($cart->items as $item) {
            if (! $this->inventoryService->hasStock($item->product_id, $item->product_variant_id, $item->quantity)) {
                $name = $item->variant ? "{$item->product->name} ({$item->variant->variant_label})" : $item->product->name;
                throw new Exception("موجودی محصول '{$name}' کافی نمی‌باشد. لطفاً سبد خرید خود را بررسی نمایید.");
            }
        }

        // Calculate final server-side prices
        $summary = $this->pricingService->calculateCartTotals($cart, $cart->coupon);

        return DB::transaction(function () use ($cart, $user, $address, $shippingMethod, $paymentMethod, $notes, $summary) {
            $orderNumber = 'ORD-'.date('Ymd').'-'.strtoupper(Str::random(6));

            $addressSnapshot = [
                'recipient_name' => $address->recipient_name,
                'recipient_phone' => $address->recipient_phone,
                'province' => $address->province,
                'city' => $address->city,
                'postal_code' => $address->postal_code,
                'address_line' => $address->address_line,
                'plaque' => $address->plaque,
                'unit' => $address->unit,
                'full_address' => $address->full_address,
            ];

            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $user->id,
                'status' => 'pending',
                'payment_status' => 'pending',
                'shipping_status' => 'pending',
                'currency' => 'IRR',
                'subtotal' => $summary['subtotal'],
                'discount_amount' => $summary['discount'],
                'tax_amount' => $summary['tax'],
                'shipping_cost' => $summary['shipping'],
                'grand_total' => $summary['grand_total'],
                'coupon_code' => $summary['coupon_code'],
                'coupon_discount' => $summary['discount'],
                'shipping_address_snapshot' => $addressSnapshot,
                'billing_address_snapshot' => $addressSnapshot,
                'shipping_method' => $shippingMethod,
                'payment_method' => $paymentMethod,
                'notes' => $notes,
            ]);

            foreach ($cart->items as $item) {
                $unitPrice = $item->variant
                    ? $item->variant->effective_price
                    : $item->product->effective_price;

                $totalPrice = $unitPrice * $item->quantity;

                $variantSnapshot = null;
                if ($item->variant) {
                    $variantSnapshot = [
                        'variant_id' => $item->variant->id,
                        'attributes' => $item->variant->attributes_json,
                        'label' => $item->variant->variant_label,
                        'sku' => $item->variant->sku,
                    ];
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_name' => $item->product->name,
                    'sku' => $item->variant ? $item->variant->sku : $item->product->sku,
                    'variant_snapshot' => $variantSnapshot,
                    'unit_price' => $unitPrice,
                    'quantity' => $item->quantity,
                    'total_price' => $totalPrice,
                ]);
            }

            // Clear user's cart after creating order
            $this->cartService->clearCart($cart);

            return $order;
        });
    }
}
