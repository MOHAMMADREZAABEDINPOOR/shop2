@extends('layouts.app')

@section('title', __('شرایط و قوانین استفاده | دیجی‌استور'))
@section('meta_description', 'شرایط و قوانین استفاده از فروشگاه اینترنتی دیجی‌استور؛ حقوق و تعهدات خریدار و فروشنده')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-3xl p-8 sm:p-12 shadow-sm space-y-6">
        <div class="flex items-center gap-3">
            <span class="w-3 h-8 bg-rose-600 rounded-full"></span>
            <h1 class="text-2xl font-black text-gray-900 dark:text-gray-100">شرایط و ضوابط خرید از دیجی‌استور</h1>
        </div>

        <div class="prose dark:prose-invert max-w-none text-sm text-gray-600 dark:text-gray-300 leading-loose space-y-4">
            <p>
                استفاده از خدمات دیجی‌استور و ثبت سفارش به منزله پذیرش کامل کلیه قوانین تجارت الکترونیک، قانون حمایت از حقوق مصرف‌کننده و مقررات داخلی این سامانه است.
            </p>
            <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 pt-2">قوانین ثبت سفارش و قیمت‌گذاری:</h3>
            <p>
                تمامی قیمت‌های درج شده در سایت قطعی و معتبر بوده و پس از نهایی شدن سفارش و پرداخت وجه، هیچ‌گونه تغییر قیمتی متوجه خریدار نخواهد بود. در صورت بروز هرگونه خطای سیستمی در محاسبه موجودی، مبلغ پرداختی ظرف کمتر از ۲۴ ساعت مسترد خواهد شد.
            </p>
        </div>
    </div>
</div>
@endsection
