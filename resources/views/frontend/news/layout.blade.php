@php
    $value = fn (string $key, ?string $default = null) => filled($settings[$key] ?? null) ? $settings[$key] : $default;
    $logo = asset($value('logo', 'assets/logo.webp'));
@endphp
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('seo_title', 'Berita') — {{ $value('site_name', 'FPAI') }}</title>
    <meta name="description" content="@yield('seo_description', 'Berita dan informasi terbaru Forum Pengayom Advokat Indonesia.')">
    <meta name="robots" content="{{ $value('robots', 'index, follow') }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('seo_title', 'Berita') — {{ $value('site_name', 'FPAI') }}">
    <meta property="og:description" content="@yield('seo_description', 'Berita dan informasi terbaru Forum Pengayom Advokat Indonesia.')">
    @hasSection('og_image')<meta property="og:image" content="@yield('og_image')">@endif
    <link rel="icon" href="{{ $logo }}">
    <link rel="stylesheet" href="{{ asset('css/frontend.css') }}">
</head>
<body class="news-page">
<header class="site-header news-header">
    <a class="brand" href="{{ route('home') }}"><img src="{{ $logo }}" alt="Logo {{ $value('site_name', 'FPAI') }}"><div><strong>{{ $value('site_name', 'FPAI') }}</strong><small>{{ $value('organization_name', 'Forum Pengayom Advokat Indonesia') }}</small></div></a>
    <button class="menu-btn" type="button" aria-label="Menu" aria-expanded="false">☰</button>
    <nav class="site-nav"><a href="{{ route('home') }}">Profil</a><a class="active news-nav-link" href="{{ route('news.index') }}">Berita</a></nav>
</header>
<main class="news-main">@yield('content')</main>
<footer class="site-footer"><img src="{{ $logo }}" alt="{{ $value('site_name', 'FPAI') }}"><h3>{{ $value('organization_name', 'Forum Pengayom Advokat Indonesia') }}</h3><p>{{ $value('tagline', 'Menyatukan · Mengayomi · Menguatkan') }}</p></footer>
@include('frontend.partials.audio-player')
<script>const menuButton=document.querySelector('.menu-btn');const siteNav=document.querySelector('.site-nav');menuButton?.addEventListener('click',()=>{const open=siteNav.classList.toggle('open');menuButton.setAttribute('aria-expanded',String(open))});</script>
</body>
</html>
