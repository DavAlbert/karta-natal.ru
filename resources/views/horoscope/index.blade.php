@php
    $currentLocale = app()->getLocale();
    $locales = config('app.available_locales', ['en']);
    $baseUrl = rtrim(config('app.url', request()->getSchemeAndHttpHost()), '/');
    $langFlags = ['en' => '🇬🇧', 'ru' => '🇷🇺', 'es' => '🇪🇸', 'fr' => '🇫🇷', 'pt' => '🇧🇷', 'hi' => '🇮🇳'];
    $ogLocaleMap = ['en' => 'en_US', 'fr' => 'fr_FR', 'es' => 'es_ES', 'pt' => 'pt_BR', 'hi' => 'hi_IN', 'ru' => 'ru_RU'];


    $elementColors = [
        'aries' => 'from-orange-500/20 to-red-500/10 border-orange-500/30',
        'leo' => 'from-orange-500/20 to-red-500/10 border-orange-500/30',
        'sagittarius' => 'from-orange-500/20 to-red-500/10 border-orange-500/30',
        'taurus' => 'from-green-500/20 to-emerald-500/10 border-green-500/30',
        'virgo' => 'from-green-500/20 to-emerald-500/10 border-green-500/30',
        'capricorn' => 'from-green-500/20 to-emerald-500/10 border-green-500/30',
        'gemini' => 'from-sky-500/20 to-cyan-500/10 border-sky-500/30',
        'libra' => 'from-sky-500/20 to-cyan-500/10 border-sky-500/30',
        'aquarius' => 'from-sky-500/20 to-cyan-500/10 border-sky-500/30',
        'cancer' => 'from-blue-500/20 to-indigo-500/10 border-blue-500/30',
        'scorpio' => 'from-blue-500/20 to-indigo-500/10 border-blue-500/30',
        'pisces' => 'from-blue-500/20 to-indigo-500/10 border-blue-500/30',
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ $currentLocale }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <title>{{ __('horoscope.hub_title') }} | NatalScope</title>
    <meta name="description" content="{{ __('horoscope.hub_description') }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ horoscope_url_for_locale(null, null, $currentLocale) }}">
    @foreach($locales as $loc)
    <link rel="alternate" hreflang="{{ $loc }}" href="{{ horoscope_url_for_locale(null, null, $loc) }}">
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ horoscope_url_for_locale(null, null, 'en') }}">

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ horoscope_url_for_locale(null, null, $currentLocale) }}">
    <meta property="og:title" content="{{ __('horoscope.hub_title') }} | NatalScope">
    <meta property="og:description" content="{{ __('horoscope.hub_description') }}">
    <meta property="og:locale" content="{{ $ogLocaleMap[$currentLocale] ?? 'en_US' }}">
    <meta property="og:site_name" content="NatalScope">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ __('horoscope.hub_title') }} | NatalScope">

    @if(config('services.google_analytics.id'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.google_analytics.id') }}"></script>
    <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{{ config('services.google_analytics.id') }}');</script>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.1.0/js/all.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html, body { background-color: #0B1120; color: #e2e8f0; }
        .hero-gradient {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.15) 0%, rgba(139, 92, 246, 0.1) 50%, rgba(236, 72, 153, 0.05) 100%);
        }
        .constellation-bg {
            background-image:
                radial-gradient(2px 2px at 20% 30%, rgba(255,255,255,0.15) 0%, transparent 100%),
                radial-gradient(2px 2px at 40% 70%, rgba(255,255,255,0.1) 0%, transparent 100%),
                radial-gradient(1px 1px at 60% 20%, rgba(255,255,255,0.12) 0%, transparent 100%),
                radial-gradient(2px 2px at 80% 50%, rgba(255,255,255,0.08) 0%, transparent 100%);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -15px rgba(99, 102, 241, 0.2);
        }
        .zodiac-image {
            filter: drop-shadow(0 0 20px rgba(99, 102, 241, 0.2));
            transition: all 0.3s ease;
        }
        .card-hover:hover .zodiac-image {
            filter: drop-shadow(0 0 30px rgba(99, 102, 241, 0.4));
            transform: scale(1.05);
        }
    </style>
</head>
<body class="font-sans antialiased constellation-bg min-h-screen">
    <!-- Navigation -->
    <nav class="border-b border-white/5 bg-[#0B1120]/80 backdrop-blur-xl sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="{{ locale_route('welcome') }}" class="text-xl font-bold text-white tracking-tight">
                NATAL<span class="text-indigo-400">SCOPE</span>
            </a>
            <div class="flex items-center gap-3">
                <a href="{{ locale_route('welcome') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-lg transition-colors">
                    {{ __('common.nav_login') }}
                </a>
                <!-- Language Switcher -->
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" class="flex items-center gap-1 text-indigo-300 text-sm hover:text-white transition-colors px-2 py-1 rounded-md hover:bg-white/5">
                        <span class="text-base">{{ $langFlags[$currentLocale] ?? '🌐' }}</span>
                        <svg class="w-3 h-3 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition class="absolute right-0 mt-2 w-44 bg-[#1e293b] border border-indigo-900/50 rounded-lg shadow-xl overflow-hidden z-50">
                        @foreach($locales as $loc)
                        <a href="{{ $baseUrl }}{{ $loc === 'en' ? '' : '/' . $loc }}/horoscope"
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
        <div class="max-w-6xl mx-auto px-4 py-12 sm:py-16 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-sm mb-6">
                <i class="fas fa-calendar-day"></i>
                <span>{{ now()->locale($currentLocale)->translatedFormat('l, F j, Y') }}</span>
            </div>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-4">
                {{ __('horoscope.hub_title') }}
            </h1>
            <p class="text-xl text-indigo-200/80 max-w-2xl mx-auto">
                {{ __('horoscope.hub_description') }}
            </p>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-10">
        <!-- Zodiac Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @foreach($signs as $sign)
                @php
                    $h = $horoscopes[$sign] ?? null;
                    $signName = __('astrology.sign_' . $sign);
                    $colors = $elementColors[$sign] ?? 'from-indigo-500/20 to-purple-500/10 border-indigo-500/30';
                    $score = $h && isset($h->content['scores']['overall']) ? $h->content['scores']['overall'] : rand(65, 90);
                @endphp
                <a href="{{ locale_route('horoscope.sign', ['sign' => $sign]) }}"
                   class="card-hover group block rounded-2xl bg-gradient-to-br {{ $colors }} border p-5 relative overflow-hidden">
                    <!-- Background decoration -->
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-white/5 to-transparent rounded-full -translate-y-1/2 translate-x-1/2"></div>

                    <div class="relative z-10">
                        <!-- Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-14 h-14 rounded-xl bg-white/5 flex items-center justify-center">
                                    <img src="{{ asset('images/zodiac/' . $sign . '.webp') }}"
                                         alt="{{ $signName }}"
                                         class="w-10 h-10 object-contain zodiac-image">
                                </div>
                                <div>
                                    <h2 class="text-lg font-bold text-white group-hover:text-indigo-300 transition-colors">
                                        {{ $signName }}
                                    </h2>
                                </div>
                            </div>
                            <!-- Score Badge -->
                            <div class="flex flex-col items-center">
                                <div class="w-12 h-12 rounded-full bg-white/5 flex items-center justify-center border border-white/10">
                                    <span class="text-lg font-bold text-white">{{ $score }}</span>
                                </div>
                                <span class="text-[10px] text-indigo-400/60 mt-1">{{ __('horoscope.score') }}</span>
                            </div>
                        </div>

                        <!-- Preview Text -->
                        @if($h && isset($h->content['overview']))
                            <p class="text-indigo-200/70 text-sm leading-relaxed line-clamp-2 mb-4">
                                {{ Str::limit($h->content['overview'], 100) }}
                            </p>
                        @else
                            <p class="text-indigo-200/70 text-sm mb-4">
                                {{ __('horoscope.cta_text') }}
                            </p>
                        @endif

                        <!-- Mini Stats -->
                        @if($h && isset($h->content['scores']))
                            <div class="grid grid-cols-3 gap-2 mb-4">
                                <div class="text-center p-2 rounded-lg bg-white/5">
                                    <div class="text-pink-400 text-xs mb-1"><i class="fas fa-heart"></i></div>
                                    <div class="text-white text-sm font-semibold">{{ $h->content['scores']['love'] ?? 70 }}%</div>
                                </div>
                                <div class="text-center p-2 rounded-lg bg-white/5">
                                    <div class="text-amber-400 text-xs mb-1"><i class="fas fa-briefcase"></i></div>
                                    <div class="text-white text-sm font-semibold">{{ $h->content['scores']['career'] ?? 75 }}%</div>
                                </div>
                                <div class="text-center p-2 rounded-lg bg-white/5">
                                    <div class="text-emerald-400 text-xs mb-1"><i class="fas fa-heart-pulse"></i></div>
                                    <div class="text-white text-sm font-semibold">{{ $h->content['scores']['health'] ?? 80 }}%</div>
                                </div>
                            </div>
                        @endif

                        <!-- Read More -->
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-indigo-400 group-hover:text-indigo-300 transition-colors font-medium">
                                {{ __('horoscope.today') }}
                            </span>
                            <span class="w-8 h-8 rounded-full bg-indigo-500/20 flex items-center justify-center group-hover:bg-indigo-500/40 transition-colors">
                                <i class="fas fa-arrow-right text-indigo-400 group-hover:translate-x-0.5 transition-transform"></i>
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- CTA Section -->
        <section class="mt-16">
            <div class="rounded-3xl bg-gradient-to-br from-indigo-600/20 to-purple-600/20 border border-indigo-500/30 p-8 sm:p-12 text-center relative overflow-hidden">
                <!-- Background decoration -->
                <div class="absolute top-0 left-0 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl -translate-y-1/2 -translate-x-1/2"></div>
                <div class="absolute bottom-0 right-0 w-64 h-64 bg-purple-500/10 rounded-full blur-3xl translate-y-1/2 translate-x-1/2"></div>

                <div class="relative z-10">
                    <h2 class="text-2xl sm:text-3xl font-bold text-white mb-3">{{ __('horoscope.cta_title') }}</h2>
                    <p class="text-indigo-200/80 text-lg max-w-xl mx-auto mb-6">{{ __('horoscope.cta_text') }}</p>
                    <a href="{{ locale_route('welcome') }}#heroSection"
                       class="inline-flex items-center gap-2 px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl transition-all hover:shadow-lg hover:shadow-indigo-500/25 text-lg">
                        <i class="fas fa-star"></i>
                        {{ __('horoscope.cta_button') }}
                    </a>
                </div>
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
                    <a href="{{ url('/privacy') }}" class="hover:text-white transition-colors">{{ __('common.footer_privacy') }}</a>
                    <a href="{{ url('/terms') }}" class="hover:text-white transition-colors">{{ __('common.footer_terms') }}</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
