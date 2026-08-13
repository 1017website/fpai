# FPAI Website & CMS

Website Institutional Profile FPAI berbasis Laravel 12. Frontend menggunakan template asli dari `D:\Project\Assets 2026\Fpai\final design\assets`; HTML, gaya visual, navigasi, animasi, dan 40 gambar WebP dipertahankan, lalu datanya dihubungkan ke CMS.

## Fitur

- Pengelolaan halaman frontend: tambah halaman baru, ganti gambar, label, slug, urutan, tema, menu navigasi, status aktif, dan alt text.
- Popup pembaca gambar dengan zoom, navigasi halaman, serta dukungan keyboard dan layar sentuh.
- Pengaturan identitas situs, logo, footer, SEO, Open Graph, canonical, robots, dan Google verification.
- Integrasi Google Analytics, Google Ads, serta Meta Pixel melalui ID di CMS.
- Analitik internal: page views, pengunjung unik berbasis sesi, tren harian, referrer, browser, dan bagian paling sering dilihat.
- Role `superadmin` dan `developer`.
- Setiap pengguna dapat mengganti kata sandinya sendiri dari menu **Ganti Password**.
- Developer Tools untuk `migrate`, `optimize:clear`, dan `storage:link` dengan daftar perintah tetap dan proteksi login/CSRF.

## Akses

- Website: `/`
- CMS: `/cms`

Akun awal setelah seeding:

| Role | Email | Kata sandi |
| --- | --- | --- |
| Superadmin | `superadmin@fpai.or.id` | `FpaiSuperadmin!2026` |
| Developer | `developer@fpai.or.id` | `FpaiDeveloper!2026` |

Ganti kata sandi awal melalui menu **Pengguna** setelah login. Nilai akun awal bisa diubah lewat variabel `CMS_SUPERADMIN_*` dan `CMS_DEVELOPER_*` sebelum menjalankan seeder.

## Menambah halaman frontend

Buka **CMS → Halaman Frontend → Tambah Halaman**, kemudian isi label, ID tautan, urutan, tema, alt text, dan unggah gambar. Halaman lama akan menyesuaikan urutannya secara otomatis. Gunakan ukuran dan rasio gambar yang sama dengan template agar tampilan tetap konsisten.

## Menjalankan proyek

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Pastikan konfigurasi database di `.env` sudah benar. Untuk pengembangan asset frontend Laravel, jalankan `npm install` lalu `npm run dev`; halaman website dan CMS saat ini memakai asset statis sehingga tetap dapat dibuka tanpa Vite.

## Deployment

Sesudah mengunggah kode dan mengatur `.env` produksi:

```bash
php artisan migrate --force
php artisan storage:link
php artisan optimize
```

Pastikan folder `storage` dan `bootstrap/cache` dapat ditulis oleh web server. Atur `APP_URL`, ubah kredensial awal, dan isi konfigurasi Analytics/Ads dari menu **Pengaturan & SEO**.

## Pengujian

```bash
php artisan test
```
