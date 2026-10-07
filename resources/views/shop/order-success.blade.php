@extends('layouts.app')

@section('title', __('Order Placed Successfully | DigiStore'))
@section('robots', 'noindex, nofollow')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-3xl p-6 sm:p-12 shadow-sm text-center space-y-8">
        
        <!-- Animated Success Icon -->
        <div class="w-20 h-20 mx-auto rounded-full bg-emerald-50 dark:bg-emerald-950/50 text-emerald-500 border-2 border-emerald-500 flex items-center justify-center shadow-xl shadow-emerald-500/10">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
        </div>

        <div class="space-y-2">
            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-gray-100">{{ __('Your order was successfully placed and paid!') }}</h1>
            <p class="text-sm text-gray-500 max-w-lg mx-auto">{{ __('Thank you for your order') }}</p>
        </div>

        <!-- Order Summary Box -->
        <div class="bg-gray-50 dark:bg-zinc-800/50 rounded-2xl p-6 border border-gray-100 dark:border-zinc-800 text-xs text-start space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-4 border-b border-gray-200 dark:border-zinc-700">
                <div>
                    <span class="text-gray-400">{{ __('Order Number') }}:</span>
                    <span class="font-mono font-bold text-gray-900 dark:text-gray-100 text-sm block mt-1">{{ $order->order_number }}</span>
                </div>
                <div>
                    <span class="text-gray-400">{{ __('Bank Transaction Tracking Code:') }}</span>
                    <span class="font-mono font-bold text-emerald-600 dark:text-emerald-400 text-sm block mt-1">{{ $order->latestPayment?->reference_id ?? '---' }}</span>
                </div>
                <div>
                    <span class="text-gray-400">{{ __('Amount Paid:') }}</span>
                    <span class="font-mono font-bold text-gray-900 dark:text-gray-100 text-sm block mt-1">{{ format_price($order->grand_total) }} {{ __('Toman') }}</span>
                </div>
                <div>
                    <span class="text-gray-400">{{ __('Shipping Method') }}:</span>
                    <span class="font-bold text-gray-900 dark:text-gray-100 text-sm block mt-1">{{ $order->shipping_method === 'express' ? __('Same-Day Courier Express') : __('Standard Express Post') }}</span>
                </div>
            </div>

            <!-- Shipping Address Snapshot -->
            <div>
                <span class="text-gray-400 block mb-1">{{ __('Recipient Delivery Address:') }}</span>
                <span class="font-medium text-gray-800 dark:text-gray-200 leading-relaxed">
                    {{ $order->shipping_address_snapshot['full_address'] ?? '---' }} 
                    @if(!empty($order->shipping_address_snapshot['recipient_name']))
                        ({{ __('Recipient: :name', ['name' => $order->shipping_address_snapshot['recipient_name']]) }} - {{ __('Phone') }}: {{ fa_num($order->shipping_address_snapshot['recipient_phone'] ?? '') }})
                    @endif
                </span>
            </div>
        </div>

        <!-- Action Links -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('account.orders.show', $order->id) }}" class="w-full sm:w-auto bg-rose-600 hover:bg-rose-700 text-white font-bold px-8 py-3.5 rounded-xl text-sm shadow-lg hover:shadow-rose-600/30 transition-all">
                {{ __('View Invoice & Track Order') }}
            </a>
            <a href="{{ route('home') }}" class="w-full sm:w-auto bg-gray-100 dark:bg-zinc-800 hover:bg-gray-200 dark:hover:bg-zinc-700 text-gray-700 dark:text-gray-200 font-bold px-8 py-3.5 rounded-xl text-sm transition-colors">
                {{ __('Back to Home') }}
            </a>
        </div>

    </div>

</div>
@endsection
