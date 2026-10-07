@extends('layouts.admin')

@section('title', __('تنظیمات کلی فروشگاه'))

@section('content')
<div class="space-y-6 max-w-4xl">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-900 dark:text-white">تنظیمات کلی و پیکربندی</h1>
            <p class="text-xs text-gray-500 mt-1">مشخصات برند، اطلاعات تماس، نرخ مالیات و قوانین ارسال کالا</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900/50 rounded-2xl text-emerald-700 dark:text-emerald-400 text-xs font-bold">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf

        <!-- General Identity -->
        <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm space-y-4">
            <h3 class="font-bold text-sm text-gray-900 dark:text-white border-b border-gray-100 dark:border-zinc-800 pb-3">هویت و نام تجاری فروشگاه</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">نام فروشگاه (Brand Name)</label>
                    <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'دیجی‌استور' }}"
                           class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">شعار یا برچسب معرف</label>
                    <input type="text" name="site_tagline" value="{{ $settings['site_tagline'] ?? 'مرجع تخصصی خرید آنلاین کالاهای اورجینال دیجیتال و الکترونیک' }}"
                           class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">متن کوتاه درباره فروشگاه (در فوتر سایت)</label>
                <textarea name="site_about" rows="3" class="w-full px-4 py-2 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 outline-none">{{ $settings['site_about'] ?? 'دیجی‌استور با سال‌ها تجربه در ارائه کالاهای دیجیتال و گجت‌های هوشمند، ضمانت اصالت و سلامت تمامی محصولات را ارائه می‌نماید.' }}</textarea>
            </div>
        </div>

        <!-- Contact & Support -->
        <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm space-y-4">
            <h3 class="font-bold text-sm text-gray-900 dark:text-white border-b border-gray-100 dark:border-zinc-800 pb-3">اطلاعات تماس و پشتیبانی</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">تلفن پشتیبانی مرکزی</label>
                    <input type="text" name="contact_phone" value="{{ $settings['contact_phone'] ?? '۰۲۱-۸۸۸۸۹۹۹۹' }}"
                           class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 outline-none dir-ltr text-left">
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">ایمیل پشتیبانی مشتریان</label>
                    <input type="email" name="contact_email" value="{{ $settings['contact_email'] ?? 'support@digistore.ir' }}"
                           class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 outline-none dir-ltr text-left">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">نشانی دفتر مرکزی / انبار توزیع</label>
                <input type="text" name="contact_address" value="{{ $settings['contact_address'] ?? 'تهران، خیابان ولیعصر، تقاطع انقلاب، برج تجارت، طبقه ۴' }}"
                       class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 outline-none">
            </div>
        </div>

        <!-- Shipping, Tax & Pricing -->
        <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm space-y-4">
            <h3 class="font-bold text-sm text-gray-900 dark:text-white border-b border-gray-100 dark:border-zinc-800 pb-3">قوانین مالی، ارسال و مالیات</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">هزینه ثابت ارسال پیشتاز (تومان)</label>
                    <input type="number" name="shipping_base_cost" value="{{ $settings['shipping_base_cost'] ?? 49000 }}" min="0" step="1000"
                           class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">حداقل خرید برای ارسال رایگان (تومان)</label>
                    <input type="number" name="free_shipping_threshold" value="{{ $settings['free_shipping_threshold'] ?? 1000000 }}" min="0" step="10000"
                           class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">درصد مالیات بر ارزش افزوده (%)</label>
                    <input type="number" name="tax_rate_percentage" value="{{ $settings['tax_rate_percentage'] ?? 0 }}" min="0" max="100" step="0.1"
                           class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 outline-none">
                </div>
            </div>
        </div>

        <!-- Social Networks -->
        <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm space-y-4">
            <h3 class="font-bold text-sm text-gray-900 dark:text-white border-b border-gray-100 dark:border-zinc-800 pb-3">شبکه‌های اجتماعی</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">اینستاگرام (Instagram URL)</label>
                    <input type="url" name="social_instagram" value="{{ $settings['social_instagram'] ?? 'https://instagram.com' }}"
                           class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 outline-none dir-ltr text-left">
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">کانال تلگرام (Telegram URL)</label>
                    <input type="url" name="social_telegram" value="{{ $settings['social_telegram'] ?? 'https://t.me' }}"
                           class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 outline-none dir-ltr text-left">
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="px-8 py-3.5 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl font-bold text-sm shadow-lg shadow-rose-600/20 transition-all">
            ذخیره و بروزرسانی تمامی تنظیمات
        </button>
    </form>
</div>
@endsection
