@extends('layouts.app')

@section('title', 'جزییات سفارش ' . $order->order_number . ' | دیجی‌استور')
@section('robots', 'noindex, nofollow')

@section('content')
<div class="max-w-4xl mx-auto px-2.5 sm:px-6 lg:px-8 py-4 sm:py-6 space-y-4 sm:space-y-8">

    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <a href="{{ route('account.orders') }}" class="text-xs text-gray-400 hover:text-rose-600">{{ __('سفارش‌ها') }}</a>
            <span class="text-xs text-gray-400">/</span>
            <h1 class="text-lg sm:text-xl font-black text-gray-900 dark:text-gray-100 font-mono">{{ __('سفارش') }} {{ $order->order_number }}</h1>
        </div>

        @if($order->canBeCancelled())
            <form action="{{ route('account.orders.cancel', $order->id) }}" method="POST">
                @csrf
                <button type="submit" onclick="return confirm('{{ __('آیا از لغو این سفارش اطمینان دارید؟ موجودی رزرو شده به انبار بازخواهد گشت.') }}')" class="bg-rose-50 dark:bg-rose-950/40 text-rose-600 border border-rose-200 dark:border-rose-900 text-xs font-bold px-4 py-2 rounded-xl hover:bg-rose-600 hover:text-white transition-colors">
                    {{ __('لغو سفارش') }}
                </button>
            </form>
        @endif
    </div>

    <!-- Order Meta Card -->
    <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl sm:rounded-3xl p-3.5 min-[360px]:p-5 sm:p-8 shadow-sm space-y-4 sm:space-y-6">
        
        <div class="grid grid-cols-1 min-[340px]:grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-6 text-xs pb-4 sm:pb-6 border-b border-gray-100 dark:border-zinc-800">
            <div>
                <span class="text-gray-400 block mb-1 text-[11px] sm:text-xs">{{ __('تاریخ ثبت:') }}</span>
                <span class="font-bold text-gray-900 dark:text-gray-100 font-mono">{{ $order->created_at->format('Y/m/d H:i') }}</span>
            </div>
            <div>
                <span class="text-gray-400 block mb-1 text-[11px] sm:text-xs">{{ __('وضعیت پرداخت:') }}</span>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $order->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                    {{ $order->payment_status_label }}
                </span>
            </div>
            <div>
                <span class="text-gray-400 block mb-1 text-[11px] sm:text-xs">{{ __('وضعیت سفارش:') }}</span>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-gray-300">
                    {{ $order->status_label }}
                </span>
            </div>
            <div>
                <span class="text-gray-400 block mb-1 text-[11px] sm:text-xs">{{ __('کد پیگیری مرسوله:') }}</span>
                <span class="font-mono font-bold text-gray-900 dark:text-gray-100 truncate block">{{ $order->tracking_number ?? __('در انتظار صدور بارنامه') }}</span>
            </div>
        </div>

        <!-- Shipping Address Snapshot -->
        <div class="text-xs space-y-1.5 pb-4 sm:pb-6 border-b border-gray-100 dark:border-zinc-800">
            <h4 class="font-bold text-xs sm:text-sm text-gray-900 dark:text-gray-100 mb-2">{{ __('نشانی تحویل گیرنده (ثبت شده در زمان سفارش):') }}</h4>
            <div class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $order->shipping_address_snapshot['full_address'] ?? '---' }}</div>
            <div class="text-gray-400 text-[11px] sm:text-xs">{{ __('گیرنده:') }} {{ $order->shipping_address_snapshot['recipient_name'] ?? '' }} | {{ __('تلفن:') }} {{ $order->shipping_address_snapshot['recipient_phone'] ?? '' }}</div>
        </div>

        <!-- Order Items Table -->
        <div class="space-y-4">
            <h4 class="font-bold text-xs sm:text-sm text-gray-900 dark:text-gray-100">{{ __('اقلام خریداری شده') }}</h4>
            
            <div class="overflow-x-auto -mx-3.5 px-3.5 sm:mx-0 sm:px-0">
                <table class="w-full text-xs text-right min-w-[480px]">
                    <thead class="text-gray-400 border-b border-gray-100 dark:border-zinc-800">
                        <tr>
                            <th class="py-2.5">{{ __('نام محصول') }}</th>
                            <th class="py-2.5">{{ __('کد کالا (SKU)') }}</th>
                            <th class="py-2.5">{{ __('قیمت واحد') }}</th>
                            <th class="py-2.5">{{ __('تعداد') }}</th>
                            <th class="py-2.5 text-left">{{ __('مجموع قیمت') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-zinc-800/60">
                        @foreach($order->items as $item)
                            <tr>
                                <td class="py-3.5">
                                    <div class="font-bold text-gray-800 dark:text-gray-200">{{ $item->product_name }}</div>
                                    @if($item->variant_snapshot)
                                        <div class="text-[10px] text-gray-400 mt-0.5">{{ __('مدل:') }} {{ $item->variant_snapshot['label'] ?? '' }}</div>
                                    @endif
                                </td>
                                <td class="py-3.5 font-mono text-gray-400">{{ $item->sku }}</td>
                                <td class="py-3.5 font-mono">{{ format_price($item->unit_price) }} {{ __('تومان') }}</td>
                                <td class="py-3.5 font-mono font-bold">{{ $item->quantity }}</td>
                                <td class="py-3.5 text-left font-mono font-bold text-rose-600">{{ format_price($item->total_price) }} {{ __('تومان') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Financial Breakdown -->
        <div class="pt-6 border-t border-gray-100 dark:border-zinc-800 max-w-sm mr-auto space-y-2.5 text-xs text-gray-600 dark:text-gray-400">
            <div class="flex justify-between">
                <span>{{ __('جمع قیمت اقلام:') }}</span>
                <span class="font-mono font-bold text-gray-900 dark:text-gray-100">{{ format_price($order->subtotal) }} {{ __('تومان') }}</span>
            </div>

            @if($order->discount_amount > 0)
                <div class="flex justify-between text-rose-600 font-bold">
                    <span>{{ __('تخفیف اعمال شده:') }}</span>
                    <span class="font-mono">{{ format_price($order->discount_amount) }}- {{ __('تومان') }}</span>
                </div>
            @endif

            @if($order->tax_amount > 0)
                <div class="flex justify-between">
                    <span>{{ __('مالیات بر ارزش افزوده:') }}</span>
                    <span class="font-mono">{{ format_price($order->tax_amount) }} {{ __('تومان') }}</span>
                </div>
            @endif

            <div class="flex justify-between">
                <span>{{ __('هزینه حمل و نقل:') }}</span>
                <span class="font-mono font-bold">{{ $order->shipping_cost > 0 ? format_price($order->shipping_cost) . ' ' . __('تومان') : __('رایگان') }}</span>
            </div>

            <div class="pt-3 border-t border-gray-100 dark:border-zinc-800 flex justify-between items-baseline">
                <span class="font-black text-xs sm:text-sm text-gray-900 dark:text-gray-100">{{ __('مبلغ کل پرداخت شده:') }}</span>
                <div class="text-left font-mono">
                    <span class="text-base sm:text-lg font-black text-rose-600 dark:text-rose-400">{{ format_price($order->grand_total) }}</span>
                    <span class="text-[10px] text-gray-400 font-sans">{{ __('تومان') }}</span>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
