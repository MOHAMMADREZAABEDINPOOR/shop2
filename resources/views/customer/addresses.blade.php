@extends('layouts.app')

@section('title', 'آدرس‌های من | دیجی‌استور')
@section('robots', 'noindex, nofollow')

@section('content')
<div class="max-w-4xl mx-auto px-2.5 sm:px-6 lg:px-8 py-4 sm:py-6 space-y-4 sm:space-y-8" x-data="{ showModal: false }">

    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <a href="{{ route('account.dashboard') }}" class="text-xs text-gray-400 hover:text-rose-600">{{ __('داشبورد') }}</a>
            <span class="text-xs text-gray-400">/</span>
            <h1 class="text-lg sm:text-xl font-black text-gray-900 dark:text-gray-100">{{ __('آدرس‌های تحویل سفارش') }}</h1>
        </div>

        <button @click="showModal = true" class="w-full min-[380px]:w-auto justify-center bg-rose-600 hover:bg-rose-700 text-white font-bold py-2.5 px-4 sm:px-5 rounded-xl text-xs flex items-center gap-1.5 shadow-md transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            {{ __('افزودن آدرس جدید') }}
        </button>
    </div>

    <!-- Addresses List -->
    @if($addresses->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            @foreach($addresses as $address)
                <div class="bg-white dark:bg-zinc-900 border {{ $address->is_default ? 'border-rose-500 shadow-md ring-1 ring-rose-500/20' : 'border-gray-100 dark:border-zinc-800' }} rounded-2xl sm:rounded-3xl p-4 sm:p-6 flex flex-col justify-between space-y-4">
                    <div class="space-y-2 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-sm text-gray-900 dark:text-gray-100">{{ $address->title }}</span>
                            @if($address->is_default)
                                <span class="bg-rose-600 text-white font-bold text-[10px] px-2.5 py-0.5 rounded-full">{{ __('پیش‌فرض') }}</span>
                            @endif
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">{{ $address->full_address }}</p>
                        <div class="text-gray-400">{{ __('تحویل گیرنده:') }} <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $address->recipient_name }}</span></div>
                        <div class="text-gray-400">{{ __('شماره تماس:') }} <span class="font-mono text-gray-700 dark:text-gray-300">{{ $address->recipient_phone }}</span></div>
                        <div class="text-gray-400">{{ __('کد پستی:') }} <span class="font-mono text-gray-700 dark:text-gray-300">{{ $address->postal_code }}</span></div>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-zinc-800 text-xs">
                        @if(!$address->is_default)
                            <form action="{{ route('account.addresses.default', $address->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-rose-600 font-bold hover:underline">{{ __('تنظیم پیش‌فرض') }}</button>
                            </form>
                        @else
                            <span></span>
                        @endif

                        <form action="{{ route('account.addresses.destroy', $address->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('{{ __('آیا از حذف این آدرس اطمینان دارید؟') }}')" class="text-gray-400 hover:text-rose-600 font-semibold">{{ __('حذف') }}</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl sm:rounded-3xl p-6 sm:p-12 text-center space-y-4 shadow-sm">
            <div class="w-14 sm:w-16 h-14 sm:h-16 mx-auto rounded-full bg-rose-50 dark:bg-zinc-800 text-rose-500 flex items-center justify-center">
                <svg class="w-7 sm:w-8 h-7 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
            </div>
            <h3 class="font-bold text-sm sm:text-base text-gray-900 dark:text-gray-100">{{ __('هنوز آدرسی ثبت نکرده‌اید') }}</h3>
            <p class="text-xs text-gray-400 max-w-sm mx-auto">{{ __('برای سهولت در خریدهای آتی، آدرس منزل یا محل کار خود را اضافه نمایید.') }}</p>
            <button @click="showModal = true" class="bg-rose-600 text-white font-bold px-6 py-2.5 rounded-xl text-xs shadow-md">
                {{ __('افزودن آدرس جدید') }}
            </button>
        </div>
    @endif

    <!-- New Address Modal -->
    <div x-show="showModal" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-2.5 sm:p-4" style="display: none;">
        <div @click.away="showModal = false" class="bg-white dark:bg-zinc-900 rounded-2xl sm:rounded-3xl max-w-lg w-full p-4 sm:p-8 space-y-4 sm:space-y-6 shadow-2xl border border-gray-100 dark:border-zinc-800 max-h-[92vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-zinc-800">
                <h3 class="font-bold text-sm sm:text-base text-gray-900 dark:text-gray-100">{{ __('ثبت آدرس تحویل جدید') }}</h3>
                <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 p-1">✕</button>
            </div>

            <form action="{{ route('account.addresses.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">عنوان آدرس:</label>
                        <input type="text" name="title" required placeholder="مثال: منزل، شرکت" class="w-full text-xs p-2.5 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 focus:ring-rose-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">نام گیرنده:</label>
                        <input type="text" name="recipient_name" required value="{{ auth()->user()->name }}" class="w-full text-xs p-2.5 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 focus:ring-rose-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">شماره موبایل گیرنده:</label>
                        <input type="text" name="recipient_phone" required placeholder="09123456789" class="w-full text-xs p-2.5 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 focus:ring-rose-500 font-mono text-left" dir="ltr">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">استان:</label>
                        <input type="text" name="province" required placeholder="مثال: تهران" class="w-full text-xs p-2.5 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 focus:ring-rose-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">شهر:</label>
                        <input type="text" name="city" required placeholder="مثال: تهران" class="w-full text-xs p-2.5 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 focus:ring-rose-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">کد پستی ۱۰ رقمی:</label>
                    <input type="text" name="postal_code" required maxlength="10" placeholder="1234567890" class="w-full text-xs p-2.5 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 focus:ring-rose-500 font-mono text-left" dir="ltr">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">نشانی دقیق پستی:</label>
                    <textarea name="address_line" required rows="2" placeholder="خیابان، کوچه، پلاک، واحد..." class="w-full text-xs p-2.5 rounded-xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800 focus:ring-rose-500"></textarea>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_default" value="1" id="is_def" class="rounded border-gray-300 text-rose-600 focus:ring-rose-500">
                    <label for="is_def" class="text-xs text-gray-700 dark:text-gray-300 cursor-pointer">تنظیم به عنوان آدرس پیش‌فرض</label>
                </div>

                <button type="submit" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-3 rounded-xl text-xs shadow-md transition-colors">
                    ذخیره آدرس
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
