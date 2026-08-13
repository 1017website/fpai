@extends('cms.layouts.app')
@section('title', 'Developer Tools')
@section('content')
<div class="hint"><b>Akses terbatas.</b> Tombol di halaman ini menjalankan operasi server secara langsung. Jalankan hanya ketika diperlukan dan tunggu sampai hasilnya ditampilkan.</div>
<div class="tool-grid">
@foreach([
['migrate','Database Migration','Menjalankan semua migration database yang belum diterapkan. Aman dijalankan ulang karena Laravel hanya memproses migration baru.','Jalankan Migrate'],
['optimize:clear','Bersihkan Cache','Membersihkan cache konfigurasi, route, view, event, dan cache aplikasi setelah perubahan atau deployment.','Jalankan Optimize:Clear'],
['storage:link','Storage Link','Membuat tautan public/storage agar file gambar yang diunggah melalui CMS dapat tampil di website.','Buat Storage Link']
] as [$command,$title,$description,$button])
<section class="card tool"><h3>{{ $title }}</h3><p>{{ $description }}</p><form method="post" action="{{ route('cms.tools.run') }}" onsubmit="return confirm('Jalankan {{ $command }} sekarang?')">@csrf<input type="hidden" name="command" value="{{ $command }}"><button class="btn btn-primary" type="submit">{{ $button }}</button></form></section>
@endforeach
</div>
@endsection
