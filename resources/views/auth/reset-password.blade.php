@extends('layouts.app')

@section('title', __('تعیین رمز عبور جدید | دیجی‌استور'))
@section('robots', 'noindex, nofollow')

@section('content')
<div class="max-w-md mx-auto px-4 py-12">
    <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-3xl p-8 shadow-xl space-y-6">
        
        <div class="text-center space-y-2">
            <h1 class="text-2xl font-black text-gray-900 dark:text-gray-100">{{ __('تنظیم رمز عبور جدید') }}</h1>
            <p class="text-xs text-gray-400">{{ __('رمز عبور جدید خود را وارد نمایید') }}</p>
        </div>

        <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">{{ __('ایمیل حساب:') }}</label>
                <input type="email" name="email" value="{{ $email ?? old('email') }}" required readonly class="w-full text-xs p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-100 dark:bg-zinc-800 font-mono text-left" dir="ltr">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">{{ __('رمز عبور جدید:') }}</label>
                <input type="password" name="password" required placeholder="••••••••" class="w-full text-xs p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 focus:ring-rose-500 font-mono text-left" dir="ltr">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">{{ __('تکرار رمز عبور جدید:') }}</label>
                <input type="password" name="password_confirmation" required placeholder="••••••••" class="w-full text-xs p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 focus:ring-rose-500 font-mono text-left" dir="ltr">
            </div>

            <button type="submit" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-3.5 rounded-xl text-sm shadow-lg hover:shadow-rose-600/30 transition-all">
                {{ __('ذخیره رمز عبور و ورود') }}
            </button>
        </form>

    </div>
</div>
@endsection
