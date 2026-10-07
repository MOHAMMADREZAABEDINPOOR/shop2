@extends('layouts.app')

@section('title', __('سیاست کوکی‌ها | دیجی‌استور'))
@section('meta_description', 'سیاست استفاده از کوکی‌ها در دیجی‌استور؛ انواع کوکی‌ها و نحوه مدیریت رضایت شما')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-3xl p-8 sm:p-12 shadow-sm space-y-6">
        <div class="flex items-center gap-3">
            <span class="w-3 h-8 bg-rose-600 rounded-full"></span>
            <h1 class="text-2xl font-black text-gray-900 dark:text-gray-100">سیاست استفاده از کوکی‌ها</h1>
        </div>

        <div class="prose dark:prose-invert max-w-none text-sm text-gray-600 dark:text-gray-300 leading-loose space-y-4">
            <p>
                ما برای بهبود عملکرد وب‌سایت، ذخیره سبد خرید شما در حالت مهمان، به خاطر سپردن تم شب/روز و ارائه پیشنهادهای مرتبط از فایل‌های کوکی ایمن و استاندارد استفاده می‌کنیم.
            </p>
            <p>
                شما می‌توانید در هر زمان از طریق تنظیمات مرورگر خود ذخیره کوکی‌ها را مدیریت یا مسدود فرمایید. با این حال برخی عملکردهای سایت از جمله ورود به حساب کاربری نیازمند فعال بودن سشن‌های ایمن هستند.
            </p>
        </div>
    </div>
</div>
@endsection
