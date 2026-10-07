@extends('layouts.app')

@section('title', 'ویرایش مشخصات کاربری | دیجی‌استور')
@section('robots', 'noindex, nofollow')

@section('content')
<div class="max-w-4xl mx-auto px-2.5 sm:px-6 lg:px-8 py-4 sm:py-6 space-y-4 sm:space-y-8">

    <div class="flex items-center gap-2">
        <a href="{{ route('account.dashboard') }}" class="text-xs text-gray-400 hover:text-rose-600">{{ __('داشبورد') }}</a>
        <span class="text-xs text-gray-400">/</span>
        <h1 class="text-lg sm:text-xl font-black text-gray-900 dark:text-gray-100">{{ __('اطلاعات حساب کاربری') }}</h1>
    </div>

    <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl sm:rounded-3xl p-4 sm:p-8 shadow-sm space-y-6 sm:space-y-8">
        
        <!-- Profile Form -->
        <form action="{{ route('account.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4 sm:space-y-6">
            @csrf
            @method('PUT')

            <h2 class="font-bold text-sm sm:text-base text-gray-900 dark:text-gray-100 pb-3 border-b border-gray-100 dark:border-zinc-800">
                {{ __('اطلاعات هویتی و ارتباطی') }}
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">{{ __('نام و نام خانوادگی:') }}</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full text-xs p-2.5 min-[360px]:p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 focus:ring-rose-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">{{ __('پست الکترونیکی (ایمیل):') }}</label>
                    <input type="email" value="{{ $user->email }}" disabled class="w-full text-xs p-2.5 min-[360px]:p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-100 dark:bg-zinc-800/50 font-mono text-gray-500 cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">{{ __('شماره موبایل:') }}</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="09123456789" class="w-full text-xs p-2.5 min-[360px]:p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 focus:ring-rose-500 font-mono text-left" dir="ltr">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">{{ __('کد ملی (۱۰ رقم):') }}</label>
                    <input type="text" name="national_code" value="{{ old('national_code', $user->profile?->national_code) }}" placeholder="1234567890" maxlength="10" class="w-full text-xs p-2.5 min-[360px]:p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 focus:ring-rose-500 font-mono text-left" dir="ltr">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">{{ __('تصویر نمایه (آواتار):') }}</label>
                <input type="file" name="avatar" accept="image/*" class="w-full text-xs p-2 sm:p-2.5 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 focus:ring-rose-500">
            </div>

            <button type="submit" class="w-full min-[360px]:w-auto bg-rose-600 hover:bg-rose-700 text-white font-bold py-2.5 sm:py-3 px-6 sm:px-8 rounded-xl text-xs shadow-md transition-all">
                {{ __('بروزرسانی مشخصات') }}
            </button>
        </form>

        <!-- Password Change Form -->
        <form action="{{ route('account.password.update') }}" method="POST" class="space-y-4 sm:space-y-6 pt-6 sm:pt-8 border-t border-gray-100 dark:border-zinc-800">
            @csrf
            @method('PUT')

            <h2 class="font-bold text-sm sm:text-base text-gray-900 dark:text-gray-100 pb-3 border-b border-gray-100 dark:border-zinc-800">
                {{ __('تغییر کلمه عبور') }}
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">{{ __('رمز عبور فعلی:') }}</label>
                    <input type="password" name="current_password" required placeholder="••••••••" class="w-full text-xs p-2.5 min-[360px]:p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 focus:ring-rose-500 font-mono text-left" dir="ltr">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">{{ __('رمز عبور جدید:') }}</label>
                    <input type="password" name="password" required placeholder="••••••••" class="w-full text-xs p-2.5 min-[360px]:p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 focus:ring-rose-500 font-mono text-left" dir="ltr">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">{{ __('تکرار رمز عبور جدید:') }}</label>
                    <input type="password" name="password_confirmation" required placeholder="••••••••" class="w-full text-xs p-2.5 min-[360px]:p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 focus:ring-rose-500 font-mono text-left" dir="ltr">
                </div>
            </div>

            <button type="submit" class="w-full min-[360px]:w-auto bg-gray-900 dark:bg-white text-white dark:text-zinc-900 hover:bg-rose-600 dark:hover:bg-rose-600 dark:hover:text-white font-bold py-2.5 sm:py-3 px-6 sm:px-8 rounded-xl text-xs shadow-md transition-all">
                {{ __('تغییر رمز عبور') }}
            </button>
        </form>

    </div>

</div>
@endsection
