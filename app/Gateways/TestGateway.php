<?php

namespace App\Gateways;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TestGateway implements PaymentGatewayInterface
{
    public function getIdentifier(): string
    {
        return 'test_gateway';
    }

    public function getName(): string
    {
        return 'درگاه پرداخت تستی (شبیه‌ساز بانکی شاپرک)';
    }

    public function initiatePayment(Order $order, string $callbackUrl): array
    {
        $idempotencyKey = 'idemp_'.$order->id.'_'.Str::random(16);
        $transactionId = 'TXN-'.strtoupper(Str::random(12));

        $secret = config('app.key');
        $signature = hash_hmac('sha256', "{$order->id}|{$order->grand_total}|{$transactionId}", $secret);

        $payment = Payment::create([
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'gateway' => $this->getIdentifier(),
            'transaction_id' => $transactionId,
            'amount' => $order->grand_total,
            'currency' => $order->currency,
            'status' => 'pending',
            'idempotency_key' => $idempotencyKey,
            'payload' => [
                'callback_url' => $callbackUrl,
                'signature' => $signature,
            ],
        ]);

        $payment->transactions()->create([
            'type' => 'request',
            'status' => 'success',
            'request_data' => [
                'order_id' => $order->id,
                'amount' => $order->grand_total,
                'callback_url' => $callbackUrl,
            ],
            'response_data' => [
                'transaction_id' => $transactionId,
                'signature' => $signature,
            ],
        ]);

        $redirectUrl = route('payment.simulator', [
            'payment' => $payment->id,
            'token' => $signature,
        ]);

        return [
            'success' => true,
            'payment' => $payment,
            'redirect_url' => $redirectUrl,
            'transaction_id' => $transactionId,
            'message' => 'انتقال به درگاه امن پرداخت',
        ];
    }

    public function verifyPayment(Request $request, Payment $payment): array
    {
        $transactionId = $request->input('transaction_id');
        $referenceId = $request->input('reference_id', 'REF-'.rand(10000000, 99999999));
        $receivedSignature = $request->input('signature');
        $status = $request->input('status'); // 'success' or 'cancel' or 'fail'

        $secret = config('app.key');
        $expectedSignature = hash_hmac('sha256', "{$payment->order_id}|{$payment->amount}|{$payment->transaction_id}", $secret);

        // Security check: Reject tampered or fake callbacks
        if (! hash_equals($expectedSignature, (string) $receivedSignature)) {
            $payment->transactions()->create([
                'type' => 'verify',
                'status' => 'failure',
                'request_data' => $request->all(),
                'response_data' => ['error' => 'Invalid cryptographic signature'],
            ]);

            return [
                'success' => false,
                'transaction_id' => $transactionId ?? $payment->transaction_id,
                'reference_id' => null,
                'amount' => (float) $payment->amount,
                'card_pan_mask' => null,
                'message' => 'امضای دیجیتال تراکنش نامعتبر است یا درخواست دستکاری شده است.',
                'raw_data' => $request->all(),
            ];
        }

        if ($status !== 'success') {
            $payment->transactions()->create([
                'type' => 'callback',
                'status' => 'failure',
                'request_data' => $request->all(),
                'response_data' => ['message' => 'تراکنش توسط کاربر لغو شد یا با خطا مواجه گردید.'],
            ]);

            return [
                'success' => false,
                'transaction_id' => $payment->transaction_id,
                'reference_id' => $referenceId,
                'amount' => (float) $payment->amount,
                'card_pan_mask' => null,
                'message' => 'تراکنش توسط کاربر لغو گردید یا پرداخت ناموفق بود.',
                'raw_data' => $request->all(),
            ];
        }

        $cardPan = $request->input('card_pan', '6037-99**-****-1234');

        $payment->transactions()->create([
            'type' => 'verify',
            'status' => 'success',
            'request_data' => $request->all(),
            'response_data' => [
                'reference_id' => $referenceId,
                'card_pan' => $cardPan,
                'verified_at' => now()->toIso8601String(),
            ],
        ]);

        return [
            'success' => true,
            'transaction_id' => $payment->transaction_id,
            'reference_id' => $referenceId,
            'amount' => (float) $payment->amount,
            'card_pan_mask' => $cardPan,
            'message' => 'پرداخت با موفقیت تأیید گردید.',
            'raw_data' => $request->all(),
        ];
    }
}
