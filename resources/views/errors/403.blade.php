@extends('layouts.app')

@section('title', __('۴۰۳ - عدم دسترسی مجاز'))
@section('robots', 'noindex, nofollow')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center py-16 px-4">
    <div class="max-w-md w-full text-center space-y-6">
        <div class="relative inline-block">
            <span class="text-8xl md:text-9xl font-black text-amber-500/10 dark:text-amber-400/10 font-mono select-none">403</span>
            <div class="absolute inset-0 flex items-center justify-center">
                <span class="text-4xl md:text-5xl font-black text-amber-500 font-mono">۴۰۳</span>
            </div>
        </div>

        <div class="space-y-2">
            <h1 class="text-2xl font-black text-gray-900 dark:text-white">دسترسی به این بخش محدود شده است!</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                شما مجوز لازم برای ورود به این بخش یا مشاهده این داده را ندارید. لطفاً با حساب کاربری مجاز وارد شوید.
            </p>
        </div>

        <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('home') }}" class="w-full sm:w-auto px-6 py-3 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl font-bold text-xs shadow-lg shadow-rose-600/20 transition-all">
                صفحه اصلی فروشگاه
            </a>
            <a href="{{ route('login') }}" class="w-full sm:w-auto px-6 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-gray-700 dark:text-gray-200 rounded-2xl font-bold text-xs transition-colors">
                ورود به حساب کاربری
            </a>
        </div>
    </div>
</div>
@endsection
