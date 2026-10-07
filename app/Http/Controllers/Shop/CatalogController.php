<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::published()->with(['primaryImage', 'category', 'brand']);

        // Search Keyword
        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('brand', function ($bq) use ($search) {
                        $bq->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('category', function ($cq) use ($search) {
                        $cq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Category Filter
        $selectedCategory = null;
        if ($categorySlug = $request->input('category')) {
            $selectedCategory = Category::where('slug', $categorySlug)->first();
            if ($selectedCategory) {
                $categoryIds = $selectedCategory->getAllChildrenIds();
                $query->whereIn('category_id', $categoryIds);
            }
        }

        // Brand Filter
        $selectedBrand = null;
        if ($brandSlug = $request->input('brand')) {
            $selectedBrand = Brand::where('slug', $brandSlug)->first();
            if ($selectedBrand) {
                $query->where('brand_id', $selectedBrand->id);
            }
        }

        // Price Filter
        if ($minPrice = $request->input('min_price')) {
            $query->where('price', '>=', (float) $minPrice);
        }
        if ($maxPrice = $request->input('max_price')) {
            $query->where('price', '<=', (float) $maxPrice);
        }

        // Availability Filter
        if ($request->boolean('in_stock')) {
            $query->where('stock', '>', 0);
        }

        // Discount Filter
        if ($request->boolean('has_discount')) {
            $query->whereNotNull('sale_price')->where('sale_price', '>', 0);
        }

        // Sorting
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'price_asc':
                $query->orderByRaw('COALESCE(sale_price, price) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('COALESCE(sale_price, price) DESC');
                break;
            case 'best_selling':
                $query->orderByDesc('is_best_seller')->orderByDesc('id');
                break;
            case 'popular':
                $query->withAvg('approvedReviews', 'rating')->orderByDesc('approved_reviews_avg_rating');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(16)->withQueryString();

        $categories = Category::active()->root()->with('children')->get();
        $brands = Brand::active()->get();
        $sidebarBanner = Banner::active()->position('sidebar')->first();

        return view('shop.catalog', compact(
            'products',
            'categories',
            'brands',
            'selectedCategory',
            'selectedBrand',
            'sort',
            'sidebarBanner'
        ));
    }

    /**
     * Autocomplete suggestions for real-time search.
     */
    public function suggestions(Request $request): JsonResponse
    {
        $keyword = trim($request->input('q', ''));
        if (mb_strlen($keyword) < 2) {
            return response()->json(['suggestions' => []]);
        }

        $products = Product::published()
            ->where('name', 'like', "%{$keyword}%")
            ->orWhere('sku', 'like', "%{$keyword}%")
            ->with(['primaryImage', 'category'])
            ->take(6)
            ->get(['id', 'name', 'slug', 'price', 'sale_price', 'category_id'])
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'url' => route('product.show', $product->slug),
                    'price' => format_price($product->effective_price).' تومان',
                    'image' => $product->primary_image_url,
                    'category' => $product->category?->name,
                ];
            });

        return response()->json([
            'suggestions' => $products,
        ]);
    }
}
