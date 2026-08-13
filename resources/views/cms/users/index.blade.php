@extends('cms.layouts.app')
@section('title', 'Pengguna CMS')
@section('content')
<section class="card"><div class="card-header"><div><h2>Akun & Hak Akses</h2><small>Superadmin mengelola pengguna; developer dapat mengakses alat server.</small></div><a class="btn btn-primary" href="{{ route('cms.users.create') }}">Tambah Pengguna</a></div><div class="table-wrap"><table class="table"><thead><tr><th>Nama</th><th>Email</th><th>Role</th><th>Diperbarui</th><th></th></tr></thead><tbody>
@foreach($users as $user)<tr><td><b>{{ $user->name }}</b></td><td>{{ $user->email }}</td><td><span class="badge {{ $user->role==='superadmin' ? 'badge-gold' : 'badge-gray' }}">{{ ucfirst($user->role) }}</span></td><td>{{ $user->updated_at->format('d M Y') }}</td><td><div class="actions"><a class="btn btn-secondary btn-small" href="{{ route('cms.users.edit',$user) }}">Edit</a>@unless(auth()->user()->is($user))<form method="post" action="{{ route('cms.users.destroy',$user) }}" onsubmit="return confirm('Hapus pengguna ini?')">@csrf @method('delete')<button class="btn btn-danger btn-small" type="submit">Hapus</button></form>@endunless</div></td></tr>@endforeach
</tbody></table></div></section>
@endsection
