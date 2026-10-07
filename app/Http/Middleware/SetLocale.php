<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Supported application locales.
     *
     * @var array<string>
     */
    protected array $supportedLocales = ['en', 'fa'];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->query('lang');

        if ($locale && in_array($locale, $this->supportedLocales, true)) {
            session(['locale' => $locale]);
            cookie()->queue(cookie()->forever('locale', $locale));
        } else {
            $locale = session('locale', $request->cookie('locale', config('app.locale', 'en')));
        }

        if (! in_array($locale, $this->supportedLocales, true)) {
            $locale = 'en';
        }

        app()->setLocale($locale);

        view()->share('currentLocale', $locale);
        view()->share('dir', $locale === 'fa' ? 'rtl' : 'ltr');
        view()->share('isRtl', $locale === 'fa');

        return $next($request);
    }
}
