@extends('layouts.app')

@section('title', __('Complete Order & Checkout | DigiStore'))
@section('robots', 'noindex, nofollow')
@section('meta_description', __('Complete your order at DigiStore; select address, delivery method and secure online payment'))

@section('content')
<div class="max-w-7xl mx-auto px-2.5 sm:px-6 lg:px-8 py-4 sm:py-6">

    <!-- Progress Indicator -->
    <div class="max-w-xl mx-auto mb-10">
        <div class="flex items-center justify-between relative">
            <div class="w-full absolute top-1/2 -translate-y-1/2 h-0.5 bg-gray-200 dark:bg-zinc-800 -z-0"></div>
            
            <div class="flex flex-col items-center gap-2 z-10">
                <span class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs font-bold shadow-md">✓</span>
                <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ __('Cart Step') }}</span>
            </div>

            <div class="flex flex-col items-center gap-2 z-10">
                <span class="w-8 h-8 rounded-full bg-rose-600 text-white flex items-center justify-center text-xs font-bold shadow-md ring-4 ring-rose-100 dark:ring-rose-950">{{ fa_num(2) }}</span>
                <span class="text-xs font-bold text-rose-600">{{ __('Shipping & Payment Info') }}</span>
            </div>

            <div class="flex flex-col items-center gap-2 z-10">
                <span class="w-8 h-8 rounded-full bg-gray-200 dark:bg-zinc-800 text-gray-500 flex items-center justify-center text-xs font-bold">{{ fa_num(3) }}</span>
                <span class="text-xs text-gray-400">{{ __('Confirm & Pay') }}</span>
            </div>
        </div>
    </div>

    <form action="{{ route('checkout.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        @csrf

        <!-- Main Form Area (8 Columns) -->
        <div class="lg:col-span-8 space-y-8">
            
            <!-- Address Selection Section -->
            <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-3xl p-3.5 sm:p-8 shadow-sm space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-zinc-800 flex-wrap gap-2">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-6 bg-rose-600 rounded-full"></span>
                        <h2 class="text-base font-black text-gray-900 dark:text-gray-100">{{ __('Delivery Address for Order') }}</h2>
                    </div>
                    <a href="{{ route('account.addresses') }}" class="text-xs font-bold text-rose-600 hover:underline flex items-center gap-1">
                        {{ __('+ Add or Manage Addresses') }}
                    </a>
                </div>

                @if($addresses->isNotEmpty())
                    <div class="space-y-3">
                        @foreach($addresses as $address)
                            <label class="flex items-start gap-4 p-4 rounded-2xl border cursor-pointer transition-all {{ $address->is_default ? 'border-rose-500 bg-rose-50/30 dark:bg-rose-950/20' : 'border-gray-200 dark:border-zinc-800 hover:border-gray-300' }}">
                                <input type="radio" name="address_id" value="{{ $address->id }}" {{ $address->is_default ? 'checked' : ($loop->first ? 'checked' : '') }} class="mt-1 text-rose-600 focus:ring-rose-500">
                                <div class="flex-1 space-y-1 text-start">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-sm text-gray-900 dark:text-gray-100">{{ $address->title }} ({{ __('Recipient: :name', ['name' => $address->recipient_name]) }})</span>
                                        @if($address->is_default)
                                            <span class="text-[10px] bg-rose-600 text-white font-bold px-2 py-0.5 rounded-full">{{ __('Default') }}</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">{{ $address->full_address }}</p>
                                    <div class="text-[11px] text-gray-400 font-mono">{{ __('Contact Phone: :phone | Postal Code: :postal', ['phone' => fa_num($address->recipient_phone), 'postal' => fa_num($address->postal_code)]) }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6 space-y-3">
                        <p class="text-xs text-gray-500">{{ __('You have not registered any address yet.') }}</p>
                        <a href="{{ route('account.addresses') }}" class="inline-block bg-rose-600 text-white text-xs font-bold px-4 py-2 rounded-xl">
                            {{ __('Register New Address') }}
                        </a>
                    </div>
                @endif
            </div>

            <!-- Shipping Method Section -->
            <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-3xl p-3.5 sm:p-8 shadow-sm space-y-6">
                <div class="flex items-center gap-2 pb-4 border-b border-gray-100 dark:border-zinc-800">
                    <span class="w-2.5 h-6 bg-rose-600 rounded-full"></span>
                    <h2 class="text-base font-black text-gray-900 dark:text-gray-100">{{ __('Delivery Method') }}</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="flex items-center gap-3 p-4 rounded-2xl border border-gray-200 dark:border-zinc-800 hover:border-rose-400 cursor-pointer transition-colors text-start">
                        <input type="radio" name="shipping_method" value="standard" checked class="text-rose-600 focus:ring-rose-500">
                        <div>
                            <div class="font-bold text-sm text-gray-900 dark:text-gray-100">{{ __('Standard Express Post') }}</div>
                            <div class="text-xs text-gray-400">{{ __('Delivery 2 to 4 business days') }}</div>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-4 rounded-2xl border border-gray-200 dark:border-zinc-800 hover:border-rose-400 cursor-pointer transition-colors text-start">
                        <input type="radio" name="shipping_method" value="express" class="text-rose-600 focus:ring-rose-500">
                        <div>
                            <div class="font-bold text-sm text-gray-900 dark:text-gray-100">{{ __('Same-day Super Express') }}</div>
                            <div class="text-xs text-gray-400">{{ __('Same-day delivery (select areas)') }}</div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Payment Method Section -->
            <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-3xl p-3.5 sm:p-8 shadow-sm space-y-6">
                <div class="flex items-center gap-2 pb-4 border-b border-gray-100 dark:border-zinc-800">
                    <span class="w-2.5 h-6 bg-rose-600 rounded-full"></span>
                    <h2 class="text-base font-black text-gray-900 dark:text-gray-100">{{ __('Payment Method') }}</h2>
                </div>

                <div class="space-y-3">
                    <label class="flex items-center gap-3 p-4 rounded-2xl border border-rose-500 bg-rose-50/20 dark:bg-rose-950/20 cursor-pointer text-start">
                        <input type="radio" name="payment_method" value="test_gateway" checked class="text-rose-600 focus:ring-rose-500">
                        <div class="flex-1 flex flex-wrap items-center justify-between gap-2">
                            <div>
                                <div class="font-bold text-sm text-gray-900 dark:text-gray-100">{{ __('Secure Online Payment (Shetab)') }}</div>
                                <div class="text-xs text-gray-400">{{ __('Online payment with all Shetab bank cards') }}</div>
                            </div>
                            <span class="text-xs font-bold text-emerald-600 bg-emerald-50 dark:bg-emerald-950/40 px-3 py-1 rounded-full border border-emerald-200 dark:border-emerald-800">{{ __('Active & Secure') }}</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Order Notes -->
            <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-3xl p-3.5 sm:p-6 shadow-sm space-y-3 text-start">
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300">{{ __('Order Notes (Optional):') }}</label>
                <textarea name="notes" rows="2" placeholder="{{ __('Order notes placeholder') }}" class="w-full text-xs p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 focus:ring-rose-500 leading-relaxed"></textarea>
            </div>

        </div>

        <!-- Sticky Summary Column (4 Columns) -->
        <div class="lg:col-span-4 space-y-4 sticky top-24">
            
            <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-3xl p-3.5 sm:p-6 shadow-sm space-y-5">
                <h3 class="font-bold text-sm text-gray-900 dark:text-gray-100 pb-3 border-b border-gray-100 dark:border-zinc-800 text-start">
                    {{ __('Order Summary') }}
                </h3>

                <!-- Mini Items List -->
                <div class="space-y-3 max-h-48 overflow-y-auto ltr:pr-1 rtl:pl-1 scrollbar-thin">
                    @foreach($cart->items as $item)
                        <div class="flex items-center gap-3 text-xs text-start">
                            <img src="{{ $item->product->primary_image_url }}" class="w-10 h-10 object-cover rounded-lg bg-gray-100 flex-shrink-0">
                            <div class="flex-1 truncate">
                                <div class="font-semibold text-gray-800 dark:text-gray-200 truncate">{{ $item->product->name }}</div>
                                <div class="text-gray-400 font-mono">{{ fa_num($item->quantity) }} × {{ format_price($item->effective_unit_price) }} {{ __('Toman') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pt-3 border-t border-gray-100 dark:border-zinc-800 space-y-2.5 text-xs text-gray-600 dark:text-gray-400">
                    <div class="flex justify-between items-center">
                        <span>{{ __('Items Subtotal:') }}</span>
                        <span class="font-mono font-bold text-gray-900 dark:text-gray-100">{{ format_price($summary['subtotal']) }} {{ __('Toman') }}</span>
                    </div>

                    @if($summary['discount'] > 0)
                        <div class="flex justify-between items-center text-rose-600 font-bold">
                            <span>{{ __('Discount:') }}</span>
                            <span class="font-mono">{{ format_price($summary['discount']) }}- {{ __('Toman') }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between items-center">
                        <span>{{ __('Shipping Cost:') }}</span>
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

                <button type="submit" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-3.5 rounded-xl text-center text-sm shadow-xl hover:shadow-rose-600/30 transition-all">
                    {{ __('Pay & Complete Order') }}
                </button>

                <div class="text-[11px] text-gray-400 text-center leading-relaxed">
                    {{ __('Payment Gateway Notice') }}
                </div>
            </div>

        </div>

    </form>

</div>
@endsection
