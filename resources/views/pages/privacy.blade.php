@extends('layouts.app')

@section('title', __('حریم خصوصی | دیجی‌استور'))
@section('meta_description', 'سیاست حفظ حریم خصوصی دیجی‌استور؛ نحوه جمع‌آوری، نگهداری و حفاظت از اطلاعات کاربران')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-3xl p-8 sm:p-12 shadow-sm space-y-6">
        <div class="flex items-center gap-3">
            <span class="w-3 h-8 bg-rose-600 rounded-full"></span>
            <h1 class="text-2xl font-black text-gray-900 dark:text-gray-100">سیاست حریم خصوصی و امنیت داده‌ها</h1>
        </div>

        <div class="prose dark:prose-invert max-w-none text-sm text-gray-600 dark:text-gray-300 leading-loose space-y-4">
            <p>
                دیجی‌استور به حریم خصوصی تمامی کاربرانی که از خدمات وب‌سایت استفاده می‌کنند احترام گذاشته و از اطلاعات خصوصی افراد محافظت می‌کند. ما متعهد می‌شویم که با استفاده از بالاترین استانداردهای امنیتی، پروتکل رمزنگاری HTTPS و هشدارهای امنیتی مدرن از داده‌های شما صیانت نماییم.
            </p>
            <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 pt-2">اطلاعاتی که جمع‌آوری می‌شود:</h3>
            <p>
                تنها اطلاعاتی که جهت پردازش و ارسال سفارش، صدور فاکتور رسمی و ارتباط با مشتری مورد نیاز است (شامل نام، تلفن، آدرس پستی و ایمیل) در سرورهای امن ذخیره می‌گردد. اطلاعات حساس بانکی اعم از شماره کارت یا رمز دوم مستقیماً در درگاه شاپرک مبادله شده و در دیجی‌استور ذخیره نخواهد شد.
            </p>
        </div>
    </div>
</div>
@endsection
