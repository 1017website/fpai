@extends('cms.layouts.app')
@section('title', 'Edit Berita')
@section('content')
<form method="post" action="{{ route('cms.news.update', $article) }}" enctype="multipart/form-data">@csrf @method('put')
<section class="card"><div class="card-header"><div><h2>{{ $article->title }}</h2><small>Perbarui isi, gambar, tanggal, atau status berita.</small></div><a class="btn btn-secondary btn-small" href="{{ route('cms.news.index') }}">Kembali</a></div><div class="card-body">
@include('cms.news._form', ['article' => $article])
<div class="form-footer"><a class="btn btn-secondary" href="{{ route('cms.news.index') }}">Batal</a><button class="btn btn-primary" type="submit">Simpan Perubahan</button></div>
</div></section></form>
@endsection
