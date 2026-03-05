@php
    $currentLocale = app()->getLocale();
    $locales = config('app.available_locales', ['en']);
    $baseUrl = config('app.url', 'https://natalscope.com');
    $ogLocaleMap = ['en' => 'en_US', 'fr' => 'fr_FR', 'es' => 'es_ES', 'pt' => 'pt_BR', 'hi' => 'hi_IN', 'ru' => 'ru_RU'];
    $displayDate = now()->format('Y-m-d');
    $formattedDate = now()->locale($currentLocale)->translatedFormat('F j, Y');
    $metaTitle = __('horoscope.meta_title', ['sign' => $signName, 'date' => $formattedDate]);

    $content = $horoscope->content;
    $scores = $content['scores'] ?? ['overall' => 75, 'love' => 70, 'career' => 80, 'health' => 75, 'luck' => 65];
    $element = $content['element'] ?? 'fire';
    $rulingPlanet = $content['ruling_planet'] ?? 'sun';
    $compatibleSigns = $content['compatible_signs'] ?? ['leo', 'sagittarius', 'gemini'];
    $mood = $content['mood'] ?? 'Energetic';
    $luckyColor = $content['lucky_color'] ?? 'gold';
    $luckyNumber = $content['lucky_number'] ?? rand(1, 99);

    $elementColors = [
        'fire' => ['from-orange-500/20', 'to-red-600/20', 'border-orange-500/30', 'text-orange-400'],
        'earth' => ['from-green-500/20', 'to-emerald-600/20', 'border-green-500/30', 'text-green-400'],
        'air' => ['from-sky-500/20', 'to-cyan-600/20', 'border-sky-500/30', 'text-sky-400'],
        'water' => ['from-blue-500/20', 'to-indigo-600/20', 'border-blue-500/30', 'text-blue-400'],
    ];
    $ec = $elementColors[$element] ?? $elementColors['fire'];

@endphp
<!DOCTYPE html>
<html lang="{{ $currentLocale }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ __('horoscope.meta_description', ['sign' => $signName, 'date' => $formattedDate]) }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ horoscope_url_for_locale($sign, $currentLocale) }}">
    @foreach($locales as $loc)
    <link rel="alternate" hreflang="{{ $loc }}" href="{{ horoscope_url_for_locale($sign, $loc) }}">
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ horoscope_url_for_locale($sign, 'en') }}">

    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ horoscope_url_for_locale($sign, $currentLocale) }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ Str::limit($content['overview'] ?? '', 150) }}">
    <meta property="og:image" content="{{ asset('images/zodiac/' . $sign . '.webp') }}">
    <meta property="og:locale" content="{{ $ogLocaleMap[$currentLocale] ?? 'en_US' }}">
    <meta property="og:site_name" content="NatalScope">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:image" content="{{ asset('images/zodiac/' . $sign . '.webp') }}">

    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@type": "Article",
        "headline": "{{ $signName }} Horoscope {{ $formattedDate }}",
        "datePublished": "{{ $displayDate }}",
        "dateModified": "{{ $displayDate }}",
        "image": "{{ asset('images/zodiac/' . $sign . '.webp') }}",
        "author": { "@type": "Organization", "name": "NatalScope" },
        "publisher": { "@type": "Organization", "name": "NatalScope", "url": "{{ $baseUrl }}" }
    }
    </script>

    @if(config('services.google_analytics.id'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.google_analytics.id') }}"></script>
    <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{{ config('services.google_analytics.id') }}');</script>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.1.0/js/all.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html, body { background-color: #0B1120; color: #e2e8f0; }
        .hero-gradient {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.15) 0%, rgba(139, 92, 246, 0.1) 50%, rgba(236, 72, 153, 0.1) 100%);
        }
        .constellation-bg {
            background-image:
                radial-gradient(2px 2px at 20% 30%, rgba(255,255,255,0.15) 0%, transparent 100%),
                radial-gradient(2px 2px at 40% 70%, rgba(255,255,255,0.1) 0%, transparent 100%),
                radial-gradient(1px 1px at 60% 20%, rgba(255,255,255,0.12) 0%, transparent 100%),
                radial-gradient(2px 2px at 80% 50%, rgba(255,255,255,0.08) 0%, transparent 100%),
                radial-gradient(1px 1px at 90% 80%, rgba(255,255,255,0.1) 0%, transparent 100%);
        }
        .progress-bar {
            background: linear-gradient(90deg, #6366f1 0%, #8b5cf6 50%, #a855f7 100%);
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.3);
        }
        .progress-bar-love { background: linear-gradient(90deg, #ec4899 0%, #f43f5e 100%); box-shadow: 0 0 20px rgba(236, 72, 153, 0.3); }
        .progress-bar-career { background: linear-gradient(90deg, #f59e0b 0%, #f97316 100%); box-shadow: 0 0 20px rgba(245, 158, 11, 0.3); }
        .progress-bar-health { background: linear-gradient(90deg, #10b981 0%, #14b8a6 100%); box-shadow: 0 0 20px rgba(16, 185, 129, 0.3); }
        .progress-bar-luck { background: linear-gradient(90deg, #8b5cf6 0%, #6366f1 100%); box-shadow: 0 0 20px rgba(139, 92, 246, 0.3); }
        .card-glow {
            box-shadow: 0 0 40px -10px rgba(99, 102, 241, 0.15);
        }
        .zodiac-image {
            filter: drop-shadow(0 0 30px rgba(99, 102, 241, 0.3));
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .score-circle {
            background: conic-gradient(from 0deg, #6366f1 calc(var(--score) * 3.6deg), rgba(255,255,255,0.1) 0deg);
        }
        .horoscope-content p { margin: 0 0 0.5rem; }
        .horoscope-content p:last-child { margin-bottom: 0; }
        .horoscope-content strong { color: #e2e8f0; font-weight: 600; }
        .horoscope-content em { color: #a5b4fc; }
        .horoscope-content ul, .horoscope-content ol { margin: 0.25rem 0 0.5rem 1.25rem; }
        .horoscope-content li { margin-bottom: 0.15rem; }
    </style>
</head>
<body class="font-sans antialiased constellation-bg min-h-screen">
    <!-- Navigation -->
    <nav class="border-b border-white/5 bg-[#0B1120]/80 backdrop-blur-xl sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 py-3 sm:py-4 flex justify-between items-center">
            <a href="{{ locale_route('welcome') }}" class="text-lg sm:text-xl font-bold text-white tracking-tight">
                NATAL<span class="text-indigo-400">SCOPE</span>
            </a>
            <div class="flex items-center gap-2 sm:gap-4">
                <a href="{{ locale_route('horoscope.index') }}" class="text-indigo-300 text-xs sm:text-sm hover:text-white transition-colors flex items-center gap-1 sm:gap-2">
                    <i class="fas fa-th-large sm:hidden"></i>
                    <span class="hidden sm:inline">{{ __('horoscope.all_signs') }}</span>
                </a>
                <a href="{{ locale_route('welcome') }}#heroSection" class="px-3 sm:px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white text-xs sm:text-sm font-semibold rounded-lg transition-all shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40">
                    <i class="fas fa-star mr-1"></i>
                    <span class="hidden sm:inline">{{ __('horoscope.cta_button') }}</span>
                    <span class="sm:hidden">{{ __('common.nav_get_chart') }}</span>
                </a>
                <!-- Language Switcher -->
                @php
                    $langFlags = ['en' => '🇬🇧', 'ru' => '🇷🇺', 'es' => '🇪🇸', 'fr' => '🇫🇷', 'pt' => '🇧🇷', 'hi' => '🇮🇳'];
                    $appBaseUrl = rtrim(config('app.url', request()->getSchemeAndHttpHost()), '/');
                @endphp
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" class="flex items-center gap-1 text-indigo-300 text-sm hover:text-white transition-colors px-2 py-1 rounded-md hover:bg-white/5">
                        <span class="text-base">{{ $langFlags[$currentLocale] ?? '🌐' }}</span>
                        <svg class="w-3 h-3 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition class="absolute right-0 mt-2 w-44 bg-[#1e293b] border border-indigo-900/50 rounded-lg shadow-xl overflow-hidden z-50">
                        @foreach($locales as $loc)
                        <a href="{{ $appBaseUrl }}{{ $loc === 'en' ? '' : '/' . $loc }}/horoscope/{{ $sign }}"
                           class="flex items-center gap-2 px-4 py-2.5 text-sm transition-colors {{ $loc === $currentLocale ? 'text-indigo-400 bg-indigo-900/30' : 'text-indigo-200 hover:bg-indigo-900/20 hover:text-white' }}">
                            <span class="text-base">{{ $langFlags[$loc] ?? '🌐' }}</span>
                            {{ __('common.lang_' . $loc) }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero-gradient border-b border-white/5">
        <div class="max-w-6xl mx-auto px-4 py-8 sm:py-12">
            <div class="flex flex-col lg:flex-row items-center gap-8">
                <!-- Zodiac Image -->
                <div class="relative flex-shrink-0">
                    <div class="w-40 h-40 sm:w-52 sm:h-52 rounded-full bg-gradient-to-br {{ $ec[0] }} {{ $ec[1] }} p-1 animate-float">
                        <div class="w-full h-full rounded-full bg-[#0B1120] flex items-center justify-center overflow-hidden">
                            <img src="{{ asset('images/zodiac/' . $sign . '.webp') }}"
                                 alt="{{ $signName }}"
                                 class="w-32 h-32 sm:w-44 sm:h-44 object-contain zodiac-image">
                        </div>
                    </div>
                </div>

                <!-- Sign Info -->
                <div class="flex-1 text-center lg:text-left">
                    <div class="flex items-center justify-center lg:justify-start gap-2 mb-2">
                        <span class="px-3 py-1 rounded-full text-xs font-medium uppercase tracking-wider {{ $ec[3] }} bg-white/5 border {{ $ec[2] }}">
                            {{ __('horoscope.element_' . $element) }}
                        </span>
                        <span class="px-3 py-1 rounded-full text-xs font-medium text-indigo-300 bg-white/5 border border-indigo-500/20">
                            {{ $formattedDate }}
                        </span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-3">
                        {{ $signName }}
                        <span class="text-indigo-400">{{ __('horoscope.today') }}</span>
                    </h1>
                    <p class="text-indigo-200/80 text-lg max-w-xl">
                        {{ __('horoscope.ruled_by') }} <span class="text-indigo-300 font-medium">{{ __('astrology.planet_' . $rulingPlanet) }}</span>
                    </p>

                    <!-- Quick Stats -->
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-3 sm:gap-4 mt-5">
                        <div class="flex items-center gap-2 text-sm bg-white/5 rounded-lg px-3 py-2 border border-white/10">
                            <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-amber-500/20 flex items-center justify-center">
                                <i class="fas fa-hashtag text-amber-400 text-xs sm:text-sm"></i>
                            </div>
                            <div>
                                <div class="text-amber-400 font-bold text-sm sm:text-base">{{ $luckyNumber }}</div>
                                <div class="text-indigo-400/60 text-[10px] sm:text-xs">{{ __('horoscope.lucky_number') }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-sm bg-white/5 rounded-lg px-3 py-2 border border-white/10">
                            <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-purple-500/20 flex items-center justify-center">
                                <i class="fas fa-palette text-purple-400 text-xs sm:text-sm"></i>
                            </div>
                            <div>
                                <div class="text-purple-400 font-bold capitalize text-sm sm:text-base">{{ __('horoscope.color_' . $luckyColor) }}</div>
                                <div class="text-indigo-400/60 text-[10px] sm:text-xs">{{ __('horoscope.lucky_color') }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-sm bg-white/5 rounded-lg px-3 py-2 border border-white/10">
                            <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-pink-500/20 flex items-center justify-center">
                                <i class="fas fa-face-smile text-pink-400 text-xs sm:text-sm"></i>
                            </div>
                            <div>
                                <div class="text-pink-400 font-bold text-sm sm:text-base">{{ $mood }}</div>
                                <div class="text-indigo-400/60 text-[10px] sm:text-xs">{{ __('horoscope.mood') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Overall Score Circle -->
                <div class="flex-shrink-0 text-center">
                    <div class="relative w-32 h-32 sm:w-40 sm:h-40">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                            <circle cx="50" cy="50" r="45" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="8"/>
                            <circle cx="50" cy="50" r="45" fill="none" stroke="url(#scoreGradient)" stroke-width="8"
                                    stroke-dasharray="{{ $scores['overall'] * 2.83 }} 283" stroke-linecap="round"/>
                            <defs>
                                <linearGradient id="scoreGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%" style="stop-color:#6366f1"/>
                                    <stop offset="100%" style="stop-color:#a855f7"/>
                                </linearGradient>
                            </defs>
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-4xl sm:text-5xl font-bold text-white">{{ $scores['overall'] }}</span>
                            <span class="text-indigo-400 text-xs uppercase tracking-wider">{{ __('horoscope.score') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-6 sm:py-8">
        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Daily Overview -->
                <article class="rounded-2xl bg-gradient-to-br from-white/[0.07] to-white/[0.03] border border-white/10 p-6 card-glow">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-indigo-500/20 flex items-center justify-center">
                            <i class="fas fa-sun text-indigo-400"></i>
                        </div>
                        <h2 class="text-xl font-bold text-white">{{ __('horoscope.daily_overview') }}</h2>
                    </div>
                    <div class="text-indigo-100/90 leading-relaxed text-lg horoscope-content">{!! Str::markdown($content['overview'] ?? '') !!}</div>
                </article>

                <!-- Rating Cards Grid -->
                <div class="grid sm:grid-cols-2 gap-4">
                    <!-- Love -->
                    <div class="rounded-2xl bg-gradient-to-br from-pink-500/10 to-rose-500/5 border border-pink-500/20 p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-pink-500/20 flex items-center justify-center">
                                    <i class="fas fa-heart text-pink-400"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-white">{{ __('horoscope.love') }}</h3>
                            </div>
                            <span class="text-2xl font-bold text-pink-400">{{ $scores['love'] }}%</span>
                        </div>
                        <div class="w-full h-2 bg-white/10 rounded-full overflow-hidden mb-3">
                            <div class="h-full progress-bar-love rounded-full transition-all duration-1000" style="width: {{ $scores['love'] }}%"></div>
                        </div>
                        <div class="text-indigo-200/80 text-sm leading-relaxed horoscope-content">{!! Str::markdown($content['love'] ?? '') !!}</div>
                    </div>

                    <!-- Career -->
                    <div class="rounded-2xl bg-gradient-to-br from-amber-500/10 to-orange-500/5 border border-amber-500/20 p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-amber-500/20 flex items-center justify-center">
                                    <i class="fas fa-briefcase text-amber-400"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-white">{{ __('horoscope.career') }}</h3>
                            </div>
                            <span class="text-2xl font-bold text-amber-400">{{ $scores['career'] }}%</span>
                        </div>
                        <div class="w-full h-2 bg-white/10 rounded-full overflow-hidden mb-3">
                            <div class="h-full progress-bar-career rounded-full transition-all duration-1000" style="width: {{ $scores['career'] }}%"></div>
                        </div>
                        <div class="text-indigo-200/80 text-sm leading-relaxed horoscope-content">{!! Str::markdown($content['career'] ?? '') !!}</div>
                    </div>

                    <!-- Health -->
                    <div class="rounded-2xl bg-gradient-to-br from-emerald-500/10 to-teal-500/5 border border-emerald-500/20 p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center">
                                    <i class="fas fa-heart-pulse text-emerald-400"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-white">{{ __('horoscope.health') }}</h3>
                            </div>
                            <span class="text-2xl font-bold text-emerald-400">{{ $scores['health'] }}%</span>
                        </div>
                        <div class="w-full h-2 bg-white/10 rounded-full overflow-hidden mb-3">
                            <div class="h-full progress-bar-health rounded-full transition-all duration-1000" style="width: {{ $scores['health'] }}%"></div>
                        </div>
                        <div class="text-indigo-200/80 text-sm leading-relaxed horoscope-content">{!! Str::markdown($content['health'] ?? '') !!}</div>
                    </div>

                    <!-- Luck -->
                    <div class="rounded-2xl bg-gradient-to-br from-violet-500/10 to-purple-500/5 border border-violet-500/20 p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-violet-500/20 flex items-center justify-center">
                                    <i class="fas fa-clover text-violet-400"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-white">{{ __('horoscope.luck') }}</h3>
                            </div>
                            <span class="text-2xl font-bold text-violet-400">{{ $scores['luck'] }}%</span>
                        </div>
                        <div class="w-full h-2 bg-white/10 rounded-full overflow-hidden mb-3">
                            <div class="h-full progress-bar-luck rounded-full transition-all duration-1000" style="width: {{ $scores['luck'] }}%"></div>
                        </div>
                        <p class="text-indigo-200/80 text-sm">{{ __('horoscope.luck_description') }}</p>
                    </div>
                </div>

            </div>

            <!-- Sidebar -->
            <aside class="space-y-6">
                <!-- Compatible Signs -->
                <div class="rounded-2xl bg-gradient-to-br from-white/[0.07] to-white/[0.03] border border-white/10 p-5">
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                        <i class="fas fa-heart-circle-check text-pink-400"></i>
                        {{ __('horoscope.compatible_signs') }}
                    </h3>
                    <div class="space-y-3">
                        @foreach($compatibleSigns as $compatSign)
                        <a href="{{ locale_route('horoscope.sign', ['sign' => $compatSign]) }}"
                           class="flex items-center gap-3 p-3 rounded-xl bg-white/5 hover:bg-white/10 border border-white/5 hover:border-indigo-500/30 transition-all group">
                            <img src="{{ asset('images/zodiac/' . $compatSign . '.webp') }}"
                                 alt="{{ __('astrology.sign_' . $compatSign) }}"
                                 class="w-10 h-10 object-contain">
                            <div class="flex-1">
                                <div class="text-white font-medium group-hover:text-indigo-300 transition-colors">
                                    {{ __('astrology.sign_' . $compatSign) }}
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-indigo-400/40 group-hover:text-indigo-400 transition-colors"></i>
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Sign Info -->
                <div class="rounded-2xl bg-gradient-to-br from-white/[0.07] to-white/[0.03] border border-white/10 p-5">
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                        <i class="fas fa-info-circle text-indigo-400"></i>
                        {{ __('horoscope.about_sign', ['sign' => $signName]) }}
                    </h3>
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between items-center py-2 border-b border-white/5">
                            <dt class="text-indigo-400/80">{{ __('horoscope.element') }}</dt>
                            <dd class="text-white font-medium flex items-center gap-2">
                                @if($element === 'fire')<i class="fas fa-fire text-orange-400"></i>@endif
                                @if($element === 'earth')<i class="fas fa-mountain text-green-400"></i>@endif
                                @if($element === 'air')<i class="fas fa-wind text-sky-400"></i>@endif
                                @if($element === 'water')<i class="fas fa-water text-blue-400"></i>@endif
                                {{ __('horoscope.element_' . $element) }}
                            </dd>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-white/5">
                            <dt class="text-indigo-400/80">{{ __('horoscope.ruling_planet') }}</dt>
                            <dd class="text-white font-medium flex items-center gap-2">
                                <img src="{{ asset('images/planets/' . $rulingPlanet . '.webp') }}" alt="" class="w-5 h-5">
                                {{ __('astrology.planet_' . $rulingPlanet) }}
                            </dd>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-white/5">
                            <dt class="text-indigo-400/80">{{ __('horoscope.lucky_number') }}</dt>
                            <dd class="text-amber-400 font-bold">{{ $luckyNumber }}</dd>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <dt class="text-indigo-400/80">{{ __('horoscope.lucky_color') }}</dt>
                            <dd class="text-white font-medium capitalize">{{ __('horoscope.color_' . $luckyColor) }}</dd>
                        </div>
                    </dl>
                </div>

            </aside>
        </div>

        <!-- Full-width CTA Card -->
        <div class="mt-10 relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-900/60 via-purple-900/50 to-indigo-900/60 border border-indigo-500/40 shadow-xl shadow-indigo-500/10">
            <!-- Background decoration -->
            <div class="absolute inset-0 opacity-30 pointer-events-none">
                <div class="absolute -top-10 left-1/4 w-32 h-32 bg-purple-500 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-10 right-1/4 w-40 h-40 bg-indigo-500 rounded-full blur-3xl"></div>
            </div>

            <a href="{{ locale_route('welcome') }}#heroSection" class="relative block p-5 sm:p-6 lg:p-8 group">
                <div class="flex flex-col lg:flex-row items-center gap-4 lg:gap-6">
                    <!-- Icon -->
                    <div class="w-14 h-14 sm:w-16 sm:h-16 lg:w-20 lg:h-20 flex-shrink-0 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/40 group-hover:scale-105 transition-transform">
                        <i class="fas fa-sun text-white text-xl sm:text-2xl lg:text-3xl"></i>
                    </div>

                    <!-- Text -->
                    <div class="flex-1 text-center lg:text-left">
                        <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-2 sm:gap-3 mb-1 sm:mb-2">
                            <h3 class="text-lg sm:text-xl lg:text-2xl text-white font-bold group-hover:text-indigo-200 transition-colors">
                                {{ __('horoscope.cta_title') }}
                            </h3>
                            <span class="px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-full bg-emerald-500/20 border border-emerald-500/40 text-emerald-400 text-xs font-semibold uppercase tracking-wide">
                                {{ __('horoscope.cta_free_badge') }}
                            </span>
                        </div>
                        <p class="text-indigo-200/70 text-sm sm:text-base max-w-xl">
                            {{ __('horoscope.cta_subtitle') }}
                        </p>
                    </div>

                    <!-- Button -->
                    <div class="flex-shrink-0 w-full sm:w-auto mt-2 lg:mt-0">
                        <span class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 sm:px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-400 hover:to-purple-400 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 group-hover:shadow-indigo-500/50 group-hover:scale-[1.02] transition-all">
                            {{ __('horoscope.cta_button_text') }}
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Other Signs -->
        <section class="mt-12">
            <h2 class="text-xl font-bold text-white mb-6">
                {{ __('horoscope.explore_signs') }}
            </h2>
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-12 gap-3">
                @foreach(\App\Models\DailyHoroscope::SIGNS as $s)
                <a href="{{ locale_route('horoscope.sign', ['sign' => $s]) }}"
                   class="group flex flex-col items-center p-3 rounded-xl {{ $s === $sign ? 'bg-indigo-600/20 border-indigo-500/50' : 'bg-white/5 border-white/10 hover:bg-white/10 hover:border-indigo-500/30' }} border transition-all">
                    <img src="{{ asset('images/zodiac/' . $s . '.webp') }}"
                         alt="{{ __('astrology.sign_' . $s) }}"
                         class="w-8 h-8 sm:w-10 sm:h-10 object-contain mb-1 {{ $s === $sign ? 'opacity-100' : 'opacity-70 group-hover:opacity-100' }} transition-opacity">
                    <span class="text-xs {{ $s === $sign ? 'text-indigo-300' : 'text-indigo-400/70 group-hover:text-white' }} transition-colors truncate max-w-full">
                        {{ __('astrology.sign_' . $s) }}
                    </span>
                </a>
                @endforeach
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="border-t border-white/5 mt-12">
        <div class="max-w-6xl mx-auto px-4 py-8">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 text-sm text-indigo-400/80">
                <div class="flex items-center gap-2">
                    <span class="font-bold text-white">NATAL<span class="text-indigo-400">SCOPE</span></span>
                    <span class="text-indigo-400/40">|</span>
                    <span>&copy; {{ date('Y') }}</span>
                </div>
                <div class="flex items-center gap-6">
                    <a href="{{ locale_route('horoscope.index') }}" class="hover:text-white transition-colors">{{ __('horoscope.all_signs') }}</a>
                    <a href="{{ url('/privacy') }}" class="hover:text-white transition-colors">{{ __('common.footer_privacy') }}</a>
                    <a href="{{ url('/terms') }}" class="hover:text-white transition-colors">{{ __('common.footer_terms') }}</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
