<?php

namespace Tests\Feature;

use App\Models\Banner;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BannerManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::create(['name' => 'مدیر کل', 'slug' => 'admin']);
        $this->adminUser = User::factory()->create();
        $this->adminUser->roles()->attach($adminRole->id);
    }

    public function test_admin_can_view_banners_index(): void
    {
        Banner::create([
            'title' => 'تست بنر هدر',
            'position' => 'hero',
            'image_path' => 'https://example.com/banner.jpg',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->adminUser)->get(route('admin.banners.index'));

        $response->assertStatus(200);
        $response->assertSee('مدیریت پوسترها و بنرهای فروشگاه');
        $response->assertSee('تست بنر هدر');
    }

    public function test_admin_can_create_banner_with_image_url_and_internal_link(): void
    {
        $response = $this->actingAs($this->adminUser)->post(route('admin.banners.store'), [
            'title' => 'بنر فروش پاییزی',
            'subtitle' => 'تخفیف فوق‌العاده',
            'image_url' => 'https://images.unsplash.com/photo-test?w=1200',
            'link_url' => '/shop?category=laptops',
            'badge_text' => 'فروش ویژه',
            'position' => 'promo_mid',
            'sort_order' => 2,
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.banners.index'));
        $this->assertDatabaseHas('banners', [
            'title' => 'بنر فروش پاییزی',
            'position' => 'promo_mid',
            'link_url' => '/shop?category=laptops',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_toggle_banner_status(): void
    {
        $banner = Banner::create([
            'title' => 'بنر فعال برای تغییر وضعیت',
            'position' => 'promo_top',
            'image_path' => 'https://example.com/test.jpg',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->adminUser)->post(route('admin.banners.toggle', $banner));

        $response->assertRedirect();
        $this->assertDatabaseHas('banners', [
            'id' => $banner->id,
            'is_active' => false,
        ]);
    }

    public function test_home_page_displays_active_banners_across_positions(): void
    {
        Banner::create([
            'title' => 'پوستر اسلایدر اصلی',
            'position' => 'hero',
            'image_path' => 'https://example.com/hero.jpg',
            'is_active' => true,
        ]);

        Banner::create([
            'title' => 'پوستر پروموشن بالا',
            'position' => 'promo_top',
            'image_path' => 'https://example.com/promo_top.jpg',
            'is_active' => true,
        ]);

        Banner::create([
            'title' => 'پوستر عریض جشنواره میانی',
            'position' => 'promo_mid',
            'image_path' => 'https://example.com/promo_mid.jpg',
            'is_active' => true,
        ]);

        Banner::create([
            'title' => 'پوستر پروموشن پایین',
            'position' => 'promo_bottom',
            'image_path' => 'https://example.com/promo_bottom.jpg',
            'is_active' => true,
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('پوستر اسلایدر اصلی');
        $response->assertSee('پوستر پروموشن بالا');
        $response->assertSee('پوستر عریض جشنواره میانی');
        $response->assertSee('پوستر پروموشن پایین');
    }
}
