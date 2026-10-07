@extends('layouts.app')

@section('title', __('رویه و شیوه‌های ارسال سفارش | دیجی‌استور'))
@section('meta_description', 'شیوه‌های ارسال سفارش در دیجی‌استور؛ پست پیشتاز، ارسال اکسپرس، هزینه و زمان تحویل')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-3xl p-8 sm:p-12 shadow-sm space-y-6">
        <div class="flex items-center gap-3">
            <span class="w-3 h-8 bg-rose-600 rounded-full"></span>
            <h1 class="text-2xl font-black text-gray-900 dark:text-gray-100">رویه و روش‌های ارسال کالا</h1>
        </div>

        <div class="prose dark:prose-invert max-w-none text-sm text-gray-600 dark:text-gray-300 leading-loose space-y-4">
            <p>
                دیجی‌استور با همکاری شرکت ملی پست جمهوری اسلامی ایران و ناوگان اختصاصی حمل شهری، امکان ارسال سریع و ایمن سفارش‌ها به کلیه استان‌ها، شهرها و روستاهای کشور را فراهم ساخته است.
            </p>
            <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 pt-2">روش‌های ارسال:</h3>
            <ul class="list-disc list-inside space-y-2 mr-4">
                <li><strong>پست پیشتاز سراسری:</strong> ارسال به تمامی نقاط کشور ظرف مدت ۲ الی ۴ روز کاری.</li>
                <li><strong>ارسال اکسپرس (تهران و کلانشهرها):</strong> تحویل همان روز برای سفارش‌های ثبت شده پیش از ساعت ۱۶:۰۰.</li>
                <li><strong>ارسال رایگان:</strong> برای کلیه سفارش‌های با مبلغ بالای ۱,۰۰۰,۰۰۰ تومان.</li>
            </ul>
        </div>
    </div>
</div>
@endsection
