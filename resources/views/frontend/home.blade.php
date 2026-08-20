@php
    $value = fn (string $key, ?string $default = null) => filled($settings[$key] ?? null) ? $settings[$key] : $default;
    $logo = asset($value('logo', 'assets/logo.webp'));
    $seoTitle = $value('seo_title', 'FPAI — Institutional Profile 2026');
    $seoDescription = $value('seo_description', 'Forum Pengayom Advokat Indonesia — Institutional Profile 2026');
    $canonical = $value('canonical_url', url()->current());
    $tagId = $value('ga_measurement_id') ?: $value('google_ads_id');
@endphp
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="keywords" content="{{ $value('seo_keywords') }}">
    <meta name="robots" content="{{ $value('robots', 'index, follow') }}">
    <link rel="canonical" href="{{ $canonical }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url" content="{{ $canonical }}">
    @if($value('og_image'))<meta property="og:image" content="{{ asset($value('og_image')) }}">@endif
    <meta name="twitter:card" content="summary_large_image">
    @if($value('google_site_verification'))<meta name="google-site-verification" content="{{ $value('google_site_verification') }}">@endif
    <link rel="icon" href="{{ $logo }}">
    <link rel="stylesheet" href="{{ asset('css/frontend.css') }}">
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => $value('organization_name', 'Forum Pengayom Advokat Indonesia'),
        'alternateName' => $value('site_name', 'FPAI'),
        'url' => $canonical,
        'logo' => $logo,
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @if($tagId)
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $tagId }}"></script>
        <script>
            window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments)}gtag('js',new Date());
            @if($value('ga_measurement_id'))gtag('config',@json($value('ga_measurement_id')));@endif
            @if($value('google_ads_id'))gtag('config',@json($value('google_ads_id')));@endif
            @if($value('google_ads_id') && $value('google_ads_conversion_label'))window.trackGoogleAdsConversion=()=>gtag('event','conversion',{'send_to':@json($value('google_ads_id').'/'.$value('google_ads_conversion_label'))});@endif
        </script>
    @endif
    @if($value('meta_pixel_id'))
        <script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init',@json($value('meta_pixel_id')));fbq('track','PageView');</script>
    @endif
</head>
<body class="home-page loading">
<div class="preloader"><div class="loader-wrap"><img class="loader-logo" src="{{ $logo }}" alt="{{ $value('site_name', 'FPAI') }}"><div class="loader-line"></div></div></div>
<header class="site-header">
    <div class="brand"><img src="{{ $logo }}" alt="Logo {{ $value('site_name', 'FPAI') }}"><div><strong>{{ $value('site_name', 'FPAI') }}</strong><small>{{ $value('organization_name', 'Forum Pengayom Advokat Indonesia') }}</small></div></div>
    <button class="menu-btn" type="button" aria-label="Menu" aria-expanded="false">☰</button>
    <nav class="site-nav">
        @foreach($pages->where('show_in_navigation', true) as $page)
            <a href="#{{ $page->slug }}" data-target="{{ $page->slug }}">{{ $page->navigation_label ?: $page->label }}</a>
        @endforeach
        <a class="news-nav-link" href="{{ route('news.index') }}">Berita</a>
    </nav>
</header>
<div class="progress-rail"></div><div class="page-counter">01 / {{ str_pad((string) $pages->count(), 2, '0', STR_PAD_LEFT) }}</div>
<main>
    @foreach($pages as $page)
        <section id="{{ $page->slug }}" class="page-section {{ $page->theme }}-page" data-page="{{ $loop->iteration }}" data-label="{{ $page->label }}">
            <div class="page-shell">
                <div class="page-kicker"><span>{{ str_pad((string) $page->page_number, 2, '0', STR_PAD_LEFT) }}</span><b>{{ $page->label }}</b></div>
                <img loading="lazy" decoding="async" src="{{ asset($page->image_path) }}" alt="{{ $page->alt_text }}" tabindex="0" role="button" aria-label="Perbesar {{ $page->label }}" data-lightbox-index="{{ $loop->index }}">
            </div>
        </section>
    @endforeach
</main>
<footer class="site-footer"><img src="{{ $logo }}" alt="{{ $value('site_name', 'FPAI') }}"><h3>{{ $value('organization_name', 'Forum Pengayom Advokat Indonesia') }}</h3><p>{{ $value('tagline', 'Menyatukan · Mengayomi · Menguatkan') }}</p></footer>
@include('frontend.partials.audio-player')
@if($popupArticle)
<div class="news-popup" data-popup-id="{{ $popupArticle->id }}" role="dialog" aria-modal="true" aria-labelledby="news-popup-title" aria-hidden="true">
    <button class="news-popup-backdrop" type="button" aria-label="Tutup popup berita"></button>
    <article class="news-popup-card">
        <button class="news-popup-close" type="button" aria-label="Tutup">×</button>
        @if($popupArticle->image_path)<img src="{{ asset($popupArticle->image_path) }}" alt="{{ $popupArticle->image_alt ?: $popupArticle->title }}">@endif
        <div class="news-popup-content"><span>Berita terbaru</span><h2 id="news-popup-title">{{ $popupArticle->title }}</h2><p>{{ $popupArticle->excerpt }}</p><div class="news-popup-actions"><a href="{{ route('news.show', $popupArticle) }}">Baca selengkapnya</a><button type="button">Nanti saja</button></div></div>
    </article>
</div>
@endif
<div class="image-lightbox" role="dialog" aria-modal="true" aria-label="Pratinjau halaman" aria-hidden="true">
    <div class="lightbox-toolbar"><button class="lightbox-zoom-out" type="button" aria-label="Perkecil">−</button><span class="lightbox-zoom-value">100%</span><button class="lightbox-zoom-in" type="button" aria-label="Perbesar">+</button><button class="lightbox-reset" type="button" aria-label="Ukuran semula">↺</button><button class="lightbox-close" type="button" aria-label="Tutup">×</button></div>
    <div class="lightbox-stage"><img class="lightbox-image" alt=""></div>
    <button class="lightbox-nav lightbox-prev" type="button" aria-label="Halaman sebelumnya">‹</button><button class="lightbox-nav lightbox-next" type="button" aria-label="Halaman berikutnya">›</button><div class="lightbox-caption"></div>
</div>
@if($value('meta_pixel_id'))<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{ urlencode($value('meta_pixel_id')) }}&ev=PageView&noscript=1" alt=""></noscript>@endif
<script>
window.addEventListener('load',()=>{document.body.classList.remove('loading');setTimeout(()=>document.querySelector('.preloader')?.classList.add('hide'),250)});
const sections=[...document.querySelectorAll('.page-section')];const navLinks=[...document.querySelectorAll('.site-nav a[data-target]')];const counter=document.querySelector('.page-counter');const rail=document.querySelector('.progress-rail');
sections.forEach((s,i)=>{const b=document.createElement('button');b.title=`Halaman ${i+1}`;b.setAttribute('aria-label',`Ke halaman ${i+1}`);b.onclick=()=>s.scrollIntoView({behavior:'smooth'});rail.appendChild(b)});const dots=[...rail.children];let lastTracked='';
const trackSection=slug=>{if(slug===lastTracked)return;lastTracked=slug;if(typeof gtag==='function')gtag('event','section_view',{section_id:slug});if(typeof fbq==='function')fbq('trackCustom','SectionView',{section_id:slug});fetch(@json(route('analytics.section')),{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':@json(csrf_token()),'Accept':'application/json'},body:JSON.stringify({section_slug:slug}),keepalive:true}).catch(()=>{})};
const navTargets=navLinks.map(link=>{const section=document.querySelector(link.getAttribute('href'));return{link,index:sections.indexOf(section)}}).filter(target=>target.index>=0);
const updateActiveNavigation=()=>{const headerBottom=document.querySelector('.site-header')?.getBoundingClientRect().bottom||0;const current=sections.reduce((closest,section)=>{const distance=Math.abs(section.getBoundingClientRect().top-headerBottom);return distance<closest.distance?{section,distance}:closest},{section:sections[0],distance:Infinity}).section;const currentIndex=sections.indexOf(current);const active=[...navTargets].reverse().find(target=>target.index<=currentIndex)?.link;navLinks.forEach(link=>link.classList.toggle('active',link===active))};
let navigationFrame=0;const queueNavigationUpdate=()=>{if(navigationFrame)return;navigationFrame=requestAnimationFrame(()=>{navigationFrame=0;updateActiveNavigation()})};window.addEventListener('scroll',queueNavigationUpdate,{passive:true});window.addEventListener('resize',queueNavigationUpdate);window.addEventListener('hashchange',queueNavigationUpdate);window.addEventListener('load',queueNavigationUpdate);queueNavigationUpdate();
const io=new IntersectionObserver(entries=>{entries.forEach(e=>{if(e.isIntersecting){e.target.classList.add('in-view');const p=+e.target.dataset.page;counter.textContent=`${String(p).padStart(2,'0')} / ${String(sections.length).padStart(2,'0')}`;dots.forEach((d,i)=>d.classList.toggle('active',i===p-1));trackSection(e.target.id)}})},{threshold:.48});
sections.forEach(s=>io.observe(s));const menuButton=document.querySelector('.menu-btn');const siteNav=document.querySelector('.site-nav');const closeMenu=()=>{siteNav.classList.remove('open');menuButton.setAttribute('aria-expanded','false')};menuButton.onclick=()=>{const open=siteNav.classList.toggle('open');menuButton.setAttribute('aria-expanded',String(open))};siteNav.querySelectorAll('a').forEach(a=>a.addEventListener('click',closeMenu));
const pageImages=[...document.querySelectorAll('.page-shell img')];const lightbox=document.querySelector('.image-lightbox');const lightboxImage=lightbox.querySelector('.lightbox-image');const lightboxCaption=lightbox.querySelector('.lightbox-caption');const zoomValue=lightbox.querySelector('.lightbox-zoom-value');let lightboxIndex=0;let lightboxZoom=1;let lastFocused=null;
const applyZoom=()=>{lightboxImage.style.setProperty('--lightbox-zoom',lightboxZoom);zoomValue.textContent=`${Math.round(lightboxZoom*100)}%`};
const showLightboxImage=index=>{lightboxIndex=(index+pageImages.length)%pageImages.length;const source=pageImages[lightboxIndex];lightboxImage.src=source.currentSrc||source.src;lightboxImage.alt=source.alt;lightboxCaption.textContent=`${String(lightboxIndex+1).padStart(2,'0')} / ${String(pageImages.length).padStart(2,'0')} — ${source.closest('.page-section')?.dataset.label||source.alt}`;lightboxZoom=1;applyZoom();lightbox.querySelector('.lightbox-stage').scrollTo(0,0)};
const openLightbox=index=>{lastFocused=document.activeElement;showLightboxImage(index);lightbox.classList.add('open');lightbox.setAttribute('aria-hidden','false');document.body.classList.add('lightbox-open');lightbox.querySelector('.lightbox-close').focus()};
const closeLightbox=()=>{lightbox.classList.remove('open');lightbox.setAttribute('aria-hidden','true');document.body.classList.remove('lightbox-open');lastFocused?.focus()};
pageImages.forEach((image,index)=>{image.addEventListener('click',()=>openLightbox(index));image.addEventListener('keydown',event=>{if(event.key==='Enter'||event.key===' '){event.preventDefault();openLightbox(index)}})});
lightbox.querySelector('.lightbox-close').onclick=closeLightbox;lightbox.querySelector('.lightbox-prev').onclick=()=>showLightboxImage(lightboxIndex-1);lightbox.querySelector('.lightbox-next').onclick=()=>showLightboxImage(lightboxIndex+1);lightbox.querySelector('.lightbox-zoom-in').onclick=()=>{lightboxZoom=Math.min(3,lightboxZoom+.25);applyZoom()};lightbox.querySelector('.lightbox-zoom-out').onclick=()=>{lightboxZoom=Math.max(.5,lightboxZoom-.25);applyZoom()};lightbox.querySelector('.lightbox-reset').onclick=()=>{lightboxZoom=1;applyZoom()};lightbox.addEventListener('click',event=>{if(event.target===lightbox||event.target.classList.contains('lightbox-stage'))closeLightbox()});document.addEventListener('keydown',event=>{if(!lightbox.classList.contains('open'))return;if(event.key==='Escape')closeLightbox();if(event.key==='ArrowLeft')showLightboxImage(lightboxIndex-1);if(event.key==='ArrowRight')showLightboxImage(lightboxIndex+1);if(event.key==='+'){lightboxZoom=Math.min(3,lightboxZoom+.25);applyZoom()}if(event.key==='-'){lightboxZoom=Math.max(.5,lightboxZoom-.25);applyZoom()}});
const newsPopup=document.querySelector('.news-popup');if(newsPopup){const closeNewsPopup=()=>{newsPopup.classList.remove('open');newsPopup.setAttribute('aria-hidden','true');document.body.classList.remove('popup-open')};newsPopup.querySelectorAll('.news-popup-close,.news-popup-backdrop,.news-popup-actions button').forEach(button=>button.addEventListener('click',closeNewsPopup));window.addEventListener('load',()=>setTimeout(()=>{newsPopup.classList.add('open');newsPopup.setAttribute('aria-hidden','false');document.body.classList.add('popup-open');newsPopup.querySelector('.news-popup-close').focus()},900));document.addEventListener('keydown',event=>{if(event.key==='Escape'&&newsPopup.classList.contains('open'))closeNewsPopup()})}
</script>
</body>
</html>
