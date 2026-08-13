@extends('cms.layouts.app')
@section('title', 'Edit Halaman '.str_pad((string) $page->page_number, 2, '0', STR_PAD_LEFT))
@section('content')
<form method="post" action="{{ route('cms.pages.update', $page) }}" enctype="multipart/form-data">@csrf @method('put')
<section class="card"><div class="card-header"><div><h2>{{ $page->label }}</h2><small>Halaman {{ $page->page_number }} dari template asli</small></div><a class="btn btn-secondary btn-small" href="{{ route('cms.pages.index') }}">Kembali</a></div><div class="card-body"><div class="field-grid">
<div class="field"><label for="label">Label halaman</label><input id="label" name="label" value="{{ old('label', $page->label) }}" required><small>Muncul pada penanda vertikal di frontend.</small></div>
<div class="field"><label for="slug">ID tautan</label><input id="slug" name="slug" value="{{ old('slug', $page->slug) }}" required><small>Huruf, angka, tanda hubung/garis bawah; tanpa spasi.</small></div>
<div class="field"><label for="position">Urutan tampil</label><input id="position" name="position" type="number" min="1" max="255" value="{{ old('position', $page->position) }}" required></div>
<div class="field"><label for="theme">Latar halaman</label><select id="theme" name="theme"><option value="dark" @selected(old('theme',$page->theme)==='dark')>Gelap</option><option value="light" @selected(old('theme',$page->theme)==='light')>Terang</option></select></div>
<div class="field field-full"><label for="alt_text">Teks alternatif gambar (SEO & aksesibilitas)</label><input id="alt_text" name="alt_text" value="{{ old('alt_text', $page->alt_text) }}" required></div>
<div class="field"><label for="navigation_label">Label menu navigasi</label><input id="navigation_label" name="navigation_label" value="{{ old('navigation_label', $page->navigation_label) }}"><small>Biarkan kosong untuk memakai label halaman.</small></div>
<div class="field"><label for="image">Ganti gambar halaman</label><input id="image" name="image" type="file" accept=".webp,.jpg,.jpeg,.png"><small>Maksimal 20 MB. Disarankan dimensi sama dengan gambar asli.</small></div>
<div class="field field-full"><img class="thumb" style="width:260px;height:165px" src="{{ asset($page->image_path) }}" alt="Preview {{ $page->label }}"></div>
<label class="check"><input type="checkbox" name="show_in_navigation" value="1" @checked(old('show_in_navigation',$page->show_in_navigation))> Tampilkan di menu navigasi</label>
<label class="check"><input type="checkbox" name="is_active" value="1" @checked(old('is_active',$page->is_active))> Tampilkan halaman di website</label>
</div><div class="form-footer"><a class="btn btn-secondary" href="{{ route('cms.pages.index') }}">Batal</a><button class="btn btn-primary" type="submit">Simpan Perubahan</button></div></div></section></form>
@endsection
