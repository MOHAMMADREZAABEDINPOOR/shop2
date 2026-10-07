@extends('layouts.app')

@section('title', __('ورود به حساب کاربری | دیجی‌استور'))
@section('robots', 'noindex, nofollow')
@section('meta_description', __('ورود امن به حساب کاربری دیجی‌استور برای پیگیری سفارش‌ها و مدیریت خریدها'))

@section('content')
<div class="max-w-md mx-auto px-2.5 sm:px-4 py-6 sm:py-12">
    <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl sm:rounded-3xl p-4 min-[360px]:p-6 sm:p-8 shadow-xl space-y-5 sm:space-y-6">
        
        <div class="text-center space-y-1.5 sm:space-y-2">
            <h1 class="text-xl min-[360px]:text-2xl font-black text-gray-900 dark:text-gray-100">{{ __('ورود به حساب کاربری') }}</h1>
            <p class="text-[11px] min-[360px]:text-xs text-gray-400">{{ __('جهت ادامه و مدیریت سفارش‌های خود وارد شوید') }}</p>
        </div>

        <form id="login-form" action="{{ route('login') }}" method="POST" class="space-y-3.5 sm:space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">{{ __('ایمیل حساب کاربری:') }}</label>
                <input id="login-email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="name@example.com" class="w-full text-xs p-2.5 min-[360px]:p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 focus:ring-rose-500 font-mono text-left" dir="ltr">
            </div>

            <div>
                <div class="flex items-center justify-between mb-1 gap-2 flex-wrap">
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300">{{ __('رمز عبور:') }}</label>
                    <a href="{{ route('password.request') }}" class="text-[11px] text-rose-600 hover:underline">{{ __('فراموشی رمز عبور؟') }}</a>
                </div>
                <input id="login-password" type="password" name="password" required placeholder="••••••••" class="w-full text-xs p-2.5 min-[360px]:p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 focus:ring-rose-500 font-mono text-left" dir="ltr">
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 cursor-pointer text-gray-600 dark:text-gray-400">
                    <input type="checkbox" name="remember" value="1" class="rounded border-gray-300 text-rose-600 focus:ring-rose-500">
                    <span class="text-xs">{{ __('مرا به خاطر بسپار') }}</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-3 sm:py-3.5 rounded-xl text-xs sm:text-sm shadow-lg hover:shadow-rose-600/30 transition-all">
                {{ __('ورود به سایت') }}
            </button>
        </form>

        <div class="pt-4 border-t border-gray-100 dark:border-zinc-800 space-y-3">
            <p class="text-center text-xs font-bold text-gray-500 dark:text-gray-400">{{ __('ورود سریع (دمو)') }}</p>
            <div class="grid grid-cols-1 min-[360px]:grid-cols-2 gap-2">
                <button type="button" onclick="quickLogin('admin@digistore.ir', 'password123')" class="bg-zinc-900 hover:bg-zinc-700 dark:bg-rose-600 dark:hover:bg-rose-700 text-white font-bold py-2 sm:py-2.5 px-2 rounded-xl text-xs shadow transition-all truncate">
                    {{ __('ورود به عنوان مدیر') }}
                </button>
                <button type="button" onclick="quickLogin('customer@digistore.ir', 'password123')" class="bg-sky-600 hover:bg-sky-700 text-white font-bold py-2 sm:py-2.5 px-2 rounded-xl text-xs shadow transition-all truncate">
                    {{ __('ورود به عنوان کاربر') }}
                </button>
            </div>
            <p class="text-center text-[10px] min-[360px]:text-[11px] text-gray-400 font-mono" dir="ltr">admin@digistore.ir / password123</p>
            <p class="text-center text-[10px] min-[360px]:text-[11px] text-gray-400 font-mono" dir="ltr">customer@digistore.ir / password123</p>
        </div>

        <div class="pt-3 sm:pt-4 border-t border-gray-100 dark:border-zinc-800 text-center text-xs text-gray-500">
            {{ __('حساب کاربری ندارید؟') }}
            <a href="{{ route('register') }}" class="text-rose-600 font-bold hover:underline">{{ __('همین حالا ثبت نام کنید') }}</a>
        </div>

    </div>
</div>

<script>
function quickLogin(email, password) {
    document.getElementById('login-email').value = email;
    document.getElementById('login-password').value = password;
    document.getElementById('login-form').submit();
}
</script>
@endsection
