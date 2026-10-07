<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected User $customerUser;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::create(['name' => 'مدیر', 'slug' => 'admin']);
        $customerRole = Role::create(['name' => 'مشتری', 'slug' => 'customer']);

        $this->adminUser = User::factory()->create();
        $this->adminUser->roles()->attach($adminRole->id);

        $this->customerUser = User::factory()->create();
        $this->customerUser->roles()->attach($customerRole->id);

        $this->category = Category::create([
            'name' => 'موبایل',
            'slug' => 'mobiles',
            'is_active' => true,
        ]);
    }

    public function test_guest_is_redirected_from_admin(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_regular_customer_is_forbidden_from_admin(): void
    {
        $response = $this->actingAs($this->customerUser)->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }

    public function test_admin_can_view_dashboard(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('داشبورد مدیریت');
    }

    public function test_admin_can_view_products_list(): void
    {
        Product::create([
            'category_id' => $this->category->id,
            'name' => 'آیفون تست',
            'slug' => 'iphone-test',
            'sku' => 'IPH-TST',
            'price' => 50000000,
            'stock' => 10,
            'status' => 'published',
        ]);

        $response = $this->actingAs($this->adminUser)->get(route('admin.products.index'));
        $response->assertStatus(200);
        $response->assertSee('آیفون تست');
    }

    public function test_admin_can_create_product(): void
    {
        $response = $this->actingAs($this->adminUser)->post(route('admin.products.store'), [
            'category_id' => $this->category->id,
            'name' => 'محصول جدید ادمین',
            'sku' => 'NEW-ADM-001',
            'price' => 2500000,
            'stock' => 20,
            'status' => 'published',
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'محصول جدید ادمین',
            'sku' => 'NEW-ADM-001',
        ]);
    }
}
