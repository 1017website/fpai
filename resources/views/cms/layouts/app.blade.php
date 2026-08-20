<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>@yield('title', 'CMS') — FPAI</title>
    <link rel="icon" href="{{ asset('assets/logo.webp') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/cms.css') }}?v={{ filemtime(public_path('css/cms.css')) }}">
</head>
<body>
<div class="cms-shell">
    <aside class="sidebar">
        <a class="sidebar-brand" href="{{ route('cms.dashboard') }}"><img src="{{ asset('assets/logo.webp') }}" alt="FPAI"><span><strong>FPAI</strong><small>Content Management</small></span></a>
        <nav>
            <a class="{{ request()->routeIs('cms.dashboard') ? 'active' : '' }}" href="{{ route('cms.dashboard') }}"><span class="nav-icon">⌂</span>Dashboard</a>
            <a class="{{ request()->routeIs('cms.pages.*') ? 'active' : '' }}" href="{{ route('cms.pages.index') }}"><span class="nav-icon">▣</span>Halaman Frontend</a>
            <a class="{{ request()->routeIs('cms.news.*') ? 'active' : '' }}" href="{{ route('cms.news.index') }}"><span class="nav-icon">◆</span>Berita</a>
            <a class="{{ request()->routeIs('cms.settings.*') ? 'active' : '' }}" href="{{ route('cms.settings.edit') }}"><span class="nav-icon">⚙</span>Pengaturan & SEO</a>
            <a class="{{ request()->routeIs('cms.analytics') ? 'active' : '' }}" href="{{ route('cms.analytics') }}"><span class="nav-icon">↗</span>Analitik</a>
            <a class="{{ request()->routeIs('cms.profile.*') ? 'active' : '' }}" href="{{ route('cms.profile.password.edit') }}"><span class="nav-icon">●</span>Ganti Password</a>
            @if(auth()->user()->isSuperadmin())<a class="{{ request()->routeIs('cms.users.*') ? 'active' : '' }}" href="{{ route('cms.users.index') }}"><span class="nav-icon">♙</span>Pengguna</a>@endif
            @if(auth()->user()->canRunDeveloperTools())<a class="{{ request()->routeIs('cms.tools.*') ? 'active' : '' }}" href="{{ route('cms.tools.index') }}"><span class="nav-icon">⌘</span>Developer Tools</a>@endif
        </nav>
        <div class="sidebar-user"><b>{{ auth()->user()->name }}</b><span>{{ ucfirst(auth()->user()->role) }}</span><form method="post" action="{{ route('cms.logout') }}">@csrf<button type="submit">Keluar</button></form></div>
    </aside>
    <button class="sidebar-overlay" type="button" aria-label="Tutup menu"></button>
    <main class="cms-main">
        <header class="topbar"><div class="topbar-title"><button class="sidebar-toggle" type="button" aria-label="Buka menu" aria-expanded="false">☰</button><h1>@yield('title', 'CMS')</h1></div><a href="{{ route('home') }}" target="_blank">Lihat Website ↗</a></header>
        <div class="content">
            @if(session('success'))<div class="flash flash-success">{{ session('success') }}</div>@endif
            @if(session('error'))<div class="flash flash-error">{{ session('error') }}</div>@endif
            @if($errors->any())<div class="flash flash-error"><b>Periksa kembali data berikut:</b><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            @yield('content')
        </div>
    </main>
</div>
<script>
const sidebarToggle=document.querySelector('.sidebar-toggle');const sidebarOverlay=document.querySelector('.sidebar-overlay');
const closeSidebar=()=>{document.body.classList.remove('sidebar-open');sidebarToggle?.setAttribute('aria-expanded','false')};
sidebarToggle?.addEventListener('click',()=>{const open=document.body.classList.toggle('sidebar-open');sidebarToggle.setAttribute('aria-expanded',String(open))});
sidebarOverlay?.addEventListener('click',closeSidebar);document.addEventListener('keydown',event=>{if(event.key==='Escape')closeSidebar()});
</script>
</body>
</html>
