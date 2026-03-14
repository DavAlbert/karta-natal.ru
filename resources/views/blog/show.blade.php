<!DOCTYPE html>
<html lang="{{ $post->locale }}" class="scroll-smooth" style="overflow-x: hidden;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">

    @php
        $baseUrl = rtrim(config('app.url', request()->getSchemeAndHttpHost()), '/');
        $postLocale = $post->locale;
        $localePath = $postLocale === 'en' ? '' : '/' . $postLocale;
        $canonicalUrl = $baseUrl . $localePath . '/blog/' . $post->slug;
        $seoTitle = $post->meta_title ?: $post->title;
        $seoDescription = $post->meta_description ?: $post->excerpt ?: Str::limit(strip_tags($post->content), 160);
    @endphp

    <title>{{ $seoTitle }} | NatalScope</title>
    <meta name="description" content="{{ $seoDescription }}">
    @if($post->meta_keywords)
    <meta name="keywords" content="{{ $post->meta_keywords }}">
    @endif
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1">
    <meta name="author" content="NatalScope">

    <link rel="canonical" href="{{ $canonicalUrl }}">
    <link rel="alternate" hreflang="{{ $postLocale }}" href="{{ $canonicalUrl }}">
    <link rel="alternate" hreflang="x-default" href="{{ $canonicalUrl }}">

    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:locale" content="{{ $postLocale }}">
    <meta property="og:site_name" content="NatalScope">
    @if($post->banner)
    <meta property="og:image" content="{{ $baseUrl }}{{ Storage::url($post->banner) }}">
    @endif
    <meta property="article:published_time" content="{{ $post->published_at?->toIso8601String() }}">
    <meta property="article:modified_time" content="{{ $post->updated_at->toIso8601String() }}">

    <meta name="twitter:card" content="{{ $post->banner ? 'summary_large_image' : 'summary' }}">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    @if($post->banner)
    <meta name="twitter:image" content="{{ $baseUrl }}{{ Storage::url($post->banner) }}">
    @endif

    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Article",
        "headline": "{{ $post->title }}",
        "description": "{{ $seoDescription }}",
        "url": "{{ $canonicalUrl }}",
        @if($post->banner)
        "image": "{{ $baseUrl }}{{ Storage::url($post->banner) }}",
        @endif
        "datePublished": "{{ $post->published_at?->toIso8601String() }}",
        "dateModified": "{{ $post->updated_at->toIso8601String() }}",
        "inLanguage": "{{ $postLocale }}",
        "author": { "@@type": "Organization", "name": "NatalScope" },
        "publisher": { "@@type": "Organization", "name": "NatalScope" },
        "mainEntityOfPage": { "@@type": "WebPage", "@@id": "{{ $canonicalUrl }}" }
    }
    </script>

    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "BreadcrumbList",
        "itemListElement": [
            { "@@type": "ListItem", "position": 1, "name": "{{ __('blog.home') }}", "item": "{{ $baseUrl }}{{ $localePath }}/" },
            { "@@type": "ListItem", "position": 2, "name": "{{ __('blog.blog') }}", "item": "{{ $baseUrl }}{{ $localePath }}/blog" },
            { "@@type": "ListItem", "position": 3, "name": "{{ $post->title }}" }
        ]
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.1.0/js/all.min.js"></script>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html, body { background-color: #0B1120; color: #e2e8f0; overflow-x: hidden; max-width: 100%; }
        .input-pro {
            background-color: rgba(15, 23, 42, 0.8);
            border: 1px solid rgba(99, 102, 241, 0.25);
            color: white; transition: all 0.2s;
        }
        .input-pro:focus { border-color: #818cf8; outline: none; box-shadow: 0 0 0 2px rgba(129, 140, 248, 0.2); }
        .gender-btn input:checked + div { border-color: #818cf8; background-color: rgba(129, 140, 248, 0.1); }
        .gender-btn input:checked + div i { color: #818cf8; }
        select.input-pro {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236366f1' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center; background-repeat: no-repeat; background-size: 1.5em 1.5em; padding-right: 2.5rem;
        }
        select.input-pro option { background-color: #1e293b; color: white; }
        @media (max-width: 640px) { .input-pro, select.input-pro { font-size: 16px; } select.input-pro { min-height: 48px; } }
        #reading-progress {
            position: fixed; top: 64px; left: 0; height: 3px; z-index: 60;
            background: linear-gradient(90deg, #6366f1, #a855f7);
            width: 0%; transition: width 0.1s linear;
        }
        /* Mobile drawer */
        #mobile-drawer-backdrop { position: fixed; inset: 0; z-index: 55; background: rgba(0,0,0,0.6); opacity: 0; pointer-events: none; transition: opacity 0.3s; }
        #mobile-drawer-backdrop.open { opacity: 1; pointer-events: auto; }
        #mobile-drawer { position: fixed; bottom: 0; left: 0; right: 0; z-index: 60; max-height: 90vh; overflow-y: auto; transform: translateY(100%); transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1); border-radius: 1rem 1rem 0 0; }
        #mobile-drawer.open { transform: translateY(0); }
        #mobile-cta-bar { position: fixed; bottom: 0; left: 0; right: 0; z-index: 50; transform: translateY(100%); transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1); }
        #mobile-cta-bar.visible { transform: translateY(0); }
    </style>
</head>
<body class="font-sans antialiased">
    <div id="reading-progress"></div>

    {{-- Navigation --}}
    <nav class="fixed top-0 w-full z-50 bg-[#0B1120]/80 backdrop-blur-sm border-b border-white/5">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ locale_route('welcome') }}" class="text-lg font-bold text-white">NatalScope</a>
                <div class="flex items-center space-x-6">
                    <a href="{{ locale_route('blog.index') }}" class="text-sm text-gray-300 hover:text-white">{{ __('blog.blog') }}</a>
                    <a href="{{ locale_route('horoscope.index') }}" class="text-sm text-gray-300 hover:text-white hidden sm:inline">{{ __('blog.horoscope') }}</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="pt-24 pb-16">
        {{-- Breadcrumb --}}
        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <nav class="flex items-center space-x-2 text-sm text-gray-500">
                <a href="{{ locale_route('welcome') }}" class="hover:text-gray-300">{{ __('blog.home') }}</a>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <a href="{{ locale_route('blog.index') }}" class="hover:text-gray-300">{{ __('blog.blog') }}</a>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <span class="text-gray-400 truncate max-w-xs">{{ $post->title }}</span>
            </nav>
        </div>

        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:flex lg:gap-10">
                {{-- Article (left) --}}
                <article id="article" class="flex-1 min-w-0 max-w-4xl">
                    @if($post->banner)
                    <div class="rounded-2xl overflow-hidden mb-8">
                        <img src="{{ Storage::url($post->banner) }}" alt="{{ $post->title }}" class="w-full max-h-[400px] object-cover">
                    </div>
                    @endif

                    <header class="mb-8">
                        <h1 class="text-3xl md:text-4xl font-bold text-white leading-tight mb-4">{{ $post->title }}</h1>
                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                            <span><i class="fa-regular fa-calendar mr-1"></i> {{ $post->published_at?->translatedFormat('F d, Y') }}</span>
                            <span>NatalScope</span>
                        </div>
                    </header>

                    <div class="prose prose-lg prose-invert max-w-none text-gray-300 leading-relaxed space-y-4">
                        {!! $post->renderContent() !!}
                    </div>
                </article>

                {{-- Sidebar form (desktop lg+) --}}
                <aside class="hidden lg:block w-[380px] xl:w-[420px] flex-shrink-0">
                    <p class="text-xs font-semibold text-indigo-400 uppercase tracking-widest mb-3">{{ __('blog.progress_cta') }}</p>
                    <h3 class="text-xl font-bold text-white mb-2">{{ __('blog.cta_title') }}</h3>
                    <p class="text-sm text-indigo-300/60 mb-6">{{ __('blog.cta_text') }}</p>
                    @include('blog._calc-form', ['formId' => 'blogCalcForm', 'prefix' => 'bl_'])
                </aside>
            </div>
        </div>

        {{-- Related Posts --}}
        @if(isset($related) && $related->count() > 0)
        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 mt-16 pt-12 border-t border-white/5">
            <h2 class="text-2xl font-bold text-white mb-6">{{ __('blog.more_articles') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($related as $relatedPost)
                <a href="{{ locale_route('blog.show', ['slug' => $relatedPost->slug]) }}" class="group block bg-[#111827] rounded-xl overflow-hidden border border-white/5 hover:border-indigo-500/30 transition-all duration-300">
                    @if($relatedPost->banner)
                    <div class="aspect-video overflow-hidden">
                        <img src="{{ Storage::url($relatedPost->banner) }}" alt="{{ $relatedPost->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                    </div>
                    @endif
                    <div class="p-5">
                        <span class="text-xs text-indigo-400">{{ $relatedPost->published_at?->format('M d, Y') }}</span>
                        <h3 class="text-base font-semibold text-white group-hover:text-indigo-300 transition-colors mt-2">{{ $relatedPost->title }}</h3>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </main>

    {{-- Mobile: sticky CTA bar (lg:hidden, appears after small scroll) --}}
    <div id="mobile-cta-bar" class="lg:hidden">
        <div class="bg-[#0B1120]/95 backdrop-blur-sm border-t border-indigo-500/20 py-3 px-4">
            <button onclick="openDrawer()" class="w-full py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/25 text-sm">
                {{ __('blog.sticky_button') }} — {{ __('blog.sticky_title') }}
            </button>
        </div>
    </div>

    {{-- Mobile: form drawer --}}
    <div id="mobile-drawer-backdrop" class="lg:hidden" onclick="closeDrawer()"></div>
    <div id="mobile-drawer" class="lg:hidden bg-[#0B1120] border-t border-indigo-500/20">
        <div class="p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-white">{{ __('blog.cta_title') }}</h3>
                <button onclick="closeDrawer()" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-white text-xl rounded-full hover:bg-white/10">&times;</button>
            </div>
            <p class="text-sm text-indigo-300/60 mb-5">{{ __('blog.cta_text') }}</p>
            @include('blog._calc-form', ['formId' => 'mobileCalcForm', 'prefix' => 'mb_'])
        </div>
    </div>

    {{-- Processing Modal --}}
    <div id="processingModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-black/70 backdrop-blur-sm">
        <div class="bg-[#111827] rounded-2xl p-8 max-w-md w-full mx-4 text-center border border-indigo-500/20">
            <div class="inline-block w-12 h-12 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin mb-4"></div>
            <p id="statusText" class="text-white font-medium mb-4">{{ __('common.processing_step1') }}</p>
            <div class="w-full bg-gray-700 rounded-full h-2 mb-2">
                <div id="progressBar" class="bg-gradient-to-r from-indigo-600 to-purple-600 h-2 rounded-full transition-all duration-500" style="width: 0%"></div>
            </div>
            <p id="percentage" class="text-xs text-gray-400">0%</p>
        </div>
    </div>

    {{-- Email Sent Modal --}}
    <div id="emailSentModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-black/70 backdrop-blur-sm">
        <div class="bg-[#111827] rounded-2xl p-8 max-w-md w-full mx-4 text-center border border-indigo-500/20">
            <div class="text-5xl mb-4">&#9993;</div>
            <h3 class="text-xl font-bold text-white mb-2">{{ __('common.processing_email_title') }}</h3>
            <p class="text-gray-300 text-sm mb-4">{{ __('common.processing_email_text') }}</p>
            <div class="text-left space-y-2 text-sm text-gray-400">
                <p><span class="text-indigo-400 font-bold">1.</span> {{ __('common.processing_email_step1') }}</p>
                <p><span class="text-indigo-400 font-bold">2.</span> {{ __('common.processing_email_step2') }}</p>
                <p><span class="text-indigo-400 font-bold">3.</span> {{ __('common.processing_email_step3') }}</p>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="border-t border-white/5 py-8 pb-24 lg:pb-8">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between text-sm text-gray-500">
            <div>&copy; {{ date('Y') }} NatalScope</div>
            <div class="flex space-x-6 mt-4 md:mt-0">
                <a href="{{ locale_route('welcome') }}" class="hover:text-gray-300">{{ __('blog.home') }}</a>
                <a href="{{ locale_route('blog.index') }}" class="hover:text-gray-300">{{ __('blog.blog') }}</a>
                <a href="{{ route('privacy') }}" class="hover:text-gray-300">{{ __('blog.privacy') }}</a>
                <a href="{{ route('terms') }}" class="hover:text-gray-300">{{ __('blog.terms') }}</a>
            </div>
        </div>
    </footer>

    <script>
    const CALC_URL = "{{ route('calculate') }}";
    const TRANSLATIONS = {
        step1: @json(__('common.processing_step1')),
        step2: @json(__('common.processing_step2')),
        step3: @json(__('common.processing_step3')),
        step4: @json(__('common.processing_step4')),
        done: @json(__('common.processing_success_title')),
        error: @json(__('common.processing_error_text')),
        cityNotFound: @json(__('common.form_city_warning')),
        cityEnglishOnly: @json(__('common.form_city_english_only')),
    };

    // Mobile drawer
    function openDrawer() {
        document.getElementById('mobile-drawer').classList.add('open');
        document.getElementById('mobile-drawer-backdrop').classList.add('open');
        document.getElementById('mobile-cta-bar').classList.remove('visible');
        document.body.style.overflow = 'hidden';
    }
    function closeDrawer() {
        document.getElementById('mobile-drawer').classList.remove('open');
        document.getElementById('mobile-drawer-backdrop').classList.remove('open');
        document.getElementById('mobile-cta-bar').classList.add('visible');
        document.body.style.overflow = '';
    }

    // Reading progress + mobile CTA
    (function() {
        const article = document.getElementById('article');
        const bar = document.getElementById('reading-progress');
        const mobileBar = document.getElementById('mobile-cta-bar');
        let shown = false;
        window.addEventListener('scroll', function() {
            const rect = article.getBoundingClientRect();
            const top = rect.top + window.scrollY - 100;
            const progress = Math.min(Math.max((window.scrollY - top) / rect.height * 100, 0), 100);
            bar.style.width = progress + '%';
            if (window.scrollY > 200 && !shown) { mobileBar?.classList.add('visible'); shown = true; }
        }, { passive: true });
    })();

    // Form init helper
    function initForm(formId, P) {
        const form = document.getElementById(formId);
        if (!form) return;

        const submitBtn = form.querySelector('[type="submit"]');
        const nameInput = form.querySelector('input[name="name"]');
        const emailInput = form.querySelector('input[name="email"]');
        const genderInputs = form.querySelectorAll('input[name="gender"]');
        const birthDateInput = form.querySelector('#' + P + 'birth_date');
        const birthTimeInput = form.querySelector('#' + P + 'birth_time');
        const cityIdInput = form.querySelector('#' + P + 'city_id');

        const dayS = form.querySelector('#' + P + 'birth_day');
        const monS = form.querySelector('#' + P + 'birth_month');
        const yearS = form.querySelector('#' + P + 'birth_year');
        const hourS = form.querySelector('#' + P + 'birth_hour');
        const minS = form.querySelector('#' + P + 'birth_minute');
        const timeUnk = form.querySelector('#' + P + 'time_unknown');

        function validate() {
            if (!submitBtn) return;
            submitBtn.disabled = !(nameInput?.value.trim() && emailInput?.value.trim()
                && Array.from(genderInputs).some(i => i.checked)
                && birthDateInput?.value && birthTimeInput?.value && cityIdInput?.value);
        }

        // Populate dropdowns
        if (dayS) for (let d = 1; d <= 31; d++) dayS.add(new Option(d, String(d).padStart(2,'0')));
        if (yearS) { const cy = new Date().getFullYear(); for (let y = cy; y >= 1920; y--) yearS.add(new Option(y, y)); }
        if (hourS) for (let h = 0; h <= 23; h++) { const v = String(h).padStart(2,'0'); hourS.add(new Option(v, v)); }
        if (minS) for (let m = 0; m <= 59; m += 5) { const v = String(m).padStart(2,'0'); minS.add(new Option(v, v)); }

        function syncDate() {
            if (birthDateInput) birthDateInput.value = (dayS?.value && monS?.value && yearS?.value) ? `${yearS.value}-${monS.value}-${dayS.value}` : '';
            validate();
        }
        function syncTime() {
            if (timeUnk?.checked) {
                birthTimeInput.value = '12:00';
                [hourS, minS].forEach(s => { if(s) { s.disabled = true; s.classList.add('opacity-50'); } });
            } else {
                [hourS, minS].forEach(s => { if(s) { s.disabled = false; s.classList.remove('opacity-50'); } });
                birthTimeInput.value = (hourS?.value && minS?.value) ? `${hourS.value}:${minS.value}` : '';
            }
            validate();
        }

        [dayS, monS, yearS].forEach(s => s?.addEventListener('change', syncDate));
        [hourS, minS].forEach(s => s?.addEventListener('change', syncTime));
        timeUnk?.addEventListener('change', syncTime);
        nameInput?.addEventListener('input', validate);
        emailInput?.addEventListener('input', validate);
        genderInputs.forEach(i => i.addEventListener('change', validate));

        // City search
        const searchInput = form.querySelector('#' + P + 'birth_place_search');
        const dropdown = form.querySelector('#' + P + 'cities_dropdown');
        const cityDetails = form.querySelector('#' + P + 'city_details');
        let searchTimeout, currentQuery = '', lastResults = [];

        function getFlag(code) {
            if (!code || code.length < 2) return '\u{1F30D}';
            const c = code.toUpperCase().slice(0, 2);
            return /^[A-Z]{2}$/.test(c) ? String.fromCodePoint(...[...c].map(l => 0x1F1E6 + l.charCodeAt(0) - 65)) : '\u{1F30D}';
        }

        function renderCities(cities) {
            dropdown.innerHTML = '';
            if (!cities.length) {
                dropdown.innerHTML = '<div class="px-4 py-3 text-center text-amber-400 text-xs">' + TRANSLATIONS.cityNotFound + '</div>';
                dropdown.classList.remove('hidden'); return;
            }
            cities.forEach(city => {
                const div = document.createElement('div');
                div.className = 'px-4 py-3 hover:bg-indigo-600/30 cursor-pointer border-b border-white/5 last:border-0 transition-all';
                div.innerHTML = `<div class="flex items-center gap-3"><span class="text-lg">${getFlag(city.country)}</span><div><div class="text-white text-sm font-medium">${city.name}</div><div class="text-indigo-400/60 text-xs">${city.country}</div></div></div>`;
                div.addEventListener('click', () => {
                    searchInput.value = city.name;
                    cityIdInput.value = city.id;
                    if (cityDetails) { cityDetails.textContent = `${city.name}, ${city.country}`; cityDetails.classList.remove('hidden'); }
                    dropdown.classList.add('hidden');
                    validate();
                });
                dropdown.appendChild(div);
            });
            dropdown.classList.remove('hidden');
        }

        async function searchCities(q) {
            q = q.trim(); if (q.length < 2) { dropdown.classList.add('hidden'); return; }
            currentQuery = q;
            try {
                const r = await fetch(`/cities/search/${encodeURIComponent(q)}`);
                const cities = await r.json();
                if (q === currentQuery) { lastResults = cities; renderCities(cities); }
            } catch(e) { console.error(e); }
        }

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                if (cityIdInput.value) { cityIdInput.value = ''; cityDetails?.classList.add('hidden'); validate(); }
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => searchCities(this.value), 300);
            });
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') { e.preventDefault(); const opts = dropdown.querySelectorAll('div[class*="hover"]'); if (opts.length) opts[0].click(); }
                else if (e.key === 'Escape') dropdown.classList.add('hidden');
            });
            searchInput.addEventListener('blur', function() {
                setTimeout(() => {
                    if (!cityIdInput.value && lastResults.length === 1) {
                        searchInput.value = lastResults[0].name; cityIdInput.value = lastResults[0].id;
                        if (cityDetails) { cityDetails.textContent = `${lastResults[0].name}, ${lastResults[0].country}`; cityDetails.classList.remove('hidden'); }
                        validate();
                    }
                    dropdown.classList.add('hidden');
                }, 200);
            });
            document.addEventListener('click', function(e) { if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) dropdown.classList.add('hidden'); });
        }

        // Turnstile + submit
        let token = null;
        const pendingKey = '_pending_' + P;
        window['onTurnstile_' + P] = function(t) { token = t; if (window[pendingKey]) window[pendingKey](); };

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            if (!token) { window[pendingKey] = () => doSubmit(); turnstile.execute('#' + P + 'turnstile'); return; }
            doSubmit();
        });

        async function doSubmit() {
            const modal = document.getElementById('processingModal');
            modal?.classList.remove('hidden');
            try {
                const fd = new FormData(form);
                fd.set('cf_turnstile_response', token);
                const resp = await fetch(CALC_URL, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                    body: fd
                });
                if (!resp.ok) { const err = await resp.json(); throw new Error(err.message || TRANSLATIONS.error); }
                const data = await resp.json();
                if (data.success === false) { alert(data.message); modal?.classList.add('hidden'); token = null; turnstile.reset('#bl_turnstile'); return; }

                const steps = [{ pct: 15, text: TRANSLATIONS.step1 },{ pct: 30, text: TRANSLATIONS.step2 },{ pct: 55, text: TRANSLATIONS.step3 },{ pct: 75, text: TRANSLATIONS.step4 },{ pct: 100, text: TRANSLATIONS.done }];
                let cur = 0;
                (function next() {
                    if (cur >= steps.length) return;
                    document.getElementById('progressBar').style.width = steps[cur].pct + '%';
                    document.getElementById('statusText').innerText = steps[cur].text;
                    document.getElementById('percentage').innerText = steps[cur].pct + '%';
                    cur++; if (cur < steps.length) setTimeout(next, 800);
                })();
                setTimeout(() => {
                    if (data.redirect?.includes('charts/show')) window.location.href = data.redirect;
                    else { modal.classList.add('hidden'); document.getElementById('emailSentModal').classList.remove('hidden'); }
                }, 4000);
            } catch(error) {
                modal?.classList.add('hidden');
                alert(error.message || TRANSLATIONS.error);
                token = null; turnstile.reset('#' + P + 'turnstile');
            }
        }

        validate();
    }

    // Init both forms
    initForm('blogCalcForm', 'bl_');
    initForm('mobileCalcForm', 'mb_');
    </script>
</body>
</html>
