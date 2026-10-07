@props(['product'])

<div class="group bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 hover:border-rose-200 dark:hover:border-zinc-700 rounded-3xl p-3 min-[360px]:p-4 flex flex-col justify-between hover:shadow-xl transition-all duration-300 relative overflow-hidden">
    
    <!-- Top Badges Bar -->
    <div class="absolute top-3 ltr:left-3 rtl:right-3 z-10 flex flex-col gap-1.5 items-start">
        @if($product->has_discount)
            <span class="bg-rose-600 text-white text-[10px] min-[360px]:text-[11px] font-black px-2 min-[360px]:px-2.5 py-0.5 rounded-lg shadow-sm">
                {{ app()->getLocale() === 'fa' ? fa_num($product->discount_percent) . '٪ ' . __('Off') : $product->discount_percent . '% ' . __('Off') }}
            </span>
        @endif

        @if($product->is_best_seller)
            <span class="bg-amber-500 text-white text-[9px] min-[360px]:text-[10px] font-bold px-1.5 min-[360px]:px-2 py-0.5 rounded-md shadow-xs">
                {{ __('Bestselling') }}
            </span>
        @elseif($product->is_new_arrival)
            <span class="bg-emerald-500 text-white text-[9px] min-[360px]:text-[10px] font-bold px-1.5 min-[360px]:px-2 py-0.5 rounded-md shadow-xs">
                {{ __('New') }}
            </span>
        @endif
    </div>

    <!-- Wishlist Floating Toggle -->
    @auth
        <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" class="absolute top-3 ltr:right-3 rtl:left-3 z-10">
            @csrf
            @php $isFavorite = auth()->user()->wishlist?->items()->where('product_id', $product->id)->exists(); @endphp
            <button type="submit" class="p-1.5 min-[360px]:p-2 bg-white/90 dark:bg-zinc-800/90 backdrop-blur-sm rounded-full text-gray-400 hover:text-rose-500 transition-colors shadow-xs" title="{{ __('Add to Wishlist') }}">
                <svg class="w-4 h-4 {{ $isFavorite ? 'text-rose-500 fill-rose-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
            </button>
        </form>
    @endauth

    <!-- Showcase Product Image -->
    <a href="{{ route('product.show', $product->slug) }}" class="block aspect-square w-full rounded-2xl overflow-hidden bg-gray-50 dark:bg-zinc-800/60 mb-3 relative group-hover:bg-gray-100/70 transition-colors">
        <img src="{{ $product->primary_image_url }}"
             alt="{{ $product->name }}"
             loading="lazy"
             class="w-full h-full object-contain p-2 min-[360px]:p-4 group-hover:scale-105 transition-transform duration-500">
        
        @if(!$product->is_in_stock)
            <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px] flex items-center justify-center">
                <span class="bg-zinc-900/90 text-white text-xs font-bold px-3 py-1 rounded-full border border-zinc-700">{{ __('Out of Stock') }}</span>
            </div>
        @endif
    </a>

    <!-- Product Metadata & Title -->
    <div class="space-y-2 flex-1 flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between text-xs text-gray-400 mb-1.5">
                <span class="font-medium truncate max-w-[120px]">{{ $product->brand?->name ?? $product->category?->name }}</span>
                @if($product->average_rating > 0)
                    <div class="flex items-center gap-1 text-amber-500 font-bold">
                        <svg class="w-3.5 h-3.5 fill-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <span>{{ app()->getLocale() === 'fa' ? fa_num($product->average_rating) : $product->average_rating }}</span>
                    </div>
                @endif
            </div>

            <a href="{{ route('product.show', $product->slug) }}" class="block font-bold text-xs sm:text-sm text-gray-900 dark:text-gray-100 line-clamp-2 leading-relaxed hover:text-rose-600 dark:hover:text-rose-400 transition-colors">
                {{ $product->name }}
            </a>
        </div>

        <!-- In-Stock Delivery Notice -->
        @if($product->is_in_stock)
            <div class="flex items-center gap-1.5 text-[10px] text-emerald-600 dark:text-emerald-400 font-bold pt-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                <span>{{ __('Express Delivery Notice') }}</span>
            </div>
        @endif

        <!-- Price Section -->
        <div class="pt-3 border-t border-gray-100 dark:border-zinc-800/80">
            <div class="flex flex-wrap items-end justify-between gap-1.5">
                <div>
                    @if($product->has_discount)
                        <div class="text-[11px] text-gray-400 line-through font-mono">
                            {{ format_price($product->price) }}
                        </div>
                    @endif
                    <div class="flex items-baseline gap-1">
                        <span class="text-base sm:text-lg font-black text-rose-600 dark:text-rose-400 font-mono tracking-tight">
                            {{ format_price($product->effective_price) }}
                        </span>
                        <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400">{{ __('Toman') }}</span>
                    </div>
                </div>

                <!-- Fast Add To Cart Form -->
                @if($product->is_in_stock)
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="p-2.5 bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 hover:bg-rose-600 hover:text-white rounded-xl transition-all shadow-xs group-hover:scale-105 active:scale-95" title="{{ __('Quick Add to Cart') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </button>
                    </form>
                @endif
            </div>
        </div>

    </div>
</div>
