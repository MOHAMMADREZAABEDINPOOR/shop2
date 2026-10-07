<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Category;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutAndPaymentTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Product $product;

    protected Address $address;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $category = Category::create([
            'name' => 'لپ‌تاپ',
            'slug' => 'laptops',
            'is_active' => true,
        ]);

        $this->product = Product::create([
            'category_id' => $category->id,
            'name' => 'مک‌بوک پرو M3',
            'slug' => 'macbook-pro-m3',
            'sku' => 'APL-MBP-M3',
            'price' => 90000000,
            'stock' => 5,
            'status' => 'published',
        ]);

        $this->product->inventory()->create([
            'stock' => 5,
            'reserved_stock' => 0,
            'low_stock_threshold' => 1,
        ]);

        $this->address = Address::create([
            'user_id' => $this->user->id,
            'title' => 'منزل',
            'recipient_name' => 'رضا احمدی',
            'recipient_phone' => '09123456789',
            'province' => 'تهران',
            'city' => 'تهران',
            'address_line' => 'خیابان بهشتی، خیابان سرافراز، پلاک ۱۰',
            'postal_code' => '1511122233',
            'is_default' => true,
        ]);
    }

    public function test_guest_is_redirected_from_checkout_to_login(): void
    {
        $response = $this->get(route('checkout.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_user_with_empty_cart_is_redirected_to_cart(): void
    {
        $response = $this->actingAs($this->user)->get(route('checkout.index'));
        $response->assertRedirect(route('cart.index'));
    }

    public function test_user_can_view_checkout_with_items_in_cart(): void
    {
        $this->actingAs($this->user)->post(route('cart.add'), [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($this->user)->get(route('checkout.index'));
        $response->assertStatus(200);
        $response->assertSee('تسویه‌حساب');
        $response->assertSee('مک‌بوک پرو M3');
    }

    public function test_checkout_creates_order_and_redirects_to_payment(): void
    {
        $this->actingAs($this->user)->post(route('cart.add'), [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($this->user)->post(route('checkout.store'), [
            'address_id' => $this->address->id,
            'shipping_method' => 'express',
            'payment_method' => 'test_gateway',
            'notes' => 'لطفاً عصر تحویل داده شود',
        ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->id,
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        $order = Order::where('user_id', $this->user->id)->first();
        $this->assertNotNull($order);
        $this->assertEquals(90000000, (int) $order->subtotal);

        // Check redirect is away to the simulator
        $response->assertStatus(302);
    }

    public function test_payment_simulator_and_callback_verifies_payment_and_deducts_stock(): void
    {
        $this->actingAs($this->user)->post(route('cart.add'), [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $this->actingAs($this->user)->post(route('checkout.store'), [
            'address_id' => $this->address->id,
            'shipping_method' => 'express',
            'payment_method' => 'test_gateway',
        ]);

        $order = Order::where('user_id', $this->user->id)->first();
        $payment = Payment::where('order_id', $order->id)->first();

        $token = hash_hmac('sha256', "{$order->id}|{$payment->amount}|{$payment->transaction_id}", config('app.key'));

        // Simulator page loads with valid token
        $simResponse = $this->actingAs($this->user)->get(route('payment.simulator', [
            'payment' => $payment->id,
            'token' => $token,
        ]));
        $simResponse->assertStatus(200);

        // Simulator rejects tampered token
        $tamperedResponse = $this->actingAs($this->user)->get(route('payment.simulator', [
            'payment' => $payment->id,
            'token' => 'invalid-token',
        ]));
        $tamperedResponse->assertStatus(403);

        // Simulate successful gateway callback with correct signature
        $callbackResponse = $this->actingAs($this->user)->post(route('payment.callback'), [
            'transaction_id' => $payment->transaction_id,
            'status' => 'success',
            'reference_id' => 'REF-123456',
            'signature' => $token,
        ]);

        $callbackResponse->assertStatus(200);
        $callbackResponse->assertSee('سفارش شما با موفقیت ثبت و پرداخت شد');

        // Verify order updated
        $order->refresh();
        $this->assertEquals('paid', $order->payment_status);

        // Verify inventory deducted atomically from 5 to 4
        $this->product->inventory->refresh();
        $this->assertEquals(4, $this->product->inventory->stock);

        // Test Idempotency: duplicate callback should not deduct stock again!
        $duplicateResponse = $this->actingAs($this->user)->post(route('payment.callback'), [
            'transaction_id' => $payment->transaction_id,
            'status' => 'success',
            'reference_id' => 'REF-123456',
            'signature' => $token,
        ]);

        $duplicateResponse->assertStatus(200);
        $this->product->inventory->refresh();
        $this->assertEquals(4, $this->product->inventory->stock); // Still 4, no double deduction!
    }
}
