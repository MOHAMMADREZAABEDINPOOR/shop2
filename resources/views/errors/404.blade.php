@extends('layouts.app')

@section('title', __('۴۰۴ - صفحه مورد نظر یافت نشد'))
@section('robots', 'noindex, nofollow')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-14 space-y-10">

    <div class="text-center space-y-4">
        <div class="relative inline-block select-none" aria-hidden="true">
            <span class="block text-[7rem] md:text-[11rem] leading-none font-black font-mono text-transparent bg-clip-text bg-gradient-to-b from-rose-600/25 to-rose-600/5 dark:from-rose-400/25 dark:to-rose-400/5">404</span>
            <div class="absolute inset-0 flex items-center justify-center">
                <span class="w-20 h-20 md:w-24 md:h-24 rounded-3xl bg-rose-600 text-white flex items-center justify-center shadow-2xl shadow-rose-600/30 -rotate-6">
                    <svg class="w-10 h-10 md:w-12 md:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
            </div>
        </div>

        <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white">این صفحه از قفسه ما افتاده!</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed max-w-md mx-auto">
            آدرسی که وارد کردید وجود ندارد یا کالا جابه‌جا شده است. عبارت مورد نظرتان را جستجو کنید یا از دسته‌بندی‌های محبوب شروع کنید.
        </p>

        <form action="{{ route('shop.index') }}" method="GET" class="max-w-md mx-auto relative">
            <input type="text" name="q" placeholder="{{ __('جستجو در میان ۱۵۰۰+ کالای دیجی‌استور...') }}" autocomplete="off"
                   class="w-full bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 rounded-2xl py-3.5 pr-12 pl-4 text-sm border border-gray-200 dark:border-zinc-700 focus:outline-none focus:ring-2 focus:ring-rose-500/50 shadow-sm">
            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
        </form>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
            <a href="{{ route('home') }}" class="w-full sm:w-auto px-8 py-3.5 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl font-bold text-sm shadow-lg shadow-rose-600/20 transition-all">
                بازگشت به صفحه اصلی
            </a>
            <a href="{{ route('shop.index') }}" class="w-full sm:w-auto px-8 py-3.5 bg-gray-100 hover:bg-gray-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-gray-700 dark:text-gray-200 rounded-2xl font-bold text-sm transition-colors">
                مشاهده همه محصولات
            </a>
        </div>
    </div>

    @php
        $popularCategories = \App\Models\Category::active()->whereNotNull('parent_id')->withCount('products')->orderByDesc('products_count')->take(6)->get();
    @endphp
    @if($popularCategories->isNotEmpty())
        <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-3xl p-6 shadow-sm space-y-4">
            <h2 class="text-sm font-black text-gray-900 dark:text-gray-100 text-center">یا از دسته‌بندی‌های پربازدید شروع کنید</h2>
            <div class="flex flex-wrap items-center justify-center gap-2">
                @foreach($popularCategories as $category)
                    <a href="{{ route('shop.index', ['category' => $category->slug]) }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-700 dark:text-gray-200 hover:border-rose-400 hover:text-rose-600 dark:hover:text-rose-400 transition-colors">
                        @if($category->image_url)
                            <img src="{{ $category->image_url }}" alt="{{ $category->name }}" loading="lazy" class="w-6 h-6 rounded-lg object-cover">
                        @endif
                        <span>{{ $category->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
