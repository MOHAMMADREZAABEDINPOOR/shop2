@extends('layouts.app')

@section('title', __('Shopping Cart | DigiStore'))
@section('robots', 'noindex, nofollow')
@section('meta_description', __('Your shopping cart at DigiStore; review items, apply coupon and proceed to secure checkout'))

@section('content')
<div class="max-w-7xl mx-auto px-2.5 sm:px-6 lg:px-8 py-4 sm:py-6">

    <div class="flex items-center gap-2 mb-6">
        <span class="w-2.5 h-6 bg-rose-600 rounded-full"></span>
        <h1 class="text-lg sm:text-xl font-black text-gray-900 dark:text-gray-100">{{ __('Your Shopping Cart') }}</h1>
        <span class="text-xs text-gray-400">({{ fa_num($cart->items_count) }} {{ __('Items') }})</span>
    </div>

    @if($cart->items->isNotEmpty())
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
            
            <!-- Cart Items List (8 Columns) -->
            <div class="lg:col-span-8 space-y-4">
                @foreach($cart->items as $item)
                    <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl p-3 min-[360px]:p-4 sm:p-6 shadow-sm flex flex-col sm:flex-row items-center gap-4 sm:gap-6">
                        
                        <!-- Product Thumbnail -->
                        <a href="{{ route('product.show', $item->product->slug) }}" class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl overflow-hidden bg-gray-50 dark:bg-zinc-800 flex-shrink-0 border border-gray-100 dark:border-zinc-700">
                            <img src="{{ $item->product->primary_image_url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                        </a>

                        <!-- Item Info -->
                        <div class="flex-1 space-y-2 text-center sm:text-start w-full sm:w-auto">
                            <a href="{{ route('product.show', $item->product->slug) }}" class="font-bold text-xs sm:text-sm text-gray-900 dark:text-gray-100 hover:text-rose-600 transition-colors line-clamp-2">
                                {{ $item->product->name }}
                            </a>

                            @if($item->variant)
                                <div class="text-xs text-gray-400">
                                    {{ __('Model:') }} <span class="text-gray-600 dark:text-gray-300 font-semibold">{{ $item->variant->variant_label }}</span>
                                </div>
                            @endif

                            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2.5 sm:gap-4 text-xs">
                                <div>
                                    {{ __('Unit price:') }}
                                    <span class="font-mono font-bold text-gray-800 dark:text-gray-200">{{ format_price($item->effective_unit_price) }}</span> {{ __('Toman') }}
                                </div>
                                @if($item->product->has_discount)
                                    <span class="bg-rose-50 dark:bg-rose-950/40 text-rose-600 font-bold px-2 py-0.5 rounded-md text-[10px]">
                                        {{ fa_num($item->product->discount_percent) }}٪ {{ __('Off') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Quantity Controls & Remove -->
                        <div class="flex flex-wrap sm:flex-col items-center justify-between sm:items-end gap-3 sm:gap-4 w-full sm:w-auto pt-2 sm:pt-0 border-t sm:border-t-0 border-gray-100 dark:border-zinc-800">
                            
                            <!-- Quantity Form -->
                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center border border-gray-200 dark:border-zinc-700 rounded-xl bg-gray-50 dark:bg-zinc-800 overflow-hidden shadow-sm">
                                @csrf
                                <button type="submit" name="quantity" value="{{ $item->quantity - 1 }}" class="px-3 py-1 text-gray-500 hover:bg-gray-200 dark:hover:bg-zinc-700 font-bold">-</button>
                                <span class="w-10 text-center text-xs font-bold font-mono">{{ fa_num($item->quantity) }}</span>
                                <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}" class="px-3 py-1 text-gray-500 hover:bg-gray-200 dark:hover:bg-zinc-700 font-bold">+</button>
                            </form>

                            <!-- Line Total Price -->
                            <div class="text-end font-mono font-black text-sm text-gray-900 dark:text-gray-100">
                                {{ format_price($item->total_price) }} <span class="text-[10px] font-normal text-gray-400 font-sans">{{ __('Toman') }}</span>
                            </div>

                            <!-- Remove Item -->
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-rose-500 hover:text-rose-700 flex items-center gap-1 font-semibold" title="{{ __('Delete from cart') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    {{ __('Remove') }}
                                </button>
                            </form>

                        </div>

                    </div>
                @endforeach

                <div class="flex justify-between items-center pt-2">
                    <a href="{{ route('shop.index') }}" class="text-xs font-bold text-rose-600 hover:underline flex items-center gap-1">
                        <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        {{ __('Continue shopping & add items') }}
                    </a>

                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs text-gray-400 hover:text-rose-600 transition-colors">
                            {{ __('Clear cart') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Order Summary & Checkout Box (4 Columns) -->
            <div class="lg:col-span-4 space-y-4 sticky top-24">
                
                <!-- Coupon Box -->
                <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl p-5 shadow-sm space-y-3">
                    <span class="text-xs font-bold text-gray-800 dark:text-gray-200 block text-start">{{ __('Have a discount coupon?') }}</span>
                    
                    @if($cart->coupon)
                        <div class="flex items-center justify-between p-3 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 rounded-xl text-xs">
                            <div class="text-start">
                                <span class="font-bold text-emerald-700 dark:text-emerald-300 font-mono">{{ $cart->coupon->code }}</span>
                                <span class="text-emerald-600 text-[11px] block">{{ __('Coupon applied') }}</span>
                            </div>
                            <form action="{{ route('cart.coupon.remove') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-500 font-bold text-xs hover:underline">{{ __('Remove') }}</button>
                            </form>
                        </div>
                    @else
                        <form action="{{ route('cart.coupon.apply') }}" method="POST" class="flex gap-2">
                            @csrf
                            <input type="text" name="coupon_code" required placeholder="{{ __('Enter discount code') }}" class="flex-1 text-xs uppercase font-mono p-2.5 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 focus:ring-rose-500 text-start">
                            <button type="submit" class="bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 font-bold px-4 rounded-xl text-xs hover:opacity-90 transition-opacity">{{ __('Apply') }}</button>
                        </form>
                    @endif
                </div>

                <!-- Price Breakdown Box -->
                <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl p-6 shadow-sm space-y-4">
                    <h3 class="font-bold text-sm text-gray-900 dark:text-gray-100 pb-3 border-b border-gray-100 dark:border-zinc-800 text-start">
                        {{ __('Order Invoice Summary') }}
                    </h3>

                    <div class="space-y-3 text-xs text-gray-600 dark:text-gray-400">
                        <div class="flex justify-between items-center">
                            <span>{{ __('Items Price (:count items):', ['count' => fa_num($cart->items_count)]) }}</span>
                            <span class="font-mono font-bold text-gray-900 dark:text-gray-100">{{ format_price($summary['subtotal']) }} {{ __('Toman') }}</span>
                        </div>

                        @if($summary['discount'] > 0)
                            <div class="flex justify-between items-center text-rose-600 dark:text-rose-400 font-semibold">
                                <span>{{ __('Your savings / discount:') }}</span>
                                <span class="font-mono font-bold">{{ format_price($summary['discount']) }}- {{ __('Toman') }}</span>
                            </div>
                        @endif

                        @if($summary['tax'] > 0)
                            <div class="flex justify-between items-center">
                                <span>{{ __('Value Added Tax:') }}</span>
                                <span class="font-mono font-bold">{{ format_price($summary['tax']) }} {{ __('Toman') }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between items-center">
                            <span>{{ __('Shipping') }}:</span>
                            @if($summary['is_free_shipping'])
                                <span class="text-emerald-600 font-bold">{{ __('Free Shipping') }}</span>
                            @else
                                <span class="font-mono font-bold text-gray-900 dark:text-gray-100">{{ format_price($summary['shipping']) }} {{ __('Toman') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 dark:border-zinc-800 flex justify-between items-baseline">
                        <span class="font-black text-sm text-gray-900 dark:text-gray-100">{{ __('Final Payable Total:') }}</span>
                        <div class="text-end font-mono">
                            <span class="text-xl font-black text-rose-600 dark:text-rose-400">{{ format_price($summary['grand_total']) }}</span>
                            <span class="text-xs text-gray-500 font-sans">{{ __('Toman') }}</span>
                        </div>
                    </div>

                    <!-- Checkout CTA Button -->
                    <a href="{{ route('checkout.index') }}" class="block w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-3.5 rounded-xl text-center text-sm shadow-xl hover:shadow-rose-600/30 transition-all">
                        {{ __('Proceed & Complete Order') }}
                    </a>
                </div>

            </div>

        </div>
    @else
        <!-- Empty Cart State -->
        <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-3xl p-8 sm:p-16 text-center space-y-4 shadow-sm">
            <div class="w-24 h-24 mx-auto rounded-full bg-rose-50 dark:bg-zinc-800 text-rose-500 flex items-center justify-center">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <h2 class="text-xl font-black text-gray-900 dark:text-gray-100">{{ __('Your shopping cart is currently empty!') }}</h2>
            <p class="text-sm text-gray-500 max-w-sm mx-auto">{{ __('You can visit the shop catalog to discover amazing tech gadgets and products.') }}</p>
            <a href="{{ route('shop.index') }}" class="inline-block bg-rose-600 text-white font-bold px-8 py-3 rounded-xl text-sm hover:bg-rose-700 transition-colors shadow-lg">
                {{ __('Start Shopping') }}
            </a>
        </div>
    @endif

</div>
@endsection
