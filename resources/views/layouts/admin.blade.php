<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'fa' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Panel') - {{ config('app.name', 'DigiStore') }}</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <meta name="theme-color" content="#e11d48">

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
<body class="bg-gray-100 dark:bg-zinc-950 text-gray-800 dark:text-gray-100 font-sans antialiased min-h-screen flex selection:bg-rose-500 selection:text-white"
      x-data="{ sidebarOpen: false }">

    <!-- Sidebar Backdrop for mobile -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/60 backdrop-blur-xs z-40 lg:hidden" style="display: none;"></div>

    <!-- Sidebar Navigation -->
    <aside class="fixed inset-y-0 {{ app()->getLocale() === 'fa' ? 'right-0' : 'left-0' }} z-50 w-64 max-w-[calc(100vw-2rem)] bg-zinc-900 text-white flex flex-col justify-between transition-transform duration-300 transform lg:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0' : '{{ app()->getLocale() === 'fa' ? 'translate-x-full' : '-translate-x-full' }} lg:translate-x-0'">
        
        <div>
            <!-- Brand Header -->
            <div class="h-16 sm:h-18 flex items-center justify-between px-4 sm:px-6 border-b border-zinc-800">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 font-black text-base sm:text-lg text-rose-500 truncate">
                    <span class="p-1.5 bg-rose-600 text-white rounded-xl flex-shrink-0">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </span>
                    <span class="truncate">{{ __('پنل مدیریت') }}</span>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white p-1">✕</button>
            </div>

            <!-- Nav Links -->
            <nav class="p-3 sm:p-4 space-y-1 text-xs font-semibold overflow-y-auto max-h-[calc(100vh-140px)]">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 sm:gap-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-rose-600 text-white font-bold' : 'text-gray-300 hover:bg-zinc-800' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    {{ __('داشبورد و آمار') }}
                </a>

                <a href="{{ route('admin.products.index') }}" class="flex items-center gap-2.5 sm:gap-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl transition-colors {{ request()->routeIs('admin.products.*') ? 'bg-rose-600 text-white font-bold' : 'text-gray-300 hover:bg-zinc-800' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    {{ __('مدیریت محصولات') }}
                </a>

                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-2.5 sm:gap-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-rose-600 text-white font-bold' : 'text-gray-300 hover:bg-zinc-800' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                    {{ __('دسته‌بندی‌ها') }}
                </a>

                <a href="{{ route('admin.brands.index') }}" class="flex items-center gap-2.5 sm:gap-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl transition-colors {{ request()->routeIs('admin.brands.*') ? 'bg-rose-600 text-white font-bold' : 'text-gray-300 hover:bg-zinc-800' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    {{ __('برندها') }}
                </a>

                <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-2.5 sm:gap-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-rose-600 text-white font-bold' : 'text-gray-300 hover:bg-zinc-800' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    {{ __('سفارش‌ها و پرداخت‌ها') }}
                </a>

                <a href="{{ route('admin.coupons.index') }}" class="flex items-center gap-2.5 sm:gap-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl transition-colors {{ request()->routeIs('admin.coupons.*') ? 'bg-rose-600 text-white font-bold' : 'text-gray-300 hover:bg-zinc-800' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                    {{ __('کدهای تخفیف') }}
                </a>

                <a href="{{ route('admin.reviews.index') }}" class="flex items-center gap-2.5 sm:gap-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl transition-colors {{ request()->routeIs('admin.reviews.*') ? 'bg-rose-600 text-white font-bold' : 'text-gray-300 hover:bg-zinc-800' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    {{ __('دیدگاه‌ها و نظرات') }}
                </a>

                <a href="{{ route('admin.banners.index') }}" class="flex items-center gap-2.5 sm:gap-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl transition-colors {{ request()->routeIs('admin.banners.*') ? 'bg-rose-600 text-white font-bold' : 'text-gray-300 hover:bg-zinc-800' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    {{ __('بنرهای صفحه اصلی (CMS)') }}
                </a>

                <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-2.5 sm:gap-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-rose-600 text-white font-bold' : 'text-gray-300 hover:bg-zinc-800' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                    {{ __('تنظیمات سامانه') }}
                </a>

                <a href="{{ route('admin.audit-logs.index') }}" class="flex items-center gap-2.5 sm:gap-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl transition-colors {{ request()->routeIs('admin.audit-logs.*') ? 'bg-rose-600 text-white font-bold' : 'text-gray-300 hover:bg-zinc-800' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    {{ __('لاگ‌های سیستمی (Audit)') }}
                </a>
            </nav>
        </div>

        <!-- Sidebar Bottom Info -->
        <div class="p-3 sm:p-4 border-t border-zinc-800 text-xs space-y-2.5">
            <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-2 text-gray-400 hover:text-white transition-colors truncate">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                <span class="truncate">{{ __('مشاهده وب‌سایت فروشگاه') }}</span>
            </a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full rtl:text-right ltr:text-left text-rose-400 hover:text-rose-300 font-bold truncate">
                    {{ __('خروج از پنل مدیریت') }}
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col {{ app()->getLocale() === 'fa' ? 'lg:mr-64' : 'lg:ml-64' }} min-h-screen min-w-0">
        
        <!-- Admin Top Navigation Bar -->
        <header class="h-16 sm:h-18 bg-white dark:bg-zinc-900 border-b border-gray-200 dark:border-zinc-800 px-3 sm:px-6 flex items-center justify-between sticky top-0 z-30 gap-2">
            <div class="flex items-center gap-2 sm:gap-4 min-w-0">
                <button @click="sidebarOpen = true" class="lg:hidden text-gray-600 dark:text-gray-300 p-1.5 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded-lg flex-shrink-0" aria-label="Open sidebar">
                    <svg class="w-5 sm:w-6 h-5 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <h1 class="text-xs sm:text-base font-bold text-gray-900 dark:text-gray-100 truncate">@yield('page_title', 'Admin Panel')</h1>
            </div>

            <div class="flex items-center gap-2 sm:gap-4 flex-shrink-0">
                <!-- Language Switcher in Admin Topbar -->
                <div class="inline-flex items-center bg-gray-100 dark:bg-zinc-800 rounded-lg p-0.5 border border-gray-200 dark:border-zinc-700 text-[11px] sm:text-xs font-semibold">
                    <a href="{{ route('lang.switch', 'en') }}" class="px-1.5 sm:px-2 py-0.5 rounded transition-all {{ app()->getLocale() === 'en' ? 'bg-rose-600 text-white shadow-xs font-bold' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">EN</a>
                    <a href="{{ route('lang.switch', 'fa') }}" class="px-1.5 sm:px-2 py-0.5 rounded transition-all {{ app()->getLocale() === 'fa' ? 'bg-rose-600 text-white shadow-xs font-bold' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">فا</a>
                </div>

                <button onclick="toggleTheme()" type="button" class="p-1.5 sm:p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded-full" aria-label="Toggle theme">
                    <svg class="w-4.5 sm:w-5 h-4.5 sm:h-5 hidden dark:block text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 9h-1m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <svg class="w-4.5 sm:w-5 h-4.5 sm:h-5 block dark:hidden text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                </button>

                <div class="flex items-center gap-1.5 sm:gap-2 text-xs font-bold">
                    <span class="w-6 sm:w-7 h-6 sm:h-7 rounded-full bg-rose-600 text-white flex items-center justify-center font-bold text-[11px] sm:text-xs flex-shrink-0">
                        {{ mb_substr(auth()->user()->name, 0, 1) }}
                    </span>
                    <span class="hidden sm:inline truncate max-w-[120px]">{{ auth()->user()->name }}</span>
                </div>
            </div>
        </header>

        <!-- Flash Messages in Admin -->
        <div class="p-3.5 sm:p-6 pb-0">
            @if(session('success'))
                <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 dark:text-emerald-400 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl mb-4 text-xs font-bold">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('info'))
                <div class="bg-sky-500/10 border border-sky-500/30 text-sky-700 dark:text-sky-400 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl mb-4 text-xs font-bold">
                    {{ session('info') }}
                </div>
            @endif
            @if($errors->any())
                <div class="bg-rose-500/10 border border-rose-500/30 text-rose-700 dark:text-rose-400 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl mb-4 text-xs font-bold">
                    @foreach($errors->all() as $error) <div>{{ $error }}</div> @endforeach
                </div>
            @endif
        </div>

        <main class="p-3.5 sm:p-6 flex-1 min-w-0">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
