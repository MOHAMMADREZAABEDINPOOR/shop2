<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Services\CouponService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected CouponService $couponService
    ) {}

    public function index(): View
    {
        $cart = $this->cartService->getCart(Auth::user());
        $summary = $this->cartService->getCartSummary($cart);

        return view('shop.cart', compact('cart', 'summary'));
    }

    public function add(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $quantity = $validated['quantity'] ?? 1;
        $cart = $this->cartService->getCart(Auth::user());

        try {
            $this->cartService->addItem(
                $cart,
                $validated['product_id'],
                $validated['product_variant_id'] ?? null,
                $quantity
            );

            if ($request->wantsJson()) {
                $cart->refresh();

                return response()->json([
                    'success' => true,
                    'message' => 'محصول با موفقیت به سبد خرید افزوده شد.',
                    'cart_count' => $cart->items_count,
                ]);
            }

            return back()->with('success', 'محصول با موفقیت به سبد خرید افزوده شد.');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()->withErrors(['cart' => $e->getMessage()]);
        }
    }

    public function update(Request $request, int $itemId): RedirectResponse|JsonResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $cart = $this->cartService->getCart(Auth::user());

        try {
            $this->cartService->updateQuantity($cart, $itemId, (int) $request->input('quantity'));

            if ($request->wantsJson()) {
                $cart->refresh();
                $summary = $this->cartService->getCartSummary($cart);

                return response()->json([
                    'success' => true,
                    'message' => 'سبد خرید بروزرسانی شد.',
                    'cart_count' => $cart->items_count,
                    'summary' => $summary,
                ]);
            }

            return back()->with('success', 'تعداد محصول در سبد خرید بروزرسانی شد.');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()->withErrors(['cart' => $e->getMessage()]);
        }
    }

    public function remove(int $itemId): RedirectResponse
    {
        $cart = $this->cartService->getCart(Auth::user());
        $this->cartService->removeItem($cart, $itemId);

        return back()->with('info', 'محصول از سبد خرید حذف شد.');
    }

    public function clear(): RedirectResponse
    {
        $cart = $this->cartService->getCart(Auth::user());
        $this->cartService->clearCart($cart);

        return back()->with('info', 'سبد خرید خالی شد.');
    }

    public function applyCoupon(Request $request): RedirectResponse
    {
        $request->validate(['coupon_code' => 'required|string']);

        $cart = $this->cartService->getCart(Auth::user());
        if ($cart->items->isEmpty()) {
            return back()->withErrors(['coupon' => 'سبد خرید شما خالی است.']);
        }

        $summary = $this->cartService->getCartSummary($cart);
        $validation = $this->couponService->validateCoupon(
            $request->input('coupon_code'),
            Auth::user(),
            $summary['subtotal'],
            $cart
        );

        if (! $validation['valid']) {
            return back()->withErrors(['coupon' => $validation['message']]);
        }

        $this->cartService->applyCoupon($cart, $validation['coupon']);

        return back()->with('success', 'کد تخفیف با موفقیت بر روی سفارش شما اعمال شد.');
    }

    public function removeCoupon(): RedirectResponse
    {
        $cart = $this->cartService->getCart(Auth::user());
        $this->cartService->removeCoupon($cart);

        return back()->with('info', 'کد تخفیف حذف گردید.');
    }
}
