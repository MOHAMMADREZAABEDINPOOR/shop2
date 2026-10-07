@extends('layouts.app')

@section('title', __('رویه بازگرداندن کالا | دیجی‌استور'))
@section('meta_description', 'شرایط ۷ روزه بازگشت و تعویض کالا در دیجی‌استور؛ مراحل ثبت درخواست مرجوعی')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-3xl p-8 sm:p-12 shadow-sm space-y-6">
        <div class="flex items-center gap-3">
            <span class="w-3 h-8 bg-rose-600 rounded-full"></span>
            <h1 class="text-2xl font-black text-gray-900 dark:text-gray-100">شرایط و قوانین بازگشت کالا (ضمانت ۷ روزه)</h1>
        </div>

        <div class="prose dark:prose-invert max-w-none text-sm text-gray-600 dark:text-gray-300 leading-loose space-y-4">
            <p>
                آسودگی خاطر و رضایت حداکثری مشتریان همواره اولویت اصلی دیجی‌استور بوده است. در همین راستا کلیه محصولات مشمول فرصت تست و بازگشت ۷ روزه مطابق با ضوابط زیر هستند:
            </p>
            <ul class="list-disc list-inside space-y-2 mr-4">
                <li>کالا نباید مورد استفاده قرار گرفته باشد و پلمپ یا بسته‌بندی اولیه آن مخدوش نشده باشد.</li>
                <li>اقلامی که به دلایل بهداشتی امکان مرجوعی ندارند، در صفحه مشخصات کالا قید شده‌اند.</li>
                <li>هزینه عودت در صورت نقص فنی یا مغایرت بر عهده فروشگاه خواهد بود.</li>
            </ul>
        </div>
    </div>
</div>
@endsection
