@extends('layouts.app')

@section('title', 'تاریخچه سفارش‌های من | دیجی‌استور')
@section('robots', 'noindex, nofollow')

@section('content')
<div class="max-w-4xl mx-auto px-2.5 sm:px-6 lg:px-8 py-4 sm:py-6 space-y-4 sm:space-y-6">

    <div class="flex items-center gap-2">
        <a href="{{ route('account.dashboard') }}" class="text-xs text-gray-400 hover:text-rose-600">{{ __('داشبورد') }}</a>
        <span class="text-xs text-gray-400">/</span>
        <h1 class="text-lg sm:text-xl font-black text-gray-900 dark:text-gray-100">{{ __('سفارش‌های من') }}</h1>
    </div>

    @if($orders->isNotEmpty())
        <div class="space-y-3 sm:space-y-4">
            @foreach($orders as $order)
                <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl sm:rounded-3xl p-3.5 min-[360px]:p-5 sm:p-6 shadow-sm space-y-3 sm:space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-gray-100 dark:border-zinc-800 gap-2 text-xs">
                        <div class="flex flex-wrap items-center gap-2 sm:gap-4">
                            <span class="font-bold text-gray-900 dark:text-gray-100">{{ __('شماره سفارش:') }} <span class="font-mono">{{ $order->order_number }}</span></span>
                            <span class="text-gray-400 text-[11px] sm:text-xs">{{ $order->created_at->format('Y/m/d H:i') }}</span>
                        </div>
                        <div class="flex items-center gap-1.5 sm:gap-2">
                            <span class="px-2 sm:px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $order->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40' : 'bg-amber-50 text-amber-600 dark:bg-amber-950/40' }}">
                                {{ $order->payment_status_label }}
                            </span>
                            <span class="px-2 sm:px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-gray-300">
                                {{ $order->status_label }}
                            </span>
                        </div>
                    </div>

                    <!-- Items Preview Carousel -->
                    <div class="flex items-center gap-2.5 sm:gap-3 overflow-x-auto py-2 -mx-1 px-1">
                        @foreach($order->items as $item)
                            <div class="flex items-center gap-2 text-xs bg-gray-50 dark:bg-zinc-800 p-2 rounded-xl flex-shrink-0 border border-gray-100 dark:border-zinc-700">
                                @if($item->product && $item->product->primaryImage)
                                    <img src="{{ $item->product->primary_image_url }}" class="w-9 h-9 sm:w-10 sm:h-10 object-cover rounded-lg">
                                @endif
                                <div>
                                    <div class="font-semibold text-gray-800 dark:text-gray-200 max-w-[120px] min-[360px]:max-w-[150px] truncate text-[11px] sm:text-xs">{{ $item->product_name }}</div>
                                    <div class="text-[10px] text-gray-400">{{ $item->quantity }} {{ __('عدد') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-2 pt-3 border-t border-gray-100 dark:border-zinc-800 text-xs">
                        <div class="flex items-baseline gap-1 font-mono">
                            <span class="text-gray-400 font-sans text-xs">{{ __('مبلغ کل:') }}</span>
                            <span class="font-black text-rose-600 dark:text-rose-400 text-xs sm:text-sm">{{ format_price($order->grand_total) }}</span>
                            <span class="text-[10px] text-gray-400 font-sans">{{ __('تومان') }}</span>
                        </div>

                        <a href="{{ route('account.orders.show', $order->id) }}" class="w-full min-[380px]:w-auto text-center bg-gray-100 dark:bg-zinc-800 hover:bg-rose-600 hover:text-white text-gray-700 dark:text-gray-200 font-bold px-3.5 sm:px-4 py-2 rounded-xl text-xs transition-colors">
                            {{ __('مشاهده فاکتور و جزییات') }}
                        </a>
                    </div>
                </div>
            @endforeach

            <div>
                {{ $orders->links() }}
            </div>
        </div>
    @else
        <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-3xl p-16 text-center space-y-4 shadow-sm">
            <div class="w-20 h-20 mx-auto rounded-full bg-rose-50 dark:bg-zinc-800 text-rose-500 flex items-center justify-center">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <h2 class="text-xl font-black text-gray-900 dark:text-gray-100">سفارشی ثبت نشده است</h2>
            <p class="text-sm text-gray-500 max-w-sm mx-auto">شما هنوز هیچ سفارشی در دیجی‌استور ثبت نکرده‌اید.</p>
            <a href="{{ route('shop.index') }}" class="inline-block bg-rose-600 text-white font-bold px-8 py-3 rounded-xl text-sm hover:bg-rose-700 transition-colors shadow-lg">
                رفتن به فروشگاه
            </a>
        </div>
    @endif

</div>
@endsection
