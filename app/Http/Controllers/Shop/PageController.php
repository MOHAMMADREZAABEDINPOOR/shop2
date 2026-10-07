<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\NewsletterSubscription;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return view('pages.about');
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function submitContact(ContactRequest $request): RedirectResponse
    {
        ContactMessage::create($request->safe()->except('website'));

        return back()->with('success', 'پیام شما با موفقیت دریافت شد. کارشناسان ما به زودی با شما تماس خواهند گرفت.');
    }

    public function faq(): View
    {
        return view('pages.faq');
    }

    public function privacy(): View
    {
        return view('pages.privacy');
    }

    public function terms(): View
    {
        return view('pages.terms');
    }

    public function shippingPolicy(): View
    {
        return view('pages.shipping-policy');
    }

    public function returnPolicy(): View
    {
        return view('pages.return-policy');
    }

    public function cookiePolicy(): View
    {
        return view('pages.cookie-policy');
    }

    public function subscribeNewsletter(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:newsletter_subscriptions,email',
            // Honeypot: bots fill it, humans never see it.
            'website' => ['nullable', 'max:0'],
        ], [
            'email.required' => 'وارد کردن ایمیل الزامی است.',
            'email.email' => 'فرمت ایمیل نامعتبر است.',
            'email.unique' => 'این ایمیل قبلاً در خبرنامه ثبت شده است.',
        ]);

        NewsletterSubscription::create(['email' => $validated['email']]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'عضویت شما در خبرنامه با موفقیت ثبت شد.',
            ]);
        }

        return back()->with('success', 'عضویت شما در خبرنامه با موفقیت ثبت شد.');
    }

    /**
     * Dynamic robots.txt
     */
    public function robots(): Response
    {
        $content = "User-agent: *\n";
        $content .= "Disallow: /admin/\n";
        $content .= "Disallow: /account/\n";
        $content .= "Disallow: /checkout/\n";
        $content .= "Disallow: /cart/\n";
        $content .= "Allow: /\n\n";
        $content .= 'Sitemap: '.url('/sitemap.xml')."\n";

        return response($content, 200, ['Content-Type' => 'text/plain']);
    }

    /**
     * Dynamic sitemap.xml
     */
    public function sitemap(): Response
    {
        $products = Product::published()->latest()->take(1000)->get();
        $categories = Category::active()->get();
        $brands = Brand::active()->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Homepage & static pages
        $staticRoutes = ['home', 'shop.index', 'pages.about', 'pages.contact', 'pages.faq', 'pages.terms', 'pages.privacy'];
        foreach ($staticRoutes as $route) {
            $xml .= '<url>';
            $xml .= '<loc>'.route($route).'</loc>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>0.8</priority>';
            $xml .= '</url>';
        }

        // Categories
        foreach ($categories as $cat) {
            $xml .= '<url>';
            $xml .= '<loc>'.route('shop.index', ['category' => $cat->slug]).'</loc>';
            $xml .= '<lastmod>'.$cat->updated_at->toAtomString().'</lastmod>';
            $xml .= '<changefreq>daily</changefreq>';
            $xml .= '<priority>0.7</priority>';
            $xml .= '</url>';
        }

        // Products
        foreach ($products as $prod) {
            $xml .= '<url>';
            $xml .= '<loc>'.route('product.show', $prod->slug).'</loc>';
            $xml .= '<lastmod>'.$prod->updated_at->toAtomString().'</lastmod>';
            $xml .= '<changefreq>daily</changefreq>';
            $xml .= '<priority>0.9</priority>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
