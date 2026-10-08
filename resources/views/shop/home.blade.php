@extends('layouts.app')

@section('title', __('DigiStore | Digital Goods Online Shop'))
@section('meta_description', __('Buy mobile phones, laptops, headphones, smartwatches, gaming consoles and digital accessories with authentic guarantee and fast nationwide shipping'))

@section('content')
<div class="max-w-7xl mx-auto px-2.5 sm:px-6 lg:px-8 py-4 sm:py-6 space-y-8 sm:space-y-12">

    <!-- Editorial Hero -->
    @if($heroBanners->isNotEmpty())
        <section class="relative overflow-hidden rounded-[2rem] bg-zinc-950 text-white shadow-2xl border border-zinc-800"
             x-data="{
                active: 0,
                total: {{ $heroBanners->count() }},
                next() { this.active = (this.active + 1) % this.total; },
                prev() { this.active = (this.active - 1 + this.total) % this.total; },
                autoplay() { setInterval(() => { this.next() }, 6500); }
             }"
             x-init="autoplay()">

            <!-- Ambient background -->
            <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
                <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-rose-600/25 blur-3xl"></div>
                <div class="absolute -bottom-40 right-1/3 w-[28rem] h-[28rem] rounded-full bg-indigo-600/20 blur-3xl"></div>
                <div class="absolute inset-0 opacity-[0.15]" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.5) 1px, transparent 0); background-size: 28px 28px;"></div>
            </div>

            <div class="relative grid lg:grid-cols-2 gap-6 lg:gap-10 items-center p-4 min-[360px]:p-7 sm:p-10 md:p-14">
                <!-- Copy -->
                <div class="space-y-6 animate-fade-up text-start">
                    @foreach($heroBanners as $index => $banner)
                        <div x-show="active === {{ $index }}" @if($index > 0) x-cloak @endif class="space-y-5">
                            @if($banner->badge_text)
                                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-white/10 border border-white/15 backdrop-blur-md text-xs font-bold rounded-full text-rose-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-400 animate-pulse"></span>
                                    {{ $banner->badge_text }}
                                </span>
                            @endif
                            <h1 class="text-2xl min-[360px]:text-3xl sm:text-4xl xl:text-5xl font-black leading-[1.25]">{{ $banner->title }}</h1>
                            @if($banner->subtitle)
                                <p class="text-sm sm:text-base text-zinc-300 leading-relaxed line-clamp-2 max-w-lg">{{ $banner->subtitle }}</p>
                            @endif
                            <div class="flex flex-wrap items-center gap-2.5 sm:gap-3 pt-1">
                                @if($banner->link_url)
                                    <a href="{{ $banner->link_url }}" class="inline-flex items-center gap-2 bg-rose-600 hover:bg-rose-500 text-white font-black px-4 min-[360px]:px-7 py-2.5 min-[360px]:py-3.5 rounded-2xl transition-all shadow-xl shadow-rose-600/30 hover:shadow-rose-500/40 hover:-translate-y-0.5 text-xs sm:text-sm">
                                        <span>{{ __('Shop Now') }}</span>
                                        <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </a>
                                @endif
                                <a href="{{ route('shop.index', ['has_discount' => 1]) }}" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/15 border border-white/15 backdrop-blur-md font-bold px-4 min-[360px]:px-6 py-2.5 min-[360px]:py-3.5 rounded-2xl transition-all text-xs sm:text-sm">
                                    {{ __("Today's Deals") }}
                                </a>
                            </div>
                        </div>
                    @endforeach

                    <!-- Trust row -->
                    <div class="flex flex-wrap items-center gap-x-5 gap-y-2 pt-2 text-xs text-zinc-400">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-amber-400 fill-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <b class="text-white font-mono">{{ fa_num('4.8') }}</b> {{ __('from 12K reviews') }}
                        </span>
                        <span class="w-1 h-1 rounded-full bg-zinc-600"></span>
                        <span><b class="text-white font-mono">{{ fa_num('1500+') }}</b> {{ __('Original Products') }}</span>
                        <span class="w-1 h-1 rounded-full bg-zinc-600"></span>
                        <span>{{ __('Nationwide Shipping') }}</span>
                    </div>

                    <!-- Controls -->
                    @if($heroBanners->count() > 1)
                        <div class="flex items-center gap-3 pt-1">
                            <button @click="prev()" aria-label="{{ __('Previous Slide') }}" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 border border-white/15 backdrop-blur-md flex items-center justify-center transition-colors">
                                <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                            </button>
                            <button @click="next()" aria-label="{{ __('Next Slide') }}" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 border border-white/15 backdrop-blur-md flex items-center justify-center transition-colors">
                                <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                            <div class="flex items-center gap-1.5 ltr:ml-1 rtl:mr-1">
                                @foreach($heroBanners as $index => $banner)
                                    <button @click="active = {{ $index }}" aria-label="{{ __('Go to slide :num', ['num' => $index + 1]) }}" class="h-1.5 rounded-full transition-all duration-300" :class="active === {{ $index }} ? 'bg-rose-500 w-8' : 'bg-white/25 w-3 hover:bg-white/40'"></button>
                                @endforeach
                            </div>
                            <span class="text-[11px] font-mono text-zinc-500 ltr:ml-auto rtl:mr-auto" x-text="(active + 1) + ' / ' + total"></span>
                        </div>
                    @endif
                </div>

                <!-- Visual -->
                <div class="relative">
                    <div class="relative h-[280px] sm:h-[360px] xl:h-[420px]">
                        @foreach($heroBanners as $index => $banner)
                            <div x-show="active === {{ $index }}"
                                 @if($index > 0) x-cloak @endif
                                 x-transition:enter="transition ease-out duration-700"
                                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-300"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="absolute inset-0">
                                <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" fetchpriority="{{ $index === 0 ? 'high' : 'auto' }}" class="w-full h-full object-cover rounded-3xl border border-white/10 shadow-2xl">
                                <div class="absolute inset-0 rounded-3xl bg-gradient-to-t from-zinc-950/50 via-transparent to-transparent"></div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Floating glass cards -->
                    <div class="absolute -top-4 ltr:-right-3 ltr:sm:-right-5 rtl:-left-3 rtl:sm:-left-5 bg-white/95 dark:bg-zinc-900/95 backdrop-blur-md rounded-2xl shadow-xl px-4 py-3 flex items-center gap-3 animate-float-slow border border-gray-100 dark:border-zinc-700">
                        <span class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-black text-sm">{{ app()->getLocale() === 'fa' ? '٪' : '%' }}</span>
                        <div>
                            <div class="text-xs font-black text-gray-900 dark:text-white">{{ __('Up to 30% Off') }}</div>
                            <div class="text-[10px] text-gray-400">{{ __('On Selected Brands') }}</div>
                        </div>
                    </div>
                    <div class="absolute -bottom-5 ltr:left-2 ltr:sm:left-6 rtl:right-2 rtl:sm:right-6 bg-white/95 dark:bg-zinc-900/95 backdrop-blur-md rounded-2xl shadow-xl px-4 py-3 flex items-center gap-3 animate-float-slow border border-gray-100 dark:border-zinc-700" style="animation-delay: -3.5s;">
                        <span class="w-9 h-9 rounded-xl bg-rose-100 dark:bg-rose-950 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0 2 2 0 00-4 0z"></path></svg>
                        </span>
                        <div>
                            <div class="text-xs font-black text-gray-900 dark:text-white">{{ __('Fast Delivery') }}</div>
                            <div class="text-[10px] text-gray-400">{{ __('Capital & Provinces') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Brand marquee -->
        @if($brands->isNotEmpty())
            <div class="relative overflow-hidden rounded-2xl border border-gray-100 dark:border-zinc-800 bg-white dark:bg-zinc-900 py-3.5 shadow-sm" dir="ltr">
                <div class="flex w-max animate-marquee gap-10 px-5">
                    @for($i = 0; $i < 2; $i++)
                        @foreach($brands as $brand)
                            <a href="{{ route('shop.index', ['brand' => $brand->slug]) }}" class="flex items-center gap-2.5 text-xs font-black text-gray-400 dark:text-zinc-500 hover:text-rose-600 dark:hover:text-rose-400 transition-colors whitespace-nowrap">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500/60"></span>
                                <span>{{ $brand->name }}</span>
                            </a>
                        @endforeach
                    @endfor
                </div>
                <div class="absolute inset-y-0 left-0 w-16 bg-gradient-to-r from-white dark:from-zinc-900 to-transparent pointer-events-none"></div>
                <div class="absolute inset-y-0 right-0 w-16 bg-gradient-to-l from-white dark:from-zinc-900 to-transparent pointer-events-none"></div>
            </div>
        @endif
    @endif

    <!-- Value Propositions Strip -->
    <div class="grid grid-cols-1 min-[340px]:grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl p-3 min-[360px]:p-4 flex items-center gap-3 shadow-xs">
            <span class="w-10 h-10 sm:w-11 sm:h-11 shrink-0 rounded-xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0 2 2 0 00-4 0z"></path></svg>
            </span>
            <div class="text-start">
                <h4 class="font-bold text-xs sm:text-sm text-gray-900 dark:text-gray-100">{{ __('Fast Nationwide Delivery') }}</h4>
                <p class="text-[11px] text-gray-400">{{ __('Express in Capital & Cities') }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl p-3 min-[360px]:p-4 flex items-center gap-3 shadow-xs">
            <span class="w-10 h-10 sm:w-11 sm:h-11 shrink-0 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </span>
            <div class="text-start">
                <h4 class="font-bold text-xs sm:text-sm text-gray-900 dark:text-gray-100">{{ __('Authenticity Guarantee Strip') }}</h4>
                <p class="text-[11px] text-gray-400">{{ __('Official Warranty & Registry') }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl p-3 min-[360px]:p-4 flex items-center gap-3 shadow-xs">
            <span class="w-10 h-10 sm:w-11 sm:h-11 shrink-0 rounded-xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            </span>
            <div class="text-start">
                <h4 class="font-bold text-xs sm:text-sm text-gray-900 dark:text-gray-100">{{ __('7-Day Return Guarantee Strip') }}</h4>
                <p class="text-[11px] text-gray-400">{{ __('Unconditional Return Policy') }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl p-3 min-[360px]:p-4 flex items-center gap-3 shadow-xs">
            <span class="w-10 h-10 sm:w-11 sm:h-11 shrink-0 rounded-xl bg-sky-50 dark:bg-sky-950/40 text-sky-600 dark:text-sky-400 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
            </span>
            <div class="text-start">
                <h4 class="font-bold text-xs sm:text-sm text-gray-900 dark:text-gray-100">{{ __('Secure Bank Payment Strip') }}</h4>
                <p class="text-[11px] text-gray-400">{{ __('Official Shetab Network Gateway') }}</p>
            </div>
        </div>
    </div>

    <!-- Special Offers / Flash Deals with Real-Time Countdown -->
    @if($discountedProducts->isNotEmpty())
        <section class="bg-gradient-to-r from-rose-600 via-rose-500 to-amber-500 rounded-3xl p-4 min-[360px]:p-6 sm:p-8 shadow-2xl text-white space-y-6"
                 x-data="{
                    hours: 9,
                    minutes: 42,
                    seconds: 15,
                    init() {
                        setInterval(() => {
                            if (this.seconds > 0) this.seconds--;
                            else if (this.minutes > 0) { this.minutes--; this.seconds = 59; }
                            else if (this.hours > 0) { this.hours--; this.minutes = 59; this.seconds = 59; }
                        }, 1000);
                    }
                 }">
            
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3 text-center sm:text-start">
                    <span class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </span>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-black">{{ __("Today's Amazing Offer") }}</h2>
                        <p class="text-xs text-rose-100 font-medium">{{ __('Handpicked bestselling products with special discount and limited stock') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Countdown Box -->
                    <div class="flex items-center gap-2 font-mono text-sm">
                        <span class="text-xs font-sans text-rose-100 ltr:mr-1 rtl:ml-1">{{ __('Time Remaining:') }}</span>
                        <div class="bg-black/30 backdrop-blur-md px-2.5 py-1 rounded-lg text-center font-bold" x-text="String(hours).padStart(2, '0')">09</div>
                        <span>:</span>
                        <div class="bg-black/30 backdrop-blur-md px-2.5 py-1 rounded-lg text-center font-bold" x-text="String(minutes).padStart(2, '0')">42</div>
                        <span>:</span>
                        <div class="bg-black/30 backdrop-blur-md px-2.5 py-1 rounded-lg text-center font-bold text-amber-300" x-text="String(seconds).padStart(2, '0')">15</div>
                    </div>

                    <a href="{{ route('shop.index', ['has_discount' => 1]) }}" class="text-xs font-black bg-white text-rose-600 px-4 py-2.5 rounded-xl hover:bg-rose-50 transition-colors shadow-md whitespace-nowrap">
                        {{ __('View All') }} ({{ fa_num($discountedProducts->count()) }}+)
                    </a>
                </div>
            </div>

            <!-- 4 Discounted Products Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($discountedProducts->take(4) as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </section>
    @endif

    <!-- Category Showcase Grid -->
    @if($featuredCategories->isNotEmpty())
        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-6 bg-rose-600 rounded-full"></span>
                    <h2 class="text-lg sm:text-xl font-black text-gray-900 dark:text-gray-100">{{ __('Shop by Category') }}</h2>
                </div>
                <a href="{{ route('shop.index') }}" class="text-xs font-bold text-rose-600 hover:underline flex items-center gap-1">
                    <span>{{ __('View All Products') }}</span>
                    <span class="inline-block rtl:rotate-180">←</span>
                </a>
            </div>

            <div class="grid grid-cols-1 min-[340px]:grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3 sm:gap-4">
                @foreach($featuredCategories as $category)
                    <a href="{{ route('shop.index', ['category' => $category->slug]) }}"
                       class="group relative rounded-2xl overflow-hidden border border-gray-100 dark:border-zinc-800 hover:shadow-xl transition-all aspect-[4/5] bg-zinc-100 dark:bg-zinc-800">
                        @if($category->image_url)
                            <img src="{{ $category->image_url }}" alt="{{ $category->name }}" loading="lazy" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-rose-50 to-gray-100 dark:from-zinc-800 dark:to-zinc-900">
                                <svg class="w-12 h-12 text-gray-300 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/25 to-transparent"></div>
                        <div class="absolute bottom-0 inset-x-0 p-4 text-white text-start">
                            <span class="block text-sm font-black leading-snug">{{ $category->name }}</span>
                            <span class="mt-1 inline-flex items-center gap-1 text-[11px] text-gray-200">
                                <span class="font-mono">{{ fa_num($category->products_count) }} {{ __('Items') }}</span>
                                <svg class="w-3.5 h-3.5 rtl:rotate-180 group-hover:ltr:translate-x-1 group-hover:rtl:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Department Strip -->
    @php
        $departments = \App\Models\Category::active()->root()->withCount('children')->orderBy('sort_order')->get();
        $totalSubcategoriesCount = \App\Models\Category::whereNotNull('parent_id')->count();
    @endphp
    @if($departments->isNotEmpty())
        <section class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-3xl p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between flex-wrap gap-2">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-6 bg-rose-600 rounded-full"></span>
                    <h2 class="text-lg sm:text-xl font-black text-gray-900 dark:text-gray-100">{{ __('Store Departments') }}</h2>
                </div>
                <span class="text-xs text-gray-500 dark:text-zinc-400 font-medium">
                    {{ __(':count Special Departments, over :subs Diverse Categories', ['count' => fa_num($departments->count()), 'subs' => fa_num($totalSubcategoriesCount)]) }}
                </span>
            </div>
            <div class="grid grid-cols-1 min-[340px]:grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2.5 sm:gap-3">
                @foreach($departments as $department)
                    <a href="{{ route('shop.index', ['category' => $department->slug]) }}"
                       class="group flex flex-col justify-between p-3.5 rounded-2xl border border-gray-100 dark:border-zinc-800/80 bg-gray-50/50 dark:bg-zinc-950/40 hover:border-rose-400 dark:hover:border-rose-500 hover:bg-rose-50/20 dark:hover:bg-zinc-800/60 hover:shadow-md transition-all text-start">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-white dark:bg-zinc-800 border border-gray-200/60 dark:border-zinc-700/60 flex items-center justify-center text-xl shrink-0 group-hover:scale-110 group-hover:rotate-3 transition-transform shadow-xs">
                                {{ $department->icon ?? '📦' }}
                            </span>
                            <div class="min-w-0 flex-1">
                                <span class="block text-xs font-bold text-gray-800 dark:text-gray-200 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors leading-snug truncate">
                                    {{ $department->name }}
                                </span>
                                <span class="block text-[10px] text-gray-400 dark:text-zinc-500 font-medium mt-0.5">
                                    {{ fa_num($department->children_count) }} {{ __('Subcategories') }}
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Promotional Top Banners -->
    @if($promoTopBanners->isNotEmpty())
        <section class="grid grid-cols-1 {{ $promoTopBanners->count() == 1 ? '' : ($promoTopBanners->count() == 3 ? 'md:grid-cols-3' : 'md:grid-cols-2') }} gap-4 sm:gap-6">
            @foreach($promoTopBanners as $banner)
                <a href="{{ $banner->link_url ?: '#' }}" class="group block rounded-3xl overflow-hidden shadow-lg relative aspect-[21/9] sm:aspect-[2/1] md:aspect-[21/9] border border-gray-100 dark:border-zinc-800 bg-zinc-900">
                    <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent flex flex-col justify-end p-5 sm:p-7 text-white space-y-1.5 text-start">
                        @if($banner->badge_text)
                            <div>
                                <span class="inline-block px-2.5 py-1 bg-rose-600/90 backdrop-blur-md text-[10px] sm:text-xs font-bold rounded-lg text-white mb-1 shadow-sm">
                                    {{ $banner->badge_text }}
                                </span>
                            </div>
                        @endif
                        <h3 class="font-black text-base sm:text-lg md:text-xl leading-snug group-hover:text-rose-300 transition-colors">{{ $banner->title }}</h3>
                        @if($banner->subtitle)
                            <p class="text-xs sm:text-sm text-gray-200 line-clamp-2">{{ $banner->subtitle }}</p>
                        @endif
                    </div>
                </a>
            @endforeach
        </section>
    @endif

    <!-- Featured Products Section -->
    @if($featuredProducts->isNotEmpty())
        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-6 bg-indigo-600 rounded-full"></span>
                    <h2 class="text-lg sm:text-xl font-black text-gray-900 dark:text-gray-100">{{ __("Editor's Special Picks") }}</h2>
                </div>
                <a href="{{ route('shop.index') }}" class="text-xs font-bold text-rose-600 hover:underline flex items-center gap-1">
                    <span>{{ __('View All') }}</span>
                    <span class="inline-block rtl:rotate-180">←</span>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </section>
    @endif

    <!-- Best Sellers Section -->
    @if($bestSellers->isNotEmpty())
        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-6 bg-amber-500 rounded-full"></span>
                    <h2 class="text-lg sm:text-xl font-black text-gray-900 dark:text-gray-100">{{ __('Bestsellers of the Week') }}</h2>
                </div>
                <a href="{{ route('shop.index', ['sort' => 'best_selling']) }}" class="text-xs font-bold text-rose-600 hover:underline flex items-center gap-1">
                    <span>{{ __('View All') }}</span>
                    <span class="inline-block rtl:rotate-180">←</span>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($bestSellers as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </section>
    @endif

    <!-- Mid-Page Campaign Poster -->
    @if(isset($promoMidBanners) && $promoMidBanners->isNotEmpty())
        <section class="space-y-4">
            @foreach($promoMidBanners as $banner)
                <div class="relative overflow-hidden rounded-[2rem] bg-zinc-950 border border-zinc-800 text-white shadow-2xl">
                    <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" loading="lazy" class="absolute inset-0 w-full h-full object-cover opacity-40 hover:scale-105 transition-transform duration-700 pointer-events-none">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/95 via-black/70 to-transparent"></div>
                    <div class="relative z-10 p-6 sm:p-10 lg:p-12 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 text-start">
                        <div class="space-y-3 max-w-2xl">
                            @if($banner->badge_text)
                                <span class="inline-flex items-center gap-2 px-3 py-1 bg-rose-600/90 border border-rose-500 text-white text-xs font-black rounded-full shadow-md">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping"></span>
                                    {{ $banner->badge_text }}
                                </span>
                            @endif
                            <h2 class="text-xl min-[360px]:text-2xl sm:text-3xl lg:text-4xl font-black leading-tight">{{ $banner->title }}</h2>
                            @if($banner->subtitle)
                                <p class="text-xs sm:text-sm text-zinc-300 leading-relaxed max-w-xl">{{ $banner->subtitle }}</p>
                            @endif
                        </div>
                        @if($banner->link_url)
                            <a href="{{ $banner->link_url }}" class="shrink-0 inline-flex items-center gap-2 bg-rose-600 hover:bg-rose-500 text-white font-black px-6 py-3.5 rounded-2xl shadow-xl shadow-rose-600/30 hover:shadow-rose-500/40 hover:-translate-y-0.5 transition-all text-xs sm:text-sm">
                                <span>{{ __('Explore Deals') }}</span>
                                <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </section>
    @endif

    <!-- New Arrivals Section -->
    @if($newArrivals->isNotEmpty())
        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-6 bg-emerald-500 rounded-full"></span>
                    <h2 class="text-lg sm:text-xl font-black text-gray-900 dark:text-gray-100">{{ __('New Arrivals') }}</h2>
                </div>
                <a href="{{ route('shop.index', ['sort' => 'newest']) }}" class="text-xs font-bold text-rose-600 hover:underline flex items-center gap-1">
                    <span>{{ __('View All') }}</span>
                    <span class="inline-block rtl:rotate-180">←</span>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($newArrivals as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </section>
    @endif

    <!-- Bottom Promotional Posters -->
    @if(isset($promoBottomBanners) && $promoBottomBanners->isNotEmpty())
        <section class="grid grid-cols-1 {{ $promoBottomBanners->count() == 1 ? '' : ($promoBottomBanners->count() == 3 ? 'md:grid-cols-3' : ($promoBottomBanners->count() >= 4 ? 'sm:grid-cols-2 lg:grid-cols-4' : 'md:grid-cols-2')) }} gap-4 sm:gap-6">
            @foreach($promoBottomBanners as $banner)
                <a href="{{ $banner->link_url ?: '#' }}" class="group relative rounded-3xl overflow-hidden aspect-[16/9] sm:aspect-[4/3] lg:aspect-[16/9] border border-gray-100 dark:border-zinc-800 bg-zinc-900 shadow-md hover:shadow-xl transition-all">
                    <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/35 to-transparent flex flex-col justify-end p-5 text-white space-y-1.5 text-start">
                        @if($banner->badge_text)
                            <div>
                                <span class="inline-block px-2.5 py-0.5 bg-amber-500 text-zinc-950 font-black text-[10px] sm:text-xs rounded-md shadow-sm">
                                    {{ $banner->badge_text }}
                                </span>
                            </div>
                        @endif
                        <h3 class="font-black text-sm sm:text-base leading-snug group-hover:text-rose-300 transition-colors">{{ $banner->title }}</h3>
                        @if($banner->subtitle)
                            <p class="text-xs text-zinc-300 line-clamp-1">{{ $banner->subtitle }}</p>
                        @endif
                    </div>
                </a>
            @endforeach
        </section>
    @endif

    <!-- Brands Showcase -->
    @if($brands->isNotEmpty())
        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-6 bg-gray-400 rounded-full"></span>
                    <h2 class="text-lg sm:text-xl font-black text-gray-900 dark:text-gray-100">{{ __('Shop by Brand') }}</h2>
                </div>
                <a href="{{ route('shop.index') }}" class="text-xs font-bold text-rose-600 hover:underline flex items-center gap-1">
                    <span>{{ __('View All') }}</span>
                    <span class="inline-block rtl:rotate-180">←</span>
                </a>
            </div>

            <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-3xl p-6 shadow-sm">
                <div class="grid grid-cols-2 min-[400px]:grid-cols-3 sm:grid-cols-4 lg:grid-cols-6 gap-2.5 sm:gap-4 items-stretch text-center">
                    @foreach($brands as $brand)
                        <a href="{{ route('shop.index', ['brand' => $brand->slug]) }}"
                           class="group p-2.5 min-[360px]:p-4 rounded-2xl border border-gray-100 dark:border-zinc-800 hover:border-rose-300 dark:hover:border-zinc-600 hover:shadow-md transition-all flex flex-col items-center justify-center gap-2 min-h-[96px]">
                            <span class="w-10 h-10 rounded-xl bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-black text-sm flex items-center justify-center group-hover:bg-rose-600 group-hover:text-white transition-colors">{{ mb_strtoupper(mb_substr(trim($brand->name), 0, 1)) }}</span>
                            <span class="font-bold text-xs text-gray-700 dark:text-gray-300 group-hover:text-rose-600 transition-colors leading-snug">{{ $brand->name }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Store Stats Band -->
    <section class="bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 rounded-3xl p-4 min-[360px]:p-6 sm:p-8 shadow-xl">
        <div class="grid grid-cols-1 min-[340px]:grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 text-center">
            <div class="space-y-1">
                <div class="text-2xl sm:text-3xl font-black font-mono">{{ fa_num('1500+') }}</div>
                <div class="text-xs text-gray-300 dark:text-gray-600">{{ __('Original Products Ready to Ship') }}</div>
            </div>
            <div class="space-y-1">
                <div class="text-2xl sm:text-3xl font-black font-mono">{{ fa_num('24') }}</div>
                <div class="text-xs text-gray-300 dark:text-gray-600">{{ __('Global Trusted Brands') }}</div>
            </div>
            <div class="space-y-1">
                <div class="text-2xl sm:text-3xl font-black font-mono">{{ fa_num('15+') }}</div>
                <div class="text-xs text-gray-300 dark:text-gray-600">{{ __('Specialized Tech Categories') }}</div>
            </div>
            <div class="space-y-1">
                <div class="text-2xl sm:text-3xl font-black">{{ fa_num('7') }} {{ __('Days') }}</div>
                <div class="text-xs text-gray-300 dark:text-gray-600">{{ __('Money-back Guarantee') }}</div>
            </div>
        </div>
    </section>

    <!-- Buying Guides -->
    <section class="space-y-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-6 bg-sky-600 rounded-full"></span>
                <h2 class="text-lg sm:text-xl font-black text-gray-900 dark:text-gray-100">{{ __('Buying Guides') }}</h2>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('shop.index', ['category' => 'mobile-phones']) }}" class="group bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl overflow-hidden hover:shadow-lg transition-all text-start">
                <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&auto=format&fit=crop&q=80" alt="{{ __('Phone Buying Guide: Flagship or Midrange?') }}" loading="lazy" class="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="p-4 space-y-1.5">
                    <h3 class="font-black text-sm text-gray-900 dark:text-gray-100 group-hover:text-rose-600 transition-colors">{{ __('Phone Buying Guide: Flagship or Midrange?') }}</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">{{ __('Key tips on registry, warranty and selecting proper storage before buying a smartphone.') }}</p>
                </div>
            </a>
            <a href="{{ route('shop.index', ['category' => 'laptops']) }}" class="group bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl overflow-hidden hover:shadow-lg transition-all text-start">
                <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800&auto=format&fit=crop&q=80" alt="{{ __('Which Laptop Fits Your Needs Best?') }}" loading="lazy" class="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="p-4 space-y-1.5">
                    <h3 class="font-black text-sm text-gray-900 dark:text-gray-100 group-hover:text-rose-600 transition-colors">{{ __('Which Laptop Fits Your Needs Best?') }}</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">{{ __('Differences between gaming, engineering and ultrabook series and recommended hardware specs.') }}</p>
                </div>
            </a>
            <a href="{{ route('shop.index', ['category' => 'headphones']) }}" class="group bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl overflow-hidden hover:shadow-lg transition-all text-start">
                <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80" alt="{{ __('How to Recognize True Active Noise Cancelling?') }}" loading="lazy" class="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="p-4 space-y-1.5">
                    <h3 class="font-black text-sm text-gray-900 dark:text-gray-100 group-hover:text-rose-600 transition-colors">{{ __('How to Recognize True Active Noise Cancelling?') }}</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">{{ __('Everything you need to know about audio drivers, Bluetooth codecs and battery endurance at a glance.') }}</p>
                </div>
            </a>
        </div>
    </section>

</div>
@endsection
