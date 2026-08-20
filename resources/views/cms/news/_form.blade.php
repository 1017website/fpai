<div class="field-grid">
    <div class="field field-full"><label for="title">Judul berita</label><input id="title" name="title" value="{{ old('title', $article?->title) }}" maxlength="255" required></div>
    <div class="field field-full"><label for="slug">Slug URL</label><input id="slug" name="slug" value="{{ old('slug', $article?->slug) }}" maxlength="255" placeholder="Otomatis dari judul jika dikosongkan"><small>Gunakan huruf, angka, tanda hubung, atau garis bawah tanpa spasi.</small></div>
    <div class="field field-full"><label for="excerpt">Ringkasan</label><textarea id="excerpt" name="excerpt" maxlength="500" required>{{ old('excerpt', $article?->excerpt) }}</textarea><small>Maksimal 500 karakter. Tampil pada daftar berita dan popup.</small></div>
    <div class="field field-full"><label for="content">Isi berita</label><textarea id="content" name="content" class="content-editor" required>{{ old('content', $article?->content) }}</textarea><small>Pisahkan paragraf dengan baris kosong.</small></div>
    <div class="field"><label for="published_at">Tanggal dan waktu terbit</label><input id="published_at" name="published_at" type="datetime-local" value="{{ old('published_at', $article?->published_at?->format('Y-m-d\TH:i')) }}"><small>Jika kosong dan status diterbitkan, waktu saat ini akan digunakan.</small></div>
    <div class="field"><label for="image">{{ $article ? 'Ganti gambar utama' : 'Gambar utama' }}</label><input id="image" name="image" type="file" accept=".webp,.jpg,.jpeg,.png" @required(!$article)><small>WebP, JPG, atau PNG; maksimal 10 MB. Rasio rekomendasi 16:9.</small></div>
    <div class="field field-full"><label for="image_alt">Teks alternatif gambar</label><input id="image_alt" name="image_alt" value="{{ old('image_alt', $article?->image_alt) }}" maxlength="255" placeholder="Jelaskan isi gambar untuk aksesibilitas"></div>
    @if($article?->image_path)<div class="field field-full"><img class="news-form-preview" src="{{ asset($article->image_path) }}" alt="Preview gambar berita"></div>@endif
    <label class="check"><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $article?->is_published ?? true))> Terbitkan di website</label>
    <label class="check"><input type="checkbox" name="show_in_popup" value="1" @checked(old('show_in_popup', $article?->show_in_popup))> Tampilkan sebagai popup saat website dibuka</label>
    <div class="hint field-full">Hanya satu berita yang dapat aktif sebagai popup. Mengaktifkan opsi ini akan menonaktifkan popup pada berita lain.</div>
</div>
