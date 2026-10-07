@extends('layouts.app')

@section('title', __('فراموشی رمز عبور | دیجی‌استور'))
@section('robots', 'noindex, nofollow')

@section('content')
<div class="max-w-md mx-auto px-2.5 sm:px-4 py-6 sm:py-12">
    <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl sm:rounded-3xl p-4 min-[360px]:p-6 sm:p-8 shadow-xl space-y-5 sm:space-y-6">
        
        <div class="text-center space-y-1.5 sm:space-y-2">
            <h1 class="text-xl min-[360px]:text-2xl font-black text-gray-900 dark:text-gray-100">{{ __('بازیابی رمز عبور') }}</h1>
            <p class="text-[11px] min-[360px]:text-xs text-gray-400">{{ __('ایمیل حساب کاربری خود را وارد نمایید تا لینک بازیابی ارسال شود') }}</p>
        </div>

        @if (session('status'))
            <div class="p-3 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 text-xs rounded-xl font-bold">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST" class="space-y-3.5 sm:space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">{{ __('ایمیل حساب:') }}</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="name@example.com" class="w-full text-xs p-2.5 min-[360px]:p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 focus:ring-rose-500 font-mono text-left" dir="ltr">
            </div>

            <button type="submit" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-3 sm:py-3.5 rounded-xl text-xs sm:text-sm shadow-lg hover:shadow-rose-600/30 transition-all">
                {{ __('ارسال لینک بازیابی') }}
            </button>
        </form>

        <div class="pt-3 sm:pt-4 border-t border-gray-100 dark:border-zinc-800 text-center text-xs text-gray-500">
            <a href="{{ route('login') }}" class="text-rose-600 font-bold hover:underline">{{ __('بازگشت به صفحه ورود') }}</a>
        </div>

    </div>
</div>
@endsection
