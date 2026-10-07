@extends('layouts.app')

@section('title', __('پرسش‌های متداول | دیجی‌استور'))
@section('meta_description', 'پاسخ پرسش‌های پرتکرار درباره ثبت سفارش، ارسال، گارانتی و بازگشت کالا در دیجی‌استور')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">
    <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-3xl p-8 sm:p-12 shadow-sm space-y-6">
        <div class="flex items-center gap-3">
            <span class="w-3 h-8 bg-rose-600 rounded-full"></span>
            <h1 class="text-2xl font-black text-gray-900 dark:text-gray-100">سوالات متداول کاربران</h1>
        </div>

        <div class="space-y-4 text-xs sm:text-sm">
            <div class="border border-gray-100 dark:border-zinc-800 rounded-2xl p-5 space-y-2">
                <h3 class="font-bold text-gray-900 dark:text-gray-100">چگونه می‌توانم وضعیت سفارش خود را پیگیری کنم؟</h3>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                    پس از ورود به حساب کاربری، با مراجعه به بخش «سفارش‌های من» می‌توانید لحظه‌به‌لحظه وضعیت آماده‌سازی، بسته‌بندی، کد مرسوله و تحویل سفارش خود را مشاهده نمایید.
                </p>
            </div>

            <div class="border border-gray-100 dark:border-zinc-800 rounded-2xl p-5 space-y-2">
                <h3 class="font-bold text-gray-900 dark:text-gray-100">شرایط استفاده از ضمانت بازگشت ۷ روزه کالا چیست؟</h3>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                    تمامی کالاهای خریداری شده در صورت عدم مغایرت، سلامت فیزیکی جعبه و باز نشدن پلمپ اورجینال، تا ۷ روز پس از دریافت قابلیت بازگشت وجه یا تعویض دارند.
                </p>
            </div>

            <div class="border border-gray-100 dark:border-zinc-800 rounded-2xl p-5 space-y-2">
                <h3 class="font-bold text-gray-900 dark:text-gray-100">هزینه ارسال چگونه محاسبه می‌شود؟</h3>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                    برای خریدهای بالاتر از ۱,۰۰۰,۰۰۰ تومان ارسال به سراسر کشور کاملاً رایگان است. برای مبالغ کمتر، هزینه ثابت طبق تعرفه پست پیشتاز محاسبه می‌شود.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
