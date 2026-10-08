@extends('layouts.app')

@section('title', $product->seo_title ?? $product->name . ' | ' . config('app.name', 'دیجی‌استور'))
@section('meta_description', $product->seo_description ?? Str::limit(strip_tags($product->short_description ?? $product->description), 150))
@section('og_image', $product->primary_image_url)

@push('scripts')
<script type="application/ld+json">
    {!! json_encode($schemaData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-2.5 sm:px-6 lg:px-8 py-4 sm:py-6 space-y-6 sm:space-y-8"
     x-data="{
        selectedVariantId: '{{ $product->variants->first()?->id ?? '' }}',
        currentPrice: '{{ format_price($product->variants->first()?->effective_price ?? $product->effective_price) }}',
        originalPrice: '{{ format_price($product->variants->first()?->price ?? $product->price) }}',
        hasDiscount: {{ ($product->variants->first()?->has_discount ?? $product->has_discount) ? 'true' : 'false' }},
        discountPercent: '{{ $product->variants->first()?->discount_percent ?? $product->discount_percent }}',
        activeImage: '{{ $product->primary_image_url }}',
        activeImageIndex: 0,
        quantity: 1,
        userRating: 5,
        hours: 14,
        minutes: 36,
        seconds: 48,
        initCountdown() {
            setInterval(() => {
                if (this.seconds > 0) {
                    this.seconds--;
                } else if (this.minutes > 0) {
                    this.minutes--;
                    this.seconds = 59;
                } else if (this.hours > 0) {
                    this.hours--;
                    this.minutes = 59;
                    this.seconds = 59;
                }
            }, 1000);
        },
        setVariant(variant) {
            this.selectedVariantId = variant.id;
            this.currentPrice = new Intl.NumberFormat('en-US').format(variant.effective_price);
            this.originalPrice = new Intl.NumberFormat('en-US').format(variant.price);
            this.hasDiscount = variant.has_discount;
            this.discountPercent = variant.discount_percent;
            if (variant.image) {
                this.activeImage = variant.image.startsWith('http') ? variant.image : ('/storage/' + variant.image);
            }
        }
     }"
     x-init="initCountdown()">

    <!-- Breadcrumb Navigation -->
    <nav class="flex items-center flex-wrap gap-2 text-xs text-gray-500 dark:text-gray-400">
        <a href="{{ route('home') }}" class="hover:text-rose-600 transition-colors">{{ __('Home') }}</a>
        <span class="text-gray-300 dark:text-zinc-600">/</span>
        <a href="{{ route('shop.index') }}" class="hover:text-rose-600 transition-colors">{{ __('Shop') }}</a>
        <span class="text-gray-300 dark:text-zinc-600">/</span>
        <a href="{{ route('shop.index', ['category' => $product->category->slug]) }}" class="hover:text-rose-600 transition-colors font-medium">
            {{ $product->category->name }}
        </a>
        <span class="text-gray-300 dark:text-zinc-600">/</span>
        <span class="text-gray-900 dark:text-gray-100 font-bold truncate max-w-sm">{{ $product->name }}</span>
    </nav>

    <!-- Special Offer Banner (if discounted) -->
    @if($product->has_discount)
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-rose-600 via-rose-500 to-amber-500 text-white p-4 shadow-lg flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3 text-start">
                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div>
                    <h3 class="font-black text-base sm:text-lg">{{ __('DigiStore Amazing Offer') }}</h3>
                    <p class="text-xs text-rose-100 font-medium">{{ __('This item features a limited-time special discount and best market price guarantee') }}</p>
                </div>
            </div>

            <!-- Real-time Countdown Box -->
            <div class="flex items-center gap-2 font-mono text-sm shrink-0">
                <span class="text-xs font-sans text-rose-100 ltr:mr-1 rtl:ml-1">{{ __('Time Remaining:') }}</span>
                <div class="bg-black/30 backdrop-blur-md px-2.5 py-1 rounded-lg text-center font-bold" x-text="String(hours).padStart(2, '0')">14</div>
                <span>:</span>
                <div class="bg-black/30 backdrop-blur-md px-2.5 py-1 rounded-lg text-center font-bold" x-text="String(minutes).padStart(2, '0')">36</div>
                <span>:</span>
                <div class="bg-black/30 backdrop-blur-md px-2.5 py-1 rounded-lg text-center font-bold text-amber-300" x-text="String(seconds).padStart(2, '0')">48</div>
            </div>
        </div>
    @endif

    <!-- Main Product Showcase Grid -->
    <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-3xl p-3.5 sm:p-8 shadow-sm grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-12">
        
        <!-- Gallery Section with Multi-Angle Viewer (5 Columns) -->
        <div class="lg:col-span-5 space-y-4">
            <!-- Main Featured Angle Image -->
            <div class="aspect-square w-full rounded-2xl overflow-hidden bg-gray-50 dark:bg-zinc-800/80 border border-gray-100 dark:border-zinc-700/60 relative group">
                <img :src="activeImage"
                     alt="{{ $product->name }}"
                     class="w-full h-full object-contain p-4 transition-transform duration-500 group-hover:scale-105">
                
                @if($product->has_discount)
                    <div class="absolute top-4 ltr:left-4 rtl:right-4 bg-rose-600 text-white font-black text-xs px-3.5 py-1.5 rounded-xl shadow-lg">
                        <span><span x-text="discountPercent">{{ $product->discount_percent }}</span>{{ app()->getLocale() === 'fa' ? '٪ ' : '% ' }}{{ __('Off') }}</span>
                    </div>
                @endif

                <div class="absolute bottom-3 ltr:right-3 rtl:left-3 bg-black/50 backdrop-blur-md text-white text-[11px] px-2.5 py-1 rounded-lg flex items-center gap-1.5 opacity-80 hover:opacity-100 transition-opacity">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span>{{ __('Multi-angle Views') }}</span>
                </div>
            </div>

            <!-- Multi-Angle Thumbnail Strip -->
            @if($product->images->isNotEmpty())
                <div class="space-y-2">
                    <span class="text-[11px] font-bold text-gray-500 dark:text-gray-400 block text-start">{{ __('Select View Angle & Photos:') }}</span>
                    <div class="flex items-center gap-2.5 overflow-x-auto pb-2 scrollbar-thin">
                        @foreach($product->images as $idx => $img)
                            @php
                                $angleTitles = [__('Front View'), __('Back View'), __('Side View'), __('Perspective'), __('In Action')];
                                $angleLabel = $angleTitles[$idx % count($angleTitles)] ?? __('Angle :num', ['num' => $idx + 1]);
                            @endphp
                            <button type="button"
                                    @click="activeImage = '{{ $img->url }}'; activeImageIndex = {{ $idx }}"
                                    class="group relative flex-shrink-0 w-20 h-20 rounded-xl overflow-hidden border-2 bg-gray-50 dark:bg-zinc-800 transition-all text-center"
                                    :class="activeImage === '{{ $img->url }}' ? 'border-rose-600 ring-2 ring-rose-500/20 shadow-md' : 'border-gray-200 dark:border-zinc-700 opacity-70 hover:opacity-100'">
                                <img src="{{ $img->url }}" alt="{{ $img->alt_text ?? $product->name }}" class="w-full h-full object-contain p-1">
                                <span class="absolute bottom-0 inset-x-0 bg-black/60 backdrop-blur-xs text-[9px] text-white text-center py-0.5 truncate px-1">
                                    {{ $angleLabel }}
                                </span>
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Trust Badges Under Gallery -->
            <div class="grid grid-cols-3 gap-2 pt-3 border-t border-gray-100 dark:border-zinc-800 text-center text-[11px] text-gray-500 dark:text-gray-400">
                <div class="p-2 rounded-xl bg-gray-50 dark:bg-zinc-800/50 flex flex-col items-center gap-1">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    <span class="font-bold text-gray-700 dark:text-gray-300">{{ __('Authenticity') }}</span>
                    <span class="text-[10px] text-gray-400">{{ __('100% Original') }}</span>
                </div>
                <div class="p-2 rounded-xl bg-gray-50 dark:bg-zinc-800/50 flex flex-col items-center gap-1">
                    <svg class="w-5 h-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0 2 2 0 00-4 0z"></path></svg>
                    <span class="font-bold text-gray-700 dark:text-gray-300">{{ __('Express Delivery') }}</span>
                    <span class="text-[10px] text-gray-400">{{ __('Fast Dispatch') }}</span>
                </div>
                <div class="p-2 rounded-xl bg-gray-50 dark:bg-zinc-800/50 flex flex-col items-center gap-1">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span class="font-bold text-gray-700 dark:text-gray-300">{{ __('Return Guarantee') }}</span>
                    <span class="text-[10px] text-gray-400">{{ __('7-Day Trial') }}</span>
                </div>
            </div>
        </div>

        <!-- Product Details Section (7 Columns) -->
        <div class="lg:col-span-7 flex flex-col justify-between space-y-6 text-start">
            
            <div class="space-y-4">
                <!-- Brand, Rating & SKU Bar -->
                <div class="flex flex-wrap items-center justify-between gap-3 text-xs text-gray-400 pb-3 border-b border-gray-100 dark:border-zinc-800">
                    <div class="flex items-center gap-3">
                        @if($product->brand)
                            <a href="{{ route('shop.index', ['brand' => $product->brand->slug]) }}"
                                class="font-black text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/40 px-3 py-1 rounded-lg border border-rose-200 dark:border-rose-900 hover:bg-rose-100 transition-colors">
                                {{ $product->brand->name }}
                            </a>
                        @endif
                        <span class="text-gray-500">{{ __('Product Code:') }} <span class="font-mono font-bold text-gray-700 dark:text-gray-300">{{ $product->sku }}</span></span>
                    </div>

                    <div class="flex items-center gap-3">
                        @if($product->average_rating > 0)
                            <div class="flex items-center gap-1 text-amber-500 font-bold bg-amber-50 dark:bg-amber-950/30 px-2.5 py-1 rounded-lg border border-amber-200 dark:border-amber-900">
                                <svg class="w-4 h-4 fill-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                <span>{{ fa_num($product->average_rating) }}</span>
                                <span class="text-gray-400 text-[11px]">({{ fa_num($product->reviews_count) }} {{ __('ratings') }})</span>
                            </div>
                        @endif
                        <a href="#reviews-section" class="text-rose-600 dark:text-rose-400 hover:underline text-xs">
                            {{ __('User Reviews') }}
                        </a>
                    </div>
                </div>

                <!-- Product Full Title -->
                <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-gray-100 leading-snug">
                    {{ $product->name }}
                </h1>

                <!-- Short Highlights Description -->
                @if($product->short_description)
                    <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed bg-gray-50/70 dark:bg-zinc-800/40 p-3.5 rounded-2xl border border-gray-100 dark:border-zinc-800">
                        {{ $product->short_description }}
                    </p>
                @endif

                <!-- Key Highlight Badges from Dimensions -->
                @if(is_array($product->dimensions) && count($product->dimensions) > 0)
                    <div class="space-y-2 pt-1">
                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ __('Key Features:') }}</span>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                            @foreach(array_slice($product->dimensions, 0, 4, true) as $key => $val)
                                <div class="flex items-center gap-2 p-2 rounded-xl bg-gray-50 dark:bg-zinc-800/60 border border-gray-100 dark:border-zinc-800">
                                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                    <span class="text-gray-500 text-[11px] font-medium">{{ $key }}:</span>
                                    <span class="text-gray-900 dark:text-gray-100 font-bold truncate">{{ is_array($val) ? implode(', ', $val) : $val }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Variants Selection (Colors, RAM, Storage) -->
                @if($product->variants->isNotEmpty())
                    <div class="space-y-3 pt-3 border-t border-gray-100 dark:border-zinc-800">
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300 flex items-center justify-between">
                            <span>{{ __('Select Color / Specification:') }}</span>
                            <span class="text-[11px] text-gray-400 font-normal">{{ __('Click on preferred option') }}</span>
                        </label>
                        <div class="flex flex-wrap gap-2.5">
                            @foreach($product->variants as $variant)
                                @php
                                    $attrColorHex = $variant->attributes_json['hex'] ?? null;
                                @endphp
                                <button type="button"
                                        @click="setVariant({{ json_encode([
                                            'id' => $variant->id,
                                            'effective_price' => $variant->effective_price,
                                            'price' => $variant->price,
                                            'has_discount' => $variant->has_discount,
                                            'discount_percent' => $variant->discount_percent,
                                            'image' => $variant->image_url ?? $variant->image,
                                        ]) }})"
                                        class="px-4 py-2.5 rounded-xl text-xs font-bold border transition-all flex items-center gap-2"
                                        :class="selectedVariantId == {{ $variant->id }} ? 'border-rose-600 bg-rose-50/70 dark:bg-rose-950/40 text-rose-600 ring-1 ring-rose-600 shadow-sm' : 'border-gray-200 dark:border-zinc-700 text-gray-700 dark:text-gray-300 hover:border-gray-300 bg-white dark:bg-zinc-900'">
                                    @if($attrColorHex)
                                        <span class="w-3.5 h-3.5 rounded-full border border-gray-300" style="background-color: {{ $attrColorHex }}"></span>
                                    @endif
                                    <span>{{ $variant->variant_label }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Live Stock Status Indicator -->
                <div class="flex items-center gap-2 pt-2">
                    @if($product->is_in_stock)
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">{{ __('In stock at DigiStore warehouse (ready for same-day dispatch)') }}</span>
                        <span class="text-xs text-gray-400">{{ __('(:count in stock)', ['count' => fa_num($product->stock)]) }}</span>
                    @else
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                        <span class="text-xs font-bold text-rose-600 dark:text-rose-400">{{ __('Currently out of stock') }}</span>
                    @endif
                </div>

            </div>

            <!-- Price Box & Fast Action CTA -->
            <div class="bg-gradient-to-br from-gray-50 to-rose-50/30 dark:from-zinc-800/80 dark:to-zinc-800/40 rounded-2xl p-4 sm:p-6 border border-gray-200 dark:border-zinc-700/80 space-y-5 shadow-sm">
                
                <div class="flex flex-wrap items-end justify-between gap-2">
                    <div>
                        <span class="text-xs text-gray-500 block">{{ __('Final Order Price:') }}</span>
                        <span class="text-[11px] text-emerald-600 dark:text-emerald-400 font-medium">{{ __('Including all taxes and discounts') }}</span>
                    </div>

                    <div class="text-end">
                        <div x-show="hasDiscount" class="text-xs text-gray-400 line-through">
                            <span x-text="originalPrice"></span> {{ __('Toman') }}
                        </div>
                        <div class="flex items-baseline gap-1.5">
                            <span class="text-2xl sm:text-3xl font-black text-rose-600 dark:text-rose-400 font-mono tracking-tight" x-text="currentPrice"></span>
                            <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ __('Toman') }}</span>
                        </div>
                    </div>
                </div>

                @if($product->is_in_stock)
                    <form action="{{ route('cart.add') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="product_variant_id" :value="selectedVariantId">

                        <div class="flex flex-col min-[380px]:flex-row items-stretch min-[380px]:items-center gap-3">
                            <!-- Quantity Selector -->
                            <div class="flex items-center justify-between min-[380px]:justify-center border border-gray-300 dark:border-zinc-600 rounded-xl bg-white dark:bg-zinc-900 overflow-hidden shadow-sm">
                                <button type="button" @click="quantity > 1 ? quantity-- : null" class="px-3.5 py-3 text-gray-600 hover:bg-gray-100 dark:hover:bg-zinc-800 font-bold transition-colors">-</button>
                                <input type="number" name="quantity" x-model="quantity" min="1" max="{{ $product->stock }}" class="w-12 text-center text-xs font-bold bg-transparent border-0 focus:ring-0 p-0">
                                <button type="button" @click="quantity < {{ $product->stock }} ? quantity++ : null" class="px-3.5 py-3 text-gray-600 hover:bg-gray-100 dark:hover:bg-zinc-800 font-bold transition-colors">+</button>
                            </div>

                            <!-- Submit Add to Cart Button -->
                            <button type="submit" class="flex-1 bg-rose-600 hover:bg-rose-700 active:scale-[0.99] text-white font-black py-3 px-4 min-[380px]:px-6 rounded-xl shadow-lg hover:shadow-rose-600/30 transition-all text-xs sm:text-sm flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                <span>{{ __('Add to Cart') }}</span>
                            </button>
                        </div>
                    </form>
                @endif

            </div>

        </div>

    </div>

    <!-- Specifications, Description, & Reviews Tabbed Section -->
    <div id="reviews-section" class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-3xl p-4 sm:p-8 shadow-sm space-y-6"
         x-data="{ tab: 'specs' }">
        
        <!-- Tab Navigation Bar -->
        <div class="flex items-center gap-4 sm:gap-8 border-b border-gray-100 dark:border-zinc-800 pb-4 text-sm font-bold overflow-x-auto scrollbar-none">
            <button @click="tab = 'specs'" class="pb-2 border-b-2 transition-all flex items-center gap-2 whitespace-nowrap" :class="tab === 'specs' ? 'border-rose-600 text-rose-600' : 'border-transparent text-gray-400 hover:text-gray-700'">
                <span>📋</span>
                <span>{{ __('Technical Specifications') }}</span>
            </button>
            <button @click="tab = 'description'" class="pb-2 border-b-2 transition-all flex items-center gap-2 whitespace-nowrap" :class="tab === 'description' ? 'border-rose-600 text-rose-600' : 'border-transparent text-gray-400 hover:text-gray-700'">
                <span>📖</span>
                <span>{{ __('Comprehensive Review') }}</span>
            </button>
            <button @click="tab = 'reviews'" class="pb-2 border-b-2 transition-all flex items-center gap-2 whitespace-nowrap" :class="tab === 'reviews' ? 'border-rose-600 text-rose-600' : 'border-transparent text-gray-400 hover:text-gray-700'">
                <span>⭐</span>
                <span>{{ __('Customer Reviews (:count)', ['count' => fa_num($product->approvedReviews->count())]) }}</span>
            </button>
        </div>

        <!-- TAB 1: TECHNICAL SPECIFICATIONS -->
        <div x-show="tab === 'specs'" class="space-y-6 text-start">
            <div class="flex items-center justify-between flex-wrap gap-2">
                <h3 class="font-black text-base text-gray-900 dark:text-gray-100 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-600"></span>
                    {{ __('Technical Specifications Table') }}
                </h3>
                <span class="text-xs text-gray-400">{{ __('Compliant with manufacturer official standards') }}</span>
            </div>

            <div class="overflow-hidden border border-gray-100 dark:border-zinc-800 rounded-2xl shadow-xs">
                <table class="w-full text-start text-xs">
                    <tbody>
                        <!-- SKU -->
                        <tr class="border-b border-gray-100 dark:border-zinc-800/80 bg-gray-50/60 dark:bg-zinc-800/40">
                            <td class="py-3 px-4 font-bold text-gray-500 dark:text-gray-400 w-1/3 sm:w-1/4">{{ __('Product Code (SKU)') }}</td>
                            <td class="py-3 px-4 font-mono font-bold text-gray-800 dark:text-gray-200">{{ $product->sku }}</td>
                        </tr>

                        <!-- Category -->
                        <tr class="border-b border-gray-100 dark:border-zinc-800/80">
                            <td class="py-3 px-4 font-bold text-gray-500 dark:text-gray-400">{{ __('Main Category') }}</td>
                            <td class="py-3 px-4 font-medium text-gray-800 dark:text-gray-200">{{ $product->category->name }}</td>
                        </tr>

                        <!-- Brand -->
                        @if($product->brand)
                            <tr class="border-b border-gray-100 dark:border-zinc-800/80 bg-gray-50/60 dark:bg-zinc-800/40">
                                <td class="py-3 px-4 font-bold text-gray-500 dark:text-gray-400">{{ __('Manufacturer Brand') }}</td>
                                <td class="py-3 px-4 font-medium text-rose-600 dark:text-rose-400 font-bold">{{ $product->brand->name }}</td>
                            </tr>
                        @endif

                        <!-- Weight -->
                        @if($product->weight)
                            <tr class="border-b border-gray-100 dark:border-zinc-800/80">
                                <td class="py-3 px-4 font-bold text-gray-500 dark:text-gray-400">{{ __('Net Weight') }}</td>
                                <td class="py-3 px-4 font-medium text-gray-800 dark:text-gray-200">{{ fa_num($product->weight) }} {{ __('Grams') }}</td>
                            </tr>
                        @endif

                        <!-- Dynamic Technical Dimensions Specs from Database -->
                        @if(is_array($product->dimensions))
                            @foreach($product->dimensions as $specKey => $specValue)
                                <tr class="border-b border-gray-100 dark:border-zinc-800/80 {{ $loop->even ? 'bg-gray-50/60 dark:bg-zinc-800/40' : '' }}">
                                    <td class="py-3.5 px-4 font-bold text-gray-600 dark:text-gray-300 flex items-center gap-1.5">
                                        <span class="text-rose-500 font-bold">▪</span>
                                        <span>{{ $specKey }}</span>
                                    </td>
                                    <td class="py-3.5 px-4 font-medium text-gray-800 dark:text-gray-200 leading-relaxed">
                                        @if(is_array($specValue))
                                            {{ implode(', ', $specValue) }}
                                        @else
                                            {{ $specValue }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAB 2: FULL DESCRIPTION -->
        <div x-show="tab === 'description'" class="space-y-4 text-start">
            <h3 class="font-black text-base text-gray-900 dark:text-gray-100 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-600"></span>
                {{ __('Expert Review & Detailed Description') }}
            </h3>
            <div class="prose dark:prose-invert max-w-none text-sm text-gray-700 dark:text-gray-300 leading-relaxed bg-gray-50/50 dark:bg-zinc-800/30 p-6 rounded-2xl border border-gray-100 dark:border-zinc-800">
                {!! nl2br(e($product->description ?? __('No additional description available for this product.'))) !!}
            </div>
        </div>

        <!-- TAB 3: USER REVIEWS & SUBMISSION -->
        <div x-show="tab === 'reviews'" class="space-y-8 text-start">
            
            <!-- Submit Review Form for Users -->
            @auth
                <div class="bg-gradient-to-br from-gray-50 to-rose-50/20 dark:from-zinc-800/60 dark:to-zinc-800/30 p-6 rounded-2xl border border-gray-200 dark:border-zinc-700 space-y-4">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <h4 class="font-black text-sm text-gray-900 dark:text-gray-100 flex items-center gap-2">
                            <span class="text-rose-600">✍️</span>
                            <span>{{ __('Share your review and experience for this product') }}</span>
                        </h4>

                        @if($isVerifiedBuyer ?? false)
                            <span class="bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 text-xs font-bold px-3 py-1 rounded-full border border-emerald-200 dark:border-emerald-800 flex items-center gap-1">
                                <span>✓</span>
                                <span>{{ __('You are a verified buyer of this product') }}</span>
                            </span>
                        @endif
                    </div>

                    <form action="{{ route('account.reviews.store', $product->id) }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <!-- Interactive Star Rating Selector -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300">{{ __('Your overall rating:') }}</label>
                            <div class="flex items-center gap-2">
                                <input type="hidden" name="rating" :value="userRating">
                                <div class="flex items-center gap-1 text-2xl cursor-pointer">
                                    <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                                        <button type="button" @click="userRating = star" class="transition-transform hover:scale-125 focus:outline-none">
                                            <span :class="star <= userRating ? 'text-amber-400' : 'text-gray-300 dark:text-zinc-600'">★</span>
                                        </button>
                                    </template>
                                </div>
                                <span class="text-xs font-bold text-amber-600 dark:text-amber-400 ltr:ml-2 rtl:mr-2" x-text="userRating + ' {{ __('Rating') }}'"></span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">{{ __('Review Title:') }}</label>
                                <input type="text"
                                       name="title"
                                       required
                                       placeholder="{{ __('Review title placeholder') }}"
                                       class="w-full text-xs p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 focus:ring-2 focus:ring-rose-500 dark:text-gray-100">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">{{ __('Author Name:') }}</label>
                                <input type="text"
                                       disabled
                                       value="{{ Auth::user()->name }}"
                                       class="w-full text-xs p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-100 dark:bg-zinc-800 text-gray-500 cursor-not-allowed">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">{{ __('Review body, pros & cons, user experience:') }}</label>
                            <textarea name="body"
                                      required
                                      rows="4"
                                      placeholder="{{ __('Review body placeholder') }}"
                                      class="w-full text-xs p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 focus:ring-2 focus:ring-rose-500 dark:text-gray-100 leading-relaxed"></textarea>
                        </div>

                        <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-black px-6 py-2.5 rounded-xl text-xs shadow-md hover:shadow-rose-600/30 transition-all flex items-center gap-2">
                            <span>{{ __('Submit Review') }}</span>
                            <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </form>
                </div>
            @else
                <div class="p-5 bg-gradient-to-r from-rose-50 to-amber-50 dark:from-zinc-800 dark:to-zinc-800/80 rounded-2xl border border-rose-100 dark:border-zinc-700 text-center space-y-2">
                    <p class="text-xs text-gray-700 dark:text-gray-300 font-bold">{{ __('Please sign in to rate and submit a review for this product.') }}</p>
                    <a href="{{ route('login') }}" class="inline-block bg-rose-600 text-white text-xs font-black px-5 py-2 rounded-xl shadow-md hover:bg-rose-700 transition-colors">
                        {{ __('Sign In to Account') }}
                    </a>
                </div>
            @endauth

            <!-- Approved Reviews List -->
            @if($product->approvedReviews->isNotEmpty())
                <div class="space-y-4">
                    <h4 class="font-black text-sm text-gray-900 dark:text-gray-100">
                        {{ __('Customer & User Reviews (:count)', ['count' => fa_num($product->approvedReviews->count())]) }}
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($product->approvedReviews as $review)
                            <div class="p-5 rounded-2xl border border-gray-100 dark:border-zinc-800 bg-white dark:bg-zinc-900/50 space-y-3 shadow-xs">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-rose-100 dark:bg-rose-950/60 text-rose-600 font-black text-xs flex items-center justify-center">
                                            {{ mb_substr($review->user->name ?? 'U', 0, 1) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-xs text-gray-900 dark:text-gray-100 block">{{ $review->user->name ?? __('DigiStore Customer') }}</span>
                                            <span class="text-[10px] text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-1 text-amber-500 font-bold text-xs bg-amber-50 dark:bg-amber-950/30 px-2 py-0.5 rounded-md">
                                        <span>★</span>
                                        <span>{{ fa_num($review->rating) }} / {{ fa_num(5) }}</span>
                                    </div>
                                </div>

                                @if($review->is_verified_purchase)
                                    <div class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded-full border border-emerald-200 dark:border-emerald-800">
                                        <span>✓</span>
                                        <span>{{ __('Verified Buyer') }}</span>
                                    </div>
                                @endif

                                <h5 class="font-bold text-xs text-gray-800 dark:text-gray-200">{{ $review->title }}</h5>
                                <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">{{ $review->body }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="text-center py-10 rounded-2xl border border-dashed border-gray-200 dark:border-zinc-800 space-y-2">
                    <span class="text-3xl block">💬</span>
                    <p class="text-xs font-bold text-gray-600 dark:text-gray-300">{{ __('No reviews have been submitted for this product yet.') }}</p>
                    <p class="text-[11px] text-gray-400">{{ __('Be the first to share your experience with others!') }}</p>
                </div>
            @endif

        </div>

    </div>

    <!-- Related Products -->
    @if($relatedProducts->isNotEmpty())
        <section class="space-y-6 pt-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-7 bg-rose-600 rounded-full"></span>
                    <h3 class="text-lg font-black text-gray-900 dark:text-gray-100">{{ __('Similar & Related Products') }}</h3>
                </div>
                <a href="{{ route('shop.index', ['category' => $product->category->slug]) }}" class="text-xs text-rose-600 font-bold hover:underline flex items-center gap-1">
                    <span>{{ __('View all products in this category') }}</span>
                    <span class="inline-block rtl:rotate-180">←</span>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $relProduct)
                    <x-product-card :product="$relProduct" />
                @endforeach
            </div>
        </section>
    @endif

    <!-- Mobile Sticky Bottom CTA Bar -->
    <div class="lg:hidden fixed bottom-14 left-0 right-0 z-30 bg-white/95 dark:bg-zinc-900/95 backdrop-blur-md border-t border-gray-200 dark:border-zinc-800 p-3 px-4 shadow-2xl flex items-center justify-between gap-4">
        <div class="text-start">
            <div class="text-[10px] text-gray-400">{{ __('Final Price:') }}</div>
            <div class="text-base font-black text-rose-600 dark:text-rose-400 font-mono">
                <span x-text="currentPrice"></span> {{ __('Toman') }}
            </div>
        </div>

        @if($product->is_in_stock)
            <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="product_variant_id" :value="selectedVariantId">
                <button type="submit" class="w-full bg-rose-600 hover:bg-rose-700 active:scale-[0.99] text-white font-bold py-2.5 px-4 rounded-xl text-xs shadow-md">
                    {{ __('Add to Cart') }}
                </button>
            </form>
        @else
            <button disabled class="bg-gray-200 dark:bg-zinc-800 text-gray-400 font-bold py-2.5 px-4 rounded-xl text-xs">
                {{ __('Out of Stock') }}
            </button>
        @endif
    </div>

</div>
@endsection
