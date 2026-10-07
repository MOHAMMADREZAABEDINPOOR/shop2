@extends('layouts.admin')

@section('title', __('افزودن محصول جدید'))

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-900 dark:text-white">{{ __('افزودن محصول جدید') }}</h1>
            <p class="text-xs text-gray-500 mt-1">مشخصات فنی، قیمت‌گذاری و تصاویر کالای جدید را وارد نمایید.</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-zinc-800 text-gray-700 dark:text-gray-300 rounded-xl text-xs font-bold hover:bg-gray-300 transition-colors">
            بازگشت به لیست
        </a>
    </div>

    @if($errors->any())
        <div class="p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/50 rounded-2xl text-rose-700 dark:text-rose-400 text-xs">
            <p class="font-bold mb-1">لطفاً خطاهای زیر را برطرف نمایید:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Details (Left 2 cols) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Info Card -->
                <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm space-y-4">
                    <h3 class="font-bold text-sm text-gray-900 dark:text-white border-b border-gray-100 dark:border-zinc-800 pb-3">اطلاعات عمومی کالا</h3>

                    <div>
                        <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">نام کالا <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 outline-none">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">شناسه اختصاصی (SKU) <span class="text-rose-500">*</span></label>
                            <input type="text" name="sku" value="{{ old('sku', 'SKU-' . strtoupper(Str::random(6))) }}" required
                                   class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 outline-none dir-ltr text-left">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">بارکد (اختیاری)</label>
                            <input type="text" name="barcode" value="{{ old('barcode') }}"
                                   class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 outline-none dir-ltr text-left">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">توضیح کوتاه (خلاصه در کارت محصول)</label>
                        <textarea name="short_description" rows="2"
                                  class="w-full px-4 py-2 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 outline-none">{{ old('short_description') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">توضیحات تکمیلی و نقد و بررسی</label>
                        <textarea name="description" rows="6"
                                  class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 outline-none">{{ old('description') }}</textarea>
                    </div>
                </div>

                <!-- Pricing & Inventory Card -->
                <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm space-y-4"
                     x-data="{
                        basePrice: {{ (int)old('price', 0) }},
                        salePrice: '{{ old('sale_price', '') }}',
                        applyPercent(percent) {
                            if (!this.basePrice || this.basePrice <= 0) return;
                            this.salePrice = Math.round(this.basePrice * (1 - (percent / 100)));
                        },
                        clearDiscount() {
                            this.salePrice = '';
                        }
                     }">
                    <h3 class="font-bold text-sm text-gray-900 dark:text-white border-b border-gray-100 dark:border-zinc-800 pb-3">قیمت‌گذاری و موجودی انبار</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">{{ __('قیمت اصلی (تومان)') }} <span class="text-rose-500">*</span></label>
                            <input type="number" name="price" x-model.number="basePrice" value="{{ old('price') }}" required min="0" step="1000"
                                   class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">قیمت تخفیف‌خورده (تومان)</label>
                            <input type="number" name="sale_price" x-model="salePrice" value="{{ old('sale_price') }}" min="0" step="1000"
                                   class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">{{ __('موجودی انبار') }} <span class="text-rose-500">*</span></label>
                            <input type="number" name="stock" value="{{ old('stock', 10) }}" required min="0"
                                   class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 outline-none">
                        </div>
                    </div>

                    <!-- Quick Discount Selector Helper Buttons -->
                    <div class="p-3 bg-amber-50/60 dark:bg-amber-950/20 border border-amber-200/80 dark:border-amber-900/50 rounded-xl space-y-2">
                        <div class="flex items-center justify-between text-xs font-bold text-amber-900 dark:text-amber-300">
                            <span class="flex items-center gap-1.5">
                                <span>محاسبه‌گر و ثبت سریع درصد تخفیف:</span>
                            </span>
                            <button type="button" @click="clearDiscount()" class="text-rose-600 hover:underline text-[11px]">پاک کردن تخفیف</button>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="p in [5, 10, 15, 20, 25, 30, 40, 50]" :key="p">
                                <button type="button"
                                        @click="applyPercent(p)"
                                        class="px-2.5 py-1 text-xs font-mono font-bold bg-white dark:bg-zinc-800 border border-amber-300 dark:border-amber-800 rounded-lg hover:bg-amber-500 hover:text-white transition-colors">
                                    <span x-text="p + '% تخفیف'"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                        <div>
                            <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">قیمت خرید یا تمام شده (اختیاری)</label>
                            <input type="number" name="cost_price" value="{{ old('cost_price') }}" min="0" step="1000"
                                   class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">وزن بسته به گرم (جهت محاسبه هزینه پست)</label>
                            <input type="number" name="weight" value="{{ old('weight', 300) }}" min="0"
                                   class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 outline-none">
                        </div>
                    </div>
                </div>

                <!-- Media Upload Card -->
                <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm space-y-4">
                    <h3 class="font-bold text-sm text-gray-900 dark:text-white border-b border-gray-100 dark:border-zinc-800 pb-3">{{ __('تصاویر محصول') }}</h3>
                    <p class="text-xs text-gray-500">اولین تصویر به عنوان تصویر شاخص محصول استفاده خواهد شد. (فرمت‌های مجاز: WebP، JPG، PNG)</p>
                    
                    <input type="file" name="images[]" multiple accept="image/*"
                           class="block w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100 dark:file:bg-zinc-800 dark:file:text-rose-400">
                </div>
            </div>

            <!-- Sidebar Controls (Right 1 col) -->
            <div class="space-y-6">
                <!-- Status & Categorization -->
                <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm space-y-4">
                    <h3 class="font-bold text-sm text-gray-900 dark:text-white border-b border-gray-100 dark:border-zinc-800 pb-3">انتشار و سازماندهی</h3>

                    <div>
                        <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">وضعیت کالا</label>
                        <select name="status" class="w-full px-3 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-xs font-semibold outline-none focus:ring-2 focus:ring-rose-500">
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>{{ __('منتشر شده') }}</option>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>{{ __('پیش‌نویس') }}</option>
                            <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>آرشیو</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">{{ __('دسته‌بندی') }} <span class="text-rose-500">*</span></label>
                        <select name="category_id" required class="w-full px-3 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-xs font-semibold outline-none focus:ring-2 focus:ring-rose-500">
                            <option value="">{{ __('انتخاب دسته‌بندی') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">برند تجاری</label>
                        <select name="brand_id" class="w-full px-3 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-xs font-semibold outline-none focus:ring-2 focus:ring-rose-500">
                            <option value="">فاقد برند یا متفرقه</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="pt-4 border-t border-gray-100 dark:border-zinc-800 space-y-3">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="rounded border-gray-300 text-rose-600 focus:ring-rose-500">
                            <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">نمایش به عنوان کالای شگفت‌انگیز</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_best_seller" value="1" {{ old('is_best_seller') ? 'checked' : '' }} class="rounded border-gray-300 text-rose-600 focus:ring-rose-500">
                            <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">کالای پرفروش</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_new_arrival" value="1" {{ old('is_new_arrival', 1) ? 'checked' : '' }} class="rounded border-gray-300 text-rose-600 focus:ring-rose-500">
                            <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">جدیدترین محصولات</span>
                        </label>
                    </div>
                </div>

                <!-- SEO Meta -->
                <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm space-y-4">
                    <h3 class="font-bold text-sm text-gray-900 dark:text-white border-b border-gray-100 dark:border-zinc-800 pb-3">سئو و متاتگ‌ها</h3>
                    
                    <div>
                        <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">عنوان سئو (SEO Title)</label>
                        <input type="text" name="seo_title" value="{{ old('seo_title') }}"
                               class="w-full px-3 py-2 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-xs outline-none focus:ring-2 focus:ring-rose-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">توضیحات سئو (SEO Description)</label>
                        <textarea name="seo_description" rows="3"
                                  class="w-full px-3 py-2 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-xs outline-none focus:ring-2 focus:ring-rose-500">{{ old('seo_description') }}</textarea>
                    </div>
                </div>

                <!-- Action Button -->
                <button type="submit" class="w-full py-3.5 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl font-bold text-sm shadow-lg shadow-rose-600/20 transition-all">
                    ذخیره و ثبت کالا
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
