@extends('cms.layouts.app')
@section('title', $user->exists ? 'Edit Pengguna' : 'Tambah Pengguna')
@section('content')
<form method="post" action="{{ $user->exists ? route('cms.users.update',$user) : route('cms.users.store') }}">@csrf @if($user->exists)@method('put')@endif
<section class="card"><div class="card-header"><h2>{{ $user->exists ? $user->name : 'Akun baru' }}</h2><a class="btn btn-secondary btn-small" href="{{ route('cms.users.index') }}">Kembali</a></div><div class="card-body"><div class="field-grid">
<div class="field"><label for="name">Nama</label><input id="name" name="name" value="{{ old('name',$user->name) }}" required></div><div class="field"><label for="email">Email</label><input id="email" name="email" type="email" value="{{ old('email',$user->email) }}" required></div>
<div class="field"><label for="role">Role</label><select id="role" name="role"><option value="superadmin" @selected(old('role',$user->role)==='superadmin')>Superadmin</option><option value="developer" @selected(old('role',$user->role)==='developer')>Developer</option></select><small>Developer juga dapat menjalankan migrate, optimize:clear, dan storage:link.</small></div><div></div>
<div class="field"><label for="password">Kata sandi {{ $user->exists ? 'baru (opsional)' : '' }}</label><input id="password" name="password" type="password" {{ $user->exists ? '' : 'required' }}><small>Minimal 10 karakter.</small></div><div class="field"><label for="password_confirmation">Ulangi kata sandi</label><input id="password_confirmation" name="password_confirmation" type="password" {{ $user->exists ? '' : 'required' }}></div>
</div><div class="form-footer"><a class="btn btn-secondary" href="{{ route('cms.users.index') }}">Batal</a><button class="btn btn-primary" type="submit">Simpan Pengguna</button></div></div></section></form>
@endsection
