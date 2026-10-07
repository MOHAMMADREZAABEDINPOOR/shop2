<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Product Analytics
    |--------------------------------------------------------------------------
    |
    | Privacy-friendly analytics for the storefront. Leave the provider empty
    | to disable tracking entirely (default). Supported providers:
    |
    |  - "ga4"       Google Analytics 4 (requires a "G-XXXXXXXXXX" ID)
    |  - "plausible" Plausible Analytics (requires the site domain as ID)
    |
    | The snippet is only rendered for guests-safe pages and never inside
    | the admin panel or the local/debug environment unless forced.
    |
    */

    'provider' => env('ANALYTICS_PROVIDER', ''),

    'id' => env('ANALYTICS_ID', ''),

    'force_local' => env('ANALYTICS_FORCE_LOCAL', false),

];
