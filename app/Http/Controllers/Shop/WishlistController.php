<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CartService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {}

    public function index(): View
    {
        $wishlist = Auth::user()->wishlist()->firstOrCreate();
        $items = $wishlist->items()->with('product.primaryImage')->latest()->get();

        return view('shop.wishlist', compact('items'));
    }

    public function toggle(Request $request, int $productId): RedirectResponse|JsonResponse
    {
        $product = Product::published()->findOrFail($productId);
        $wishlist = Auth::user()->wishlist()->firstOrCreate();

        $existing = $wishlist->items()->where('product_id', $productId)->first();

        if ($existing) {
            $existing->delete();
            $added = false;
            $message = 'محصول از علاقه‌مندی‌ها حذف شد.';
        } else {
            $wishlist->items()->create(['product_id' => $productId]);
            $added = true;
            $message = 'محصول به علاقه‌مندی‌ها افزوده شد.';
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'added' => $added,
                'message' => $message,
                'wishlist_count' => $wishlist->items()->count(),
            ]);
        }

        return back()->with('success', $message);
    }

    public function moveToCart(int $productId): RedirectResponse
    {
        $user = Auth::user();
        $wishlist = $user->wishlist;

        if ($wishlist) {
            $wishlist->items()->where('product_id', $productId)->delete();
        }

        $cart = $this->cartService->getCart($user);

        try {
            $this->cartService->addItem($cart, $productId, null, 1);

            return redirect()->route('cart.index')->with('success', 'محصول از لیست علاقه‌مندی به سبد خرید منتقل گردید.');
        } catch (Exception $e) {
            return back()->withErrors(['wishlist' => $e->getMessage()]);
        }
    }
}
