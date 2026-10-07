<?php

namespace Tests\Feature;

use App\Models\Banner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_security_headers_are_present(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeaderMissing('X-Powered-By');
        $this->assertStringContainsString(
            'object-src \'none\'',
            $response->headers->get('Content-Security-Policy', '')
        );
        // Alpine.js compiles x-data expressions at runtime, so the policy
        // must explicitly allow 'unsafe-eval' or all interactivity breaks.
        $this->assertStringContainsString(
            '\'unsafe-eval\'',
            $response->headers->get('Content-Security-Policy', '')
        );
        // Dev-server origins must never leak outside local development.
        $this->assertStringNotContainsString(
            'ws://',
            $response->headers->get('Content-Security-Policy', '')
        );
    }

    public function test_csp_allows_vite_dev_server_on_local(): void
    {
        $original = app()->environment();
        app()->detectEnvironment(fn () => 'local');

        try {
            $response = $this->get(route('home'));

            $response->assertOk();
            $this->assertStringContainsString(
                'ws://127.0.0.1',
                $response->headers->get('Content-Security-Policy', '')
            );
        } finally {
            app()->detectEnvironment(fn () => $original);
        }
    }

    public function test_custom_404_page_renders_without_error(): void
    {
        $response = $this->get('/this-page-does-not-exist-xyz');

        $response->assertNotFound();
        $response->assertSee('این صفحه از قفسه ما افتاده!');
    }

    public function test_register_honeypot_blocks_bots(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'ربات مخرب',
            'email' => 'bot@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => '1',
            'website' => 'http://spam.example.com',
        ]);

        $response->assertSessionHasErrors('website');
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'bot@example.com']);
    }

    public function test_contact_honeypot_blocks_bots(): void
    {
        $response = $this->post(route('pages.contact.submit'), [
            'name' => 'ربات',
            'email' => 'bot@example.com',
            'subject' => 'spam',
            'message' => 'spam message',
            'website' => 'spam',
        ]);

        $response->assertSessionHasErrors('website');
    }

    public function test_private_pages_are_noindex(): void
    {
        $this->get(route('login'))->assertSee('noindex, nofollow', false);
        $this->get(route('register'))->assertSee('noindex, nofollow', false);
        $this->get(route('cart.index'))->assertSee('noindex, nofollow', false);
    }

    public function test_admin_panel_is_noindex_for_guests_redirect(): void
    {
        $admin = User::factory()->create();
        $admin->roles()->create(['name' => 'مدیر', 'slug' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('noindex, nofollow', false);
    }

    public function test_session_payload_is_encrypted_by_default(): void
    {
        $this->assertTrue((bool) config('session.encrypt'));
        $this->assertTrue((bool) config('session.http_only'));
    }

    public function test_hero_slides_are_cloaked_until_alpine_boots(): void
    {
        // Non-first slides must carry x-cloak so they never stack
        // visibly when JS is slow or disabled.
        foreach ([1, 2] as $i) {
            Banner::create([
                'title' => 'بنر تست '.$i,
                'subtitle' => 'زیرنویس تست',
                'image_path' => 'https://example.com/banner-'.$i.'.jpg',
                'link_url' => '/shop',
                'badge_text' => 'پیشنهاد',
                'position' => 'hero',
                'sort_order' => $i,
                'is_active' => true,
            ]);
        }

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('x-cloak', false);
    }
}
