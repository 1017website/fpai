<?php

namespace Tests\Feature;

use App\Models\NewsArticle;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NewsFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_published_news_is_visible_on_listing_and_detail_pages(): void
    {
        $article = NewsArticle::query()->create([
            'title' => 'Kegiatan Nasional FPAI',
            'slug' => 'kegiatan-nasional-fpai',
            'excerpt' => 'Ringkasan kegiatan nasional.',
            'content' => "Paragraf pertama.\n\nParagraf kedua.",
            'published_at' => now()->subMinute(),
            'is_published' => true,
        ]);

        $this->get(route('news.index'))
            ->assertOk()
            ->assertSee($article->title);

        $this->get(route('news.show', $article))
            ->assertOk()
            ->assertSee($article->title)
            ->assertSee('Paragraf pertama.');
    }

    public function test_draft_and_future_news_are_not_publicly_accessible(): void
    {
        $draft = NewsArticle::query()->create([
            'title' => 'Berita Draf', 'slug' => 'berita-draf', 'excerpt' => 'Draf',
            'content' => 'Isi draf', 'published_at' => now(), 'is_published' => false,
        ]);
        $future = NewsArticle::query()->create([
            'title' => 'Berita Terjadwal', 'slug' => 'berita-terjadwal', 'excerpt' => 'Terjadwal',
            'content' => 'Isi terjadwal', 'published_at' => now()->addDay(), 'is_published' => true,
        ]);

        $this->get(route('news.index'))->assertOk()->assertDontSee($draft->title)->assertDontSee($future->title);
        $this->get(route('news.show', $draft))->assertNotFound();
        $this->get(route('news.show', $future))->assertNotFound();
    }

    public function test_selected_published_news_is_rendered_in_homepage_popup(): void
    {
        $article = NewsArticle::query()->create([
            'title' => 'Berita Popup', 'slug' => 'berita-popup', 'excerpt' => 'Ringkasan popup',
            'content' => 'Isi berita', 'published_at' => now()->subMinute(),
            'is_published' => true, 'show_in_popup' => true,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('news-popup', false)
            ->assertSee($article->title)
            ->assertSee(route('news.index'))
            ->assertSee('fpai-audio-player', false)
            ->assertSee('fpai-hymne.mp3', false)
            ->assertSee('fpai-mars.mp3', false);
    }

    public function test_authenticated_cms_user_can_publish_news_and_select_it_for_popup(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'superadmin']);
        $oldPopup = NewsArticle::query()->create([
            'title' => 'Popup Lama', 'slug' => 'popup-lama', 'excerpt' => 'Ringkasan lama',
            'content' => 'Isi lama', 'published_at' => now()->subDay(),
            'is_published' => true, 'show_in_popup' => true,
        ]);

        $this->actingAs($admin)->post(route('cms.news.store'), [
            'title' => 'Berita Baru dari CMS',
            'excerpt' => 'Ringkasan berita baru dari halaman CMS.',
            'content' => 'Isi lengkap berita baru.',
            'published_at' => now()->format('Y-m-d H:i:s'),
            'is_published' => '1',
            'show_in_popup' => '1',
            'image' => UploadedFile::fake()->image('berita.jpg', 1200, 675),
        ])->assertRedirect(route('cms.news.index'));

        $article = NewsArticle::query()->where('title', 'Berita Baru dari CMS')->firstOrFail();
        $this->assertSame('berita-baru-dari-cms', $article->slug);
        $this->assertTrue($article->is_published);
        $this->assertTrue($article->show_in_popup);
        $this->assertFalse($oldPopup->fresh()->show_in_popup);
        Storage::disk('public')->assertExists(substr($article->image_path, 8));
    }
}
