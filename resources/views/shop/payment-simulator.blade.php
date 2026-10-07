<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>درگاه پرداخت اینترنتی به پرداخت ملت / شاپرک</title>
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="درگاه امن پرداخت اینترنتی سفارش دیجی‌استور">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100 text-gray-800 font-sans min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-200">
        
        <!-- Header with Shaparak Brand -->
        <div class="bg-gradient-to-r from-emerald-600 to-teal-700 text-white p-6 text-center space-y-2">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white/20 backdrop-blur mb-1">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>
            <h1 class="text-lg font-black tracking-tight">درگاه پرداخت الکترونیک شاپرک</h1>
            <p class="text-xs text-emerald-100">محیط امن پرداخت اینترنتی بانک مرکزی</p>
        </div>

        <!-- Order & Merchant Meta -->
        <div class="bg-gray-50 border-b border-gray-200 px-6 py-4 text-xs space-y-2">
            <div class="flex justify-between">
                <span class="text-gray-500">پذیرنده:</span>
                <span class="font-bold text-gray-900">{{ config('app.name', 'دیجی‌استور') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">شماره سفارش:</span>
                <span class="font-mono font-bold">{{ $payment->order->order_number }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">{{ __('مبلغ قابل پرداخت:') }}</span>
                <span class="font-mono font-black text-rose-600 text-sm">{{ format_price($payment->amount) }} تومان</span>
            </div>
        </div>

        <!-- Payment Simulation Form -->
        <form action="{{ route('payment.callback') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="transaction_id" value="{{ $payment->transaction_id }}">
            <input type="hidden" name="signature" value="{{ $token }}">

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">شماره کارت (۱۶ رقم):</label>
                <input type="text" name="card_pan" value="6037-9918-4521-8890" required class="w-full text-center font-mono font-bold text-sm tracking-widest p-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">کد شناسایی دوم (CVV2):</label>
                    <input type="password" value="482" maxlength="4" class="w-full text-center font-mono text-sm p-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">تاریخ انقضا:</label>
                    <div class="grid grid-cols-2 gap-1">
                        <input type="text" placeholder="{{ __('ماه') }}" value="08" maxlength="2" class="text-center font-mono text-xs p-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500">
                        <input type="text" placeholder="{{ __('سال') }}" value="06" maxlength="2" class="text-center font-mono text-xs p-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">رمز دوم پویا (OTP):</label>
                <div class="flex gap-2">
                    <input type="password" value="123456" maxlength="8" class="flex-1 text-center font-mono text-sm p-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500">
                    <button type="button" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold px-3 py-2 rounded-xl text-xs">دریافت رمز</button>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 space-y-2">
                <button type="submit" name="status" value="success" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl text-sm shadow-lg hover:shadow-emerald-600/30 transition-all">
                    پرداخت و تکمیل تراکنش
                </button>

                <button type="submit" name="status" value="cancel" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-2.5 rounded-xl text-xs transition-colors">
                    انصراف از پرداخت و بازگشت به فروشگاه
                </button>
            </div>
        </form>

        <div class="bg-gray-50 border-t border-gray-200 p-4 text-center text-[10px] text-gray-400">
            این یک درگاه تستی جهت شبیه‌سازی خرید امن است. اطلاعات کارت اعتباری شما ذخیره نمی‌گردد.
        </div>

    </div>

</body>
</html>
