<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>Masuk CMS — FPAI</title><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet"><link rel="stylesheet" href="{{ asset('css/cms.css') }}"></head>
<body><main class="login-page"><section class="login-card"><div class="login-brand"><img src="{{ asset('assets/logo.webp') }}" alt="FPAI"><h1>CMS FPAI</h1><p>Kelola seluruh konten website</p></div>
@if($errors->any())<div class="flash flash-error">{{ $errors->first() }}</div>@endif
<form method="post" action="{{ route('cms.login.submit') }}">@csrf
<div class="field"><label for="email">Email</label><input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required autofocus></div>
<div class="field"><label for="password">Kata sandi</label><input id="password" name="password" type="password" autocomplete="current-password" required></div>
<label class="check"><input type="checkbox" name="remember" value="1"> Ingat saya</label><button class="btn btn-primary" type="submit">Masuk ke CMS</button></form></section></main></body></html>
