<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth" style="overflow-x: hidden;">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('seo.title') }}</title>
    <meta name="description" content="{{ __('seo.description') }}">
    <meta name="keywords" content="{{ __('seo.keywords') }}">
    <meta name="author" content="SMART CREATOR AI LLC">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">

    @php
        $currentLocale = app()->getLocale();
        $locales = config('app.available_locales', ['en']);
        $baseUrl = rtrim(config('app.url', request()->getSchemeAndHttpHost()), '/');
    @endphp

    <link rel="canonical" href="{{ $baseUrl }}{{ $currentLocale === 'en' ? '/' : '/' . $currentLocale . '/' }}">

    @foreach($locales as $loc)
    <link rel="alternate" hreflang="{{ $loc }}" href="{{ $baseUrl }}{{ $loc === 'en' ? '/' : '/' . $loc . '/' }}">
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ $baseUrl }}/">

    <!-- Yandex.Metrika -->
    <script type="text/javascript">
        (function(m,e,t,r,i,k,a){
            m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
            m[i].l=1*new Date();
            for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
            k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)
        })(window, document,'script','https://mc.yandex.ru/metrika/tag.js?id=106818761', 'ym');
        ym(106818761, 'init', {ssr:true, webvisor:true, clickmap:true, ecommerce:"dataLayer", referrer: document.referrer, url: location.href, accurateTrackBounce:true, trackLinks:true});
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/106818761" style="position:absolute; left:-9999px;" alt="" /></div></noscript>

    <!-- Google Analytics 4 -->
    @if(config('services.google_analytics.id'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.google_analytics.id') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ config('services.google_analytics.id') }}');
    </script>
    @endif

    <!-- Cloudflare Turnstile -->
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>

    @php
        $ogLocaleMap = ['en' => 'en_US', 'fr' => 'fr_FR', 'es' => 'es_ES', 'pt' => 'pt_BR', 'hi' => 'hi_IN', 'ru' => 'ru_RU'];
    @endphp

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $baseUrl }}{{ $currentLocale === 'en' ? '/' : '/' . $currentLocale . '/' }}">
    <meta property="og:title" content="{{ __('seo.og_title') }}">
    <meta property="og:description" content="{{ __('seo.og_description') }}">
    <meta property="og:image" content="{{ asset('images/demo.webp') }}">
    <meta property="og:locale" content="{{ $ogLocaleMap[$currentLocale] ?? 'en_US' }}">
    @foreach($locales as $loc)
    @if($loc !== $currentLocale)
    <meta property="og:locale:alternate" content="{{ $ogLocaleMap[$loc] ?? 'en_US' }}">
    @endif
    @endforeach
    <meta property="og:site_name" content="NatalScope">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ $baseUrl }}{{ $currentLocale === 'en' ? '/' : '/' . $currentLocale . '/' }}">
    <meta name="twitter:title" content="{{ __('seo.og_title') }}">
    <meta name="twitter:description" content="{{ __('seo.og_description') }}">
    <meta name="twitter:image" content="{{ asset('images/demo.webp') }}">

    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "WebApplication",
        "name": "{{ __('seo.app_name') }}",
        "url": "{{ $baseUrl }}",
        "description": "{{ __('seo.app_description') }}",
        "applicationCategory": "LifestyleApplication",
        "operatingSystem": "Web",
        "inLanguage": ["en", "fr", "es", "pt", "hi", "ru"],
        "offers": {
            "@@type": "Offer",
            "price": "0",
            "priceCurrency": "USD"
        },
        "creator": {
            "@@type": "Organization",
            "name": "SMART CREATOR AI LLC"
        }
    }
    </script>
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "FAQPage",
        "mainEntity": [
            {
                "@@type": "Question",
                "name": "{{ __('seo.faq_q1') }}",
                "acceptedAnswer": { "@@type": "Answer", "text": "{{ __('seo.faq_a1') }}" }
            },
            {
                "@@type": "Question",
                "name": "{{ __('seo.faq_q2') }}",
                "acceptedAnswer": { "@@type": "Answer", "text": "{{ __('seo.faq_a2') }}" }
            },
            {
                "@@type": "Question",
                "name": "{{ __('seo.faq_q3') }}",
                "acceptedAnswer": { "@@type": "Answer", "text": "{{ __('seo.faq_a3') }}" }
            },
            {
                "@@type": "Question",
                "name": "{{ __('seo.faq_q4') }}",
                "acceptedAnswer": { "@@type": "Answer", "text": "{{ __('seo.faq_a4') }}" }
            }
        ]
    }
    </script>
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Organization",
        "name": "NatalScope",
        "url": "{{ $baseUrl }}",
        "logo": "{{ $baseUrl }}/images/logo.webp",
        "legalName": "SMART CREATOR AI LLC",
        "sameAs": []
    }
    </script>
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "WebSite",
        "name": "NatalScope",
        "alternateName": ["NatalScope.com"],
        "url": "{{ $baseUrl }}"
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.1.0/js/all.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html, body {
            background-color: #0B1120;
            color: #e2e8f0;
            overflow-x: hidden;
            max-width: 100%;
        }
        .text-gold { color: #fbbf24; }
        .bg-gold { background-color: #fbbf24; }
        .input-pro {
            background-color: rgba(15, 23, 42, 0.8);
            border: 1px solid rgba(99, 102, 241, 0.25);
            color: white;
            transition: all 0.2s;
        }
        .input-pro:focus {
            border-color: #818cf8;
            outline: none;
            box-shadow: 0 0 0 2px rgba(129, 140, 248, 0.2);
        }
        .gender-btn input:checked + div {
            border-color: #818cf8;
            background-color: rgba(129, 140, 248, 0.1);
        }
        .gender-btn input:checked + div i { color: #818cf8; }
        input[type="date"]::-webkit-calendar-picker-indicator,
        input[type="time"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
        }
        select.input-pro {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236366f1' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }
        select.input-pro option {
            background-color: #1e293b;
            color: white;
            padding: 0.5rem;
        }
        @media (max-width: 640px) {
            .input-pro, select.input-pro { font-size: 16px; }
            select.input-pro { min-height: 48px; }
        }
        #cities-dropdown {
            max-width: calc(100% - 2rem);
            overflow-x: auto;
        }
        .constellation-bg {
            background-image:
                radial-gradient(1px 1px at 10% 20%, rgba(255,255,255,0.15) 0%, transparent 50%),
                radial-gradient(1px 1px at 30% 60%, rgba(255,255,255,0.1) 0%, transparent 50%),
                radial-gradient(1px 1px at 50% 10%, rgba(255,255,255,0.12) 0%, transparent 50%),
                radial-gradient(1px 1px at 70% 40%, rgba(255,255,255,0.08) 0%, transparent 50%),
                radial-gradient(1px 1px at 90% 80%, rgba(255,255,255,0.1) 0%, transparent 50%),
                radial-gradient(1.5px 1.5px at 20% 90%, rgba(255,255,255,0.15) 0%, transparent 50%),
                radial-gradient(1px 1px at 80% 15%, rgba(255,255,255,0.12) 0%, transparent 50%);
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.3s ease-out forwards;
        }
        @@keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body class="font-sans antialiased constellation-bg">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 border-b border-white/5 bg-[#0B1120]/90 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ locale_route('welcome') }}" class="flex items-center gap-2">
                    <span class="text-xl font-bold text-white tracking-wider">
                        NATAL<span class="text-indigo-400">SCOPE</span>
                    </span>
                </a>

                <div class="flex items-center gap-3 sm:gap-5">
                    <a href="{{ locale_route('horoscope.index') }}" class="hidden sm:flex items-center gap-1.5 text-indigo-300 text-sm hover:text-white transition-colors">
                        <i class="fas fa-moon text-xs"></i>
                        {{ __('horoscope.title') }}
                    </a>
                    @auth
                        @php $chart = Auth::user()->natalCharts()->first(); @endphp
                        @if($chart)
                            <a href="{{ locale_route('charts.show', ['natalChart' => $chart]) }}" class="hidden sm:flex items-center gap-1.5 text-indigo-300 text-sm hover:text-white transition-colors" onclick="localStorage.setItem('activeTab', 'compatibility')">
                                <i class="fas fa-heart text-xs"></i>
                                {{ __('common.nav_compatibility') }}
                            </a>
                            <a href="{{ locale_route('charts.show', ['natalChart' => $chart]) }}" class="flex items-center gap-1.5 text-indigo-300 text-sm font-medium hover:text-white transition-colors">
                                <i class="fas fa-user text-xs"></i>
                                {{ __('common.nav_my_chart') }}
                            </a>
                        @endif
                        <form method="POST" action="{{ locale_route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center gap-1.5 text-indigo-400 text-sm hover:text-white transition-colors">
                                <i class="fas fa-sign-out-alt text-xs"></i>
                                <span class="hidden sm:inline">{{ __('common.nav_logout') }}</span>
                            </button>
                        </form>
                    @else
                        <button onclick="document.getElementById('loginModal').classList.remove('hidden')" class="flex items-center gap-1.5 text-indigo-300 text-sm font-medium hover:text-white transition-colors">
                            <i class="fas fa-user text-xs"></i>
                            {{ __('common.nav_login') }}
                        </button>
                    @endauth

                    <!-- Language Switcher -->
                    @php
                        $langFlags = ['en' => '🇬🇧', 'ru' => '🇷🇺', 'es' => '🇪🇸', 'fr' => '🇫🇷', 'pt' => '🇧🇷', 'hi' => '🇮🇳'];
                    @endphp
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open" class="flex items-center gap-1.5 text-indigo-300 text-sm hover:text-white transition-colors px-2 py-1 rounded-md hover:bg-white/5">
                            <span class="text-base">{{ $langFlags[$currentLocale] ?? '🌐' }}</span>
                            <span class="uppercase font-medium">{{ $currentLocale }}</span>
                            <svg class="w-3 h-3 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-transition class="absolute right-0 mt-2 w-44 bg-[#1e293b] border border-indigo-900/50 rounded-lg shadow-xl overflow-hidden z-50">
                            @foreach($locales as $loc)
                            <a href="{{ $baseUrl }}{{ $loc === 'en' ? '/' : '/' . $loc . '/' }}"
                               class="flex items-center gap-2 px-4 py-2.5 text-sm transition-colors {{ $loc === $currentLocale ? 'text-indigo-400 bg-indigo-900/30' : 'text-indigo-200 hover:bg-indigo-900/20 hover:text-white' }}">
                                <span class="text-base">{{ $langFlags[$loc] ?? '🌐' }}</span>
                                {{ __('common.lang_' . $loc) }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="heroSection" class="relative pt-28 pb-16 lg:pt-40 lg:pb-28 overflow-hidden bg-cover bg-center"
        style="background-image: url('/images/hero-bg.webp');">

        <div class="absolute inset-0 bg-gradient-to-b from-[#0B1120]/70 via-[#0B1120]/50 to-[#0B1120]"></div>

        <div class="absolute top-20 right-0 w-[600px] h-[600px] bg-indigo-600/10 rounded-full blur-[120px] pointer-events-none -translate-y-1/2"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-purple-600/10 rounded-full blur-[100px] pointer-events-none translate-y-1/2"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <div class="text-center lg:text-left">
                    <div class="inline-block px-4 py-1.5 rounded-full border border-indigo-500/20 bg-indigo-950/60 text-indigo-300 text-xs font-semibold uppercase tracking-widest mb-6 backdrop-blur-sm">
                        {{ __('landing.hero_badge') }}
                    </div>
                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
                        {{ __('landing.hero_title') }} <br class="hidden sm:block"><span class="bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">{{ __('landing.hero_title_accent') }}</span>
                    </h1>
                    <p class="text-base sm:text-lg text-indigo-200/80 mb-8 leading-relaxed max-w-xl mx-auto lg:mx-0">
                        {{ __('landing.hero_description') }}
                    </p>

                    <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-start text-sm">
                        <div class="flex items-center gap-2 text-indigo-300/80">
                            <svg class="w-4 h-4 text-indigo-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ __('landing.hero_feature_precision') }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-indigo-300/80">
                            <svg class="w-4 h-4 text-indigo-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ __('landing.hero_feature_speed') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Hero Form -->
                <div class="relative">
                    <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500/20 to-purple-500/20 rounded-2xl blur-xl"></div>
                    <div class="relative bg-[#111827]/95 backdrop-blur-sm rounded-2xl border border-white/10 p-6 sm:p-8 shadow-2xl">
                        @auth
                            @php $chart = Auth::user()->natalCharts()->first(); @endphp
                            @if($chart)
                                <div class="flex flex-col items-center justify-center py-8">
                                    <div class="w-16 h-16 bg-emerald-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-check text-2xl text-emerald-400"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-white mb-2">{{ __('common.chart_exists_title') }}</h3>
                                    <p class="text-indigo-300 text-sm mb-4">{{ __('common.chart_exists_text') }}</p>
                                    <a href="{{ locale_route('charts.show', ['natalChart' => $chart]) }}" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-500 hover:to-indigo-600 text-white font-bold rounded-xl shadow-lg transition-all">
                                        <i class="fas fa-star"></i>
                                        {{ __('common.chart_exists_btn') }}
                                    </a>
                                </div>
                            @endif
                        @endauth
                        @if(!Auth::check() || (Auth::check() && !Auth::user()->natalCharts()->first()))
                            <form id="calcForm" action="{{ route('calculate') }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="locale" value="{{ $currentLocale }}">

                                <div>
                                    <label class="block text-xs font-semibold text-indigo-300/80 uppercase tracking-wider mb-1.5">
                                        <i class="fas fa-user mr-1 text-indigo-500"></i>{{ __('common.form_name') }}
                                    </label>
                                    <input type="text" name="name" id="name" required
                                        class="w-full input-pro rounded-lg px-4 py-3" placeholder="{{ __('common.form_name_placeholder') }}">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-indigo-300/80 uppercase tracking-wider mb-1.5">
                                        <i class="fas fa-envelope mr-1 text-indigo-500"></i>{{ __('common.form_email') }}
                                    </label>
                                    <input type="email" name="email" id="email" required autocomplete="email"
                                        class="w-full input-pro rounded-lg px-4 py-3" placeholder="{{ __('common.form_email_placeholder') }}">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-indigo-300/80 uppercase tracking-wider mb-1.5">
                                        <i class="fas fa-venus-mars mr-1 text-indigo-500"></i>{{ __('common.form_gender') }}
                                    </label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <label class="gender-btn cursor-pointer">
                                            <input type="radio" name="gender" value="male" required class="hidden" checked>
                                            <div class="flex items-center justify-center gap-2 py-3 px-4 rounded-lg border border-indigo-800/50 bg-[#0f172a]/60 hover:border-indigo-600/50 transition-all">
                                                <i class="fas fa-mars text-indigo-400"></i>
                                                <span class="text-white text-sm">{{ __('common.form_gender_male') }}</span>
                                            </div>
                                        </label>
                                        <label class="gender-btn cursor-pointer">
                                            <input type="radio" name="gender" value="female" required class="hidden">
                                            <div class="flex items-center justify-center gap-2 py-3 px-4 rounded-lg border border-indigo-800/50 bg-[#0f172a]/60 hover:border-indigo-600/50 transition-all">
                                                <i class="fas fa-venus text-indigo-400"></i>
                                                <span class="text-white text-sm">{{ __('common.form_gender_female') }}</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <input type="hidden" name="purpose" value="general">

                                <div>
                                    <label class="block text-xs font-semibold text-indigo-300/80 uppercase tracking-wider mb-1.5">
                                        <i class="far fa-calendar-alt mr-1 text-indigo-500"></i>{{ __('common.form_birth_date') }}
                                    </label>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                        <select id="birth_day" required class="w-full input-pro rounded-lg px-3 py-3 text-center appearance-none cursor-pointer order-2 sm:order-1">
                                            <option value="">{{ __('common.form_day') }}</option>
                                        </select>
                                        <select id="birth_month" required class="w-full input-pro rounded-lg px-3 py-3 text-center appearance-none cursor-pointer col-span-2 sm:col-span-1 order-1 sm:order-2">
                                            <option value="">{{ __('common.form_month') }}</option>
                                            @for($m = 1; $m <= 12; $m++)
                                            <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">{{ __('common.month_' . str_pad($m, 2, '0', STR_PAD_LEFT)) }}</option>
                                            @endfor
                                        </select>
                                        <select id="birth_year" required class="w-full input-pro rounded-lg px-3 py-3 text-center appearance-none cursor-pointer order-3">
                                            <option value="">{{ __('common.form_year') }}</option>
                                        </select>
                                    </div>
                                    <input type="hidden" name="birth_date" id="birth_date">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-indigo-300/80 uppercase tracking-wider mb-1.5">
                                        <i class="far fa-clock mr-1 text-indigo-500"></i>{{ __('common.form_birth_time') }}
                                    </label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <select id="birth_hour" required class="w-full input-pro rounded-lg px-3 py-3 text-center appearance-none cursor-pointer">
                                            <option value="">{{ __('common.form_hour') }}</option>
                                        </select>
                                        <select id="birth_minute" required class="w-full input-pro rounded-lg px-3 py-3 text-center appearance-none cursor-pointer">
                                            <option value="">{{ __('common.form_minute') }}</option>
                                        </select>
                                    </div>
                                    <input type="hidden" name="birth_time" id="birth_time">
                                    <label class="flex items-center gap-2 mt-2 cursor-pointer group">
                                        <input type="checkbox" id="time_unknown"
                                            class="w-4 h-4 text-indigo-600 bg-[#0f172a] border-indigo-800 rounded focus:ring-indigo-500 focus:ring-2">
                                        <span class="text-xs text-indigo-400/80 group-hover:text-indigo-300 transition-colors">{{ __('common.form_time_unknown') }}</span>
                                    </label>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-indigo-300/80 uppercase tracking-wider mb-1.5">
                                        <i class="fas fa-city mr-1 text-indigo-500"></i>{{ __('common.form_city') }}
                                    </label>
                                    <div class="relative">
                                        <input type="text" id="birth_place_search" autocomplete="off"
                                            class="w-full input-pro rounded-lg px-4 py-3 pr-10"
                                            placeholder="{{ __('common.form_city_placeholder') }}">
                                        <div id="search-spinner" class="hidden absolute right-3 top-1/2 -translate-y-1/2">
                                            <svg class="animate-spin h-5 w-5 text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>
                                        <svg id="search-icon" class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-indigo-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                        <div id="cities-dropdown"
                                            class="hidden absolute z-50 mt-1 bg-[#1e293b] border border-indigo-500/30 rounded-lg shadow-xl max-h-60 overflow-y-auto w-full left-0">
                                        </div>
                                    </div>
                                    <p class="text-xs text-amber-400/80 mt-1.5 flex items-center gap-1.5">
                                        <i class="fas fa-language"></i>
                                        {{ __('common.form_city_hint') }}
                                    </p>
                                    <p id="city-warning" class="hidden text-xs text-amber-400 mt-1">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>{{ __('common.form_city_warning') }}
                                    </p>
                                </div>
                                <input type="hidden" id="city_id" name="city_id" required>

                                <div id="city-details" class="hidden mt-2 p-3 bg-indigo-900/20 rounded-lg border border-indigo-800/30 text-xs">
                                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1">
                                        <span class="text-white" id="display-city">-</span>
                                        <span class="text-indigo-500">·</span>
                                        <span class="text-indigo-300" id="display-country">-</span>
                                        <span class="text-indigo-500">·</span>
                                        <span class="text-indigo-400 font-mono"><span id="display-latitude">-</span>, <span id="display-longitude">-</span></span>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <label class="flex items-start gap-3 cursor-pointer group">
                                        <input type="checkbox" name="marketing_consent" value="1"
                                            class="mt-1 w-4 h-4 text-indigo-600 bg-[#0f172a] border-indigo-800 rounded focus:ring-indigo-500 focus:ring-2">
                                        <span class="text-xs text-indigo-300/70 leading-relaxed group-hover:text-indigo-200 transition-colors">
                                            {{ __('common.form_marketing_consent') }}
                                        </span>
                                    </label>
                                </div>

                                <div id="form-errors" class="hidden mt-3 p-3 bg-red-900/20 border border-red-800/30 rounded-lg">
                                    <p class="text-xs text-red-400 flex items-start gap-2">
                                        <i class="fas fa-exclamation-circle mt-0.5"></i>
                                        <span id="form-errors-text">{{ __('common.form_errors_default') }}</span>
                                    </p>
                                </div>

                                <!-- Cloudflare Turnstile (invisible) -->
                                <div id="turnstile-widget" class="cf-turnstile" data-sitekey="{{ config('services.turnstile.site_key') }}" data-size="invisible" data-callback="onCalcTurnstileSuccess"></div>

                                <button type="submit" id="submit-btn" disabled
                                    class="w-full mt-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold py-4 rounded-xl shadow-lg shadow-indigo-500/20 transition-all transform hover:scale-[1.01] disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none text-base sm:text-lg">
                                    {{ __('common.form_submit') }}
                                </button>

                                <p class="text-xs text-center text-indigo-400/40 mt-3">
                                    {{ __('common.form_privacy_note') }}
                                </p>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Preview Section -->
    <section class="py-16 bg-[#0B1120]">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-3">{{ __('landing.preview_title') }}</h2>
                <p class="text-indigo-300/70">{{ __('landing.preview_subtitle') }}</p>
            </div>
            <div class="relative rounded-2xl overflow-hidden border border-white/5 shadow-2xl shadow-indigo-900/20">
                <img src="{{ asset('images/demo.webp') }}" alt="{{ __('landing.preview_title') }}" class="w-full h-auto">
                <div class="absolute inset-0 bg-gradient-to-t from-[#0B1120] via-transparent to-transparent"></div>
            </div>
            <div class="mt-8 grid md:grid-cols-3 gap-4 text-center">
                <div class="p-4 rounded-xl bg-[#0f172a]/60 border border-white/5">
                    <p class="text-indigo-300 text-sm">{{ __('landing.preview_planets') }}</p>
                </div>
                <div class="p-4 rounded-xl bg-[#0f172a]/60 border border-white/5">
                    <p class="text-indigo-300 text-sm">{{ __('landing.preview_houses') }}</p>
                </div>
                <div class="p-4 rounded-xl bg-[#0f172a]/60 border border-white/5">
                    <p class="text-indigo-300 text-sm">{{ __('landing.preview_aspects') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Zodiac Signs Section -->
    <section class="py-20 bg-[#080d15] border-t border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="inline-block px-3 py-1 rounded-full bg-indigo-900/40 text-indigo-300 text-xs font-semibold uppercase tracking-wider mb-4">{{ __('landing.zodiac_badge') }}</span>
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-4">{{ __('landing.zodiac_title') }}</h2>
                <p class="text-indigo-300/70">{{ __('landing.zodiac_subtitle') }}</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-5">
                @php
                    $signs = [
                        ['file' => 'aries', 'key' => 'aries', 'element' => 'fire'],
                        ['file' => 'taurus', 'key' => 'taurus', 'element' => 'earth'],
                        ['file' => 'gemini', 'key' => 'gemini', 'element' => 'air'],
                        ['file' => 'cancer', 'key' => 'cancer', 'element' => 'water'],
                        ['file' => 'leo', 'key' => 'leo', 'element' => 'fire'],
                        ['file' => 'virgo', 'key' => 'virgo', 'element' => 'earth'],
                        ['file' => 'libra', 'key' => 'libra', 'element' => 'air'],
                        ['file' => 'scorpio', 'key' => 'scorpio', 'element' => 'water'],
                        ['file' => 'sagittarius', 'key' => 'sagittarius', 'element' => 'fire'],
                        ['file' => 'capricorn', 'key' => 'capricorn', 'element' => 'earth'],
                        ['file' => 'aquarius', 'key' => 'aquarius', 'element' => 'air'],
                        ['file' => 'pisces', 'key' => 'pisces', 'element' => 'water'],
                    ];
                    $elementStyles = [
                        'fire' => ['border' => 'hover:border-red-500/40', 'bg' => 'from-red-500/5', 'text' => 'text-red-400'],
                        'earth' => ['border' => 'hover:border-amber-500/40', 'bg' => 'from-amber-500/5', 'text' => 'text-amber-400'],
                        'air' => ['border' => 'hover:border-cyan-500/40', 'bg' => 'from-cyan-500/5', 'text' => 'text-cyan-400'],
                        'water' => ['border' => 'hover:border-blue-500/40', 'bg' => 'from-blue-500/5', 'text' => 'text-blue-400'],
                    ];
                @endphp
                @foreach($signs as $sign)
                @php $style = $elementStyles[$sign['element']]; @endphp
                <div class="group relative p-5 rounded-2xl bg-gradient-to-b {{ $style['bg'] }} to-[#111827]/60 border border-white/5 {{ $style['border'] }} transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-14 h-14 md:w-16 md:h-16">
                            <img src="/images/zodiac/{{ $sign['file'] }}.webp" alt="{{ __('landing.zodiac_' . $sign['key']) }}" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300">
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-white font-bold text-lg mb-0.5">{{ __('landing.zodiac_' . $sign['key']) }}</h3>
                            <p class="text-indigo-400/50 text-xs mb-2">{{ __('landing.zodiac_' . $sign['key'] . '_date') }}</p>
                            <p class="{{ $style['text'] }} text-xs leading-relaxed">{{ __('landing.zodiac_' . $sign['key'] . '_traits') }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-[#0B1120] border-t border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="inline-block px-3 py-1 rounded-full bg-indigo-900/40 text-indigo-300 text-xs font-semibold uppercase tracking-wider mb-4">{{ __('landing.features_badge') }}</span>
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-4">{{ __('landing.features_title') }}</h2>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="p-6 rounded-2xl bg-[#111827]/60 border border-white/5 hover:border-indigo-500/20 transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-indigo-500/10 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <img src="/images/planets/sun.webp" alt="" class="w-8 h-8">
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">{{ __('landing.feature_planets_title') }}</h3>
                    <p class="text-indigo-300/60 text-sm leading-relaxed">{{ __('landing.feature_planets_desc') }}</p>
                </div>
                <div class="p-6 rounded-2xl bg-[#111827]/60 border border-white/5 hover:border-purple-500/20 transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-purple-500/10 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">{{ __('landing.feature_houses_title') }}</h3>
                    <p class="text-indigo-300/60 text-sm leading-relaxed">{{ __('landing.feature_houses_desc') }}</p>
                </div>
                <div class="p-6 rounded-2xl bg-[#111827]/60 border border-white/5 hover:border-pink-500/20 transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-pink-500/10 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">{{ __('landing.feature_aspects_title') }}</h3>
                    <p class="text-indigo-300/60 text-sm leading-relaxed">{{ __('landing.feature_aspects_desc') }}</p>
                </div>
                <div class="p-6 rounded-2xl bg-[#111827]/60 border border-white/5 hover:border-amber-500/20 transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">{{ __('landing.feature_ai_title') }}</h3>
                    <p class="text-indigo-300/60 text-sm leading-relaxed">{{ __('landing.feature_ai_desc') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Elements Section -->
    <section class="py-20 bg-[#0B1120] border-t border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="inline-block px-3 py-1 rounded-full bg-indigo-900/40 text-indigo-300 text-xs font-semibold uppercase tracking-wider mb-4">{{ __('landing.elements_badge') }}</span>
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-4">{{ __('landing.elements_title') }}</h2>
                <p class="text-indigo-300/70">{{ __('landing.elements_subtitle') }}</p>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $elements = [
                        ['key' => 'fire', 'emoji' => '🔥', 'color' => 'red', 'signs' => ['aries','leo','sagittarius']],
                        ['key' => 'earth', 'emoji' => '🌍', 'color' => 'amber', 'signs' => ['taurus','virgo','capricorn']],
                        ['key' => 'air', 'emoji' => '💨', 'color' => 'cyan', 'signs' => ['gemini','libra','aquarius']],
                        ['key' => 'water', 'emoji' => '💧', 'color' => 'blue', 'signs' => ['cancer','scorpio','pisces']],
                    ];
                @endphp
                @foreach($elements as $el)
                <div class="group p-6 rounded-2xl bg-gradient-to-b from-{{ $el['color'] }}-900/20 to-[#111827]/60 border border-{{ $el['color'] }}-500/15 hover:border-{{ $el['color'] }}-500/30 transition-all hover:-translate-y-1">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-{{ $el['color'] }}-500/10 flex items-center justify-center">
                            <span class="text-2xl">{{ $el['emoji'] }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-{{ $el['color'] }}-400">{{ __('landing.element_' . $el['key']) }}</h3>
                    </div>
                    <p class="text-indigo-300/60 text-sm mb-4 leading-relaxed">{{ __('landing.element_' . $el['key'] . '_desc') }}</p>
                    <div class="flex items-center gap-2 pt-4 border-t border-white/5">
                        @foreach($el['signs'] as $signFile)
                        <img src="/images/zodiac/{{ $signFile }}.webp" alt="{{ __('landing.zodiac_' . $signFile) }}" class="w-7 h-7 opacity-70 hover:opacity-100 transition-opacity">
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-20 bg-[#080d15] border-t border-white/5">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-2xl md:text-3xl font-bold text-white">{{ __('landing.how_title') }}</h2>
            </div>
            <div class="flex flex-col md:flex-row items-center justify-center gap-4 md:gap-8">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-indigo-500/15 border border-indigo-500/25 flex items-center justify-center text-indigo-400 font-bold text-sm">1</div>
                    <span class="text-indigo-200/80 text-sm">{{ __('landing.how_step1') }}</span>
                </div>
                <svg class="hidden md:block w-6 h-6 text-indigo-800" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-purple-500/15 border border-purple-500/25 flex items-center justify-center text-purple-400 font-bold text-sm">2</div>
                    <span class="text-indigo-200/80 text-sm">{{ __('landing.how_step2') }}</span>
                </div>
                <svg class="hidden md:block w-6 h-6 text-indigo-800" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-amber-500/15 border border-amber-500/25 flex items-center justify-center text-amber-400 font-bold text-sm">3</div>
                    <span class="text-indigo-200/80 text-sm">{{ __('landing.how_step3') }}</span>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-[#0B1120] border-t border-white/5">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-4">{{ __('landing.cta_title') }}</h2>
            <p class="text-indigo-300/70 mb-8">{{ __('landing.cta_subtitle') }}</p>
            <a href="#calcForm" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/20 transition-all transform hover:scale-105">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                {{ __('landing.cta_button') }}
            </a>
        </div>
    </section>

    <!-- SEO Content -->
    <section class="py-20 bg-[#080d15] border-t border-white/5">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-6">{{ __('landing.seo_what_title') }}</h2>
                <p class="text-indigo-300/80 text-lg leading-relaxed max-w-3xl mx-auto">{!! __('landing.seo_what_text') !!}</p>
            </div>
            <div class="grid md:grid-cols-2 gap-6 mb-12">
                <div class="bg-[#111827]/60 rounded-2xl border border-white/5 p-6 md:p-8">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-12 h-12 rounded-xl bg-indigo-500/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-white">{{ __('landing.seo_how_title') }}</h3>
                    </div>
                    <p class="text-indigo-300/70 leading-relaxed mb-4">{!! __('landing.seo_how_text1') !!}</p>
                    <p class="text-indigo-300/70 leading-relaxed">{{ __('landing.seo_how_text2') }}</p>
                </div>
                <div class="bg-[#111827]/60 rounded-2xl border border-white/5 p-6 md:p-8">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-12 h-12 rounded-xl bg-pink-500/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-white">{{ __('landing.seo_compat_title') }}</h3>
                    </div>
                    <p class="text-indigo-300/70 leading-relaxed">{{ __('landing.seo_compat_text') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#050914] border-t border-white/5 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-8">
                <span class="text-xl font-bold text-white/40 tracking-wider">
                    NATAL<span class="text-indigo-400/40">SCOPE</span>
                </span>
                <nav class="flex flex-wrap justify-center gap-6 text-sm">
                    <a href="{{ url('/privacy') }}" class="text-indigo-400/70 hover:text-white transition-colors">{{ __('common.footer_privacy') }}</a>
                    <a href="{{ url('/terms') }}" class="text-indigo-400/70 hover:text-white transition-colors">{{ __('common.footer_terms') }}</a>
                </nav>
            </div>
            <div class="border-t border-white/5 pt-6 text-center">
                <p class="text-indigo-500/60 text-xs mb-2">{{ __('common.footer_tagline') }}</p>
                <p class="text-indigo-600/40 text-xs">{{ __('common.footer_copyright', ['year' => date('Y')]) }}</p>
            </div>
        </div>
    </footer>

    <!-- Processing Modal -->
    <div id="processingModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center">
        <div class="absolute inset-0 bg-[#0B1120]/98 backdrop-blur-sm"></div>
        <div class="relative z-10 max-w-sm w-full px-6">
            <div id="loadingState">
                <div class="bg-[#111827] rounded-2xl border border-white/10 p-8 shadow-2xl">
                    <div class="flex justify-center mb-8">
                        <div class="relative">
                            <div class="w-20 h-20 rounded-full border-2 border-indigo-900/50"></div>
                            <div class="absolute inset-0 w-20 h-20 rounded-full border-2 border-transparent border-t-indigo-500 animate-spin"></div>
                            <div class="absolute inset-3 w-14 h-14 rounded-full bg-indigo-500/10 animate-pulse"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <svg class="w-8 h-8 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            </div>
                        </div>
                    </div>
                    <h3 class="text-xl font-semibold text-white text-center mb-2">{{ __('common.processing_title') }}</h3>
                    <p class="text-indigo-400 text-sm text-center mb-6" id="statusText">{{ __('common.processing_step1') }}</p>
                    <div class="relative w-full h-2 bg-indigo-900/30 rounded-full overflow-hidden mb-2">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-500 h-full transition-all duration-500 ease-out rounded-full" style="width: 0%" id="progressBar"></div>
                    </div>
                    <p class="text-indigo-500 text-xs text-right" id="percentage">0%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Email Sent Modal -->
    <div id="emailSentModal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-[#0B1120]"></div>
        <div class="relative z-10 flex flex-col items-center justify-center min-h-screen px-4">
            <div class="w-24 h-24 bg-emerald-500/10 rounded-full flex items-center justify-center mb-8">
                <svg class="w-12 h-12 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <h2 class="text-3xl font-bold text-white mb-4">{{ __('common.processing_success_title') }}</h2>
            <p class="text-indigo-300 text-center max-w-md mb-8">{{ __('common.processing_success_text') }}</p>
            <button onclick="document.getElementById('emailSentModal').classList.add('hidden'); resetForm();"
                class="px-8 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-medium rounded-lg transition-colors">
                {{ __('common.processing_success_btn') }}
            </button>
        </div>
    </div>

    <!-- Login Modal -->
    <div id="loginModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center">
        <div class="absolute inset-0 bg-[#0B1120]/98 backdrop-blur-sm" onclick="closeLoginModal()"></div>
        <div class="relative z-10 max-w-md w-full px-6">
            <div class="bg-[#111827] rounded-2xl border border-white/10 p-8 shadow-2xl relative">
                <button onclick="closeLoginModal()" class="absolute top-4 right-4 text-indigo-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <div id="loginSuccessState" class="hidden">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-emerald-500/10 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">{{ __('common.login_success_title') }}</h3>
                        <p class="text-indigo-300 text-sm mb-6">{{ __('common.login_success_text') }}</p>
                    </div>
                </div>
                <div id="loginFormState">
                    <div class="text-center mb-6">
                        <h3 class="text-xl font-bold text-white">{{ __('common.login_title') }}</h3>
                        <p class="text-indigo-300 text-sm mt-1">{{ __('common.login_subtitle') }}</p>
                    </div>
                    <form id="loginForm" action="{{ locale_route('magic.login.send') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <input type="email" name="email" id="loginEmail" required autocomplete="email"
                                class="w-full bg-[#0f172a] border border-indigo-800/50 rounded-lg px-4 py-3 text-white placeholder-indigo-500 focus:border-indigo-500 focus:outline-none text-center"
                                placeholder="{{ __('common.form_email_placeholder') }}" autofocus>
                            <p id="loginError" class="text-red-400 text-sm mt-2 text-center hidden"></p>
                        </div>
                        <!-- Cloudflare Turnstile -->
                        <div class="cf-turnstile mb-4 flex justify-center" data-sitekey="{{ config('services.turnstile.site_key') }}" data-theme="dark"></div>
                        <button type="submit" id="loginSubmitBtn"
                            class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-500 text-white font-medium rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            {{ __('common.login_submit') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
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
            loginCaptchaError: @json(__('common.login_captcha_error')),
            loginSending: @json(__('common.login_sending')),
            loginSubmit: @json(__('common.login_submit')),
            loginNetworkError: @json(__('common.login_network_error')),
        };

        // Form validation
        const calcForm = document.getElementById('calcForm');
        const submitBtn = document.getElementById('submit-btn');
        const nameInput = document.querySelector('input[name="name"]');
        const emailInput = document.querySelector('input[name="email"]');
        const genderInputs = document.querySelectorAll('input[name="gender"]');
        const purposeInput = document.querySelector('input[name="purpose"]');
        const birthDateInput = document.getElementById('birth_date');
        const birthTimeInput = document.getElementById('birth_time');
        const cityIdInputValidation = document.getElementById('city_id');

        function validateForm() {
            if (!nameInput || !emailInput || !submitBtn) return;
            const name = nameInput.value.trim();
            const email = emailInput.value.trim();
            const gender = Array.from(genderInputs).some(input => input.checked);
            const purpose = purposeInput ? purposeInput.value : 'general';
            const birthDate = birthDateInput.value;
            const birthTime = birthTimeInput.value;
            const cityId = cityIdInputValidation.value;
            submitBtn.disabled = !(name && email && gender && purpose && birthDate && birthTime && cityId);
        }

        // Date/Time dropdowns
        const birthDaySelect = document.getElementById('birth_day');
        const birthMonthSelect = document.getElementById('birth_month');
        const birthYearSelect = document.getElementById('birth_year');
        const birthHourSelect = document.getElementById('birth_hour');
        const birthMinuteSelect = document.getElementById('birth_minute');
        const timeUnknownCheckbox = document.getElementById('time_unknown');

        if (birthDaySelect) {
            for (let d = 1; d <= 31; d++) {
                const opt = document.createElement('option');
                opt.value = String(d).padStart(2, '0');
                opt.textContent = d;
                birthDaySelect.appendChild(opt);
            }
        }
        if (birthYearSelect) {
            const curYear = new Date().getFullYear();
            for (let y = curYear; y >= 1920; y--) {
                const opt = document.createElement('option');
                opt.value = y; opt.textContent = y;
                birthYearSelect.appendChild(opt);
            }
        }
        if (birthHourSelect) {
            for (let h = 0; h <= 23; h++) {
                const opt = document.createElement('option');
                opt.value = String(h).padStart(2, '0');
                opt.textContent = String(h).padStart(2, '0');
                birthHourSelect.appendChild(opt);
            }
        }
        if (birthMinuteSelect) {
            for (let m = 0; m <= 59; m += 5) {
                const opt = document.createElement('option');
                opt.value = String(m).padStart(2, '0');
                opt.textContent = String(m).padStart(2, '0');
                birthMinuteSelect.appendChild(opt);
            }
        }

        function syncBirthDate() {
            const d = birthDaySelect?.value, mo = birthMonthSelect?.value, y = birthYearSelect?.value;
            if (birthDateInput) birthDateInput.value = (d && mo && y) ? `${y}-${mo}-${d}` : '';
            validateForm();
        }
        function syncBirthTime() {
            if (timeUnknownCheckbox?.checked) {
                if (birthTimeInput) birthTimeInput.value = '12:00';
                if (birthHourSelect) { birthHourSelect.disabled = true; birthHourSelect.classList.add('opacity-50'); }
                if (birthMinuteSelect) { birthMinuteSelect.disabled = true; birthMinuteSelect.classList.add('opacity-50'); }
            } else {
                if (birthHourSelect) { birthHourSelect.disabled = false; birthHourSelect.classList.remove('opacity-50'); }
                if (birthMinuteSelect) { birthMinuteSelect.disabled = false; birthMinuteSelect.classList.remove('opacity-50'); }
                const h = birthHourSelect?.value, mi = birthMinuteSelect?.value;
                if (birthTimeInput) birthTimeInput.value = (h && mi) ? `${h}:${mi}` : '';
            }
            validateForm();
        }

        birthDaySelect?.addEventListener('change', syncBirthDate);
        birthMonthSelect?.addEventListener('change', syncBirthDate);
        birthYearSelect?.addEventListener('change', syncBirthDate);
        birthHourSelect?.addEventListener('change', syncBirthTime);
        birthMinuteSelect?.addEventListener('change', syncBirthTime);
        timeUnknownCheckbox?.addEventListener('change', syncBirthTime);
        nameInput?.addEventListener('input', validateForm);
        emailInput?.addEventListener('input', validateForm);
        genderInputs.forEach(input => input.addEventListener('change', validateForm));
        validateForm();

        // Turnstile callback - submit form after token received
        let calcTurnstileToken = null;
        window.onCalcTurnstileSuccess = function(token) {
            calcTurnstileToken = token;
            if (window.pendingCalcSubmit) {
                window.pendingCalcSubmit();
            }
        };

        // Form submission with Turnstile
        if (calcForm) {
            calcForm.addEventListener('submit', async function (e) {
                e.preventDefault();
                const form = this;

                // If no token yet, execute turnstile and wait
                if (!calcTurnstileToken) {
                    document.getElementById('loadingState')?.classList.remove('hidden');
                    window.pendingCalcSubmit = () => submitCalcForm(form);
                    turnstile.execute('#turnstile-widget');
                    return;
                }

                submitCalcForm(form);
            });
        }

        async function submitCalcForm(form) {
            document.getElementById('loadingState')?.classList.remove('hidden');

            try {
                const formData = new FormData(form);
                formData.set('cf-turnstile-response', calcTurnstileToken);

                const response = await fetch(CALC_URL, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    if (!response.ok) {
                        const errData = await response.json();
                        let msg = errData.message || TRANSLATIONS.error;
                        if (errData.errors) {
                            const first = Object.values(errData.errors)[0];
                            if (Array.isArray(first)) msg = first[0];
                        }
                        throw new Error(msg);
                    }

                    const data = await response.json();
                    if (data.success === false) {
                        alert(data.message || TRANSLATIONS.error);
                        validateForm();
                        return;
                    }

                    const modal = document.getElementById('processingModal');
                    if (modal) modal.classList.remove('hidden');

                    const steps = [
                        { pct: 15, text: TRANSLATIONS.step1 },
                        { pct: 30, text: TRANSLATIONS.step2 },
                        { pct: 55, text: TRANSLATIONS.step3 },
                        { pct: 75, text: TRANSLATIONS.step4 },
                        { pct: 100, text: TRANSLATIONS.done }
                    ];

                    let curStep = 0;
                    const progressBar = document.getElementById('progressBar');
                    const statusText = document.getElementById('statusText');
                    const pctText = document.getElementById('percentage');

                    function nextStep() {
                        if (curStep >= steps.length) return;
                        const s = steps[curStep];
                        if (progressBar) progressBar.style.width = s.pct + '%';
                        if (statusText) statusText.innerText = s.text;
                        if (pctText) pctText.innerText = s.pct + '%';
                        curStep++;
                        if (curStep < steps.length) setTimeout(nextStep, 800);
                    }
                    nextStep();

                    setTimeout(() => {
                        if (data.redirect && data.redirect.includes('charts/show')) {
                            window.location.href = data.redirect;
                        } else {
                            document.getElementById('processingModal').classList.add('hidden');
                            document.getElementById('emailSentModal').classList.remove('hidden');
                        }
                    }, 4000);

                } catch (error) {
                    console.error('Error:', error);
                    document.getElementById('processingModal')?.classList.add('hidden');
                    alert(error.message || TRANSLATIONS.error);
                    validateForm();
                }
            });
        }

        function resetForm() {
            if (calcForm) calcForm.reset();
            ['city_id','birth_date','birth_time'].forEach(id => { const el = document.getElementById(id); if (el) el.value = ''; });
            ['birth_day','birth_month','birth_year','birth_hour','birth_minute'].forEach(id => { const el = document.getElementById(id); if (el) el.value = ''; });
            document.getElementById('city-details')?.classList.add('hidden');
            document.getElementById('form-errors')?.classList.add('hidden');
            const tu = document.getElementById('time_unknown');
            if (tu) { tu.checked = false; }
            if (birthHourSelect) { birthHourSelect.disabled = false; birthHourSelect.classList.remove('opacity-50'); }
            if (birthMinuteSelect) { birthMinuteSelect.disabled = false; birthMinuteSelect.classList.remove('opacity-50'); }
            validateForm();
        }

        // City search
        const searchInput = document.getElementById('birth_place_search');
        const cityIdInput = document.getElementById('city_id');
        const dropdown = document.getElementById('cities-dropdown');
        const cityDetails = document.getElementById('city-details');
        const displayCountry = document.getElementById('display-country');
        const displayCity = document.getElementById('display-city');
        const displayLatitude = document.getElementById('display-latitude');
        const displayLongitude = document.getElementById('display-longitude');
        const searchSpinner = document.getElementById('search-spinner');
        const searchIcon = document.getElementById('search-icon');
        const cityWarning = document.getElementById('city-warning');
        let searchTimeout = null, currentQuery = '', lastSearchResults = [];

        function showSpinner() { searchSpinner?.classList.remove('hidden'); searchIcon?.classList.add('hidden'); }
        function hideSpinner() { searchSpinner?.classList.add('hidden'); searchIcon?.classList.remove('hidden'); }

        // Convert ISO 2-letter country code to flag emoji
        function getFlag(code) {
            if (!code || code.length < 2) return '🌍';
            // Try to convert 2-letter ISO code to regional indicator symbols (flag emoji)
            const c = code.toUpperCase().slice(0, 2);
            if (/^[A-Z]{2}$/.test(c)) {
                return String.fromCodePoint(...[...c].map(l => 0x1F1E6 + l.charCodeAt(0) - 65));
            }
            return '🌍';
        }

        function renderCities(cities) {
            if (!dropdown) return;
            dropdown.innerHTML = '';
            if (cities.length === 0) {
                dropdown.innerHTML = '<div class="px-4 py-4 text-center"><div class="text-amber-400 text-sm mb-1"><i class="fas fa-exclamation-circle mr-2"></i>' + TRANSLATIONS.cityNotFound + '</div><div class="text-indigo-400/60 text-xs">' + TRANSLATIONS.cityEnglishOnly + '</div></div>';
                dropdown.classList.remove('hidden');
                return;
            }
            cities.forEach(city => {
                const div = document.createElement('div');
                div.className = 'city-option px-4 py-3 hover:bg-indigo-600/30 cursor-pointer border-b border-white/5 last:border-0 transition-all';
                div.dataset.cityId = city.id;
                div.dataset.cityName = city.name;
                div.dataset.cityCountry = city.country;
                div.dataset.cityLat = city.latitude;
                div.dataset.cityLon = city.longitude;
                div.dataset.cityTz = city.timezone_gmt;
                const flag = getFlag(city.country);
                div.innerHTML = `
                    <div class="flex items-center gap-3">
                        <span class="text-xl flex-shrink-0">${flag}</span>
                        <div class="flex-1 min-w-0">
                            <div class="text-white font-medium truncate">${city.name}</div>
                            <div class="text-indigo-400/60 text-xs">${city.country}</div>
                        </div>
                        <i class="fas fa-chevron-right text-indigo-500/40 text-xs"></i>
                    </div>`;
                div.addEventListener('click', function() { selectCity(this); });
                dropdown.appendChild(div);
            });
            dropdown.classList.remove('hidden');
        }

        async function searchCities(query) {
            query = query.trim();
            if (query.length < 2) { dropdown?.classList.add('hidden'); hideSpinner(); return; }
            currentQuery = query;
            showSpinner();
            try {
                const resp = await fetch(`/cities/search/${encodeURIComponent(query)}`);
                const cities = await resp.json();
                if (query === currentQuery) { lastSearchResults = cities; renderCities(cities); hideSpinner(); }
            } catch (err) { console.error(err); hideSpinner(); }
        }

        function selectCity(el) {
            if (!searchInput || !cityIdInput) return;
            searchInput.value = el.dataset.cityName;
            cityIdInput.value = el.dataset.cityId;
            if (displayCountry) displayCountry.textContent = el.dataset.cityCountry;
            if (displayCity) displayCity.textContent = el.dataset.cityName;
            if (displayLatitude) displayLatitude.textContent = parseFloat(el.dataset.cityLat).toFixed(2) + '°';
            if (displayLongitude) displayLongitude.textContent = parseFloat(el.dataset.cityLon).toFixed(2) + '°';
            cityDetails?.classList.remove('hidden');
            dropdown?.classList.add('hidden');
            cityWarning?.classList.add('hidden');
            validateForm();
        }

        if (searchInput && dropdown && cityIdInput) {
            searchInput.addEventListener('input', function () {
                if (cityIdInput.value) { cityIdInput.value = ''; cityDetails?.classList.add('hidden'); validateForm(); }
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => searchCities(this.value), 300);
            });
            searchInput.addEventListener('focus', function () {
                cityWarning?.classList.add('hidden');
                if (this.value.trim().length >= 2) searchCities(this.value);
            });
            document.addEventListener('click', function (e) {
                if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) dropdown.classList.add('hidden');
            });
            searchInput.addEventListener('keydown', function(e) {
                const opts = dropdown.querySelectorAll('.city-option');
                const active = dropdown.querySelector('.city-option.active');
                let idx = Array.from(opts).indexOf(active);
                if (e.key === 'ArrowDown') { e.preventDefault(); if (active) active.classList.remove('active'); idx = (idx + 1) % opts.length; opts[idx]?.classList.add('active'); opts[idx]?.scrollIntoView({ block: 'nearest' }); }
                else if (e.key === 'ArrowUp') { e.preventDefault(); if (active) active.classList.remove('active'); idx = idx <= 0 ? opts.length - 1 : idx - 1; opts[idx]?.classList.add('active'); opts[idx]?.scrollIntoView({ block: 'nearest' }); }
                else if (e.key === 'Enter') { e.preventDefault(); if (active) selectCity(active); else if (opts.length === 1) selectCity(opts[0]); }
                else if (e.key === 'Escape') dropdown.classList.add('hidden');
            });
            searchInput.addEventListener('blur', function() {
                setTimeout(() => {
                    if (!cityIdInput.value && searchInput.value.trim().length > 0) {
                        const opts = dropdown.querySelectorAll('.city-option');
                        if (opts.length === 1) selectCity(opts[0]);
                        else if (lastSearchResults.length === 1) {
                            const c = lastSearchResults[0];
                            const fake = { dataset: { cityId: c.id, cityName: c.name, cityCountry: c.country, cityLat: c.latitude, cityLon: c.longitude, cityTz: c.timezone_gmt } };
                            selectCity(fake);
                        } else { cityWarning?.classList.remove('hidden'); }
                    }
                    dropdown?.classList.add('hidden');
                }, 200);
            });
        }

        // Login modal
        function closeLoginModal() {
            document.getElementById('loginModal')?.classList.add('hidden');
            document.getElementById('loginFormState')?.classList.remove('hidden');
            document.getElementById('loginSuccessState')?.classList.add('hidden');
            document.getElementById('loginForm')?.reset();
            const btn = document.getElementById('loginSubmitBtn');
            if (btn) { btn.disabled = false; btn.textContent = TRANSLATIONS.loginSubmit; }
            document.getElementById('loginError')?.classList.add('hidden');
        }

        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const btn = document.getElementById('loginSubmitBtn');
                const errEl = document.getElementById('loginError');
                errEl?.classList.add('hidden');

                if (btn) { btn.disabled = true; btn.textContent = TRANSLATIONS.loginSending; }

                try {
                    const resp = await fetch(this.action, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json' },
                        body: formData
                    });
                    const data = await resp.json();
                    if (data.errors) {
                        const first = data.errors.email?.[0] || data.errors.cf_turnstile_response?.[0] || TRANSLATIONS.loginNetworkError;
                        if (errEl) { errEl.textContent = first; errEl.classList.remove('hidden'); }
                        if (btn) { btn.disabled = false; btn.textContent = TRANSLATIONS.loginSubmit; }
                    } else {
                        document.getElementById('loginFormState')?.classList.add('hidden');
                        document.getElementById('loginSuccessState')?.classList.remove('hidden');
                    }
                } catch (err) {
                    if (errEl) { errEl.textContent = TRANSLATIONS.loginNetworkError; errEl.classList.remove('hidden'); }
                    if (btn) { btn.disabled = false; btn.textContent = TRANSLATIONS.loginSubmit; }
                }
            });
        }

        // Auto-open login modal
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('login') === 'true') {
            document.getElementById('loginModal')?.classList.remove('hidden');
            const ep = urlParams.get('email');
            const le = document.getElementById('loginEmail');
            if (ep && le) le.value = ep;
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    </script>
</body>
</html>
