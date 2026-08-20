@extends('frontend.news.layout')
@section('seo_title', 'Berita')
@section('content')
<section class="news-hero"><div><span>FPAI Update</span><h1>Berita & Informasi</h1><p>Ikuti kegiatan, perkembangan, dan informasi terbaru dari Forum Pengayom Advokat Indonesia.</p></div></section>
<section class="news-listing">
    <div class="news-grid">
        @forelse($articles as $article)
            <article class="news-card">
                <a class="news-card-image" href="{{ route('news.show', $article) }}">@if($article->image_path)<img src="{{ asset($article->image_path) }}" alt="{{ $article->image_alt ?: $article->title }}" loading="lazy">@endif</a>
                <div class="news-card-body"><time datetime="{{ $article->published_at->toDateString() }}">{{ $article->published_at->locale('id')->translatedFormat('d F Y') }}</time><h2><a href="{{ route('news.show', $article) }}">{{ $article->title }}</a></h2><p>{{ $article->excerpt }}</p><a class="news-read-more" href="{{ route('news.show', $article) }}">Baca selengkapnya <span>→</span></a></div>
            </article>
        @empty
            <div class="news-empty"><h2>Belum ada berita</h2><p>Informasi terbaru akan segera kami hadirkan di halaman ini.</p></div>
        @endforelse
    </div>
    @if($articles->hasPages())<nav class="news-pagination" aria-label="Navigasi halaman">@if($articles->onFirstPage())<span>← Sebelumnya</span>@else<a href="{{ $articles->previousPageUrl() }}">← Sebelumnya</a>@endif <b>Halaman {{ $articles->currentPage() }} dari {{ $articles->lastPage() }}</b> @if($articles->hasMorePages())<a href="{{ $articles->nextPageUrl() }}">Berikutnya →</a>@else<span>Berikutnya →</span>@endif</nav>@endif
</section>
@endsection
