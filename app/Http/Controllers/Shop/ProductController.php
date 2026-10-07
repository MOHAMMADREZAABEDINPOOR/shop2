<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\RecentlyViewed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(Request $request, string $slug): View
    {
        $product = Product::where('slug', $slug)
            ->with([
                'category',
                'brand',
                'images',
                'variants' => function ($q) {
                    $q->where('is_active', true);
                },
                'approvedReviews.user',
            ])
            ->firstOrFail();

        $this->authorize('view', $product);

        // Record Recently Viewed
        $userId = Auth::id();
        $sessionId = Session::getId();

        RecentlyViewed::updateOrCreate(
            [
                'user_id' => $userId,
                'session_id' => $userId ? null : $sessionId,
                'product_id' => $product->id,
            ],
            ['viewed_at' => now()]
        );

        // Related Products in same category
        $relatedProducts = Product::published()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['primaryImage', 'category', 'brand'])
            ->take(6)
            ->get();

        // Recently Viewed items
        $recentlyViewedItems = RecentlyViewed::query()
            ->when($userId, fn ($q) => $q->where('user_id', $userId))
            ->when(! $userId, fn ($q) => $q->where('session_id', $sessionId))
            ->where('product_id', '!=', $product->id)
            ->with('product.primaryImage')
            ->latest('viewed_at')
            ->take(6)
            ->get()
            ->pluck('product')
            ->filter();

        // Allow any authenticated user to review, with verified buyer flag for actual purchasers
        $canReview = (bool) $userId;
        $isVerifiedBuyer = false;
        if ($userId) {
            $isVerifiedBuyer = Auth::user()->orders()
                ->where('payment_status', 'paid')
                ->whereHas('items', function ($q) use ($product) {
                    $q->where('product_id', $product->id);
                })
                ->exists();
        }

        // Generate Structured Schema.org Product data
        $schemaData = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'image' => [$product->primary_image_url],
            'description' => strip_tags($product->short_description ?? $product->description ?? $product->name),
            'sku' => $product->sku,
            'brand' => [
                '@type' => 'Brand',
                'name' => $product->brand?->name ?? config('app.name'),
            ],
            'offers' => [
                '@type' => 'Offer',
                'priceCurrency' => 'IRR',
                'price' => $product->effective_price,
                'availability' => $product->is_in_stock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'url' => route('product.show', $product->slug),
            ],
        ];

        if ($product->average_rating > 0) {
            $schemaData['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => $product->average_rating,
                'reviewCount' => max(1, $product->reviews_count),
            ];
        }

        return view('shop.product-detail', compact(
            'product',
            'relatedProducts',
            'recentlyViewedItems',
            'canReview',
            'isVerifiedBuyer',
            'schemaData'
        ));
    }
}
