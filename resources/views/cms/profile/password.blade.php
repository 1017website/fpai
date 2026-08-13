@extends('cms.layouts.app')
@section('title', 'Ganti Password')
@section('content')
<section class="card form-card"><div class="card-header"><div><h2>Keamanan Akun</h2><small>Ganti kata sandi untuk akun {{ auth()->user()->email }}.</small></div></div><div class="card-body">
<form method="post" action="{{ route('cms.profile.password.update') }}">@csrf @method('put')
<div class="field-grid single-column">
<div class="field"><label for="current_password">Kata sandi saat ini</label><input id="current_password" name="current_password" type="password" autocomplete="current-password" required></div>
<div class="field"><label for="password">Kata sandi baru</label><input id="password" name="password" type="password" autocomplete="new-password" required><small>Minimal 10 karakter, berisi huruf besar, huruf kecil, dan angka.</small></div>
<div class="field"><label for="password_confirmation">Ulangi kata sandi baru</label><input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required></div>
</div><div class="form-footer"><button class="btn btn-primary" type="submit">Ganti Password</button></div></form>
</div></section>
@endsection
