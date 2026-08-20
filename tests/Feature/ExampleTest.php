<?php

namespace Tests\Feature;

use App\Models\BrochurePage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_frontend_uses_seeded_template_pages(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Forum Pengayom Advokat Indonesia')
            ->assertSee('assets/page-01.webp', false)
            ->assertSee('image-lightbox', false);

        $this->assertSame(40, BrochurePage::query()->count());
    }

    public function test_superadmin_can_open_user_management(): void
    {
        $admin = User::query()->where('role', 'superadmin')->firstOrFail();
        $this->actingAs($admin)->get('/cms/users')->assertOk()->assertSee('Akun & Hak Akses', false);
    }

    public function test_authenticated_user_visiting_cms_login_is_redirected_to_cms_dashboard(): void
    {
        $admin = User::query()->where('role', 'superadmin')->firstOrFail();

        $this->actingAs($admin)
            ->get('/cms/login')
            ->assertRedirect('/cms');
    }

    public function test_guest_can_still_open_cms_login(): void
    {
        $this->get('/cms/login')
            ->assertOk()
            ->assertSee('Masuk ke CMS');
    }

    public function test_only_configured_email_can_open_developer_tools(): void
    {
        $allowedUser = User::factory()->create([
            'email' => config('cms.developer_tools_email'),
            'role' => 'developer',
        ]);
        $otherDeveloper = User::query()->where('role', 'developer')->firstOrFail();
        $superadmin = User::query()->where('role', 'superadmin')->firstOrFail();

        $this->actingAs($allowedUser)
            ->get('/cms/developer-tools')
            ->assertOk()
            ->assertSee('Storage Link');

        $this->actingAs($otherDeveloper)
            ->get('/cms/developer-tools')
            ->assertForbidden();

        $this->actingAs($superadmin)
            ->get('/cms/developer-tools')
            ->assertForbidden();
    }

    public function test_developer_tools_menu_is_only_visible_to_configured_email(): void
    {
        $allowedUser = User::factory()->create([
            'email' => strtoupper(config('cms.developer_tools_email')),
            'role' => 'developer',
        ]);
        $otherDeveloper = User::query()->where('role', 'developer')->firstOrFail();

        $this->actingAs($allowedUser)
            ->get('/cms')
            ->assertOk()
            ->assertSee('Developer Tools');

        $this->actingAs($otherDeveloper)
            ->get('/cms')
            ->assertOk()
            ->assertDontSee('Developer Tools');
    }

    public function test_superadmin_can_update_frontend_page_content(): void
    {
        $admin = User::query()->where('role', 'superadmin')->firstOrFail();
        $page = BrochurePage::query()->where('page_number', 1)->firstOrFail();

        $this->actingAs($admin)->put("/cms/pages/{$page->id}", [
            'label' => 'Beranda Utama',
            'slug' => 'beranda',
            'position' => 1,
            'theme' => 'dark',
            'alt_text' => 'Profil resmi FPAI',
            'navigation_label' => 'Beranda',
            'show_in_navigation' => '1',
            'is_active' => '1',
        ])->assertRedirect('/cms/pages');

        $this->assertDatabaseHas('brochure_pages', [
            'id' => $page->id,
            'label' => 'Beranda Utama',
            'alt_text' => 'Profil resmi FPAI',
        ]);
    }

    public function test_authenticated_user_can_change_own_password(): void
    {
        $developer = User::query()->where('role', 'developer')->firstOrFail();

        $this->actingAs($developer)->put('/cms/profile/password', [
            'current_password' => config('cms.developer.password'),
            'password' => 'PasswordBaru123',
            'password_confirmation' => 'PasswordBaru123',
        ])->assertRedirect();

        $this->assertTrue(Hash::check('PasswordBaru123', $developer->fresh()->password));
    }

    public function test_authenticated_user_can_add_a_frontend_page(): void
    {
        Storage::fake('public');
        $admin = User::query()->where('role', 'superadmin')->firstOrFail();

        $this->actingAs($admin)->post('/cms/pages', [
            'label' => 'Kegiatan Nasional',
            'slug' => 'kegiatan-nasional',
            'position' => 2,
            'theme' => 'light',
            'alt_text' => 'Dokumentasi kegiatan nasional FPAI',
            'navigation_label' => 'Kegiatan',
            'show_in_navigation' => '1',
            'is_active' => '1',
            'image' => UploadedFile::fake()->image('kegiatan.jpg', 800, 1200),
        ])->assertRedirect('/cms/pages');

        $page = BrochurePage::query()->where('slug', 'kegiatan-nasional')->firstOrFail();
        $this->assertSame(41, $page->page_number);
        $this->assertSame(2, $page->position);
        $this->assertTrue($page->is_active);
        Storage::disk('public')->assertExists(substr($page->image_path, 8));
        $this->assertSame(3, BrochurePage::query()->where('page_number', 2)->value('position'));
    }
}
