@extends('frontend.news.layout')
@section('seo_title', e($article->title))
@section('seo_description', e($article->excerpt))
@section('og_type', 'article')
@if($article->image_path)@section('og_image', asset($article->image_path))@endif
@section('content')
<article class="news-detail">
    <header class="news-detail-header"><a href="{{ route('news.index') }}">← Kembali ke Berita</a><time datetime="{{ $article->published_at->toDateString() }}">{{ $article->published_at->locale('id')->translatedFormat('d F Y') }}</time><h1>{{ $article->title }}</h1><p>{{ $article->excerpt }}</p></header>
    @if($article->image_path)<figure><img src="{{ asset($article->image_path) }}" alt="{{ $article->image_alt ?: $article->title }}"></figure>@endif
    <div class="news-prose">{!! nl2br(e($article->content)) !!}</div>
</article>
@if($otherArticles->isNotEmpty())<section class="related-news"><div class="related-heading"><span>Berita lainnya</span><a href="{{ route('news.index') }}">Lihat semua →</a></div><div class="news-grid">@foreach($otherArticles as $other)<article class="news-card"><a class="news-card-image" href="{{ route('news.show', $other) }}">@if($other->image_path)<img src="{{ asset($other->image_path) }}" alt="{{ $other->image_alt ?: $other->title }}" loading="lazy">@endif</a><div class="news-card-body"><time>{{ $other->published_at->locale('id')->translatedFormat('d F Y') }}</time><h2><a href="{{ route('news.show', $other) }}">{{ $other->title }}</a></h2><a class="news-read-more" href="{{ route('news.show', $other) }}">Baca selengkapnya →</a></div></article>@endforeach</div></section>@endif
@endsection
