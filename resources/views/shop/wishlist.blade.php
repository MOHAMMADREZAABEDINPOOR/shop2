@extends('layouts.app')

@section('title', __('Wishlist | DigiStore'))
@section('robots', 'noindex, nofollow')
@section('meta_description', __('Your favorite products at DigiStore; save and quickly transfer to cart'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    <div class="flex items-center gap-2 mb-6">
        <span class="w-2.5 h-6 bg-rose-600 rounded-full"></span>
        <h1 class="text-xl font-black text-gray-900 dark:text-gray-100">{{ __('Wishlist') }}</h1>
        <span class="text-xs text-gray-400">({{ fa_num($items->count()) }} {{ __('Items') }})</span>
    </div>

    @if($items->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($items as $item)
                @if($item->product)
                    <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl p-4 flex flex-col justify-between shadow-sm relative group">
                        
                        <!-- Remove button -->
                        <form action="{{ route('wishlist.toggle', $item->product->id) }}" method="POST" class="absolute top-3 ltr:right-3 rtl:left-3 z-10">
                            @csrf
                            <button type="submit" class="p-1.5 bg-white/80 dark:bg-zinc-800/80 backdrop-blur rounded-full text-rose-500 hover:scale-110 transition-transform shadow-sm" title="{{ __('Remove from Wishlist') }}">
                                <svg class="w-4 h-4 fill-rose-500" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            </button>
                        </form>

                        <a href="{{ route('product.show', $item->product->slug) }}" class="block aspect-square w-full rounded-xl overflow-hidden bg-gray-50 dark:bg-zinc-800 mb-3">
                            <img src="{{ $item->product->primary_image_url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </a>

                        <div class="space-y-3 text-start">
                            <a href="{{ route('product.show', $item->product->slug) }}" class="block font-bold text-sm text-gray-800 dark:text-gray-100 line-clamp-2 hover:text-rose-600 transition-colors">
                                {{ $item->product->name }}
                            </a>

                            <div class="flex items-baseline justify-between pt-2 border-t border-gray-50 dark:border-zinc-800">
                                <span class="text-xs text-gray-400">{{ __('Price') }}:</span>
                                <div class="font-mono font-black text-rose-600 dark:text-rose-400 text-sm">
                                    {{ format_price($item->product->effective_price) }} <span class="text-[10px] text-gray-400 font-sans">{{ __('Toman') }}</span>
                                </div>
                            </div>

                            <form action="{{ route('wishlist.move-to-cart', $item->product->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-rose-50 dark:bg-zinc-800 text-rose-600 dark:text-rose-400 hover:bg-rose-600 hover:text-white font-bold py-2.5 rounded-xl text-xs transition-colors flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                    {{ __('Move to Cart') }}
                                </button>
                            </form>
                        </div>

                    </div>
                @endif
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-3xl p-8 sm:p-16 text-center space-y-4 shadow-sm">
            <div class="w-20 h-20 mx-auto rounded-full bg-rose-50 dark:bg-zinc-800 text-rose-500 flex items-center justify-center">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
            </div>
            <h2 class="text-xl font-black text-gray-900 dark:text-gray-100">{{ __('Your wishlist is empty!') }}</h2>
            <p class="text-sm text-gray-500 max-w-sm mx-auto">{{ __('Save your favorite products to easily access them in the future.') }}</p>
            <a href="{{ route('shop.index') }}" class="inline-block bg-rose-600 text-white font-bold px-8 py-3 rounded-xl text-sm hover:bg-rose-700 transition-colors shadow-lg">
                {{ __('Browse Products') }}
            </a>
        </div>
    @endif

</div>
@endsection
