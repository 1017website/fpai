@extends('cms.layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="stat-grid">
    <div class="stat-card"><span>Pengunjung hari ini</span><strong>{{ number_format($stats['today']) }}</strong></div>
    <div class="stat-card"><span>7 hari terakhir</span><strong>{{ number_format($stats['week']) }}</strong></div>
    <div class="stat-card"><span>30 hari terakhir</span><strong>{{ number_format($stats['month']) }}</strong></div>
    <div class="stat-card"><span>Halaman aktif</span><strong>{{ $stats['pages'] }}</strong></div>
</div>
<div class="two-col">
    <section class="card"><div class="card-header"><h2>Akses cepat</h2></div><div class="card-body"><div class="actions"><a class="btn btn-primary" href="{{ route('cms.pages.index') }}">Kelola 40 Halaman</a><a class="btn btn-secondary" href="{{ route('cms.settings.edit') }}">SEO & Tracking</a></div></div></section>
    <section class="card"><div class="card-header"><h2>Ringkasan sistem</h2></div><div class="card-body"><p><b>{{ $stats['users'] }}</b> akun CMS tersedia.</p><p>Semua konten utama frontend dikelola melalui menu <b>Halaman Frontend</b> dan <b>Pengaturan & SEO</b>.</p></div></section>
</div>
<section class="card"><div class="card-header"><h2>Kunjungan terbaru</h2><a class="btn btn-secondary btn-small" href="{{ route('cms.analytics') }}">Analitik lengkap</a></div><div class="table-wrap"><table class="table"><thead><tr><th>Waktu</th><th>Halaman</th><th>Sumber</th></tr></thead><tbody>@forelse($recent as $event)<tr><td>{{ $event->visited_at->format('d M Y, H:i') }}</td><td>{{ $event->path }}</td><td>{{ $event->referrer ? (parse_url($event->referrer, PHP_URL_HOST) ?: 'Lainnya') : 'Langsung' }}</td></tr>@empty<tr><td colspan="3" class="empty">Belum ada data kunjungan.</td></tr>@endforelse</tbody></table></div></section>
@endsection
