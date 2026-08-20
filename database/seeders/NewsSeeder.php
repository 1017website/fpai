<?php

namespace Database\Seeders;

use App\Models\NewsArticle;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        if (NewsArticle::query()->exists()) {
            return;
        }

        NewsArticle::query()->create([
            'title' => 'Selamat Datang di Website Resmi FPAI',
            'slug' => 'selamat-datang-di-website-resmi-fpai',
            'excerpt' => 'Website resmi Forum Pengayom Advokat Indonesia hadir sebagai pusat informasi organisasi, program kerja, kegiatan, dan perkembangan terbaru FPAI.',
            'content' => <<<'TEXT'
Forum Pengayom Advokat Indonesia (FPAI) dengan bangga menghadirkan website resmi sebagai pusat informasi mengenai profil, nilai, program kerja, dan kegiatan organisasi.

Kehadiran website ini merupakan bagian dari komitmen FPAI untuk membangun komunikasi yang terbuka, mudah diakses, dan relevan bagi para advokat, pemangku kepentingan, serta masyarakat luas.

Melalui kanal Berita, FPAI akan menyampaikan pembaruan kegiatan, agenda organisasi, informasi kelembagaan, dan berbagai inisiatif yang mendukung penguatan profesi advokat di Indonesia.

Kami mengundang seluruh pengunjung untuk mengenal FPAI lebih dekat, mengikuti perkembangan organisasi, dan bersama-sama mewujudkan semangat Menyatukan, Mengayomi, dan Menguatkan.
TEXT,
            'image_path' => 'assets/page-01.webp',
            'image_alt' => 'Sampul profil resmi Forum Pengayom Advokat Indonesia',
            'published_at' => now(),
            'is_published' => true,
            'show_in_popup' => true,
        ]);
    }
}
