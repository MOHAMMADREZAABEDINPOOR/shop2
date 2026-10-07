<?php

namespace Tests\Feature;

use App\Models\Banner;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BilingualLocalizationTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::create([
            'name' => 'مدیر ارشد',
            'slug' => 'admin',
            'description' => 'Administrator',
        ]);

        $this->admin = User::factory()->create([
            'email' => 'admin@test.com',
        ]);
        $this->admin->roles()->attach($adminRole);
    }

    public function test_language_switcher_redirects_and_persists_choice(): void
    {
        $response = $this->get('/lang/en');
        $response->assertRedirect();
        $response->assertSessionHas('locale', 'en');
        $response->assertCookie('locale', 'en');

        $responseFa = $this->get('/lang/fa');
        $responseFa->assertRedirect();
        $responseFa->assertSessionHas('locale', 'fa');
        $responseFa->assertCookie('locale', 'fa');
    }

    public function test_home_page_renders_in_persian_with_rtl(): void
    {
        $response = $this->withSession(['locale' => 'fa'])->get('/');
        $response->assertStatus(200);
        $response->assertSee('dir="rtl"', false);
        $response->assertSee('lang="fa"', false);
        $response->assertSee(__('صفحه اصلی'));
    }

    public function test_home_page_renders_in_english_with_ltr(): void
    {
        $response = $this->withSession(['locale' => 'en'])->get('/');
        $response->assertStatus(200);
        $response->assertSee('dir="ltr"', false);
        $response->assertSee('lang="en"', false);
        $response->assertSee('Home');
        $response->assertSee('Products');
        $response->assertSee('About Us');
        $response->assertSee('Contact Us');
    }

    public function test_admin_panel_renders_in_persian_with_rtl(): void
    {
        $response = $this->actingAs($this->admin)->withSession(['locale' => 'fa'])->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('dir="rtl"', false);
        $response->assertSee('lang="fa"', false);
        $response->assertSee('داشبورد مدیریت');
        $response->assertSee('مدیریت محصولات');
    }

    public function test_admin_panel_renders_in_english_with_ltr(): void
    {
        $response = $this->actingAs($this->admin)->withSession(['locale' => 'en'])->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('dir="ltr"', false);
        $response->assertSee('lang="en"', false);
        $response->assertSee('Admin Dashboard');
        $response->assertSee('Product Management');
        $response->assertSee('Logout from Admin Panel');
    }

    public function test_admin_banners_page_is_fully_bilingual(): void
    {
        Banner::create([
            'title' => 'پوستر تستی نوروزی',
            'position' => 'hero',
            'image_path' => 'https://example.com/banner.jpg',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // In Persian
        $resFa = $this->actingAs($this->admin)->withSession(['locale' => 'fa'])->get('/admin/banners');
        $resFa->assertStatus(200);
        $resFa->assertSee('پیش‌نمایش تصویر');
        $resFa->assertSee('عنوان و نشان تبلیغاتی');
        $resFa->assertSee('موقعیت نمایش');
        $resFa->assertSee('لینک مقصد');

        // In English
        $resEn = $this->actingAs($this->admin)->withSession(['locale' => 'en'])->get('/admin/banners');
        $resEn->assertStatus(200);
        $resEn->assertSee('Image Preview');
        $resEn->assertSee('Title &amp; Badge', false);
        $resEn->assertSee('Display Position');
        $resEn->assertSee('Target Link');
    }
}
