<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;
use App\Models\User;
use App\Models\Media;

class FlixoraTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_homepage_renders_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('FLIXORA');
        $response->assertSee('Inception');
    }

    public function test_media_detail_page_renders_successfully(): void
    {
        $response = $this->get('/media/inception');
        $response->assertStatus(200);
        $response->assertSee('Inception');
        $response->assertSee('Christopher Nolan');
        $response->assertSee('Rekomendasi Film Sejenis');
    }

    public function test_watch_history_page_renders(): void
    {
        $response = $this->get('/watch-history');
        $response->assertStatus(200);
        $response->assertSee('Film Terakhir Ditonton');
    }

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_login_and_access_dashboard(): void
    {
        $admin = User::where('email', 'admin@flixora.com')->first();

        $response = $this->post('/admin/login', [
            'email' => 'admin@flixora.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($admin);

        $dashboardResponse = $this->get('/admin/dashboard');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Overview Dashboard Admin');
    }
}
