<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'fa' ? 'rtl' : 'ltr' }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', __('Shop')) - {{ config('app.name', 'DigiStore') }}</title>
    <meta name="description" content="@yield('meta_description', __('Modern e-commerce platform with the widest selection of products, fast delivery, and secure payments'))">
    @hasSection('robots')
        <meta name="robots" content="@yield('robots')">
    @else
        <meta name="robots" content="index, follow">
    @endif
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Favicon & App Icons -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">
    <meta name="theme-color" content="#e11d48">

    <!-- Open Graph / Social Media -->
    <meta property="og:title" content="@yield('title', __('Shop'))">
    <meta property="og:description" content="@yield('meta_description', __('Modern e-commerce platform with the widest selection of products, fast delivery, and secure payments'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="{{ app()->getLocale() === 'fa' ? 'fa_IR' : 'en_US' }}">
    <meta property="og:site_name" content="{{ config('app.name', 'DigiStore') }}">
    <meta property="og:image" content="@yield('og_image', asset('images/og-cover.svg'))">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', __('Shop'))">
    <meta name="twitter:description" content="@yield('meta_description', __('Modern e-commerce platform with the widest selection of products, fast delivery, and secure payments'))">
    <meta name="twitter:image" content="@yield('og_image', asset('images/og-cover.svg'))">

    <!-- Performance: font preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Structured Data: Organization -->
    <script type="application/ld+json">
        {!! json_encode([
            '@'.'context' => 'https://schema.org',
            '@type' => 'Store',
            'name' => config('app.name', 'DigiStore'),
            'url' => url('/'),
            'logo' => asset('favicon.svg'),
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>

    @includeWhen(config('analytics.provider') !== '' && config('analytics.id') !== '' && (app()->isProduction() || config('analytics.force_local')), 'partials.analytics')

    <!-- Anti-flicker Theme Script -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        window.toggleTheme = function () {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        };
    </script>

    <style>
        svg { max-width: 100%; height: auto; }
        svg.w-6 { width: 1.5rem; height: 1.5rem; }
        svg.w-5 { width: 1.25rem; height: 1.25rem; }
        svg.w-4 { width: 1rem; height: 1rem; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-50 dark:bg-zinc-950 text-gray-800 dark:text-gray-100 font-sans transition-colors duration-200 antialiased min-h-screen flex flex-col selection:bg-rose-500 selection:text-white">

    <!-- Top Announcement Bar / CTA & Language Switcher -->
    <div class="bg-gradient-to-l from-rose-700 via-rose-600 to-rose-700 text-white text-[11px] sm:text-xs py-1.5 sm:py-2 px-2.5 sm:px-4 shadow-sm">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-2 sm:gap-4">
            <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-1.5 sm:gap-2 hover:underline font-medium min-w-0 flex-1">
                <span class="bg-white/20 px-1.5 py-0.5 rounded-full text-[9px] sm:text-[10px] font-bold uppercase tracking-wider shrink-0">{{ __('Special Offers') }}</span>
                <span class="truncate">{{ __('Top Deals CTA') }}</span>
            </a>
            <div class="flex items-center gap-2 shrink-0 font-semibold text-[11px] sm:text-xs">
                <!-- Language Switcher -->
                <div class="inline-flex items-center bg-black/20 rounded-lg p-0.5 border border-white/20">
                    <a href="{{ route('lang.switch', 'en') }}" class="px-1.5 sm:px-2 py-0.5 rounded transition-all {{ app()->getLocale() === 'en' ? 'bg-white text-rose-700 font-bold shadow-xs' : 'text-white/80 hover:text-white' }}">EN</a>
                    <a href="{{ route('lang.switch', 'fa') }}" class="px-1.5 sm:px-2 py-0.5 rounded transition-all {{ app()->getLocale() === 'fa' ? 'bg-white text-rose-700 font-bold shadow-xs' : 'text-white/80 hover:text-white' }}">فا</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="sticky top-0 z-40 bg-white/95 dark:bg-zinc-900/95 backdrop-blur-md border-b border-gray-200 dark:border-zinc-800 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-2.5 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center justify-between gap-x-3 gap-y-3 py-3">
                
                <!-- Logo & Brand -->
                <div class="flex items-center gap-6 min-w-0">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 text-2xl font-black tracking-tight text-rose-600 dark:text-rose-500 group shrink-0">
                        <span class="p-2 bg-rose-600 text-white rounded-xl shadow-md group-hover:scale-105 transition-transform duration-200 flex items-center justify-center">
                            <svg class="w-6 h-6 shrink-0" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </span>
                        <span class="hidden min-[420px]:inline bg-gradient-to-r from-rose-600 to-pink-500 bg-clip-text text-transparent font-black">{{ config('app.name', 'DigiStore') }}</span>
                    </a>

                    <!-- Desktop Navigation Links -->
                    <nav class="hidden lg:flex items-center gap-6 text-sm font-semibold text-gray-700 dark:text-gray-200">
                        <a href="{{ route('home') }}" class="hover:text-rose-600 dark:hover:text-rose-400 transition-colors {{ request()->routeIs('home') ? 'text-rose-600 dark:text-rose-400 font-bold' : '' }}">{{ __('Home') }}</a>
                        <a href="{{ route('shop.index') }}" class="hover:text-rose-600 dark:hover:text-rose-400 transition-colors {{ request()->routeIs('shop.index') ? 'text-rose-600 dark:text-rose-400 font-bold' : '' }}">{{ __('Products') }}</a>
                        <a href="{{ route('pages.about') }}" class="hover:text-rose-600 dark:hover:text-rose-400 transition-colors">{{ __('About Us') }}</a>
                        <a href="{{ route('pages.contact') }}" class="hover:text-rose-600 dark:hover:text-rose-400 transition-colors">{{ __('Contact Us') }}</a>
                    </nav>
                </div>

                <!-- Live Autocomplete Search Bar: full-width second row on mobile -->
                <div class="order-3 basis-full md:order-none md:basis-auto md:flex-1 md:max-w-xl md:mx-4 min-w-0 relative" x-data="{
                    query: '',
                    results: [],
                    loading: false,
                    open: false,
                    search() {
                        if (this.query.length < 2) {
                            this.results = [];
                            this.open = false;
                            return;
                        }
                        this.loading = true;
                        fetch('{{ route('search.suggestions') }}?q=' + encodeURIComponent(this.query))
                            .then(res => res.json())
                            .then(data => {
                                this.results = data.suggestions;
                                this.open = true;
                                this.loading = false;
                            })
                            .catch(() => { this.loading = false; });
                    }
                }" @click.away="open = false">
                    <form action="{{ route('shop.index') }}" method="GET" class="relative">
                        <input type="text"
                               name="q"
                               x-model="query"
                               @input.debounce.300ms="search()"
                               @focus="if(results.length) open = true"
                               placeholder="{{ __('Search in thousands of products, brands and categories...') }}"
                               autocomplete="off"
                               class="w-full bg-gray-100 dark:bg-zinc-800 text-gray-900 dark:text-gray-100 rounded-full py-2 sm:py-2.5 pr-10 sm:pr-11 pl-9 sm:pl-10 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/50 focus:bg-white dark:focus:bg-zinc-900 border border-transparent dark:border-zinc-700 transition-all">
                        
                        <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>

                        <span x-show="loading" class="absolute left-3 top-1/2 -translate-y-1/2 text-rose-500 animate-spin">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </span>
                    </form>

                    <!-- Search Suggestions Popup -->
                    <div x-show="open && results.length > 0"
                         x-transition
                         class="absolute top-full mt-2 inset-x-0 bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl border border-gray-100 dark:border-zinc-800 py-2 z-50 overflow-hidden max-h-[60vh] overflow-y-auto">
                        <div class="px-4 py-1.5 text-xs font-medium text-gray-400 border-b border-gray-100 dark:border-zinc-800">{{ __('Live Search Suggestions') }}</div>
                        <template x-for="item in results" :key="item.id">
                            <a :href="item.url" class="flex items-center gap-3 px-4 py-2.5 hover:bg-rose-50 dark:hover:bg-zinc-800/60 transition-colors">
                                <img :src="item.image" :alt="item.name" class="w-10 h-10 object-cover rounded-lg bg-gray-100 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700">
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-semibold text-gray-800 dark:text-gray-100 truncate" x-text="item.name"></div>
                                    <div class="text-xs text-rose-600 dark:text-rose-400 font-bold" x-text="item.price"></div>
                                </div>
                                <span class="text-xs text-gray-400 bg-gray-100 dark:bg-zinc-800 px-2 py-0.5 rounded-full" x-text="item.category"></span>
                            </a>
                        </template>
                    </div>
                </div>

                <!-- Header Actions (Theme, Wishlist, Cart, Account) -->
                <div class="flex items-center gap-2 sm:gap-3">
                    
                    <!-- Dark / Light Mode Switcher -->
                    <button onclick="toggleTheme()" type="button" aria-label="{{ __('Toggle Dark/Light Mode') }}" class="p-2.5 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded-full transition-colors">
                        <svg class="w-5 h-5 hidden dark:block text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 9h-1m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <svg class="w-5 h-5 block dark:hidden text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    </button>

                    <!-- Wishlist Icon -->
                    @auth
                    <a href="{{ route('wishlist.index') }}" class="p-2.5 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded-full transition-colors relative" title="{{ __('Wishlist') }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        @php $wishlistCount = auth()->user()->wishlist?->items()->count() ?? 0; @endphp
                        @if($wishlistCount > 0)
                            <span class="absolute -top-1 -right-1 bg-rose-500 text-white text-[10px] font-bold w-4.5 h-4.5 rounded-full flex items-center justify-center">{{ $wishlistCount }}</span>
                        @endif
                    </a>
                    @endauth

                    <!-- Cart Icon & Counter -->
                    @php
                        $cartService = app(\App\Services\CartService::class);
                        $headerCart = $cartService->getCart(auth()->user());
                        $headerCartCount = $headerCart->items_count;
                    @endphp
                    <a href="{{ route('cart.index') }}" class="p-2.5 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded-full transition-colors relative" title="{{ __('Cart') }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        @if($headerCartCount > 0)
                            <span class="absolute -top-1 -right-1 bg-rose-600 text-white text-[10px] font-bold w-4.5 h-4.5 rounded-full flex items-center justify-center animate-pulse">{{ $headerCartCount }}</span>
                        @endif
                    </a>

                    <!-- User Account / Auth Dropdown -->
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors">
                                <div class="w-8 h-8 rounded-full bg-rose-600 text-white font-bold flex items-center justify-center text-sm shadow-sm overflow-hidden">
                                    @if(auth()->user()->avatar)
                                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-full h-full object-cover">
                                    @else
                                        {{ mb_substr(auth()->user()->name, 0, 1) }}
                                    @endif
                                </div>
                                <span class="hidden md:inline-block text-sm font-semibold max-w-[100px] truncate text-gray-700 dark:text-gray-200">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <!-- User Dropdown Menu -->
                            <div x-show="open" @click.away="open = false" x-transition class="absolute ltr:right-0 rtl:left-0 mt-2 w-56 bg-white dark:bg-zinc-900 rounded-2xl shadow-xl border border-gray-100 dark:border-zinc-800 py-2 z-50">
                                <div class="px-4 py-2 border-b border-gray-100 dark:border-zinc-800">
                                    <div class="font-bold text-sm text-gray-900 dark:text-gray-100">{{ auth()->user()->name }}</div>
                                    <div class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</div>
                                </div>

                                @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-rose-600 dark:text-rose-400 font-bold hover:bg-rose-50 dark:hover:bg-zinc-800">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        {{ __('Admin Panel') }}
                                    </a>
                                @endif

                                <a href="{{ route('account.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-zinc-800">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    {{ __('Customer Dashboard') }}
                                </a>
                                <a href="{{ route('account.orders') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-zinc-800">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    {{ __('My Orders') }}
                                </a>
                                <a href="{{ route('account.addresses') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-zinc-800">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                    {{ __('Delivery Addresses') }}
                                </a>
                                <a href="{{ route('account.profile') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-zinc-800">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    {{ __('Edit Profile') }}
                                </a>

                                <div class="border-t border-gray-100 dark:border-zinc-800 my-1"></div>

                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-start flex items-center gap-2.5 px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 dark:hover:bg-zinc-800 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        {{ __('Logout') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 sm:gap-2 bg-gray-900 text-white dark:bg-white dark:text-zinc-900 p-2 min-[360px]:px-3 min-[420px]:px-4 min-[360px]:py-2 rounded-xl text-xs sm:text-sm font-bold shadow-md hover:shadow-lg hover:opacity-95 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                            <span class="hidden min-[360px]:inline">{{ __('Login / Register') }}</span>
                        </a>
                    @endauth
                </div>

            </div>
        </div>

        <!-- Sub-Navigation Quick Category & Deals Bar -->
        <div class="border-t border-gray-100 dark:border-zinc-800/80 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md relative"
             x-data="{ megaMenuOpen: false, activeDept: 1 }"
             @keydown.escape.window="megaMenuOpen = false">
            <div class="max-w-7xl mx-auto px-2.5 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-11 text-xs gap-2 sm:gap-4">
                    @php
                        $allDepartments = \App\Models\Category::active()->root()->with(['children' => fn($q) => $q->active()->orderBy('sort_order')])->orderBy('sort_order')->get();
                        $quickCategories = \App\Models\Category::active()->whereNotNull('parent_id')->orderBy('sort_order')->take(6)->get();
                    @endphp

                    <!-- Mega Menu Toggle Button -->
                    <div class="relative shrink-0" @click.outside="megaMenuOpen = false">
                        <button @click="megaMenuOpen = !megaMenuOpen"
                                type="button"
                                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl font-black text-gray-900 dark:text-white hover:text-rose-600 dark:hover:text-rose-400 bg-gray-100/80 dark:bg-zinc-800/80 hover:bg-rose-50 dark:hover:bg-zinc-800 transition-all cursor-pointer">
                            <svg class="w-4 h-4 text-rose-600 dark:text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <span class="text-xs">{{ __('Product Categories') }}</span>
                            <svg class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200" :class="megaMenuOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Amazon-Style Mega Menu Dropdown -->
                        <div x-show="megaMenuOpen"
                             x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                             class="absolute top-full ltr:left-0 rtl:right-0 mt-2 w-[860px] max-w-[calc(100vw-1rem)] bg-white dark:bg-zinc-900 rounded-3xl shadow-2xl border border-gray-200 dark:border-zinc-800 z-50 overflow-hidden flex flex-col md:flex-row h-[480px]">

                            <!-- Right/Left Pane: Departments List -->
                            <div class="w-full md:w-64 bg-gray-50/80 dark:bg-zinc-950/60 border-b md:border-b-0 ltr:md:border-r rtl:md:border-l border-gray-100 dark:border-zinc-800 p-2 overflow-y-auto scrollbar-thin shrink-0">
                                <div class="px-3 py-2 text-[11px] font-black text-gray-400 dark:text-zinc-500 uppercase tracking-wider">
                                    {{ __('Main Departments') }}
                                </div>
                                <div class="space-y-0.5">
                                    @foreach($allDepartments as $dept)
                                        <button @mouseenter="activeDept = {{ $dept->id }}"
                                                @click="activeDept = {{ $dept->id }}"
                                                type="button"
                                                :class="activeDept === {{ $dept->id }} ? 'bg-white dark:bg-zinc-800 text-rose-600 dark:text-rose-400 font-bold shadow-xs' : 'text-gray-700 dark:text-zinc-300 hover:bg-gray-100/70 dark:hover:bg-zinc-800/40'"
                                                class="w-full text-start flex items-center justify-between gap-2 px-3 py-2 rounded-xl text-xs transition-all cursor-pointer">
                                            <span class="flex items-center gap-2 truncate">
                                                <span>{{ $dept->icon ?? '📦' }}</span>
                                                <span class="truncate">{{ $dept->name }}</span>
                                            </span>
                                            <svg class="w-3.5 h-3.5 text-gray-300 dark:text-zinc-600 rtl:rotate-180 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Content Pane: Subcategories of Active Department -->
                            <div class="flex-1 p-6 overflow-y-auto scrollbar-thin">
                                @foreach($allDepartments as $dept)
                                    <div x-show="activeDept === {{ $dept->id }}" x-cloak class="space-y-4">
                                        
                                        <!-- Header of active department -->
                                        <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-zinc-800">
                                            <div class="flex items-center gap-2">
                                                <span class="text-xl">{{ $dept->icon ?? '📦' }}</span>
                                                <h3 class="font-black text-sm text-gray-900 dark:text-white">{{ $dept->name }}</h3>
                                            </div>
                                            <a href="{{ route('shop.index', ['category' => $dept->slug]) }}"
                                               class="text-xs font-bold text-rose-600 dark:text-rose-400 hover:underline inline-flex items-center gap-1">
                                                <span>{{ __('View All Products in Category') }}</span>
                                                <span class="inline-block rtl:rotate-180">→</span>
                                            </a>
                                        </div>

                                        <!-- Subcategories Grid -->
                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5 pt-1">
                                            @forelse($dept->children as $sub)
                                                <a href="{{ route('shop.index', ['category' => $sub->slug]) }}"
                                                   class="group flex items-center gap-2.5 p-2.5 rounded-2xl border border-gray-100 dark:border-zinc-800/80 hover:border-rose-300 dark:hover:border-zinc-600 hover:bg-rose-50/30 dark:hover:bg-zinc-800/50 transition-all">
                                                    <span class="w-8 h-8 rounded-xl bg-gray-100 dark:bg-zinc-800 flex items-center justify-center text-sm shrink-0 group-hover:scale-110 transition-transform">
                                                        {{ $sub->icon ?? '🔹' }}
                                                    </span>
                                                    <span class="text-xs font-semibold text-gray-700 dark:text-zinc-300 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors leading-tight truncate">
                                                        {{ $sub->name }}
                                                    </span>
                                                </a>
                                            @empty
                                                <div class="col-span-full py-8 text-center text-gray-400 text-xs">
                                                    {{ __('No subcategories found.') }}
                                                </div>
                                            @endforelse
                                        </div>

                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>

                    <!-- Quick Navigation Links (scrollable) -->
                    <div class="flex items-center gap-4 whitespace-nowrap font-bold text-gray-700 dark:text-gray-300 overflow-x-auto scrollbar-none py-1 flex-1">
                        <a href="{{ route('shop.index', ['has_discount' => 1]) }}" class="flex items-center gap-1.5 text-rose-600 dark:text-rose-400 hover:text-rose-700 transition-colors shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                            <span>{{ __('Special Offers') }}</span>
                        </a>
                        @foreach($quickCategories as $quickCat)
                            <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-zinc-700 shrink-0"></span>
                            <a href="{{ route('shop.index', ['category' => $quickCat->slug]) }}" class="hover:text-rose-600 dark:hover:text-rose-400 transition-colors shrink-0">
                                <span>{{ $quickCat->name }}</span>
                            </a>
                        @endforeach
                    </div>

                    <!-- 24/7 Support Info -->
                    <div class="hidden lg:flex items-center gap-3 text-gray-400 text-[11px] whitespace-nowrap shrink-0">
                        <span class="text-emerald-500 font-bold">● {{ __('24/7 Support') }}</span>
                        <span>|</span>
                        <span>{{ __('Phone') }}: <span class="font-mono">{{ __('Support Phone Number') }}</span></span>
                    </div>

                </div>
            </div>
        </div>
    </header>

    <!-- Global Toast / Flash Notifications -->
    <div class="max-w-7xl mx-auto px-2.5 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="bg-emerald-500/10 dark:bg-emerald-500/20 border border-emerald-500/30 text-emerald-700 dark:text-emerald-400 px-4 py-3 rounded-2xl mb-4 flex items-center justify-between text-sm shadow-sm" role="alert">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ __(session('success')) }}</span>
                </div>
            </div>
        @endif

        @if(session('error') || $errors->any())
            <div class="bg-rose-500/10 dark:bg-rose-500/20 border border-rose-500/30 text-rose-700 dark:text-rose-400 px-4 py-3 rounded-2xl mb-4 text-sm shadow-sm" role="alert">
                <div class="flex items-center gap-2.5 mb-1 font-bold">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ __('لطفاً خطاهای زیر را بررسی نمایید:') }}</span>
                </div>
                <ul class="list-disc list-inside space-y-1 ltr:ml-6 rtl:mr-6 text-xs">
                    @if(session('error')) <li>{{ __(session('error')) }}</li> @endif
                    @foreach($errors->all() as $error) <li>{{ __($error) }}</li> @endforeach
                </ul>
            </div>
        @endif

        @if(session('info'))
            <div class="bg-sky-500/10 dark:bg-sky-500/20 border border-sky-500/30 text-sky-700 dark:text-sky-400 px-4 py-3 rounded-2xl mb-4 flex items-center gap-2.5 text-sm shadow-sm">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ __(session('info')) }}</span>
            </div>
        @endif
    </div>

    <!-- Main Content Container with bottom mobile nav clearance -->
    <main class="flex-1 w-full pb-24 lg:pb-12">
        @yield('content')
    </main>

    <!-- Footer Component -->
    <footer class="bg-white dark:bg-zinc-900 border-t border-gray-200 dark:border-zinc-800 mt-auto transition-colors duration-200">
        <!-- Trust Badges Bar -->
        <div class="border-b border-gray-100 dark:border-zinc-800/80 py-6 sm:py-8 bg-gray-50/50 dark:bg-zinc-900/50">
            <div class="max-w-7xl mx-auto px-2.5 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 min-[340px]:grid-cols-2 md:grid-cols-4 gap-3 sm:gap-6 text-center">
                    <div class="flex flex-col items-center gap-2 p-3">
                        <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-zinc-800 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <span class="font-bold text-sm text-gray-900 dark:text-gray-100">{{ __('Express Delivery') }}</span>
                        <span class="text-xs text-gray-500">{{ __('Fast Delivery Nationwide') }}</span>
                    </div>

                    <div class="flex flex-col items-center gap-2 p-3">
                        <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-zinc-800 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <span class="font-bold text-sm text-gray-900 dark:text-gray-100">{{ __('100% Original Guarantee') }}</span>
                        <span class="text-xs text-gray-500">{{ __('All items with valid warranty') }}</span>
                    </div>

                    <div class="flex flex-col items-center gap-2 p-3">
                        <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-zinc-800 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        </div>
                        <span class="font-bold text-sm text-gray-900 dark:text-gray-100">{{ __('7 Days Return Policy') }}</span>
                        <span class="text-xs text-gray-500">{{ __('Hassle-free return and exchange') }}</span>
                    </div>

                    <div class="flex flex-col items-center gap-2 p-3">
                        <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-zinc-800 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <span class="font-bold text-sm text-gray-900 dark:text-gray-100">{{ __('24/7 Expert Support') }}</span>
                        <span class="text-xs text-gray-500">{{ __('Customer service all week') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Footer Links & Newsletter -->
        <div class="max-w-7xl mx-auto px-2.5 sm:px-6 lg:px-8 py-8 sm:py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8">
                
                <!-- About column -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center gap-2 text-xl font-black text-rose-600">
                        <span>{{ config('app.name', 'DigiStore') }}</span>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                        {{ __('DigiStore Footer Bio') }}
                    </p>
                    <div class="text-xs text-gray-500 dark:text-gray-400 space-y-1">
                        <div>{{ __('Support Tel') }}: <span class="font-bold font-mono">{{ __('Support Phone Number') }}</span></div>
                        <div>{{ __('Support Email Label') }}: <span class="font-mono">{{ __('Support Email') }}</span></div>
                    </div>
                </div>

                <!-- Quick links -->
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100 mb-4">{{ __('Quick Access') }}</h3>
                    <ul class="space-y-2.5 text-sm text-gray-600 dark:text-gray-400">
                        <li><a href="{{ route('shop.index') }}" class="hover:text-rose-600 transition-colors">{{ __('Product List') }}</a></li>
                        <li><a href="{{ route('pages.about') }}" class="hover:text-rose-600 transition-colors">{{ __('About Us') }}</a></li>
                        <li><a href="{{ route('pages.contact') }}" class="hover:text-rose-600 transition-colors">{{ __('Contact Us') }}</a></li>
                        <li><a href="{{ route('pages.faq') }}" class="hover:text-rose-600 transition-colors">{{ __('FAQ') }}</a></li>
                    </ul>
                </div>

                <!-- Legal policies -->
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100 mb-4">{{ __('Customer Service') }}</h3>
                    <ul class="space-y-2.5 text-sm text-gray-600 dark:text-gray-400">
                        <li><a href="{{ route('pages.shipping-policy') }}" class="hover:text-rose-600 transition-colors">{{ __('Shipping Methods') }}</a></li>
                        <li><a href="{{ route('pages.return-policy') }}" class="hover:text-rose-600 transition-colors">{{ __('Return Guarantee Terms') }}</a></li>
                        <li><a href="{{ route('pages.privacy') }}" class="hover:text-rose-600 transition-colors">{{ __('Privacy') }}</a></li>
                        <li><a href="{{ route('pages.terms') }}" class="hover:text-rose-600 transition-colors">{{ __('Terms & Conditions') }}</a></li>
                    </ul>
                </div>

                <!-- Newsletter Subscription -->
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100 mb-4">{{ __('Newsletter Subscription') }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">{{ __('Be first to know about deals and discounts') }}</p>
                    <form action="{{ route('pages.newsletter') }}" method="POST" class="space-y-2">
                        @csrf
                        <div class="absolute -z-10 opacity-0 pointer-events-none" aria-hidden="true">
                            <input type="text" name="website" value="" tabindex="-1" autocomplete="off">
                        </div>
                        <input type="email" name="email" required placeholder="{{ __('Enter your email...') }}" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-rose-500">
                        <button type="submit" class="w-full bg-rose-600 text-white text-xs font-bold py-2.5 rounded-xl hover:bg-rose-700 transition-colors shadow-sm">{{ __('Subscribe') }}</button>
                    </form>
                </div>

            </div>

            <!-- Bottom Copyright -->
            <div class="border-t border-gray-100 dark:border-zinc-800 mt-8 pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-gray-400 gap-4">
                <div>{{ __('All rights reserved.') }} © {{ date('Y') }}</div>
                <div class="flex gap-4">
                    <a href="{{ route('pages.privacy') }}" class="hover:underline">{{ __('Privacy') }}</a>
                    <a href="{{ route('pages.terms') }}" class="hover:underline">{{ __('Terms of Use') }}</a>
                    <a href="{{ route('pages.cookie-policy') }}" class="hover:underline">{{ __('Cookies') }}</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Sticky Mobile CTA Bar -->
    <div class="lg:hidden fixed bottom-14 left-0 right-0 z-30 p-2 pointer-events-none">
        <div class="pointer-events-auto max-w-md mx-auto bg-gradient-to-r from-rose-600 to-pink-600 text-white rounded-2xl shadow-xl px-4 py-2 flex items-center justify-between gap-3 border border-white/20 backdrop-blur-md">
            <div class="flex items-center gap-2 text-xs font-bold truncate">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping shrink-0"></span>
                <span class="truncate">{{ __('Top Deals CTA') }}</span>
            </div>
            <a href="{{ route('shop.index') }}" class="shrink-0 bg-white text-rose-600 font-extrabold text-xs px-3 py-1.5 rounded-xl shadow-xs hover:bg-rose-50 transition-colors">
                {{ __('Shop') }}
            </a>
        </div>
    </div>

    <!-- Mobile Bottom Navigation Bar -->
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 dark:bg-zinc-900/95 backdrop-blur-md border-t border-gray-200 dark:border-zinc-800 py-2 px-2.5 min-[360px]:px-6 flex items-center justify-between shadow-2xl">
        <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 text-xs {{ request()->routeIs('home') ? 'text-rose-600 font-bold' : 'text-gray-500' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span>{{ __('Home') }}</span>
        </a>
        <a href="{{ route('shop.index') }}" class="flex flex-col items-center gap-1 text-xs {{ request()->routeIs('shop.index') ? 'text-rose-600 font-bold' : 'text-gray-500' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
            <span>{{ __('Categories') }}</span>
        </a>
        <a href="{{ route('cart.index') }}" class="flex flex-col items-center gap-1 text-xs relative {{ request()->routeIs('cart.*') ? 'text-rose-600 font-bold' : 'text-gray-500' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            @if($headerCartCount > 0)
                <span class="absolute -top-1 right-2 bg-rose-500 text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center">{{ $headerCartCount }}</span>
            @endif
            <span>{{ __('Cart') }}</span>
        </a>
        <a href="{{ auth()->check() ? route('account.dashboard') : route('login') }}" class="flex flex-col items-center gap-1 text-xs {{ request()->routeIs('account.*') || request()->routeIs('login') ? 'text-rose-600 font-bold' : 'text-gray-500' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            <span>{{ __('Account') }}</span>
        </a>
    </nav>

    @include('partials.cookie-consent')

    <!-- Form Loading States & Submissions -->
    <script>
        document.addEventListener('submit', function (e) {
            const form = e.target;
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn && !submitBtn.disabled && !submitBtn.classList.contains('no-spin')) {
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
                const loadingText = submitBtn.getAttribute('data-loading-text') || '{{ __("Processing...") }}';
                submitBtn.innerHTML = '<span class="inline-flex items-center gap-2"><svg class="animate-spin h-4 w-4 text-current" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> ' + loadingText + '</span>';
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                    submitBtn.innerHTML = originalText;
                }, 8000);
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
