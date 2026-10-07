<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Services\CartService;
use App\Services\CheckoutService;
use App\Services\PaymentService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected CheckoutService $checkoutService,
        protected PaymentService $paymentService
    ) {}

    public function index(): View|RedirectResponse
    {
        $user = Auth::user();
        $cart = $this->cartService->getCart($user);

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->withErrors(['cart' => 'برای ثبت سفارش، ابتدا محصولی به سبد خرید خود اضافه کنید.']);
        }

        $addresses = $user->addresses()->latest()->get();
        $summary = $this->cartService->getCartSummary($cart);

        return view('shop.checkout', compact('cart', 'addresses', 'summary'));
    }

    public function store(CheckoutRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $cart = $this->cartService->getCart($user);

        $address = $user->addresses()->findOrFail($request->validated('address_id'));

        try {
            $order = $this->checkoutService->createOrderFromCart(
                cart: $cart,
                user: $user,
                address: $address,
                shippingMethod: $request->validated('shipping_method'),
                paymentMethod: $request->validated('payment_method'),
                notes: $request->validated('notes')
            );

            // Initiate payment via PaymentService
            $callbackUrl = route('payment.callback');
            $paymentResponse = $this->paymentService->initiatePayment(
                order: $order,
                gatewayName: $order->payment_method,
                callbackUrl: $callbackUrl
            );

            if ($paymentResponse['success'] && ! empty($paymentResponse['redirect_url'])) {
                return redirect()->away($paymentResponse['redirect_url']);
            }

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'سفارش شما با موفقیت ثبت شد.');
        } catch (Exception $e) {
            return back()->withErrors(['checkout' => $e->getMessage()])->withInput();
        }
    }
}
