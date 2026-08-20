@extends('cms.layouts.app')
@section('title', 'Berita')
@section('content')
<section class="card">
    <div class="card-header"><div><h2>Daftar Berita</h2><small>Kelola berita yang tampil pada website dan tentukan satu berita untuk popup.</small></div><a class="btn btn-primary" href="{{ route('cms.news.create') }}">+ Tambah Berita</a></div>
    <div class="table-wrap"><table class="table"><thead><tr><th>Gambar</th><th>Judul</th><th>Tanggal</th><th>Status</th><th>Popup</th><th></th></tr></thead><tbody>
    @forelse($articles as $article)
        <tr>
            <td>@if($article->image_path)<img class="thumb" src="{{ asset($article->image_path) }}" alt="">@else<span class="badge badge-gray">Tanpa gambar</span>@endif</td>
            <td><b>{{ $article->title }}</b><br><small>/berita/{{ $article->slug }}</small></td>
            <td>{{ $article->published_at?->format('d/m/Y H:i') ?: 'Belum diatur' }}</td>
            @php($isLive = $article->is_published && $article->published_at?->isPast())
            <td><span class="badge {{ $isLive ? 'badge-green' : 'badge-gray' }}">{{ $isLive ? 'Terbit' : ($article->is_published ? 'Terjadwal' : 'Draf') }}</span></td>
            <td>@if($article->show_in_popup && $article->is_published)<span class="badge badge-gold">{{ $isLive ? 'Aktif' : 'Terjadwal' }}</span>@else<span class="badge badge-gray">Tidak</span>@endif</td>
            <td><div class="actions">@if($isLive)<a class="btn btn-secondary btn-small" href="{{ route('news.show', $article) }}" target="_blank">Lihat</a>@endif<a class="btn btn-secondary btn-small" href="{{ route('cms.news.edit', $article) }}">Edit</a><form method="post" action="{{ route('cms.news.destroy', $article) }}" onsubmit="return confirm('Hapus berita ini secara permanen?')">@csrf @method('delete')<button class="btn btn-danger btn-small" type="submit">Hapus</button></form></div></td>
        </tr>
    @empty
        <tr><td colspan="6" class="empty">Belum ada berita. Klik “Tambah Berita” untuk membuat berita pertama.</td></tr>
    @endforelse
    </tbody></table></div>
    @if($articles->hasPages())<div class="cms-pagination">@if($articles->onFirstPage())<span>← Sebelumnya</span>@else<a href="{{ $articles->previousPageUrl() }}">← Sebelumnya</a>@endif <b>Halaman {{ $articles->currentPage() }} dari {{ $articles->lastPage() }}</b> @if($articles->hasMorePages())<a href="{{ $articles->nextPageUrl() }}">Berikutnya →</a>@else<span>Berikutnya →</span>@endif</div>@endif
</section>
@endsection
