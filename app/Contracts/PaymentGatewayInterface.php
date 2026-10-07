<?php

namespace App\Contracts;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

interface PaymentGatewayInterface
{
    /**
     * Get the unique identifier of the payment gateway.
     */
    public function getIdentifier(): string;

    /**
     * Get the human-readable name of the payment gateway.
     */
    public function getName(): string;

    /**
     * Initiate a payment request and return gateway redirect / form parameters.
     *
     * @return array{
     *     success: bool,
     *     payment: Payment,
     *     redirect_url: string,
     *     transaction_id: string,
     *     message: ?string
     * }
     */
    public function initiatePayment(Order $order, string $callbackUrl): array;

    /**
     * Verify payment response from callback or webhook.
     *
     * @return array{
     *     success: bool,
     *     transaction_id: string,
     *     reference_id: ?string,
     *     amount: float,
     *     card_pan_mask: ?string,
     *     message: string,
     *     raw_data: array
     * }
     */
    public function verifyPayment(Request $request, Payment $payment): array;
}
