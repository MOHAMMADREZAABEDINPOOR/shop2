@extends('layouts.admin')

@section('title', __('افزودن پوستر تبلیغاتی جدید'))
@section('page_title', __('افزودن پوستر تبلیغاتی جدید'))

@section('content')
<div class="space-y-6 max-w-3xl" x-data="{
    imageSource: 'upload',
    previewUrl: '',
    imageUrlInput: '{{ old('image_url') }}',
    handleFileChange(event) {
        const file = event.target.files[0];
        if (file) {
            this.previewUrl = URL.createObjectURL(file);
        }
    }
}">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white flex items-center gap-2">
                <span class="p-2 rounded-xl bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </span>
                {{ __('افزودن پوستر تبلیغاتی جدید') }}
            </h1>
            <p class="text-xs text-gray-500 dark:text-zinc-400 mt-1">{{ __('ایجاد پوستر و اسلایدرهای جذاب برای جایگاه‌های مختلف سایت') }}</p>
        </div>
        <a href="{{ route('admin.banners.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-gray-700 dark:text-gray-300 rounded-xl text-xs font-bold transition-colors">
            {{ __('بازگشت به لیست') }}
        </a>
    </div>

    @if($errors->any())
        <div class="p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/50 rounded-2xl text-rose-700 dark:text-rose-400 text-xs">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl p-5 sm:p-7 shadow-sm space-y-5">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold mb-1.5 text-gray-700 dark:text-gray-300">{{ __('عنوان پوستر') }} <span class="text-rose-500">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required placeholder="{{ __('مثلاً: جشنواره تابستانه محصولات گیمینگ') }}"
                       class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-xs sm:text-sm focus:ring-2 focus:ring-rose-500 outline-none text-gray-900 dark:text-white">
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1.5 text-gray-700 dark:text-gray-300">{{ __('متن نشان / برچسب (Badge)') }}</label>
                <input type="text" name="badge_text" value="{{ old('badge_text') }}" placeholder="{{ __('مثلاً: فروش ویژه، تا ۵۰٪ تخفیف') }}"
                       class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-xs sm:text-sm focus:ring-2 focus:ring-rose-500 outline-none text-gray-900 dark:text-white">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold mb-1.5 text-gray-700 dark:text-gray-300">{{ __('زیرعنوان یا توضیحات کوتاه') }}</label>
            <input type="text" name="subtitle" value="{{ old('subtitle') }}" placeholder="{{ __('مثلاً: جدیدترین لپ‌تاپ‌ها با گارانتی معتبر و ارسال رایگان') }}"
                   class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-xs sm:text-sm focus:ring-2 focus:ring-rose-500 outline-none text-gray-900 dark:text-white">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold mb-1.5 text-gray-700 dark:text-gray-300">{{ __('موقعیت قرارگیری پوستر') }} <span class="text-rose-500">*</span></label>
                <select name="position" required class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-xs font-semibold outline-none focus:ring-2 focus:ring-rose-500 text-gray-900 dark:text-white">
                    <option value="hero" {{ old('position') == 'hero' ? 'selected' : '' }}>{{ __('اسلایدر هدر اصلی (Hero)') }}</option>
                    <option value="promo_top" {{ old('position') == 'promo_top' ? 'selected' : '' }}>{{ __('پوسترهای بالای صفحه (Top Promo)') }}</option>
                    <option value="promo_mid" {{ old('position') == 'promo_mid' ? 'selected' : '' }}>{{ __('پوستر عریض جشنواره میانی (Mid Campaign)') }}</option>
                    <option value="promo_bottom" {{ old('position') == 'promo_bottom' ? 'selected' : '' }}>{{ __('پوسترهای ردیفی پایین (Bottom Promo)') }}</option>
                    <option value="sidebar" {{ old('position') == 'sidebar' ? 'selected' : '' }}>{{ __('ستون کناری فروشگاه (Sidebar)') }}</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1.5 text-gray-700 dark:text-gray-300">{{ __('ترتیب نمایش (اعداد کوچکتر اول)') }}</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                       class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-xs sm:text-sm focus:ring-2 focus:ring-rose-500 outline-none text-gray-900 dark:text-white">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold mb-1.5 text-gray-700 dark:text-gray-300">{{ __('لینک مقصد پوستر (هنگام کلیک کاربر)') }}</label>
            <input type="text" name="link_url" value="{{ old('link_url') }}" placeholder="/shop?category=mobile-phones"
                   class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-xs sm:text-sm focus:ring-2 focus:ring-rose-500 outline-none dir-ltr text-left text-gray-900 dark:text-white">
            <span class="text-[11px] text-gray-400 mt-1 block">{{ __('می‌توانید لینک داخلی مانند /shop یا آدرس اینترنتی کامل وارد نمایید.') }}</span>
        </div>

        <!-- Image Input Section with Toggle for Upload or URL -->
        <div class="space-y-3 pt-2 border-t border-gray-100 dark:border-zinc-800">
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300">
                {{ __('تصویر پوستر') }} <span class="text-rose-500">*</span>
            </label>

            <!-- Toggle Buttons -->
            <div class="inline-flex rounded-xl bg-gray-100 dark:bg-zinc-800 p-1 border border-gray-200 dark:border-zinc-700 text-xs font-bold">
                <button type="button" @click="imageSource = 'upload'" :class="imageSource === 'upload' ? 'bg-white dark:bg-zinc-900 text-rose-600 shadow-xs' : 'text-gray-500 dark:text-gray-400'" class="px-3.5 py-1.5 rounded-lg transition-all">
                    {{ __('آپلود فایل از رایانه') }}
                </button>
                <button type="button" @click="imageSource = 'url'" :class="imageSource === 'url' ? 'bg-white dark:bg-zinc-900 text-rose-600 shadow-xs' : 'text-gray-500 dark:text-gray-400'" class="px-3.5 py-1.5 rounded-lg transition-all">
                    {{ __('درج آدرس اینترنتی (URL)') }}
                </button>
            </div>

            <!-- Upload File Option -->
            <div x-show="imageSource === 'upload'" class="space-y-2">
                <input type="file" name="image" accept="image/*" @change="handleFileChange($event)"
                       class="block w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100 dark:file:bg-zinc-800 dark:file:text-rose-400">
                <span class="text-[11px] text-gray-400 block">{{ __('فرمت‌های مجاز: JPG, PNG, WEBP, SVG تا حداکثر ۵ مگابایت') }}</span>
            </div>

            <!-- Image URL Option -->
            <div x-show="imageSource === 'url'" class="space-y-2" x-cloak>
                <input type="text" name="image_url" x-model="imageUrlInput" placeholder="https://images.unsplash.com/..."
                       class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-xs sm:text-sm focus:ring-2 focus:ring-rose-500 outline-none dir-ltr text-left text-gray-900 dark:text-white">
                <span class="text-[11px] text-gray-400 block">{{ __('می‌توانید لینک مستقیم تصویر (مانند Unsplash، هاست ابری یا CDN) را قرار دهید.') }}</span>
            </div>

            <!-- Live Image Preview -->
            <template x-if="imageSource === 'upload' && previewUrl">
                <div class="mt-3 p-3 bg-gray-50 dark:bg-zinc-800/50 rounded-2xl border border-gray-200 dark:border-zinc-700">
                    <span class="text-[11px] font-bold text-gray-500 block mb-2">{{ __('پیش‌نمایش تصویر آپلود شده:') }}</span>
                    <img :src="previewUrl" class="max-h-48 rounded-xl object-cover shadow-sm">
                </div>
            </template>
            <template x-if="imageSource === 'url' && imageUrlInput">
                <div class="mt-3 p-3 bg-gray-50 dark:bg-zinc-800/50 rounded-2xl border border-gray-200 dark:border-zinc-700">
                    <span class="text-[11px] font-bold text-gray-500 block mb-2">{{ __('پیش‌نمایش آدرس تصویر:') }}</span>
                    <img :src="imageUrlInput" class="max-h-48 rounded-xl object-cover shadow-sm" onerror="this.classList.add('hidden')">
                </div>
            </template>
        </div>

        <div class="pt-3 border-t border-gray-100 dark:border-zinc-800">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }} class="rounded border-gray-300 text-rose-600 focus:ring-rose-500">
                <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">{{ __('پوستر هم‌اکنون فعال و در سایت به نمایش درآید') }}</span>
            </label>
        </div>

        <div class="pt-4 flex items-center gap-3">
            <button type="submit" class="px-7 py-3 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-lg shadow-rose-600/25 hover:shadow-rose-600/40 transition-all">
                {{ __('ثبت و ذخیره پوستر') }}
            </button>
            <a href="{{ route('admin.banners.index') }}" class="px-5 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-gray-700 dark:text-gray-300 rounded-xl font-bold text-xs transition-colors">
                {{ __('انصراف') }}
            </a>
        </div>
    </form>
</div>
@endsection
