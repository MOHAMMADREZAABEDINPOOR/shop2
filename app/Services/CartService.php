<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CartService
{
    public function __construct(
        protected PricingService $pricingService,
        protected InventoryService $inventoryService
    ) {}

    /**
     * Get or create active cart for user or guest session.
     */
    public function getCart(?User $user = null): Cart
    {
        $sessionId = Session::getId();

        if ($user) {
            $cart = Cart::with(['items.product.images', 'items.variant', 'coupon'])
                ->where('user_id', $user->id)
                ->latest()
                ->first();

            if (! $cart) {
                // Check if guest cart exists for current session to attach
                $guestCart = Cart::where('session_id', $sessionId)
                    ->whereNull('user_id')
                    ->first();

                if ($guestCart) {
                    $guestCart->update(['user_id' => $user->id]);

                    return $guestCart->load(['items.product.images', 'items.variant', 'coupon']);
                }

                $cart = Cart::create([
                    'user_id' => $user->id,
                    'session_id' => $sessionId,
                ]);
            }

            return $cart->load(['items.product.images', 'items.variant', 'coupon']);
        }

        // Guest user
        $cart = Cart::with(['items.product.images', 'items.variant', 'coupon'])
            ->where('session_id', $sessionId)
            ->whereNull('user_id')
            ->first();

        if (! $cart) {
            $cart = Cart::create([
                'session_id' => $sessionId,
            ]);
        }

        return $cart->load(['items.product.images', 'items.variant', 'coupon']);
    }

    /**
     * Merge guest cart into user's cart upon login.
     */
    public function mergeGuestCart(User $user, string $guestSessionId): void
    {
        $guestCart = Cart::with('items')
            ->where('session_id', $guestSessionId)
            ->whereNull('user_id')
            ->first();

        if (! $guestCart || $guestCart->items->isEmpty()) {
            return;
        }

        $userCart = Cart::firstOrCreate(
            ['user_id' => $user->id],
            ['session_id' => Session::getId()]
        );

        DB::transaction(function () use ($guestCart, $userCart) {
            foreach ($guestCart->items as $guestItem) {
                $existingItem = CartItem::where('cart_id', $userCart->id)
                    ->where('product_id', $guestItem->product_id)
                    ->where('product_variant_id', $guestItem->product_variant_id)
                    ->first();

                if ($existingItem) {
                    $newQty = $existingItem->quantity + $guestItem->quantity;
                    // Check stock limit
                    if ($this->inventoryService->hasStock($guestItem->product_id, $guestItem->product_variant_id, $newQty)) {
                        $existingItem->update(['quantity' => $newQty]);
                    }
                } else {
                    $guestItem->update(['cart_id' => $userCart->id]);
                }
            }

            if ($guestCart->coupon_id && ! $userCart->coupon_id) {
                $userCart->update(['coupon_id' => $guestCart->coupon_id]);
            }

            $guestCart->delete();
        });
    }

    /**
     * Add item to cart with inventory validation.
     *
     * @throws Exception
     */
    public function addItem(Cart $cart, int $productId, ?int $variantId = null, int $quantity = 1): CartItem
    {
        $product = Product::published()->findOrFail($productId);
        $variant = null;

        if ($variantId) {
            $variant = ProductVariant::active()->where('product_id', $product->id)->findOrFail($variantId);
        }

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->where('product_variant_id', $variantId)
            ->first();

        $currentQty = $item ? $item->quantity : 0;
        $requestedQty = $currentQty + $quantity;

        if (! $this->inventoryService->hasStock($productId, $variantId, $requestedQty)) {
            $availableStock = $variant ? $variant->stock : $product->stock;
            throw new Exception("تعداد درخواستی بیشتر از موجودی انبار می‌باشد (موجودی فعلی: {$availableStock} عدد).");
        }

        $price = $variant ? $variant->effective_price : $product->effective_price;

        if ($item) {
            $item->update([
                'quantity' => $requestedQty,
                'price' => $price,
            ]);
        } else {
            $item = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'product_variant_id' => $variantId,
                'quantity' => $quantity,
                'price' => $price,
            ]);
        }

        return $item;
    }

    /**
     * Update item quantity in cart.
     *
     * @throws Exception
     */
    public function updateQuantity(Cart $cart, int $itemId, int $quantity): CartItem
    {
        $item = $cart->items()->findOrFail($itemId);

        if ($quantity <= 0) {
            $item->delete();

            return $item;
        }

        if (! $this->inventoryService->hasStock($item->product_id, $item->product_variant_id, $quantity)) {
            $stock = $item->variant ? $item->variant->stock : $item->product->stock;
            throw new Exception("تعداد درخواستی بیشتر از موجودی انبار می‌باشد (موجودی فعلی: {$stock} عدد).");
        }

        $item->update(['quantity' => $quantity]);

        return $item;
    }

    /**
     * Remove item from cart.
     */
    public function removeItem(Cart $cart, int $itemId): void
    {
        $cart->items()->where('id', $itemId)->delete();
    }

    /**
     * Clear all items from cart.
     */
    public function clearCart(Cart $cart): void
    {
        $cart->items()->delete();
        $cart->update(['coupon_id' => null]);
    }

    /**
     * Apply coupon to cart.
     */
    public function applyCoupon(Cart $cart, Coupon $coupon): void
    {
        $cart->update(['coupon_id' => $coupon->id]);
    }

    /**
     * Remove coupon from cart.
     */
    public function removeCoupon(Cart $cart): void
    {
        $cart->update(['coupon_id' => null]);
    }

    /**
     * Calculate summary breakdown for cart.
     */
    public function getCartSummary(Cart $cart): array
    {
        return $this->pricingService->calculateCartTotals($cart, $cart->coupon);
    }
}
