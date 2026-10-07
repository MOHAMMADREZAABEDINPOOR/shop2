@extends('layouts.app')

@section('title', __('داشبورد حساب کاربری | دیجی‌استور'))
@section('robots', 'noindex, nofollow')

@section('content')
<div class="max-w-7xl mx-auto px-2.5 sm:px-6 lg:px-8 py-4 sm:py-6">

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 sm:gap-8 items-start">
        
        <!-- Sidebar Navigation -->
        <aside class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm space-y-4 sm:space-y-6">
            <div class="flex items-center gap-3 sm:gap-4 pb-4 sm:pb-6 border-b border-gray-100 dark:border-zinc-800">
                <div class="w-12 sm:w-14 h-12 sm:h-14 rounded-2xl bg-rose-600 text-white font-black text-lg sm:text-xl flex items-center justify-center overflow-hidden shadow-md flex-shrink-0">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" class="w-full h-full object-cover">
                    @else
                        {{ mb_substr($user->name, 0, 1) }}
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h2 class="font-bold text-sm sm:text-base text-gray-900 dark:text-gray-100 truncate">{{ $user->name }}</h2>
                    <p class="text-[11px] sm:text-xs text-gray-400 font-mono truncate">{{ $user->email }}</p>
                </div>
            </div>

            <nav class="space-y-1 text-xs sm:text-sm font-semibold">
                <a href="{{ route('account.dashboard') }}" class="flex items-center gap-2.5 sm:gap-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400">
                    <svg class="w-4.5 h-4.5 sm:w-5 sm:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    {{ __('داشبورد') }}
                </a>
                <a href="{{ route('account.orders') }}" class="flex items-center gap-2.5 sm:gap-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-800 transition-colors">
                    <svg class="w-4.5 h-4.5 sm:w-5 sm:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    {{ __('سفارش‌های من') }}
                </a>
                <a href="{{ route('account.addresses') }}" class="flex items-center gap-2.5 sm:gap-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-800 transition-colors">
                    <svg class="w-4.5 h-4.5 sm:w-5 sm:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                    {{ __('آدرس‌ها') }}
                </a>
                <a href="{{ route('wishlist.index') }}" class="flex items-center gap-2.5 sm:gap-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-800 transition-colors">
                    <svg class="w-4.5 h-4.5 sm:w-5 sm:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    {{ __('علاقه‌مندی‌ها') }}
                </a>
                <a href="{{ route('account.profile') }}" class="flex items-center gap-2.5 sm:gap-3 px-3 sm:px-4 py-2.5 sm:py-3 rounded-xl sm:rounded-2xl text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-zinc-800 transition-colors">
                    <svg class="w-4.5 h-4.5 sm:w-5 sm:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    {{ __('اطلاعات کاربری') }}
                </a>
            </nav>
        </aside>

        <!-- Main Dashboard Content -->
        <main class="lg:col-span-3 space-y-4 sm:space-y-6">
            
            <!-- Welcome KPI Cards -->
            <div class="grid grid-cols-1 min-[340px]:grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-6">
                <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl sm:rounded-3xl p-3.5 min-[360px]:p-5 sm:p-6 shadow-sm flex items-center gap-3 sm:gap-4">
                    <div class="w-10 sm:w-12 h-10 sm:h-12 rounded-xl sm:rounded-2xl bg-rose-50 dark:bg-zinc-800 text-rose-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 sm:w-6 h-5 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <div>
                        <span class="text-[11px] sm:text-xs text-gray-400 block">{{ __('کل سفارش‌ها') }}</span>
                        <span class="text-lg sm:text-xl font-black text-gray-900 dark:text-gray-100 font-mono">{{ $ordersCount }}</span>
                    </div>
                </div>

                <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl sm:rounded-3xl p-3.5 min-[360px]:p-5 sm:p-6 shadow-sm flex items-center gap-3 sm:gap-4">
                    <div class="w-10 sm:w-12 h-10 sm:h-12 rounded-xl sm:rounded-2xl bg-rose-50 dark:bg-zinc-800 text-rose-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 sm:w-6 h-5 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </div>
                    <div>
                        <span class="text-[11px] sm:text-xs text-gray-400 block">{{ __('لیست علاقه‌مندی') }}</span>
                        <span class="text-lg sm:text-xl font-black text-gray-900 dark:text-gray-100 font-mono">{{ $wishlistCount }}</span>
                    </div>
                </div>

                <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl sm:rounded-3xl p-3.5 min-[360px]:p-5 sm:p-6 shadow-sm flex items-center gap-3 sm:gap-4 col-span-1 min-[340px]:col-span-2 sm:col-span-1">
                    <div class="w-10 sm:w-12 h-10 sm:h-12 rounded-xl sm:rounded-2xl bg-emerald-50 dark:bg-zinc-800 text-emerald-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 sm:w-6 h-5 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <span class="text-[11px] sm:text-xs text-gray-400 block">{{ __('وضعیت حساب') }}</span>
                        <span class="text-xs sm:text-sm font-bold text-emerald-600">{{ __('فعال و تایید شده') }}</span>
                    </div>
                </div>
            </div>

            <!-- Recent Orders Table -->
            <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-zinc-800">
                    <h3 class="font-bold text-sm sm:text-base text-gray-900 dark:text-gray-100">{{ __('آخرین سفارش‌ها') }}</h3>
                    <a href="{{ route('account.orders') }}" class="text-xs text-rose-600 hover:underline">{{ __('مشاهده همه') }}</a>
                </div>

                @if($recentOrders->isNotEmpty())
                    <div class="overflow-x-auto -mx-4 px-4 sm:mx-0 sm:px-0">
                        <table class="w-full text-xs text-start min-w-[500px]">
                            <thead class="text-gray-400 border-b border-gray-100 dark:border-zinc-800">
                                <tr>
                                    <th class="py-3 px-2">{{ __('شماره سفارش') }}</th>
                                    <th class="py-3 px-2">{{ __('تاریخ') }}</th>
                                    <th class="py-3 px-2">{{ __('مبلغ کل') }}</th>
                                    <th class="py-3 px-2">{{ __('وضعیت پرداخت') }}</th>
                                    <th class="py-3 px-2">{{ __('وضعیت سفارش') }}</th>
                                    <th class="py-3 px-2">{{ __('عملیات') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-zinc-800/60">
                                @foreach($recentOrders as $order)
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-zinc-800/40 transition-colors">
                                        <td class="py-3.5 px-2 font-mono font-bold">{{ $order->order_number }}</td>
                                        <td class="py-3.5 px-2 text-gray-500">{{ $order->created_at->format('Y/m/d') }}</td>
                                        <td class="py-3.5 px-2 font-mono font-bold text-rose-600">{{ format_price($order->grand_total) }} {{ __('تومان') }}</td>
                                        <td class="py-3.5 px-2">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $order->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40' : 'bg-amber-50 text-amber-600 dark:bg-amber-950/40' }}">
                                                {{ $order->payment_status_label }}
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-2">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-gray-300">
                                                {{ $order->status_label }}
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-2">
                                            <a href="{{ route('account.orders.show', $order->id) }}" class="text-rose-600 hover:underline font-bold">
                                                {{ __('مشاهده') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8 text-xs text-gray-400">{{ __('شما تاکنون سفارشی ثبت نکرده‌اید.') }}</div>
                @endif
            </div>

            <!-- Default Address Box -->
            @if($defaultAddress)
                <div class="bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-sm space-y-2 text-xs">
                    <div class="flex items-center justify-between pb-2 border-b border-gray-100 dark:border-zinc-800">
                        <span class="font-bold text-xs sm:text-sm text-gray-900 dark:text-gray-100">{{ __('آدرس تحویل پیش‌فرض') }}</span>
                        <a href="{{ route('account.addresses') }}" class="text-xs text-rose-600 hover:underline">{{ __('ویرایش آدرس‌ها') }}</a>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed pt-2">{{ $defaultAddress->full_address }}</p>
                    <div class="text-gray-400 text-[11px] sm:text-xs">{{ __('گیرنده:') }} {{ $defaultAddress->recipient_name }} | {{ __('شماره موبایل:') }} {{ $defaultAddress->recipient_phone }}</div>
                </div>
            @endif

        </main>

    </div>

</div>
@endsection
