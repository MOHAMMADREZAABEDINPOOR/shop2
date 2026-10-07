@extends('layouts.admin')

@section('title', __('ایجاد کد تخفیف جدید'))

@section('content')
<div class="space-y-6 max-w-3xl">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-900 dark:text-white">ایجاد کد تخفیف</h1>
            <p class="text-xs text-gray-500 mt-1">تعریف کمپین تخفیفاتی با محدودیت تعداد و سقف خرید</p>
        </div>
        <a href="{{ route('admin.coupons.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-zinc-800 text-gray-700 dark:text-gray-300 rounded-xl text-xs font-bold hover:bg-gray-300 transition-colors">
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

    <form action="{{ route('admin.coupons.store') }}" method="POST" class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm space-y-5">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">کد تخفیف (Coupon Code) <span class="text-rose-500">*</span></label>
                <input type="text" name="code" value="{{ old('code', strtoupper(Str::random(8))) }}" required
                       class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-sm font-mono focus:ring-2 focus:ring-rose-500 outline-none dir-ltr text-left">
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">نوع محاسبه تخفیف <span class="text-rose-500">*</span></label>
                <select name="type" class="w-full px-3 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-xs font-semibold outline-none focus:ring-2 focus:ring-rose-500">
                    <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>درصدی (%)</option>
                    <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>مبلغ ثابت (تومان)</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">مقدار تخفیف (درصد یا تومان) <span class="text-rose-500">*</span></label>
                <input type="number" name="value" value="{{ old('value') }}" required min="0" step="any"
                       class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">سقف تخفیف (تومان - مخصوص تخفیف درصدی)</label>
                <input type="number" name="maximum_discount_amount" value="{{ old('maximum_discount_amount') }}" min="0" step="1000"
                       class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 outline-none">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">حداقل مبلغ سفارش (تومان)</label>
                <input type="number" name="minimum_order_amount" value="{{ old('minimum_order_amount') }}" min="0" step="1000"
                       class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">کل دفعات مجاز مصرف</label>
                <input type="number" name="usage_limit" value="{{ old('usage_limit') }}" min="1" placeholder="{{ __('نامحدود') }}"
                       class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">تعداد مجاز برای هر کاربر <span class="text-rose-500">*</span></label>
                <input type="number" name="per_user_limit" value="{{ old('per_user_limit', 1) }}" required min="1"
                       class="w-full px-4 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 outline-none">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">تاریخ شروع اعتبار</label>
                <input type="date" name="starts_at" value="{{ old('starts_at') }}"
                       class="w-full px-4 py-2 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-xs focus:ring-2 focus:ring-rose-500 outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">{{ __('تاریخ انقضا') }}</label>
                <input type="date" name="expires_at" value="{{ old('expires_at') }}"
                       class="w-full px-4 py-2 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-xs focus:ring-2 focus:ring-rose-500 outline-none">
            </div>
        </div>

        <div class="pt-3 border-t border-gray-100 dark:border-zinc-800">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }} class="rounded border-gray-300 text-rose-600 focus:ring-rose-500">
                <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">کد تخفیف هم‌اکنون فعال و قابل اعمال باشد</span>
            </label>
        </div>

        <div class="pt-4">
            <button type="submit" class="px-6 py-3 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-lg shadow-rose-600/20 transition-all">
                ایجاد و فعال‌سازی کوپن
            </button>
        </div>
    </form>
</div>
@endsection
