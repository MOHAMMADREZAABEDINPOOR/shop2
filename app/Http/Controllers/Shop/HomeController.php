<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $heroBanners = Banner::active()->position('hero')->get();
        $promoTopBanners = Banner::active()->position('promo_top')->take(4)->get();
        $promoMidBanners = Banner::active()->position('promo_mid')->take(2)->get();
        $promoBottomBanners = Banner::active()->position('promo_bottom')->take(4)->get();

        $featuredCategories = Category::active()->withCount('products')->orderByDesc('products_count')->take(10)->get();
        $brands = Brand::active()->take(12)->get();

        $featuredProducts = Product::published()
            ->featured()
            ->with(['primaryImage', 'category', 'brand'])
            ->latest()
            ->take(8)
            ->get();

        $bestSellers = Product::published()
            ->bestSeller()
            ->with(['primaryImage', 'category', 'brand'])
            ->take(8)
            ->get();

        $newArrivals = Product::published()
            ->newArrival()
            ->with(['primaryImage', 'category', 'brand'])
            ->latest()
            ->take(8)
            ->get();

        $discountedProducts = Product::published()
            ->whereNotNull('sale_price')
            ->where('sale_price', '>', 0)
            ->with(['primaryImage', 'category', 'brand'])
            ->latest()
            ->take(8)
            ->get();

        return view('shop.home', compact(
            'heroBanners',
            'promoTopBanners',
            'promoMidBanners',
            'promoBottomBanners',
            'featuredCategories',
            'brands',
            'featuredProducts',
            'bestSellers',
            'newArrivals',
            'discountedProducts'
        ));
    }
}
