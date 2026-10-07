@extends('layouts.admin')

@section('title', __('مدیریت کدهای تخفیف'))

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-900 dark:text-white">کدهای تخفیف</h1>
            <p class="text-xs text-gray-500 mt-1">مدیریت کوپن‌های درصدی و مبلغ ثابت، سقف مصرف و تاریخ انقضا</p>
        </div>
        <a href="{{ route('admin.coupons.create') }}" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-rose-600/20 transition-all">
            + ایجاد کد تخفیف جدید
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900/50 rounded-2xl text-emerald-700 dark:text-emerald-400 text-xs font-bold">
            {{ session('success') }}
        </div>
    @endif
    @if(session('info'))
        <div class="p-4 bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-900/50 rounded-2xl text-blue-700 dark:text-blue-400 text-xs font-bold">
            {{ session('info') }}
        </div>
    @endif

    <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-start text-xs">
                <thead class="bg-gray-50 dark:bg-zinc-800/50 text-gray-500 border-b border-gray-200 dark:border-zinc-800 font-bold">
                    <tr>
                        <th class="p-4">{{ __('کد تخفیف') }}</th>
                        <th class="p-4">نوع</th>
                        <th class="p-4">{{ __('میزان تخفیف') }}</th>
                        <th class="p-4">حداقل خرید</th>
                        <th class="p-4">استفاده / محدودیت</th>
                        <th class="p-4">انقضا</th>
                        <th class="p-4">{{ __('وضعیت') }}</th>
                        <th class="p-4 text-left">{{ __('عملیات') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-zinc-800">
                    @forelse($coupons as $coupon)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-zinc-800/20 transition-colors">
                            <td class="p-4 font-bold font-mono text-sm text-rose-600 dark:text-rose-400">
                                {{ $coupon->code }}
                            </td>
                            <td class="p-4 text-gray-500">
                                {{ $coupon->type === 'percentage' ? 'درصدی' : 'مبلغ ثابت' }}
                            </td>
                            <td class="p-4 font-black text-gray-900 dark:text-white">
                                @if($coupon->type === 'percentage')
                                    {{ $coupon->value }}%
                                    @if($coupon->maximum_discount_amount)
                                        <span class="text-[10px] text-gray-400 block font-normal">(تا سقف {{ format_price($coupon->maximum_discount_amount) }} ت)</span>
                                    @endif
                                @else
                                    {{ format_price($coupon->value) }} تومان
                                @endif
                            </td>
                            <td class="p-4 font-semibold text-gray-600 dark:text-gray-300">
                                {{ $coupon->minimum_order_amount ? format_price($coupon->minimum_order_amount) . ' تومان' : '-' }}
                            </td>
                            <td class="p-4 text-gray-600 dark:text-gray-300">
                                <span class="font-bold">{{ $coupon->times_used ?? 0 }}</span>
                                @if($coupon->usage_limit)
                                    / <span class="text-gray-400">{{ $coupon->usage_limit }}</span>
                                @else
                                    <span class="text-gray-400 text-[10px]"> (نامحدود)</span>
                                @endif
                            </td>
                            <td class="p-4 text-gray-500 font-mono text-[11px]">
                                {{ $coupon->expires_at ? \Carbon\Carbon::parse($coupon->expires_at)->format('Y/m/d') : 'دائمی' }}
                            </td>
                            <td class="p-4">
                                @if($coupon->is_active)
                                    <span class="px-2 py-0.5 bg-emerald-50 dark:bg-emerald-950 text-emerald-600 rounded-full font-bold text-[10px]">{{ __('فعال') }}</span>
                                @else
                                    <span class="px-2 py-0.5 bg-gray-100 dark:bg-zinc-800 text-gray-500 rounded-full font-bold text-[10px]">{{ __('غیرفعال') }}</span>
                                @endif
                            </td>
                            <td class="p-4 text-left">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.coupons.edit', $coupon) }}" class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-zinc-800 rounded-lg transition-colors" title="ویرایش">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('{{ __('آیا از حذف این کوپن اطمینان دارید؟') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 dark:hover:bg-zinc-800 rounded-lg transition-colors" title="حذف">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-gray-500 text-xs">هیچ کد تخفیفی ثبت نشده است.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($coupons->hasPages())
            <div class="p-4 border-t border-gray-100 dark:border-zinc-800">
                {{ $coupons->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
