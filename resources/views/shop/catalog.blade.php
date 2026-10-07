@extends('layouts.app')

@section('title', ($selectedCategory ? $selectedCategory->name . ' | ' : '') . (request('q') ? __('Search: :query | ', ['query' => request('q')]) : '') . __('Shop & Products | DigiStore'))
@section('meta_description', ($selectedCategory ? __('Buy :category with authenticity guarantee, official warranty and fast shipping. ', ['category' => $selectedCategory->name]) : '') . (request('q') ? __('Search results for «:query». ', ['query' => request('q')]) : '') . __('Over 1500 authentic digital goods at the best prices on DigiStore'))

@section('content')
<div class="max-w-7xl mx-auto px-2.5 sm:px-6 lg:px-8 py-4 sm:py-6">

    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mb-6 flex-wrap">
        <a href="{{ route('home') }}" class="hover:text-rose-600 transition-colors">{{ __('Home') }}</a>
        <span class="text-gray-300 dark:text-zinc-600">/</span>
        <a href="{{ route('shop.index') }}" class="hover:text-rose-600 transition-colors">{{ __('Shop') }}</a>
        @if($selectedCategory)
            <span class="text-gray-300 dark:text-zinc-600">/</span>
            <span class="text-gray-900 dark:text-gray-100 font-bold">{{ $selectedCategory->name }}</span>
        @endif
        @if(request('q'))
            <span class="text-gray-300 dark:text-zinc-600">/</span>
            <span>{{ __('Search results for: ":query"', ['query' => request('q')]) }}</span>
        @endif
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 lg:gap-8 items-start">
        
        <!-- Sidebar Filters -->
        <aside class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-3xl p-4 sm:p-6 shadow-sm space-y-6 sticky top-24">
            <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-zinc-800">
                <span class="font-black text-base text-gray-900 dark:text-gray-100 flex items-center gap-2">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    {{ __('Filters') }}
                </span>
                @if(request()->hasAny(['q', 'category', 'brand', 'min_price', 'max_price', 'in_stock', 'has_discount']))
                    <a href="{{ route('shop.index') }}" class="text-xs text-rose-600 hover:underline">{{ __('Clear All') }}</a>
                @endif
            </div>

            <form action="{{ route('shop.index') }}" method="GET" class="space-y-6">
                @if(request('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                @endif

                <!-- In-Stock Toggle -->
                <div class="flex items-center justify-between py-2">
                    <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ __('In Stock Only') }}</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="in_stock" value="1" {{ request('in_stock') ? 'checked' : '' }} onchange="this.form.submit()" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-zinc-800 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-600"></div>
                    </label>
                </div>

                <!-- Has-Discount Toggle -->
                <div class="flex items-center justify-between py-2 border-t border-gray-100 dark:border-zinc-800">
                    <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ __('Discounted Only') }}</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="has_discount" value="1" {{ request('has_discount') ? 'checked' : '' }} onchange="this.form.submit()" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-zinc-800 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-600"></div>
                    </label>
                </div>

                <!-- Categories Accordion -->
                <div class="space-y-3 border-t border-gray-100 dark:border-zinc-800 pt-4"
                     x-data="{
                         catSearch: '',
                         expandedDept: '{{ $selectedCategory?->parent?->slug ?? $selectedCategory?->slug ?? '' }}'
                     }">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ __('Categories') }}</h3>
                        <span class="text-[11px] text-gray-400 font-mono">{{ fa_num($categories->count()) }}</span>
                    </div>

                    <!-- Quick Category Search -->
                    <div class="relative">
                        <input type="text"
                               x-model="catSearch"
                               placeholder="{{ __('Search categories...') }}"
                               class="w-full text-xs px-3 py-1.5 bg-gray-50 dark:bg-zinc-800/80 border border-gray-200 dark:border-zinc-700/60 rounded-xl text-gray-800 dark:text-zinc-200 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-rose-500">
                        <span x-show="catSearch" @click="catSearch = ''" class="absolute ltr:right-2.5 rtl:left-2.5 top-2 text-xs text-gray-400 hover:text-gray-600 cursor-pointer">✕</span>
                    </div>

                    <div class="space-y-1.5 max-h-72 overflow-y-auto ltr:pr-1 rtl:pl-1 scrollbar-thin">
                        @foreach($categories as $cat)
                            <div class="border border-gray-100 dark:border-zinc-800/60 rounded-xl overflow-hidden bg-gray-50/30 dark:bg-zinc-950/20"
                                 x-show="!catSearch || '{{ mb_strtolower($cat->name) }}'.includes(catSearch.toLowerCase()) || {{ json_encode($cat->children->pluck('name')->map(fn($n) => mb_strtolower($n))->all()) }}.some(n => n.includes(catSearch.toLowerCase()))">
                                
                                <!-- Department Header -->
                                <div class="flex items-center justify-between px-2.5 py-1.5 hover:bg-gray-100/60 dark:hover:bg-zinc-800/50 transition-colors cursor-pointer"
                                     @click="expandedDept = (expandedDept === '{{ $cat->slug }}' ? '' : '{{ $cat->slug }}')">
                                    <label class="flex items-center gap-2 text-xs font-bold text-gray-800 dark:text-zinc-200 hover:text-rose-600 cursor-pointer flex-1 py-0.5 truncate" @click.stop>
                                        <input type="radio" name="category" value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'checked' : '' }} onchange="this.form.submit()" class="text-rose-600 focus:ring-rose-500">
                                        <span class="truncate">{{ $cat->icon }} {{ $cat->name }}</span>
                                    </label>
                                    @if($cat->children->isNotEmpty())
                                        <svg class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200 shrink-0"
                                             :class="(expandedDept === '{{ $cat->slug }}' || catSearch) ? 'rotate-90' : 'rtl:rotate-180'"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                        </svg>
                                    @endif
                                </div>

                                <!-- Subcategories list -->
                                @if($cat->children->isNotEmpty())
                                    <div class="px-2 pb-2 pt-0.5 space-y-1 bg-white dark:bg-zinc-900 border-t border-gray-100 dark:border-zinc-800/60"
                                         x-show="(expandedDept === '{{ $cat->slug }}' || catSearch)"
                                         x-collapse>
                                        @foreach($cat->children as $sub)
                                            <label class="flex items-center gap-2 text-[11px] text-gray-600 dark:text-zinc-400 hover:text-rose-600 dark:hover:text-rose-400 cursor-pointer py-1 px-1.5 rounded-lg hover:bg-rose-50/40 dark:hover:bg-zinc-800/40 transition-colors {{ request('category') === $sub->slug ? 'font-bold text-rose-600 dark:text-rose-400 bg-rose-50/60 dark:bg-zinc-800/60' : '' }}"
                                                   x-show="!catSearch || '{{ mb_strtolower($sub->name) }}'.includes(catSearch.toLowerCase())">
                                                <input type="radio" name="category" value="{{ $sub->slug }}" {{ request('category') === $sub->slug ? 'checked' : '' }} onchange="this.form.submit()" class="text-rose-600 focus:ring-rose-500">
                                                <span class="truncate">{{ $sub->icon }} {{ $sub->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Brands Accordion -->
                <div class="space-y-3 border-t border-gray-100 dark:border-zinc-800 pt-4">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ __('Brand') }}</h3>
                    <div class="space-y-1 max-h-48 overflow-y-auto ltr:pr-1 rtl:pl-1">
                        @foreach($brands as $brand)
                            <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 hover:text-rose-600 cursor-pointer py-1">
                                <input type="radio" name="brand" value="{{ $brand->slug }}" {{ request('brand') === $brand->slug ? 'checked' : '' }} onchange="this.form.submit()" class="text-rose-600 focus:ring-rose-500">
                                <span>{{ $brand->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Price Range Filter -->
                <div class="space-y-3 border-t border-gray-100 dark:border-zinc-800 pt-4">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ __('Price Range (Toman)') }}</h3>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="{{ __('From') }}" class="w-full text-xs p-2.5 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-rose-500 text-start">
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="{{ __('To') }}" class="w-full text-xs p-2.5 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-rose-500 text-start">
                    </div>
                    <button type="submit" class="w-full bg-gray-900 dark:bg-white text-white dark:text-zinc-900 font-bold py-2 rounded-xl text-xs hover:bg-rose-600 dark:hover:bg-rose-600 dark:hover:text-white transition-colors">{{ __('Apply Price Filter') }}</button>
                </div>
            </form>

            @if(isset($sidebarBanner) && $sidebarBanner)
                <div class="pt-2 border-t border-gray-100 dark:border-zinc-800">
                    <a href="{{ $sidebarBanner->link_url ?: '#' }}" class="group block rounded-2xl overflow-hidden relative aspect-[3/4] border border-gray-100 dark:border-zinc-800 bg-zinc-900 shadow-sm hover:shadow-md transition-all">
                        <img src="{{ $sidebarBanner->image_url }}" alt="{{ $sidebarBanner->title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent flex flex-col justify-end p-4 text-white text-start">
                            @if($sidebarBanner->badge_text)
                                <span class="inline-block px-2 py-0.5 bg-rose-600 text-white font-bold text-[10px] rounded-md self-start mb-1">{{ $sidebarBanner->badge_text }}</span>
                            @endif
                            <h4 class="font-black text-xs sm:text-sm group-hover:text-rose-300 transition-colors leading-tight">{{ $sidebarBanner->title }}</h4>
                            @if($sidebarBanner->subtitle)
                                <p class="text-[11px] text-gray-200 line-clamp-2 mt-0.5">{{ $sidebarBanner->subtitle }}</p>
                            @endif
                        </div>
                    </a>
                </div>
            @endif
        </aside>

        <!-- Product Listing Grid Area -->
        <main class="lg:col-span-3 space-y-6">
            
            <!-- Sorting & Counter Bar -->
            <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl p-3 sm:p-4 flex flex-col sm:flex-row items-center justify-between gap-3 sm:gap-4 shadow-sm">
                
                <!-- Sorting Options -->
                <div class="flex items-center gap-2 text-xs font-semibold overflow-x-auto w-full sm:w-auto pb-2 sm:pb-0 scrollbar-none">
                    <span class="text-gray-400 flex items-center gap-1 flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path></svg>
                        {{ __('Sorting:') }}
                    </span>
                    @php
                        $currentSort = request('sort', 'newest');
                        $sortOptions = [
                            'newest' => __('Newest'),
                            'best_selling' => __('Bestselling'),
                            'popular' => __('Customer Rating'),
                            'price_asc' => __('Cheapest'),
                            'price_desc' => __('Most Expensive'),
                        ];
                    @endphp
                    @foreach($sortOptions as $key => $label)
                        <a href="{{ request()->fullUrlWithQuery(['sort' => $key]) }}"
                           class="px-3 py-1.5 rounded-xl transition-colors flex-shrink-0 {{ $currentSort === $key ? 'bg-rose-600 text-white font-bold shadow-sm' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-zinc-800' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>

                <div class="text-xs text-gray-500 dark:text-zinc-400 font-bold bg-gray-100/80 dark:bg-zinc-800/80 px-3 py-1 rounded-xl whitespace-nowrap">
                    {{ fa_num(number_format($products->total())) }} {{ __('Items') }}
                </div>

            </div>

            <!-- Products Grid -->
            @if($products->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    @foreach($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="pt-6">
                    {{ $products->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-3xl p-8 sm:p-12 text-center space-y-4 shadow-sm">
                    <div class="w-20 h-20 mx-auto rounded-full bg-rose-50 dark:bg-zinc-800 text-rose-500 flex items-center justify-center">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-black text-gray-900 dark:text-gray-100">{{ __('No products found matching your criteria!') }}</h3>
                    <p class="text-sm text-gray-500 max-w-md mx-auto">{{ __('Please change filters or search with another keyword.') }}</p>
                    <a href="{{ route('shop.index') }}" class="inline-block bg-rose-600 text-white font-bold px-6 py-2.5 rounded-xl text-xs hover:bg-rose-700 transition-colors shadow-md">
                        {{ __('Clear all filters') }}
                    </a>
                </div>
            @endif

        </main>

    </div>

</div>
@endsection
