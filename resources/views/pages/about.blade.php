@extends('layouts.app')

@section('title', __('درباره ما | دیجی‌استور'))
@section('meta_description', 'دیجی‌استور؛ فروشگاه اینترنتی کالای دیجیتال با ضمانت اصالت، ارسال سریع و ۷ روز مهلت بازگشت')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">
    <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-3xl p-8 sm:p-12 shadow-sm space-y-6">
        <div class="flex items-center gap-3">
            <span class="w-3 h-8 bg-rose-600 rounded-full"></span>
            <h1 class="text-2xl font-black text-gray-900 dark:text-gray-100">درباره فروشگاه اینترنتی دیجی‌استور</h1>
        </div>

        <div class="prose dark:prose-invert max-w-none text-sm text-gray-600 dark:text-gray-300 leading-loose space-y-4">
            <p>
                دیجی‌استور با هدف خلق بهترین تجربه خرید آنلاین در ایران تأسیس شده است. ما با اتکا به به‌روزترین فناوری‌های توسعه نرم‌افزار، زیرساخت امن پردازش داده‌ها، لجستیک یکپارچه و رعایت دقیق حقوق مصرف‌کننده، بستری امن و هوشمند را در اختیار مشتریان سراسر کشور قرار داده‌ایم.
            </p>
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 pt-4">ارزش‌های بنیادین ما:</h3>
            <ul class="list-disc list-inside space-y-2 mr-4">
                <li><strong>تضمین اصالت صددرصدی کالاها:</strong> تمامی محصولات از مراجع قانونی و برندهای معتبر تهیه و عرضه می‌شوند.</li>
                <li><strong>سرعت و دقت در تحویل:</strong> سفارش‌های مشتریان با استفاده از مجهزترین سیستم‌های انبارداری و ارسال سریع پردازش می‌شوند.</li>
                <li><strong>پاسخگویی و احترام به حقوق خریدار:</strong> تیم پشتیبانی متخصص در ۷ روز هفته آماده رفع هرگونه دغدغه مشتریان است.</li>
            </ul>
        </div>
    </div>
</div>
@endsection
