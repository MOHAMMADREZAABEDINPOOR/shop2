@extends('layouts.admin')

@section('title', __('پوسترها و بنرهای تبلیغاتی'))
@section('page_title', __('مدیریت پوسترها و بنرهای فروشگاه'))

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white flex items-center gap-2.5">
                <span class="p-2 rounded-xl bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </span>
                {{ __('مدیریت پوسترها و بنرهای فروشگاه') }}
            </h1>
            <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">{{ __('تغییر و مدیریت کلیه پوسترهای صفحه اصلی، کمپین‌ها، اسلایدرها و ستون‌های کناری') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('home') }}" target="_blank" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-gray-700 dark:text-gray-300 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                {{ __('مشاهده در سایت') }}
            </a>
            <a href="{{ route('admin.banners.create') }}" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-rose-600/25 hover:shadow-rose-600/40 hover:-translate-y-0.5 transition-all flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                {{ __('افزودن پوستر جدید') }}
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900/50 rounded-2xl text-emerald-700 dark:text-emerald-400 text-xs font-bold flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('info'))
        <div class="p-4 bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-900/50 rounded-2xl text-blue-700 dark:text-blue-400 text-xs font-bold flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('info') }}
        </div>
    @endif

    <!-- Position Filter Tabs -->
    <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
        <a href="{{ route('admin.banners.index') }}" 
           class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 {{ !request('position') ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 shadow-sm' : 'bg-white dark:bg-zinc-900 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-zinc-800 hover:bg-gray-50' }}">
            <span>{{ __('همه موقعیت‌ها') }}</span>
            <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ !request('position') ? 'bg-rose-500 text-white' : 'bg-gray-100 dark:bg-zinc-800 text-gray-500' }}">
                {{ $counts['all'] }}
            </span>
        </a>
        <a href="{{ route('admin.banners.index', ['position' => 'hero']) }}" 
           class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 {{ request('position') === 'hero' ? 'bg-rose-600 text-white shadow-sm' : 'bg-white dark:bg-zinc-900 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-zinc-800 hover:bg-gray-50' }}">
            <span>{{ __('اسلایدر هدر اصلی (Hero)') }}</span>
            <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ request('position') === 'hero' ? 'bg-white/20 text-white' : 'bg-gray-100 dark:bg-zinc-800 text-gray-500' }}">
                {{ $counts['hero'] }}
            </span>
        </a>
        <a href="{{ route('admin.banners.index', ['position' => 'promo_top']) }}" 
           class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 {{ request('position') === 'promo_top' ? 'bg-rose-600 text-white shadow-sm' : 'bg-white dark:bg-zinc-900 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-zinc-800 hover:bg-gray-50' }}">
            <span>{{ __('پوسترهای بالای صفحه (Top Promo)') }}</span>
            <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ request('position') === 'promo_top' ? 'bg-white/20 text-white' : 'bg-gray-100 dark:bg-zinc-800 text-gray-500' }}">
                {{ $counts['promo_top'] }}
            </span>
        </a>
        <a href="{{ route('admin.banners.index', ['position' => 'promo_mid']) }}" 
           class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 {{ request('position') === 'promo_mid' ? 'bg-rose-600 text-white shadow-sm' : 'bg-white dark:bg-zinc-900 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-zinc-800 hover:bg-gray-50' }}">
            <span>{{ __('پوستر عریض جشنواره میانی (Mid Campaign)') }}</span>
            <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ request('position') === 'promo_mid' ? 'bg-white/20 text-white' : 'bg-gray-100 dark:bg-zinc-800 text-gray-500' }}">
                {{ $counts['promo_mid'] }}
            </span>
        </a>
        <a href="{{ route('admin.banners.index', ['position' => 'promo_bottom']) }}" 
           class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 {{ request('position') === 'promo_bottom' ? 'bg-rose-600 text-white shadow-sm' : 'bg-white dark:bg-zinc-900 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-zinc-800 hover:bg-gray-50' }}">
            <span>{{ __('پوسترهای ردیفی پایین (Bottom Promo)') }}</span>
            <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ request('position') === 'promo_bottom' ? 'bg-white/20 text-white' : 'bg-gray-100 dark:bg-zinc-800 text-gray-500' }}">
                {{ $counts['promo_bottom'] }}
            </span>
        </a>
        <a href="{{ route('admin.banners.index', ['position' => 'sidebar']) }}" 
           class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-1.5 {{ request('position') === 'sidebar' ? 'bg-rose-600 text-white shadow-sm' : 'bg-white dark:bg-zinc-900 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-zinc-800 hover:bg-gray-50' }}">
            <span>{{ __('ستون کناری فروشگاه (Sidebar)') }}</span>
            <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ request('position') === 'sidebar' ? 'bg-white/20 text-white' : 'bg-gray-100 dark:bg-zinc-800 text-gray-500' }}">
                {{ $counts['sidebar'] }}
            </span>
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-start text-xs">
                <thead class="bg-gray-50 dark:bg-zinc-800/60 text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-zinc-800 font-bold">
                    <tr>
                        <th class="p-4 text-start">{{ __('پیش‌نمایش تصویر') }}</th>
                        <th class="p-4 text-start">{{ __('عنوان و نشان تبلیغاتی') }}</th>
                        <th class="p-4 text-start">{{ __('موقعیت نمایش') }}</th>
                        <th class="p-4 text-start">{{ __('لینک مقصد') }}</th>
                        <th class="p-4 text-center">{{ __('ترتیب') }}</th>
                        <th class="p-4 text-center">{{ __('وضعیت') }}</th>
                        <th class="p-4 text-end">{{ __('عملیات') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-zinc-800">
                    @forelse($banners as $banner)
                        <tr class="hover:bg-gray-50/60 dark:hover:bg-zinc-800/30 transition-colors">
                            <td class="p-4">
                                <a href="{{ $banner->image_url }}" target="_blank" class="block w-28 h-16 rounded-xl bg-gray-100 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 overflow-hidden group relative shadow-xs">
                                    <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-[10px] font-bold">
                                        {{ __('مشاهده') }}
                                    </div>
                                </a>
                            </td>
                            <td class="p-4 max-w-xs">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span class="font-bold text-gray-900 dark:text-white text-xs leading-snug">{{ $banner->title }}</span>
                                        @if($banner->badge_text)
                                            <span class="px-2 py-0.5 bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-900/60 rounded-md text-[10px] font-bold">
                                                {{ $banner->badge_text }}
                                            </span>
                                        @endif
                                    </div>
                                    @if($banner->subtitle)
                                        <p class="text-[11px] text-gray-500 dark:text-zinc-400 line-clamp-1">{{ $banner->subtitle }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="p-4">
                                @php
                                    $posStyles = [
                                        'hero' => ['title' => __('اسلایدر هدر اصلی'), 'class' => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 border-indigo-200 dark:border-indigo-900/60'],
                                        'promo_top' => ['title' => __('پوستر بالای صفحه'), 'class' => 'bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 border-amber-200 dark:border-amber-900/60'],
                                        'promo_mid' => ['title' => __('پوستر جشنواره میانی'), 'class' => 'bg-purple-50 text-purple-700 dark:bg-purple-950/60 dark:text-purple-300 border-purple-200 dark:border-purple-900/60'],
                                        'promo_bottom' => ['title' => __('پوستر ردیف پایین'), 'class' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border-emerald-200 dark:border-emerald-900/60'],
                                        'sidebar' => ['title' => __('ستون کناری (سایدبار)'), 'class' => 'bg-sky-50 text-sky-700 dark:bg-sky-950/60 dark:text-sky-300 border-sky-200 dark:border-sky-900/60'],
                                    ];
                                    $currentPos = $posStyles[$banner->position] ?? ['title' => $banner->position, 'class' => 'bg-gray-100 text-gray-700 dark:bg-zinc-800 dark:text-zinc-300 border-gray-200'];
                                @endphp
                                <span class="inline-block px-2.5 py-1 rounded-lg border text-[10px] font-bold {{ $currentPos['class'] }}">
                                    {{ $currentPos['title'] }}
                                </span>
                            </td>
                            <td class="p-4 text-gray-500 font-mono text-[11px] max-w-[180px] truncate">
                                @if($banner->link_url)
                                    <a href="{{ $banner->link_url }}" target="_blank" class="text-rose-600 hover:underline flex items-center gap-1 truncate" title="{{ $banner->link_url }}">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        <span class="truncate">{{ $banner->link_url }}</span>
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="p-4 text-center font-mono text-gray-600 dark:text-gray-400 font-bold">
                                {{ $banner->sort_order ?? 0 }}
                            </td>
                            <td class="p-4 text-center">
                                <form action="{{ route('admin.banners.toggle', $banner) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold transition-all shadow-xs {{ $banner->is_active ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-900/50' : 'bg-gray-100 dark:bg-zinc-800 text-gray-500 hover:bg-gray-200' }}" title="{{ __('تغییر وضعیت') }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $banner->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-gray-400' }}"></span>
                                        {{ $banner->is_active ? __('فعال') : __('غیرفعال') }}
                                    </button>
                                </form>
                            </td>
                            <td class="p-4 text-end">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.banners.edit', $banner) }}" class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-zinc-800 rounded-xl transition-colors" title="{{ __('ویرایش') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" onsubmit="return confirm('{{ __('آیا از حذف این پوستر اطمینان دارید؟') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 dark:hover:bg-zinc-800 rounded-xl transition-colors" title="{{ __('حذف') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-12 text-center space-y-3">
                                <div class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-zinc-800 text-gray-400 mx-auto flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <p class="text-xs text-gray-500 font-bold">{{ __('هیچ پوستری در این بخش یافت نشد.') }}</p>
                                <a href="{{ route('admin.banners.create') }}" class="inline-block px-4 py-2 bg-rose-600 text-white rounded-xl text-xs font-bold hover:bg-rose-700 transition-colors">
                                    {{ __('افزودن اولین پوستر') }}
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($banners->hasPages())
            <div class="p-4 border-t border-gray-100 dark:border-zinc-800">
                {{ $banners->links() }}
            </div>
        @endif
    </div>

    <!-- Placement Guide Banner -->
    <div class="bg-gradient-to-r from-zinc-900 to-zinc-950 text-white p-5 rounded-2xl border border-zinc-800 text-xs space-y-3 shadow-lg">
        <div class="flex items-center gap-2 font-bold text-rose-400 text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ __('راهنمای جایگاه‌های پوستر در سایت') }}
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-zinc-300">
            <div class="bg-white/5 p-3 rounded-xl border border-white/10 space-y-1">
                <span class="font-bold text-white block">{{ __('۱. اسلایدر هدر اصلی (Hero)') }}</span>
                <p class="text-[11px] leading-relaxed text-zinc-400">{{ __('نمایش در بالاترین بخش صفحه اصلی بصورت اسلایدر متحرک، با عنوان و دکمه خرید. نسبت مناسب: ۱۶ به ۹ یا عریض‌تر (1600x600).') }}</p>
            </div>
            <div class="bg-white/5 p-3 rounded-xl border border-white/10 space-y-1">
                <span class="font-bold text-white block">{{ __('۲. پوسترهای بالا و میانی (Top & Mid Promo)') }}</span>
                <p class="text-[11px] leading-relaxed text-zinc-400">{{ __('پوسترهای بالای صفحه بصورت ۲ الی ۳ تایی زیر دسته‌ها قرار می‌گیرند. پوستر میانی عریض برای کمپین‌ها و جشنواره‌های تخفیف فوق‌العاده است.') }}</p>
            </div>
            <div class="bg-white/5 p-3 rounded-xl border border-white/10 space-y-1">
                <span class="font-bold text-white block">{{ __('۳. پوسترهای پایین و سایدبار (Bottom & Sidebar)') }}</span>
                <p class="text-[11px] leading-relaxed text-zinc-400">{{ __('پوسترهای ردیف پایین بصورت ۲ الی ۴ تایی قبل از برندها چیده می‌شوند. پوستر سایدبار نیز در ستون فیلترهای فروشگاه نمایش می‌یابد.') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
