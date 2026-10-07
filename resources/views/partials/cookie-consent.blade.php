{{-- Cookie consent banner (GDPR-style). Choice stored in localStorage; no tracking without consent. --}}
<div id="cookie-consent" class="hidden fixed z-50 bottom-20 lg:bottom-6 inset-x-4 sm:inset-x-auto sm:ltr:right-6 sm:rtl:left-6 sm:max-w-sm">
    <div class="bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 rounded-2xl shadow-2xl border border-zinc-700 dark:border-gray-200 p-5 space-y-3 text-start">
        <div class="flex items-center gap-2.5">
            <span class="w-9 h-9 rounded-xl bg-rose-600 text-white flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </span>
            <h3 class="font-black text-sm">{{ __('Your Privacy Matters') }}</h3>
        </div>
        <p class="text-xs leading-relaxed text-gray-300 dark:text-gray-600">
            {{ __('We use cookies to enhance your shopping experience. By clicking Accept, you agree to our') }}
            <a href="{{ route('pages.cookie-policy') }}" class="underline font-bold">{{ __('Cookie Policy') }}</a>.
        </p>
        <div class="flex items-center gap-2">
            <button type="button" onclick="setCookieConsent(true)" class="flex-1 bg-rose-600 hover:bg-rose-700 text-white font-bold py-2.5 rounded-xl text-xs transition-colors">
                {{ __('Accept & Continue') }}
            </button>
            <button type="button" onclick="setCookieConsent(false)" class="flex-1 bg-zinc-800 dark:bg-gray-100 hover:opacity-90 text-gray-200 dark:text-gray-700 font-bold py-2.5 rounded-xl text-xs transition-colors">
                {{ __('Essential Only') }}
            </button>
        </div>
    </div>
</div>

<script>
function setCookieConsent(accepted) {
    try {
        localStorage.setItem('cookie-consent', accepted ? 'accepted' : 'essential');
        localStorage.setItem('cookie-consent-at', new Date().toISOString());
    } catch (e) {}
    document.getElementById('cookie-consent').classList.add('hidden');
}
(function () {
    var shown = false;
    try {
        shown = !!localStorage.getItem('cookie-consent');
    } catch (e) {}
    if (!shown) {
        setTimeout(function () {
            document.getElementById('cookie-consent').classList.remove('hidden');
        }, 1200);
    }
})();
</script>
