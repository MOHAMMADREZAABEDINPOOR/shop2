<?php

namespace App\Services;

use App\Contracts\PaymentGatewayInterface;
use App\Gateways\TestGateway;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Payment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PaymentService
{
    /**
     * @var array<string, class-string<PaymentGatewayInterface>>
     */
    protected array $gateways = [
        'test_gateway' => TestGateway::class,
    ];

    public function __construct(
        protected InventoryService $inventoryService,
        protected CouponService $couponService
    ) {}

    public function getGateway(string $name): PaymentGatewayInterface
    {
        if (! isset($this->gateways[$name])) {
            throw new InvalidArgumentException("درگاه پرداخت '{$name}' تعریف نشده است.");
        }

        return app($this->gateways[$name]);
    }

    /**
     * Initiate payment flow with selected gateway.
     */
    public function initiatePayment(Order $order, string $gatewayName, string $callbackUrl): array
    {
        $gateway = $this->getGateway($gatewayName);

        return $gateway->initiatePayment($order, $callbackUrl);
    }

    /**
     * Verify payment with idempotency protection.
     *
     * @throws Exception
     */
    public function verifyPayment(Request $request, Payment $payment): array
    {
        // Idempotency check: If already successful, do not re-process or re-deduct
        if ($payment->isSuccessful()) {
            return [
                'success' => true,
                'already_processed' => true,
                'order' => $payment->order,
                'message' => 'این تراکنش قبلاً با موفقیت ثبت و تأیید گردیده است.',
            ];
        }

        $gateway = $this->getGateway($payment->gateway);
        $result = $gateway->verifyPayment($request, $payment);

        return DB::transaction(function () use ($result, $payment) {
            $order = $payment->order()->lockForUpdate()->firstOrFail();

            if ($result['success']) {
                $payment->update([
                    'status' => 'successful',
                    'reference_id' => $result['reference_id'],
                    'verified_at' => now(),
                    'payload' => array_merge($payment->payload ?? [], [
                        'verification_result' => $result,
                    ]),
                ]);

                $order->update([
                    'status' => 'processing',
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                ]);

                // Deduct inventory atomically for all items in order
                foreach ($order->items as $item) {
                    $this->inventoryService->deductStock(
                        productId: $item->product_id,
                        variantId: $item->product_variant_id,
                        quantity: $item->quantity,
                        reason: 'order_paid',
                        referenceId: "ORDER-{$order->order_number}",
                        userId: $order->user_id
                    );
                }

                // If coupon was applied, record usage
                if ($order->coupon_code && $order->coupon_discount > 0) {
                    $coupon = Coupon::where('code', $order->coupon_code)->first();
                    if ($coupon) {
                        $this->couponService->recordUsage($coupon, $order->user, $order, (float) $order->coupon_discount);
                    }
                }

                return [
                    'success' => true,
                    'already_processed' => false,
                    'order' => $order,
                    'message' => $result['message'],
                ];
            }

            // Payment Failed
            $payment->update([
                'status' => 'failed',
                'payload' => array_merge($payment->payload ?? [], [
                    'failure_reason' => $result['message'],
                ]),
            ]);

            $order->update([
                'payment_status' => 'failed',
            ]);

            return [
                'success' => false,
                'already_processed' => false,
                'order' => $order,
                'message' => $result['message'],
            ];
        });
    }
}
