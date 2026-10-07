<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    protected Product $product;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create([
            'name' => 'کالای دیجیتال',
            'slug' => 'digital',
            'is_active' => true,
        ]);

        $this->product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'هدفون سونی',
            'slug' => 'sony-headphones',
            'sku' => 'SNY-H100',
            'price' => 5000000,
            'stock' => 10,
            'status' => 'published',
        ]);

        $this->product->inventory()->create([
            'stock' => 10,
            'reserved_stock' => 0,
            'low_stock_threshold' => 2,
        ]);
    }

    public function test_guest_can_view_cart_page(): void
    {
        $response = $this->get(route('cart.index'));
        $response->assertStatus(200);
        $response->assertSee('سبد خرید');
    }

    public function test_guest_can_add_item_to_cart(): void
    {
        $response = $this->post(route('cart.add'), [
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('cart_items', [
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);
    }

    public function test_authenticated_user_can_add_item_to_cart(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('cart.add'), [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
        ]);
        $this->assertDatabaseHas('cart_items', [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);
    }

    public function test_user_can_update_cart_item_quantity(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('cart.add'), [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $cart = $user->cart;
        $item = $cart->items()->first();

        $response = $this->actingAs($user)->post(route('cart.update', $item->id), [
            'quantity' => 3,
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('cart_items', [
            'id' => $item->id,
            'quantity' => 3,
        ]);
    }

    public function test_user_can_remove_item_from_cart(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('cart.add'), [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $cart = $user->cart;
        $item = $cart->items()->first();

        $response = $this->actingAs($user)->delete(route('cart.remove', $item->id));

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('cart_items', [
            'id' => $item->id,
        ]);
    }

    public function test_user_can_apply_valid_coupon(): void
    {
        $user = User::factory()->create();

        $coupon = Coupon::create([
            'code' => 'OFF10',
            'type' => 'percentage',
            'value' => 10,
            'minimum_order_amount' => 1000000,
            'per_user_limit' => 2,
            'is_active' => true,
        ]);

        $this->actingAs($user)->post(route('cart.add'), [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($user)->post(route('cart.coupon.apply'), [
            'coupon_code' => 'OFF10',
        ]);

        $response->assertSessionHasNoErrors();
        $user->cart->refresh();
        $this->assertEquals($coupon->id, $user->cart->coupon_id);
    }

    public function test_user_cannot_apply_invalid_coupon(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('cart.add'), [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($user)->post(route('cart.coupon.apply'), [
            'coupon_code' => 'FAKECODE',
        ]);

        $response->assertSessionHasErrors('coupon');
    }
}
