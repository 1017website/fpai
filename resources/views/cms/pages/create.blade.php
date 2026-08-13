@extends('cms.layouts.app')
@section('title', 'Tambah Halaman Frontend')
@section('content')
<form method="post" action="{{ route('cms.pages.store') }}" enctype="multipart/form-data">@csrf
<section class="card"><div class="card-header"><div><h2>Halaman Baru</h2><small>Unggah satu gambar halaman dengan rasio dan dimensi yang sama seperti halaman template.</small></div><a class="btn btn-secondary btn-small" href="{{ route('cms.pages.index') }}">Kembali</a></div><div class="card-body"><div class="field-grid">
<div class="field"><label for="label">Label halaman</label><input id="label" name="label" value="{{ old('label') }}" placeholder="Contoh: Kegiatan Nasional" required><small>Muncul pada penanda vertikal di frontend.</small></div>
<div class="field"><label for="slug">ID tautan</label><input id="slug" name="slug" value="{{ old('slug') }}" placeholder="contoh-kegiatan" required><small>Huruf, angka, tanda hubung/garis bawah; tanpa spasi.</small></div>
<div class="field"><label for="position">Urutan tampil</label><input id="position" name="position" type="number" min="1" max="255" value="{{ old('position', $nextPosition) }}" required><small>Halaman lain akan menyesuaikan urutannya secara otomatis.</small></div>
<div class="field"><label for="theme">Latar halaman</label><select id="theme" name="theme"><option value="dark" @selected(old('theme','dark')==='dark')>Gelap</option><option value="light" @selected(old('theme')==='light')>Terang</option></select></div>
<div class="field field-full"><label for="alt_text">Teks alternatif gambar (SEO & aksesibilitas)</label><input id="alt_text" name="alt_text" value="{{ old('alt_text') }}" placeholder="Jelaskan isi utama gambar" required></div>
<div class="field"><label for="navigation_label">Label menu navigasi</label><input id="navigation_label" name="navigation_label" value="{{ old('navigation_label') }}"><small>Biarkan kosong jika halaman tidak perlu masuk menu utama.</small></div>
<div class="field"><label for="image">Gambar halaman</label><input id="image" name="image" type="file" accept=".webp,.jpg,.jpeg,.png" required><small>Wajib. Maksimal 20 MB; format WebP, JPG, atau PNG.</small></div>
<label class="check"><input type="checkbox" name="show_in_navigation" value="1" @checked(old('show_in_navigation'))> Tampilkan di menu navigasi</label>
<label class="check"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))> Langsung tampilkan di website</label>
</div><div class="form-footer"><a class="btn btn-secondary" href="{{ route('cms.pages.index') }}">Batal</a><button class="btn btn-primary" type="submit">Tambah Halaman</button></div></div></section></form>
@endsection
