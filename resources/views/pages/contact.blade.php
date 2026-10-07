@extends('layouts.app')

@section('title', __('تماس با ما | دیجی‌استور'))
@section('meta_description', 'راه‌های ارتباط با پشتیبانی دیجی‌استور؛ تلفن، ایمیل و فرم ارسال پیام')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
        
        <!-- Contact Information (5 Columns) -->
        <div class="md:col-span-5 bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-3xl p-8 shadow-sm space-y-6">
            <div class="flex items-center gap-2 pb-4 border-b border-gray-100 dark:border-zinc-800">
                <span class="w-2.5 h-6 bg-rose-600 rounded-full"></span>
                <h2 class="text-lg font-black text-gray-900 dark:text-gray-100">اطلاعات ارتباطی</h2>
            </div>

            <div class="space-y-4 text-xs text-gray-600 dark:text-gray-300">
                <div class="flex items-start gap-3">
                    <span class="p-2 bg-rose-50 dark:bg-zinc-800 text-rose-600 rounded-xl flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                    </span>
                    <div>
                        <span class="font-bold block text-gray-900 dark:text-gray-100 mb-0.5">آدرس دفتر مرکزی:</span>
                        <span>تهران، خیابان ولیعصر، تقاطع میرداماد، مجتمع تجاری پایتخت، طبقه ۵، واحد ۲۰</span>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <span class="p-2 bg-rose-50 dark:bg-zinc-800 text-rose-600 rounded-xl flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    </span>
                    <div>
                        <span class="font-bold block text-gray-900 dark:text-gray-100 mb-0.5">تلفن پشتیبانی مشتریان:</span>
                        <span class="font-mono text-sm font-bold">۰۲۱-۸۸۸۸۹۹۹۹</span>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <span class="p-2 bg-rose-50 dark:bg-zinc-800 text-rose-600 rounded-xl flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </span>
                    <div>
                        <span class="font-bold block text-gray-900 dark:text-gray-100 mb-0.5">پست الکترونیکی:</span>
                        <span class="font-mono">support@digistore.ir</span>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <span class="p-2 bg-rose-50 dark:bg-zinc-800 text-rose-600 rounded-xl flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </span>
                    <div>
                        <span class="font-bold block text-gray-900 dark:text-gray-100 mb-0.5">ساعات پاسخگویی:</span>
                        <span>شنبه تا چهارشنبه: ۸:۰۰ الی ۲۱:۰۰ | پنجشنبه: ۸:۰۰ الی ۱۶:۰۰</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form (7 Columns) -->
        <div class="md:col-span-7 bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-3xl p-8 shadow-sm space-y-6">
            <div class="flex items-center gap-2 pb-4 border-b border-gray-100 dark:border-zinc-800">
                <span class="w-2.5 h-6 bg-rose-600 rounded-full"></span>
                <h2 class="text-lg font-black text-gray-900 dark:text-gray-100">ارسال پیام یا پیشنهاد</h2>
            </div>

            <form action="{{ route('pages.contact.submit') }}" method="POST" class="space-y-4">
                @csrf
                {{-- Honeypot anti-bot field: must stay empty --}}
                <div class="absolute -z-10 opacity-0 pointer-events-none" aria-hidden="true">
                    <input type="text" name="website" value="" tabindex="-1" autocomplete="off">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">نام شما:</label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="{{ __('نام و نام خانوادگی') }}" class="w-full text-xs p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 focus:ring-rose-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">ایمیل معتبر:</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="name@example.com" class="w-full text-xs p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 focus:ring-rose-500 font-mono text-left" dir="ltr">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">شماره تماس (اختیاری):</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="09123456789" class="w-full text-xs p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 focus:ring-rose-500 font-mono text-left" dir="ltr">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">موضوع پیام:</label>
                        <input type="text" name="subject" value="{{ old('subject') }}" required placeholder="{{ __('پیگیری سفارش، انتقاد یا پیشنهاد') }}" class="w-full text-xs p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 focus:ring-rose-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">متن کامل پیام:</label>
                    <textarea name="message" required rows="4" placeholder="{{ __('پیام خود را به طور کامل بنویسید...') }}" class="w-full text-xs p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 focus:ring-rose-500"></textarea>
                </div>

                <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-bold py-3 px-8 rounded-xl text-xs shadow-md transition-all">
                    {{ __('ارسال پیام') }}
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
