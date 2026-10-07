@extends('layouts.admin')

@section('title', __('مدیریت محصولات'))
@section('page_title', 'کاتالوگ و لیست جامع محصولات (' . number_format($products->total()) . ' کالا)')

@section('content')
<div class="space-y-6"
     x-data="{
        discountModalOpen: false,
        selectedProduct: null,
        discountAction: 'set_percent',
        discountPercent: 15,
        salePriceInput: '',
        openDiscountModal(product) {
            this.selectedProduct = product;
            this.discountAction = 'set_percent';
            this.discountPercent = product.discount_percent > 0 ? product.discount_percent : 15;
            this.salePriceInput = product.sale_price ? Math.round(product.sale_price) : Math.round(product.price * 0.85);
            this.discountModalOpen = true;
        },
        calculatedSalePrice() {
            if (!this.selectedProduct) return 0;
            if (this.discountAction === 'set_percent') {
                return Math.round(this.selectedProduct.price * (1 - (this.discountPercent / 100)));
            }
            if (this.discountAction === 'set_price') {
                return Number(this.salePriceInput) || 0;
            }
            return this.selectedProduct.price;
        }
     }">

    <!-- Filter & Action Bar -->
    <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-3xl p-5 shadow-sm space-y-4">
        <form action="{{ route('admin.products.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- Search -->
            <div class="lg:col-span-2">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="{{ __('جستجو نام کالا یا شناسه SKU...') }}"
                       class="w-full text-xs p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:ring-2 focus:ring-rose-500">
            </div>

            <!-- Category Filter -->
            <div>
                <select name="category_id" class="w-full text-xs p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:ring-2 focus:ring-rose-500">
                    <option value="">{{ __('همه دسته‌بندی‌ها') }}</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Discount Status Filter -->
            <div>
                <select name="has_discount" class="w-full text-xs p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50/50 dark:bg-zinc-800 focus:ring-2 focus:ring-rose-500">
                    <option value="">{{ __('وضعیت تخفیف (همه)') }}</option>
                    <option value="yes" {{ request('has_discount') === 'yes' ? 'selected' : '' }}>{{ __('فقط محصولات تخفیف‌دار') }}</option>
                    <option value="no" {{ request('has_discount') === 'no' ? 'selected' : '' }}>{{ __('محصولات بدون تخفیف') }}</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 bg-gray-900 dark:bg-white text-white dark:text-zinc-900 text-xs font-black py-3 px-4 rounded-xl hover:opacity-90 transition-opacity">
                    {{ __('اعمال فیلتر') }}
                </button>
                @if(request()->hasAny(['search', 'category_id', 'has_discount', 'status']))
                    <a href="{{ route('admin.products.index') }}" class="p-3 bg-gray-100 dark:bg-zinc-800 text-gray-500 rounded-xl hover:bg-gray-200 transition-colors" title="پاکسازی فیلترها">
                        ✕
                    </a>
                @endif
            </div>
        </form>

        <div class="flex items-center justify-between pt-2 border-t border-gray-100 dark:border-zinc-800">
            <span class="text-xs text-gray-500">
                نمایش {{ number_format($products->firstItem() ?? 0) }} تا {{ number_format($products->lastItem() ?? 0) }} از {{ number_format($products->total()) }} کالا
            </span>

            <a href="{{ route('admin.products.create') }}" class="bg-rose-600 hover:bg-rose-700 text-white text-xs font-black py-2.5 px-4 rounded-xl flex items-center gap-2 shadow-md hover:shadow-rose-600/30 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>{{ __('افزودن محصول جدید') }}</span>
            </a>
        </div>
    </div>

    <!-- Product Table -->
    <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-3xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-start">
                <thead class="bg-gray-50 dark:bg-zinc-800/60 text-gray-500 border-b border-gray-100 dark:border-zinc-800">
                    <tr>
                        <th class="py-3.5 px-4">{{ __('کالا / تصویر') }}</th>
                        <th class="py-3.5 px-4">{{ __('کد اختصاصی (SKU)') }}</th>
                        <th class="py-3.5 px-4">{{ __('دسته‌بندی و برند') }}</th>
                        <th class="py-3.5 px-4">{{ __('قیمت و تخفیف') }}</th>
                        <th class="py-3.5 px-4">{{ __('موجودی') }}</th>
                        <th class="py-3.5 px-4">{{ __('وضعیت') }}</th>
                        <th class="py-3.5 px-4 text-center">{{ __('مدیریت و تخفیف') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-zinc-800/60">
                    @forelse($products as $product)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-zinc-800/40 transition-colors">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" class="w-12 h-12 object-contain rounded-xl bg-gray-50 dark:bg-zinc-800 p-1 border border-gray-100 dark:border-zinc-700/60">
                                    <div class="max-w-xs">
                                        <a href="{{ route('product.show', $product->slug) }}" target="_blank" class="font-bold text-gray-900 dark:text-gray-100 hover:text-rose-600 transition-colors line-clamp-2">
                                            {{ $product->name }}
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 font-mono text-gray-500">{{ $product->sku }}</td>
                            <td class="py-3 px-4">
                                <div class="text-gray-700 dark:text-gray-300 font-medium">{{ $product->category->name }}</div>
                                <div class="text-[10px] text-gray-400">{{ $product->brand?->name ?? __('بدون برند') }}</div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="space-y-0.5">
                                    @if($product->has_discount)
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-mono font-black text-rose-600 text-sm">{{ format_price($product->effective_price) }} تومان</span>
                                            <span class="bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 text-[10px] font-black px-1.5 py-0.5 rounded-md">
                                                {{ $product->discount_percent }}٪
                                            </span>
                                        </div>
                                        <div class="text-[10px] text-gray-400 line-through font-mono">
                                            {{ format_price($product->price) }} تومان
                                        </div>
                                    @else
                                        <span class="font-mono font-bold text-gray-800 dark:text-gray-200 text-sm">
                                            {{ format_price($product->price) }} تومان
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                @if($product->stock <= 5)
                                    <span class="bg-rose-50 dark:bg-rose-950/40 text-rose-600 font-bold px-2 py-0.5 rounded-full font-mono text-[11px] border border-rose-200 dark:border-rose-900">
                                        {{ $product->stock }} (کمبود)
                                    </span>
                                @else
                                    <span class="font-mono text-gray-700 dark:text-gray-300 font-bold text-[11px] bg-gray-100 dark:bg-zinc-800 px-2 py-0.5 rounded-full">
                                        {{ $product->stock }} عدد
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black {{ $product->status === 'published' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $product->status === 'published' ? 'منتشر شده' : 'پیش‌نویس' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Quick Discount Action Button -->
                                    <button type="button"
                                            @click="openDiscountModal({{ json_encode([
                                                'id' => $product->id,
                                                'name' => $product->name,
                                                'sku' => $product->sku,
                                                'price' => (float)$product->price,
                                                'sale_price' => $product->sale_price ? (float)$product->sale_price : null,
                                                'has_discount' => $product->has_discount,
                                                'discount_percent' => $product->discount_percent,
                                            ]) }})"
                                            class="p-2 rounded-xl text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-950/30 transition-colors font-bold text-xs flex items-center gap-1"
                                            title="تخفیف‌گذاری سریع">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                        <span>{{ __('تخفیف') }}</span>
                                    </button>

                                    <!-- Edit Link -->
                                    <a href="{{ route('admin.products.edit', $product->id) }}"
                                       class="p-2 rounded-xl text-sky-600 hover:bg-sky-50 dark:hover:bg-sky-950/30 transition-colors font-bold text-xs"
                                       title="ویرایش کامل">
                                        {{ __('ویرایش') }}
                                    </a>

                                    <!-- Delete Form -->
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('آیا از آرشیو کردن این محصول اطمینان دارید؟') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-rose-600 rounded-xl transition-colors text-xs" title="حذف">
                                            {{ __('حذف') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-gray-400">{{ __('هیچ محصولی با مشخصات وارد شده یافت نشد.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100 dark:border-zinc-800">
            {{ $products->links() }}
        </div>
    </div>

    <!-- Quick Discount Modal -->
    <div x-show="discountModalOpen"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs"
         @keydown.escape.window="discountModalOpen = false">
        
        <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-3xl p-6 max-w-lg w-full shadow-2xl space-y-5"
             @click.away="discountModalOpen = false">
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-zinc-800">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    </span>
                    <h3 class="font-black text-sm text-gray-900 dark:text-gray-100">{{ __('تنظیم سریع تخفیف و فروش ویژه') }}</h3>
                </div>
                <button type="button" @click="discountModalOpen = false" class="text-gray-400 hover:text-gray-600">✕</button>
            </div>

            <!-- Modal Body Form -->
            <form :action="'/admin/products/' + (selectedProduct ? selectedProduct.id : '') + '/discount'" method="POST" class="space-y-4">
                @csrf
                
                <!-- Product Overview -->
                <div class="p-3 bg-gray-50 dark:bg-zinc-800/50 rounded-2xl border border-gray-100 dark:border-zinc-800 space-y-1">
                    <span class="text-xs font-bold text-gray-900 dark:text-gray-100 block truncate" x-text="selectedProduct?.name"></span>
                    <div class="flex items-center justify-between text-xs text-gray-500">
                        <span>{{ __('قیمت فعلی مصرف‌کننده:') }}</span>
                        <span class="font-mono font-bold text-gray-800 dark:text-gray-200" x-text="new Intl.NumberFormat('fa-IR').format(selectedProduct?.price || 0) + ' تومان'"></span>
                    </div>
                </div>

                <!-- Action Type Selector -->
                <div class="grid grid-cols-3 gap-2">
                    <button type="button"
                            @click="discountAction = 'set_percent'"
                            class="py-2 px-3 rounded-xl text-xs font-bold border text-center transition-all"
                            :class="discountAction === 'set_percent' ? 'border-amber-500 bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300 shadow-xs' : 'border-gray-200 dark:border-zinc-700 text-gray-600 dark:text-gray-300'">
                        {{ __('درصد تخفیف') }}
                    </button>
                    <button type="button"
                            @click="discountAction = 'set_price'"
                            class="py-2 px-3 rounded-xl text-xs font-bold border text-center transition-all"
                            :class="discountAction === 'set_price' ? 'border-amber-500 bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300 shadow-xs' : 'border-gray-200 dark:border-zinc-700 text-gray-600 dark:text-gray-300'">{{ __('قیمت قطعی حراج') }}</button>
                    <button type="button"
                            @click="discountAction = 'remove'"
                            class="py-2 px-3 rounded-xl text-xs font-bold border text-center transition-all"
                            :class="discountAction === 'remove' ? 'border-rose-500 bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-300 shadow-xs' : 'border-gray-200 dark:border-zinc-700 text-gray-600 dark:text-gray-300'">
                        {{ __('حذف تخفیف') }}
                    </button>
                </div>
                <input type="hidden" name="action" :value="discountAction">

                <!-- Percentage Option -->
                <div x-show="discountAction === 'set_percent'" class="space-y-3">
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300">{{ __('انتخاب درصد تخفیف:') }}</label>
                    <div class="flex items-center gap-2">
                        <template x-for="p in [5, 10, 15, 20, 25, 30, 50]" :key="p">
                            <button type="button"
                                    @click="discountPercent = p"
                                    class="flex-1 py-1.5 rounded-lg text-xs font-mono font-bold border transition-colors"
                                    :class="discountPercent == p ? 'bg-amber-500 text-white border-amber-500' : 'bg-gray-50 dark:bg-zinc-800 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-zinc-700'">
                                <span x-text="p + '%'"></span>
                            </button>
                        </template>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="number"
                               name="discount_percent"
                               x-model="discountPercent"
                               min="1"
                               max="99"
                               class="w-24 text-center font-mono font-bold text-sm p-2 rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                        <span class="text-xs text-gray-500">{{ __('درصد تخفیف اعمال شود') }}</span>
                    </div>
                </div>

                <!-- Exact Sale Price Option -->
                <div x-show="discountAction === 'set_price'" class="space-y-2">
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300">{{ __('مبلغ فروش ویژه (تومان):') }}</label>
                    <input type="number"
                           name="sale_price"
                           x-model="salePriceInput"
                           step="1000"
                           placeholder="{{ __('مثال: 4500000') }}"
                           class="w-full font-mono text-sm p-3 rounded-xl border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                </div>

                <!-- Remove Notice -->
                <div x-show="discountAction === 'remove'" class="p-3 bg-rose-50 dark:bg-rose-950/40 rounded-xl text-xs text-rose-700 dark:text-rose-300">{{ __('با تایید این بخش، تخفیف محصول لغو شده و با قیمت اصلی به مشتریان نمایش داده می‌شود.') }}</div>

                <!-- Preview Box -->
                <div x-show="discountAction !== 'remove'" class="p-3.5 bg-amber-50/60 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900/50 rounded-2xl flex items-center justify-between text-xs">
                    <span class="text-amber-800 dark:text-amber-300 font-bold">{{ __('قیمت پس از اعمال تخفیف:') }}</span>
                    <span class="font-mono font-black text-sm text-rose-600" x-text="new Intl.NumberFormat('fa-IR').format(calculatedSalePrice()) + ' تومان'"></span>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center gap-2 pt-2">
                    <button type="submit" class="flex-1 bg-amber-600 hover:bg-amber-700 text-white font-black py-3 rounded-xl text-xs shadow-md transition-colors">{{ __('ذخیره و اعمال بر روی محصول') }}</button>
                    <button type="button" @click="discountModalOpen = false" class="py-3 px-4 bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-gray-300 rounded-xl text-xs font-bold hover:bg-gray-200">
                        {{ __('انصراف') }}
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection
