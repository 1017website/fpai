@extends('cms.layouts.app')
@section('title', 'Halaman Frontend')
@section('content')
<section class="card"><div class="card-header"><div><h2>Konten Institutional Profile</h2><small>Ganti gambar halaman untuk mengubah isi visual tanpa mengubah desain asli.</small></div><a class="btn btn-primary" href="{{ route('cms.pages.create') }}">+ Tambah Halaman</a></div><div class="table-wrap"><table class="table"><thead><tr><th>No.</th><th>Preview</th><th>Label</th><th>Navigasi</th><th>Tema</th><th>Status</th><th></th></tr></thead><tbody>
@foreach($pages as $page)<tr><td><b>{{ str_pad((string) $page->page_number, 2, '0', STR_PAD_LEFT) }}</b><br><small>Urutan {{ $page->position }}</small></td><td><img class="thumb" src="{{ asset($page->image_path) }}" alt=""></td><td><b>{{ $page->label }}</b><br><small>#{{ $page->slug }}</small></td><td>@if($page->show_in_navigation)<span class="badge badge-gold">{{ $page->navigation_label ?: $page->label }}</span>@else<span class="badge badge-gray">Tidak tampil</span>@endif</td><td>{{ ucfirst($page->theme) }}</td><td><span class="badge {{ $page->is_active ? 'badge-green' : 'badge-gray' }}">{{ $page->is_active ? 'Aktif' : 'Nonaktif' }}</span></td><td><a class="btn btn-secondary btn-small" href="{{ route('cms.pages.edit', $page) }}">Edit</a></td></tr>@endforeach
</tbody></table></div></section>
@endsection
