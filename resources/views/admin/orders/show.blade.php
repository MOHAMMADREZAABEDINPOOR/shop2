@extends('layouts.admin')

@section('title', __('جزئیات سفارش:') . ' ' . $order->order_number)
@section('page_title', __('سفارش') . ' #' . $order->order_number)

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-black text-gray-900 dark:text-white">{{ __('سفارش') }} #{{ $order->order_number }}</h1>
                @php
                    $statusTitles = [
                        'pending' => __('در انتظار پرداخت'),
                        'paid' => __('پرداخت شده'),
                        'processing' => __('آماده‌سازی'),
                        'shipped' => __('تحویل به پست'),
                        'delivered' => __('تحویل شده'),
                        'cancelled' => __('لغو شده'),
                    ];
                @endphp
                <span class="px-3 py-1 bg-rose-50 dark:bg-zinc-800 text-rose-600 dark:text-rose-400 rounded-full font-bold text-xs">
                    {{ $statusTitles[$order->status] ?? $order->status }}
                </span>
            </div>
            <p class="text-xs text-gray-500 mt-1">{{ __('ثبت شده در:') }} {{ $order->created_at->format('Y/m/d H:i') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-zinc-800 text-gray-700 dark:text-gray-300 rounded-xl text-xs font-bold hover:bg-gray-300 transition-colors">
                {{ __('بازگشت به لیست سفارش‌ها') }}
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900/50 rounded-2xl text-emerald-700 dark:text-emerald-400 text-xs font-bold">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Order Content (Left 2 cols) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Items Card -->
            <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm">
                <h3 class="font-bold text-sm text-gray-900 dark:text-white border-b border-gray-100 dark:border-zinc-800 pb-3 mb-4">{{ __('اقلام سفارش') }}</h3>
                
                <div class="divide-y divide-gray-100 dark:divide-zinc-800">
                    @foreach($order->items as $item)
                        <div class="py-3 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-14 h-14 bg-gray-100 dark:bg-zinc-800 rounded-xl overflow-hidden flex-shrink-0 border border-gray-200 dark:border-zinc-700">
                                    @if($item->product && $item->product->primaryImage)
                                        <img src="{{ Str::startsWith($item->product->primaryImage->image_path, 'http') ? $item->product->primaryImage->image_path : Storage::url($item->product->primaryImage->image_path) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-xs text-gray-400 font-bold">{{ __('کالا') }}</div>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-bold text-xs text-gray-900 dark:text-white">{{ $item->product_name }}</h4>
                                    @if($item->variant_title)
                                        <p class="text-[11px] text-gray-500 mt-0.5">{{ __('تنوع:') }} {{ $item->variant_title }}</p>
                                    @endif
                                    <p class="text-[10px] text-gray-400 font-mono mt-0.5">{{ __('کد:') }} {{ $item->sku }}</p>
                                </div>
                            </div>
                            <div class="text-left rtl:text-right ltr:text-left">
                                <p class="text-xs text-gray-500">{{ $item->quantity }} × {{ format_price($item->unit_price) }} {{ __('تومان') }}</p>
                                <p class="font-bold text-sm text-gray-900 dark:text-white mt-1">{{ format_price($item->total_price) }} {{ __('تومان') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Totals Breakdown -->
                <div class="mt-6 pt-4 border-t border-gray-100 dark:border-zinc-800 space-y-2 text-xs">
                    <div class="flex justify-between text-gray-500">
                        <span>{{ __('مجموع اقلام:') }}</span>
                        <span class="font-semibold">{{ format_price($order->subtotal) }} {{ __('تومان') }}</span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="flex justify-between text-emerald-600">
                            <span>{{ __('تخفیف') }} ({{ __('کد تخفیف') }}: {{ $order->coupon_code }}):</span>
                            <span class="font-semibold">-{{ format_price($order->discount_amount) }} {{ __('تومان') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-gray-500">
                        <span>{{ __('هزینه حمل و نقل:') }}</span>
                        <span class="font-semibold">{{ $order->shipping_cost > 0 ? format_price($order->shipping_cost) . ' ' . __('تومان') : __('رایگان') }}</span>
                    </div>
                    @if($order->tax_amount > 0)
                        <div class="flex justify-between text-gray-500">
                            <span>{{ __('مالیات بر ارزش افزوده:') }}</span>
                            <span class="font-semibold">{{ format_price($order->tax_amount) }} {{ __('تومان') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between font-black text-sm text-gray-900 dark:text-white pt-2 border-t border-gray-100 dark:border-zinc-800">
                        <span>{{ __('مبلغ نهایی پرداختی:') }}</span>
                        <span class="text-rose-600">{{ format_price($order->grand_total) }} {{ __('تومان') }}</span>
                    </div>
                </div>
            </div>

            <!-- Customer & Shipping Address Snapshot -->
            <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm">
                <h3 class="font-bold text-sm text-gray-900 dark:text-white border-b border-gray-100 dark:border-zinc-800 pb-3 mb-4">{{ __('آدرس و مشخصات تحویل‌گیرنده') }}</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-gray-400 block mb-1">{{ __('نام گیرنده:') }}</span>
                        <span class="font-bold text-gray-800 dark:text-gray-200">{{ $order->shipping_name }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block mb-1">{{ __('شماره تماس:') }}</span>
                        <span class="font-mono text-gray-800 dark:text-gray-200 dir-ltr text-right rtl:text-right ltr:text-left">{{ $order->shipping_phone }}</span>
                    </div>
                    <div class="md:col-span-2">
                        <span class="text-gray-400 block mb-1">{{ __('نشانی پستی:') }}</span>
                        <span class="text-gray-800 dark:text-gray-200 font-semibold">{{ $order->shipping_province }}، {{ $order->shipping_city }} - {{ $order->shipping_address }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block mb-1">{{ __('کد پستی:') }}</span>
                        <span class="font-mono text-gray-800 dark:text-gray-200">{{ $order->shipping_postal_code }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block mb-1">{{ __('روش ارسال:') }}</span>
                        <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $order->shipping_method_title ?? __('پست پیشتاز اکسپرس') }}</span>
                    </div>
                </div>

                @if($order->customer_note)
                    <div class="mt-4 pt-3 border-t border-gray-100 dark:border-zinc-800 text-xs">
                        <span class="text-gray-400 block mb-1">{{ __('یادداشت مشتری:') }}</span>
                        <p class="p-3 bg-amber-50 dark:bg-zinc-800/60 rounded-xl text-amber-800 dark:text-amber-200">{{ $order->customer_note }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar Actions & Payment Info (Right 1 col) -->
        <div class="space-y-6">
            <!-- Status Update Form -->
            <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm space-y-4">
                <h3 class="font-bold text-sm text-gray-900 dark:text-white border-b border-gray-100 dark:border-zinc-800 pb-3">{{ __('مدیریت وضعیت سفارش') }}</h3>

                <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">{{ __('وضعیت فعلی سفارش') }}</label>
                        <select name="status" class="w-full px-3 py-2.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-xs font-semibold outline-none focus:ring-2 focus:ring-rose-500">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>{{ __('در انتظار پرداخت') }}</option>
                            <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>{{ __('پرداخت شده') }}</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>{{ __('در حال پردازش و بسته‌بندی') }}</option>
                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>{{ __('تحویل به پست (ارسال شد)') }}</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>{{ __('تحویل مشتری داده شد') }}</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>{{ __('لغو سفارش (برگشت موجودی)') }}</option>
                            <option value="refunded" {{ $order->status === 'refunded' ? 'selected' : '' }}>{{ __('مرجوع و استرداد وجه') }}</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold mb-1 text-gray-700 dark:text-gray-300">{{ __('کد رهگیری پستی (Tracking Code)') }}</label>
                        <input type="text" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}" placeholder="{{ __('مثال: 1234567890123456') }}"
                               class="w-full px-3 py-2 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-xs outline-none focus:ring-2 focus:ring-rose-500 dir-ltr text-left">
                    </div>

                    <button type="submit" class="w-full py-3 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-lg shadow-rose-600/20 transition-all">
                        {{ __('بروزرسانی وضعیت سفارش') }}
                    </button>
                </form>
            </div>

            <!-- Payment & Gateway Details -->
            <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm space-y-3 text-xs">
                <h3 class="font-bold text-sm text-gray-900 dark:text-white border-b border-gray-100 dark:border-zinc-800 pb-3">{{ __('اطلاعات درگاه پرداخت') }}</h3>

                <div class="flex justify-between">
                    <span class="text-gray-500">{{ __('درگاه:') }}</span>
                    <span class="font-bold text-gray-800 dark:text-gray-200">{{ strtoupper($order->payment_method ?? 'Sandbox') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">{{ __('وضعیت پرداخت:') }}</span>
                    <span class="font-bold {{ $order->payment_status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">
                        {{ $order->payment_status === 'paid' ? __('موفق') : $order->payment_status }}
                    </span>
                </div>

                @if($order->payments->isNotEmpty())
                    <div class="pt-2 border-t border-gray-100 dark:border-zinc-800 space-y-2">
                        @foreach($order->payments as $payment)
                            <div class="p-3 bg-gray-50 dark:bg-zinc-800 rounded-xl">
                                <p class="text-[10px] text-gray-400">{{ __('تراکنش') }} #{{ $payment->id }}</p>
                                <p class="font-mono text-xs font-bold text-gray-700 dark:text-gray-300 mt-1">{{ __('کد پیگیری:') }} {{ $payment->reference_id ?? __('ندارد') }}</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">{{ __('شناسه تراکنش درگاه:') }} {{ $payment->transaction_id ?? '-' }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
