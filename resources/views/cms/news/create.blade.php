@extends('cms.layouts.app')
@section('title', 'Tambah Berita')
@section('content')
<form method="post" action="{{ route('cms.news.store') }}" enctype="multipart/form-data">@csrf
<section class="card"><div class="card-header"><div><h2>Berita Baru</h2><small>Isi informasi berita, unggah gambar, lalu pilih status publikasinya.</small></div><a class="btn btn-secondary btn-small" href="{{ route('cms.news.index') }}">Kembali</a></div><div class="card-body">
@include('cms.news._form', ['article' => null])
<div class="form-footer"><a class="btn btn-secondary" href="{{ route('cms.news.index') }}">Batal</a><button class="btn btn-primary" type="submit">Simpan Berita</button></div>
</div></section></form>
@endsection
