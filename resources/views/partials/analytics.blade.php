{{-- Product analytics snippet. Rendered only when ANALYTICS_PROVIDER + ANALYTICS_ID are set. --}}
@if(config('analytics.provider') === 'ga4')
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('analytics.id') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ config('analytics.id') }}', { 'anonymize_ip': true });
    </script>
@elseif(config('analytics.provider') === 'plausible')
    <script defer data-domain="{{ config('analytics.id') }}" src="https://plausible.io/js/script.js"></script>
@endif
