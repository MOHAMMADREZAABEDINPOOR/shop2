@extends('layouts.app')

@section('title', __('ثبت‌نام و عضویت | دیجی‌استور'))
@section('robots', 'noindex, nofollow')
@section('meta_description', __('عضویت رایگان در دیجی‌استور؛ خرید سریع‌تر، پیگیری سفارش و پیشنهادهای اختصاصی'))

@section('content')
<div class="max-w-md mx-auto px-2.5 sm:px-4 py-6 sm:py-12">
    <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl sm:rounded-3xl p-4 min-[360px]:p-6 sm:p-8 shadow-xl space-y-5 sm:space-y-6">
        
        <div class="text-center space-y-1.5 sm:space-y-2">
            <h1 class="text-xl min-[360px]:text-2xl font-black text-gray-900 dark:text-gray-100">{{ __('ایجاد حساب کاربری جدید') }}</h1>
            <p class="text-[11px] min-[360px]:text-xs text-gray-400">{{ __('به جمع هزاران مشتری راضی دیجی‌استور بپیوندید') }}</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-3.5 sm:space-y-4">
            @csrf

            {{-- Honeypot anti-bot field: must stay empty --}}
            <div class="absolute -z-10 opacity-0 pointer-events-none" aria-hidden="true">
                <input type="text" name="website" value="" tabindex="-1" autocomplete="off">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">{{ __('نام و نام خانوادگی:') }}</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="{{ __('مثال: علی محمدی') }}" class="w-full text-xs p-2.5 min-[360px]:p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 focus:ring-rose-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">{{ __('پست الکترونیکی (ایمیل):') }}</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="name@example.com" class="w-full text-xs p-2.5 min-[360px]:p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 focus:ring-rose-500 font-mono text-left" dir="ltr">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">{{ __('شماره تلفن همراه (اختیاری):') }}</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="09123456789" class="w-full text-xs p-2.5 min-[360px]:p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 focus:ring-rose-500 font-mono text-left" dir="ltr">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">{{ __('رمز عبور (حداقل ۸ کاراکتر):') }}</label>
                <input type="password" name="password" required placeholder="••••••••" class="w-full text-xs p-2.5 min-[360px]:p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 focus:ring-rose-500 font-mono text-left" dir="ltr">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">{{ __('تکرار رمز عبور:') }}</label>
                <input type="password" name="password_confirmation" required placeholder="••••••••" class="w-full text-xs p-2.5 min-[360px]:p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 focus:ring-rose-500 font-mono text-left" dir="ltr">
            </div>

            <button type="submit" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-3 sm:py-3.5 rounded-xl text-xs sm:text-sm shadow-lg hover:shadow-rose-600/30 transition-all">
                {{ __('ثبت‌نام در سایت') }}
            </button>
        </form>

        <div class="pt-3 sm:pt-4 border-t border-gray-100 dark:border-zinc-800 text-center text-xs text-gray-500">
            {{ __('قبلاً حساب کاربری ساخته‌اید؟') }}
            <a href="{{ route('login') }}" class="text-rose-600 font-bold hover:underline">{{ __('وارد شوید') }}</a>
        </div>

    </div>
</div>
@endsection
