@extends('layouts.admin')

@section('title', __('داشبورد مدیریت'))
@section('page_title', __('مرکز آمار و مدیریت فروشگاه'))

@section('content')
<div class="space-y-4 sm:space-y-8">

    <!-- KPI Metric Cards -->
    <div class="grid grid-cols-1 min-[340px]:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
        
        <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl sm:rounded-3xl p-3.5 min-[360px]:p-5 sm:p-6 shadow-sm flex items-center gap-3 sm:gap-4">
            <div class="w-10 sm:w-12 h-10 sm:h-12 rounded-xl sm:rounded-2xl bg-rose-50 dark:bg-zinc-800 text-rose-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 sm:w-6 h-5 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <span class="text-[11px] sm:text-xs text-gray-400 block">{{ __('درآمد کل حاصل از فروش') }}</span>
                <span class="text-base sm:text-lg font-black text-gray-900 dark:text-gray-100 font-mono">{{ format_price($totalRevenue) }} <span class="text-[10px] font-normal text-gray-400 font-sans">{{ __('تومان') }}</span></span>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl sm:rounded-3xl p-3.5 min-[360px]:p-5 sm:p-6 shadow-sm flex items-center gap-3 sm:gap-4">
            <div class="w-10 sm:w-12 h-10 sm:h-12 rounded-xl sm:rounded-2xl bg-indigo-50 dark:bg-zinc-800 text-indigo-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 sm:w-6 h-5 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <div>
                <span class="text-[11px] sm:text-xs text-gray-400 block">{{ __('سفارش‌های ثبت شده') }}</span>
                <span class="text-base sm:text-lg font-black text-gray-900 dark:text-gray-100 font-mono">{{ $totalOrders }} <span class="text-[10px] font-normal text-gray-400 font-sans">{{ __('سفارش') }}</span></span>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl sm:rounded-3xl p-3.5 min-[360px]:p-5 sm:p-6 shadow-sm flex items-center gap-3 sm:gap-4">
            <div class="w-10 sm:w-12 h-10 sm:h-12 rounded-xl sm:rounded-2xl bg-emerald-50 dark:bg-zinc-800 text-emerald-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 sm:w-6 h-5 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <span class="text-[11px] sm:text-xs text-gray-400 block">{{ __('مشتریان ثبت‌شده') }}</span>
                <span class="text-base sm:text-lg font-black text-gray-900 dark:text-gray-100 font-mono">{{ $totalCustomers }} <span class="text-[10px] font-normal text-gray-400 font-sans">{{ __('کاربر') }}</span></span>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl sm:rounded-3xl p-3.5 min-[360px]:p-5 sm:p-6 shadow-sm flex items-center gap-3 sm:gap-4">
            <div class="w-10 sm:w-12 h-10 sm:h-12 rounded-xl sm:rounded-2xl bg-amber-50 dark:bg-zinc-800 text-amber-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 sm:w-6 h-5 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
                <span class="text-[11px] sm:text-xs text-gray-400 block">{{ __('کالاهای رو به اتمام (کمبود موجودی)') }}</span>
                <span class="text-base sm:text-lg font-black text-gray-900 dark:text-gray-100 font-mono">{{ $lowStockCount }} <span class="text-[10px] font-normal text-gray-400 font-sans">{{ __('کالا') }}</span></span>
            </div>
        </div>

    </div>

    <!-- Tables Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-8 items-start">
        
        <!-- Recent Orders (8 Columns) -->
        <div class="lg:col-span-8 bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-zinc-800">
                <h3 class="font-bold text-sm text-gray-900 dark:text-gray-100">{{ __('سفارش‌های اخیر') }}</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-xs text-rose-600 hover:underline">{{ __('مشاهده همه') }}</a>
            </div>

            @if($recentOrders->isNotEmpty())
                <div class="overflow-x-auto -mx-4 px-4 sm:mx-0 sm:px-0">
                    <table class="w-full text-xs rtl:text-start ltr:text-left min-w-[440px]">
                        <thead class="text-gray-400 border-b border-gray-100 dark:border-zinc-800">
                            <tr>
                                <th class="py-2.5">{{ __('شماره') }}</th>
                                <th class="py-2.5">{{ __('مشتری') }}</th>
                                <th class="py-2.5">{{ __('مبلغ کل') }}</th>
                                <th class="py-2.5">{{ __('وضعیت') }}</th>
                                <th class="py-2.5">{{ __('عملیات') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-zinc-800/60">
                            @foreach($recentOrders as $order)
                                <tr>
                                    <td class="py-3 font-mono font-bold">{{ $order->order_number }}</td>
                                    <td class="py-3 font-semibold">{{ $order->user->name }}</td>
                                    <td class="py-3 font-mono font-bold text-rose-600">{{ format_price($order->grand_total) }} {{ __('تومان') }}</td>
                                    <td class="py-3">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 dark:bg-zinc-800">
                                            {{ $order->status_label }}
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-rose-600 font-bold hover:underline">
                                            {{ __('بررسی') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-6 text-xs text-gray-400">{{ __('سفارشی ثبت نشده است.') }}</div>
            @endif
        </div>

        <!-- Top Products (4 Columns) -->
        <div class="lg:col-span-4 bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm space-y-4">
            <h3 class="font-bold text-sm text-gray-900 dark:text-gray-100 pb-3 border-b border-gray-100 dark:border-zinc-800">
                {{ __('پرفروش‌ترین کالاها') }}
            </h3>

            @if($topProducts->isNotEmpty())
                <div class="space-y-3">
                    @foreach($topProducts as $item)
                        <div class="p-2.5 sm:p-3 bg-gray-50 dark:bg-zinc-800/50 rounded-xl sm:rounded-2xl flex items-center justify-between text-xs gap-2">
                            <div class="truncate min-w-0">
                                <div class="font-bold text-gray-800 dark:text-gray-200 truncate">{{ $item->product_name }}</div>
                                <div class="text-[10px] text-gray-400">{{ __('تعداد فروش:') }} <span class="font-mono font-bold text-emerald-600">{{ $item->total_sold }}</span></div>
                            </div>
                            <div class="text-left font-mono font-bold text-rose-600 flex-shrink-0 text-xs">
                                {{ format_price($item->total_revenue) }} <span class="text-[9px] font-normal text-gray-400 font-sans">{{ __('تومان') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6 text-xs text-gray-400">{{ __('داده‌ای موجود نیست.') }}</div>
            @endif
        </div>

    </div>

</div>
@endsection
