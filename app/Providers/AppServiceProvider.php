<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS URLs when running in production behind TLS.
        if ($this->app->isProduction()) {
            URL::forceScheme('https');
        }

        // Set Persian modern Tailwind pagination as default
        Paginator::defaultView('vendor.pagination.tailwind');

        // "Remember me" cookie lifetime (minutes), configurable via env.
        // Default 576000 minutes (~400 days) matches the framework default.
        try {
            Auth::guard('web')->setRememberDuration(
                (int) config('auth.remember_duration', 576000)
            );
        } catch (\Throwable) {
            // Guard may not be resolvable in console contexts without a request.
        }
    }
}
