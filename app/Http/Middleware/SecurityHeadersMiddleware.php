<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');
        $response->headers->remove('X-Powered-By');

        // HSTS: only when served over HTTPS (production behind TLS).
        if ($request->isSecure()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age='.((int) env('HSTS_MAX_AGE', 31536000)).'; includeSubDomains; preload'
            );
        }

        // Content-Security-Policy. Inline scripts/styles are allowed because
        // the storefront relies on Alpine.js attributes and small inline
        // snippets; framing, objects and base-uri stay locked down.
        // upgrade-insecure-requests is only sent over HTTPS/production so
        // local http://localhost development keeps working.
        // Local dev must also allow the Vite HMR server (see public/hot),
        // otherwise app.js/CSS never load and the page renders unstyled.
        // IPv4/wildcard-port form is used because Chrome fails to match
        // IPv6 literals (http://[::1]:5173) against CSP source lists.
        $devOrigins = app()->isLocal()
            ? 'http://localhost:* http://127.0.0.1:* ws://localhost:* ws://127.0.0.1:*'
            : '';
        // NOTE: 'unsafe-eval' is required by Alpine.js, which compiles
        // x-data / @click expressions via AsyncFunction at runtime.
        // Without it every interactive component (sliders, search,
        // dropdowns) dies with an EvalError. All other directives
        // (object-src 'none', base-uri, form-action, framing) stay strict.
        $csp = implode('; ', array_filter([
            "default-src 'self'",
            trim("script-src 'self' 'unsafe-inline' 'unsafe-eval' https: {$devOrigins}"),
            trim("style-src 'self' 'unsafe-inline' https://fonts.googleapis.com {$devOrigins}"),
            "font-src 'self' https://fonts.gstatic.com data:",
            "img-src 'self' data: blob: https:",
            "media-src 'self' https:",
            trim("connect-src 'self' https: {$devOrigins}"),
            "frame-src 'self' https:",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            ($request->isSecure() || app()->isProduction()) ? 'upgrade-insecure-requests' : null,
        ]));
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
