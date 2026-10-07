<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PaymentService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {}

    /**
     * Realistic simulated payment gateway UI for local/staging development.
     */
    public function simulator(Request $request, Payment $payment): View
    {
        $token = $request->input('token');
        $expectedSignature = hash_hmac('sha256', "{$payment->order_id}|{$payment->amount}|{$payment->transaction_id}", config('app.key'));

        if (! hash_equals($expectedSignature, (string) $token)) {
            abort(403, 'توکن امنیتی درگاه پرداخت نامعتبر است.');
        }

        return view('shop.payment-simulator', compact('payment', 'token'));
    }

    /**
     * Process verification of the gateway callback.
     */
    public function callback(Request $request): View|RedirectResponse
    {
        $transactionId = $request->input('transaction_id');
        $payment = Payment::where('transaction_id', $transactionId)->firstOrFail();

        try {
            $verification = $this->paymentService->verifyPayment($request, $payment);

            if ($verification['success']) {
                return view('shop.order-success', [
                    'order' => $verification['order'],
                    'message' => $verification['message'],
                    'alreadyProcessed' => $verification['already_processed'],
                ]);
            }

            return redirect()->route('account.orders.show', $payment->order_id)
                ->withErrors(['payment' => $verification['message']]);
        } catch (Exception $e) {
            return redirect()->route('account.orders.show', $payment->order_id)
                ->withErrors(['payment' => 'خطا در فرآیند تأیید پرداخت: '.$e->getMessage()]);
        }
    }
}
