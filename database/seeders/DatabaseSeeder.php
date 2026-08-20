<?php

namespace Database\Seeders;

use App\Models\BrochurePage;
use App\Models\SiteSetting;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach (['superadmin', 'developer'] as $role) {
            User::query()->updateOrCreate(
                ['email' => config("cms.$role.email")],
                [
                    'name' => config("cms.$role.name"),
                    'password' => config("cms.$role.password"),
                    'role' => $role,
                    'email_verified_at' => now(),
                ]
            );
        }

        $special = [
            1 => ['beranda', 'Beranda', 'dark', true, 'Beranda'],
            4 => ['profil', 'Profil', 'light', true, 'Profil'],
            5 => ['sambutan', 'Sambutan', 'light', true, 'Sambutan'],
            6 => ['komitmen', 'Nilai & Komitmen', 'dark', true, 'Nilai & Komitmen'],
            10 => ['pendiri', 'Pendiri', 'dark', true, 'Pendiri'],
            16 => ['dewan', 'Dewan Strategis', 'light', true, 'Dewan Strategis'],
            25 => ['hymne', 'Hymne & Mars', 'dark', true, 'Hymne & Mars'],
            26 => ['arah', 'Arah Organisasi', 'light', true, 'Arah Organisasi'],
            32 => ['program', 'Program', 'dark', true, 'Program'],
            37 => ['struktur', 'Struktur', 'dark', true, 'Struktur'],
        ];
        $lightPages = [3, 4, 5, 7, 9, 16, 26, 27, 29, 31, 36, 38, 39];

        foreach (range(1, 40) as $number) {
            [$slug, $label, $theme, $showNav, $navLabel] = $special[$number] ?? [
                'page-'.str_pad((string) $number, 2, '0', STR_PAD_LEFT),
                'Halaman '.str_pad((string) $number, 2, '0', STR_PAD_LEFT),
                in_array($number, $lightPages, true) ? 'light' : 'dark',
                false,
                null,
            ];

            BrochurePage::query()->updateOrCreate(
                ['page_number' => $number],
                [
                    'position' => $number,
                    'slug' => $slug,
                    'label' => $label,
                    'image_path' => 'assets/page-'.str_pad((string) $number, 2, '0', STR_PAD_LEFT).'.webp',
                    'theme' => $theme,
                    'alt_text' => "FPAI Institutional Profile halaman $number",
                    'show_in_navigation' => $showNav,
                    'navigation_label' => $navLabel,
                    'is_active' => true,
                ]
            );
        }

        $settings = [
            ['site_name', 'FPAI', 'general', 'text', 'Nama singkat', 'Ditampilkan di header situs.', 1],
            ['organization_name', 'Forum Pengayom Advokat Indonesia', 'general', 'text', 'Nama organisasi', 'Nama lengkap pada header dan footer.', 2],
            ['tagline', 'Menyatukan · Mengayomi · Menguatkan', 'general', 'text', 'Tagline footer', null, 3],
            ['logo', 'assets/logo.webp', 'general', 'image', 'Logo', 'Format WebP, PNG, atau JPG. Logo lama tetap digunakan bila tidak mengunggah file baru.', 4],
            ['seo_title', 'FPAI — Institutional Profile 2026', 'seo', 'text', 'Judul SEO', 'Idealnya 50–60 karakter.', 10],
            ['seo_description', 'Forum Pengayom Advokat Indonesia — Institutional Profile 2026', 'seo', 'textarea', 'Deskripsi SEO', 'Idealnya 120–160 karakter.', 11],
            ['seo_keywords', 'FPAI, Forum Pengayom Advokat Indonesia, advokat Indonesia', 'seo', 'text', 'Kata kunci', 'Pisahkan dengan koma.', 12],
            ['canonical_url', null, 'seo', 'url', 'Canonical URL', 'Kosongkan untuk menggunakan alamat halaman saat ini.', 13],
            ['robots', 'index, follow', 'seo', 'text', 'Meta robots', 'Contoh: index, follow.', 14],
            ['google_site_verification', null, 'seo', 'text', 'Google Site Verification', 'Isi nilai token verifikasi, bukan seluruh tag HTML.', 15],
            ['og_image', null, 'seo', 'image', 'Gambar Open Graph', 'Gambar saat tautan dibagikan. Rekomendasi 1200×630 px.', 16],
            ['ga_measurement_id', null, 'analytics', 'text', 'Google Analytics Measurement ID', 'Contoh: G-XXXXXXXXXX.', 20],
            ['google_ads_id', null, 'analytics', 'text', 'Google Ads ID', 'Contoh: AW-123456789.', 21],
            ['google_ads_conversion_label', null, 'analytics', 'text', 'Google Ads Conversion Label', 'Opsional, digunakan untuk event konversi.', 22],
            ['meta_pixel_id', null, 'analytics', 'text', 'Meta Pixel ID', 'ID numerik dari Meta Events Manager.', 23],
        ];

        foreach ($settings as [$key, $value, $group, $type, $label, $help, $position]) {
            SiteSetting::query()->updateOrCreate(['key' => $key], [
                'value' => $value,
                'group' => $group,
                'type' => $type,
                'label' => $label,
                'help_text' => $help,
                'position' => $position,
            ]);
        }

        $this->call(NewsSeeder::class);
    }
}
