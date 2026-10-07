@extends('layouts.admin')

@section('title', __('مدیریت سفارش‌ها و فاکتورها'))

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-900 dark:text-white">{{ __('سفارش‌ها و پرداخت‌ها') }}</h1>
            <p class="text-xs text-gray-500 mt-1">{{ __('مدیریت مراحل ارسال، وضعیت پرداخت و رهگیری بسته‌های پستی') }}</p>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl p-4 shadow-sm">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('شماره سفارش، نام مشتری، ایمیل...') }}"
                   class="px-4 py-2 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-xs outline-none focus:ring-2 focus:ring-rose-500">

            <select name="status" class="px-3 py-2 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-xs font-semibold outline-none focus:ring-2 focus:ring-rose-500">
                <option value="">{{ __('همه وضعیت‌های سفارش') }}</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('در انتظار پرداخت') }}</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>{{ __('پرداخت شده') }}</option>
                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>{{ __('در حال آماده‌سازی') }}</option>
                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>{{ __('تحویل به پست') }}</option>
                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>{{ __('تحویل شده') }}</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>{{ __('لغو شده') }}</option>
            </select>

            <select name="payment_status" class="px-3 py-2 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-xs font-semibold outline-none focus:ring-2 focus:ring-rose-500">
                <option value="">{{ __('همه وضعیت‌های پرداخت') }}</option>
                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>{{ __('موفق (پرداخت شده)') }}</option>
                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>{{ __('در انتظار') }}</option>
                <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>{{ __('ناموفق') }}</option>
            </select>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 rounded-xl text-xs font-bold hover:opacity-90 transition-opacity">
                    {{ __('اعمال فیلتر') }}
                </button>
                @if(request()->hasAny(['search', 'status', 'payment_status']))
                    <a href="{{ route('admin.orders.index') }}" class="px-3 py-2 bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-gray-400 rounded-xl text-xs font-bold hover:bg-gray-200">
                        {{ __('حذف') }}
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-start text-xs">
                <thead class="bg-gray-50 dark:bg-zinc-800/50 text-gray-500 border-b border-gray-200 dark:border-zinc-800 font-bold">
                    <tr>
                        <th class="p-4">{{ __('شماره سفارش') }}</th>
                        <th class="p-4">{{ __('مشتری') }}</th>
                        <th class="p-4">{{ __('مبلغ کل (تومان)') }}</th>
                        <th class="p-4">{{ __('وضعیت سفارش') }}</th>
                        <th class="p-4">{{ __('وضعیت پرداخت') }}</th>
                        <th class="p-4">{{ __('تاریخ ثبت') }}</th>
                        <th class="p-4 text-left">{{ __('عملیات') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-zinc-800">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-zinc-800/20 transition-colors">
                            <td class="p-4 font-bold font-mono text-gray-900 dark:text-white">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-rose-600 hover:underline">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-gray-900 dark:text-white">{{ $order->user?->name ?? __('مهمان') }}</div>
                                <div class="text-[10px] text-gray-400 font-mono">{{ $order->user?->email ?? $order->shipping_phone }}</div>
                            </td>
                            <td class="p-4 font-black text-gray-900 dark:text-white">
                                {{ format_price($order->grand_total) }}
                            </td>
                            <td class="p-4">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-amber-50 text-amber-600 dark:bg-amber-950/50',
                                        'paid' => 'bg-blue-50 text-blue-600 dark:bg-blue-950/50',
                                        'processing' => 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50',
                                        'shipped' => 'bg-purple-50 text-purple-600 dark:bg-purple-950/50',
                                        'delivered' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50',
                                        'cancelled' => 'bg-rose-50 text-rose-600 dark:bg-rose-950/50',
                                    ];
                                    $statusTitles = [
                                        'pending' => __('در انتظار پرداخت'),
                                        'paid' => __('پرداخت شده'),
                                        'processing' => __('آماده‌سازی'),
                                        'shipped' => __('تحویل به پست'),
                                        'delivered' => __('تحویل شده'),
                                        'cancelled' => __('لغو شده'),
                                    ];
                                @endphp
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ $statusTitles[$order->status] ?? $order->status }}
                                </span>
                            </td>
                            <td class="p-4">
                                @if($order->payment_status === 'paid')
                                    <span class="px-2 py-0.5 bg-emerald-50 dark:bg-emerald-950 text-emerald-600 rounded-full font-bold text-[10px]">{{ __('موفق') }}</span>
                                @elseif($order->payment_status === 'pending')
                                    <span class="px-2 py-0.5 bg-amber-50 dark:bg-amber-950 text-amber-600 rounded-full font-bold text-[10px]">{{ __('در انتظار') }}</span>
                                @else
                                    <span class="px-2 py-0.5 bg-rose-50 dark:bg-rose-950 text-rose-600 rounded-full font-bold text-[10px]">{{ __('ناموفق') }}</span>
                                @endif
                            </td>
                            <td class="p-4 text-gray-500 font-mono text-[11px] dir-ltr text-start">
                                {{ $order->created_at->format('Y/m/d H:i') }}
                            </td>
                            <td class="p-4 text-left">
                                <a href="{{ route('admin.orders.show', $order) }}" class="px-3 py-1.5 bg-gray-100 dark:bg-zinc-800 hover:bg-gray-200 text-gray-700 dark:text-gray-300 rounded-xl text-xs font-bold transition-colors">{{ __('مشاهده و تغییر وضعیت') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-500 text-xs">{{ __('سفارشی با این مشخصات یافت نشد.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="p-4 border-t border-gray-100 dark:border-zinc-800">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
