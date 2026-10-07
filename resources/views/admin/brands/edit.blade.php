@extends('layouts.admin')

@section('title', 'ویرایش برند: ' . $brand->name)

@section('content')
<div class="space-y-6 max-w-3xl">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-900 dark:text-white">{{ __('ویرایش برند') }}</h1>
            <p class="text-xs text-gray-500 mt-1">شناسه برند: #{{ $brand->id }} | تعداد محصولات مرتبط: {{ $brand->products()->count() }}</p>
        </div>
        <a href="{{ route('admin.brands.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-zinc-800 text-gray-700 dark:text-gray-300 rounded-xl text-xs font-bold hover:bg-gray-300 transition-colors">
            بازگشت به لیست
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

    <form action="{{ route('admin.brands.update', $brand) }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm space-y-5">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">{{ __('نام برند') }} <span class="text-rose-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $brand->name) }}" required
                       class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">اسلاگ یکتا (Slug)</label>
                <input type="text" name="slug" value="{{ old('slug', $brand->slug) }}"
                       class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 outline-none dir-ltr text-left">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">{{ __('لوگوی برند') }}</label>
                @if($brand->logo)
                    <div class="mb-2">
                        <img src="{{ Str::startsWith($brand->logo, 'http') ? $brand->logo : Storage::url($brand->logo) }}" class="w-16 h-16 object-contain rounded-xl border border-gray-200 dark:border-zinc-700 p-1">
                    </div>
                @endif
                <input type="file" name="logo" accept="image/*"
                       class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100 dark:file:bg-zinc-800 dark:file:text-rose-400">
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">آدرس وب‌سایت رسمی</label>
                <input type="url" name="website" value="{{ old('website', $brand->website) }}"
                       class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 outline-none dir-ltr text-left">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">درباره برند / توضیحات</label>
            <textarea name="description" rows="4" class="w-full px-4 py-2 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 outline-none">{{ old('description', $brand->description) }}</textarea>
        </div>

        <div class="pt-3 border-t border-gray-100 dark:border-zinc-800">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $brand->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-rose-600 focus:ring-rose-500">
                <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">برند فعال باشد</span>
            </label>
        </div>

        <div class="pt-4">
            <button type="submit" class="px-6 py-3 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-lg shadow-rose-600/20 transition-all">
                ذخیره تغییرات برند
            </button>
        </div>
    </form>
</div>
@endsection
