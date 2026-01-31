<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Карта Судьбы — {{ $chart->name }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700&family=Lato:wght@300;400;700&display=swap"
        rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            serif: ['Cinzel', 'serif'],
                            sans: ['Lato', 'sans-serif'],
                        },
                        colors: {
                            mystic: {
                                900: '#0B1120',
                                800: '#1e1b4b',
                                700: '#312e81',
                            },
                            gold: {
                                400: '#fbbf24',
                                500: '#f59e0b',
                                600: '#d97706',
                            }
                        }
                    }
                }
            }
        </script>
    @endif

    <style>
        body {
            background-color: #0B1120;
            color: #e2e8f0;
        }

        .text-gold {
            color: #fbbf24;
        }

        .card-dark {
            background-color: #111827;
            border: 1px solid rgba(49, 46, 129, 0.3);
            border-radius: 16px;
        }

        .premium-blur {
            filter: blur(8px);
            user-select: none;
            pointer-events: none;
        }
    </style>
</head>

<body class="font-sans antialiased min-h-screen">

    <!-- Navbar -->
    <nav class="border-b border-indigo-900/30 bg-[#0B1120]/95 backdrop-blur-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ url('/') }}"
                    class="text-2xl font-serif font-bold text-white tracking-widest hover:text-gold-400 transition-colors">
                    ASTRO<span class="text-gold">CHART</span>
                </a>
                <div class="flex items-center gap-4">
                    <span
                        class="hidden md:inline text-indigo-400 text-xs uppercase tracking-widest">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-indigo-400 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <div class="relative py-12 border-b border-indigo-900/20 overflow-hidden">
        <!-- Hero BG -->
        <div class="absolute inset-0 bg-[url('/images/hero-bg.png')] bg-cover bg-center opacity-30"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-[#0B1120] via-transparent to-[#0B1120]"></div>

        <div class="max-w-4xl mx-auto px-4 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl font-serif font-bold text-white mb-2 tracking-wide">{{ $chart->name }}</h1>
            <div class="flex justify-center items-center gap-4 text-indigo-300 text-sm font-medium">
                <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg> {{ $chart->birth_date->format('d.m.Y') }}</span>
                <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg> {{ $chart->birth_place }}</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-12">

        <!-- Section 1: The Cosmic Map (Real Calculations) -->
        <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-start">

            <!-- THE REAL CHART -->
            <div class="relative order-2 lg:order-1">
                <div class="aspect-square relative flex items-center justify-center max-w-[500px] mx-auto">

                    <!-- 1. Zodiac Ring Background -->
                    <!-- We assume Aries (0 deg) is at 9 o'clock (standard west chart) or 3 o'clock. 
                         Let's assume the PNG is standard: Aries @ 0 deg (3 o'clock). 
                         We rotate it so the Ascendant is at 9 o'clock (Left). -->
                    @php
                        // Helper to get absolute degree of a sign (0 = Aries, 30 = Taurus...)
                        $signs = [
                            'Aries' => 0,
                            'Taurus' => 30,
                            'Gemini' => 60,
                            'Cancer' => 90,
                            'Leo' => 120,
                            'Virgo' => 150,
                            'Libra' => 180,
                            'Scorpio' => 210,
                            'Sagittarius' => 240,
                            'Capricorn' => 270,
                            'Aquarius' => 300,
                            'Pisces' => 330
                        ];

                        $ascSign = $chart->chart_data['ascendant']['sign'] ?? 'Aries';
                        $ascDeg = $chart->chart_data['ascendant']['degree'] ?? 0;
                        $ascAbsolute = ($signs[$ascSign] ?? 0) + $ascDeg;

                        // To put Ascendant at 9'oclock (180 deg in CSS rotation from 3'oclock start):
                        // Rotation = 180 - AscAbsolute.
                        $chartRotation = 180 - $ascAbsolute;
                    @endphp

                    <img src="/images/zodiac_ring.png" alt="Natal Chart Wheel"
                        class="absolute inset-0 w-full h-full object-contain pointer-events-none"
                        style="transform: rotate({{ $chartRotation }}deg); transition: transform 1.5s ease-out;">

                    <!-- 2. Planet Icons Positioned Absolutely -->
                    <!-- Center is 50%, 50%. Radius is approx 35% (to sit inside the ring). -->
                    @php
                        $planets = $chart->chart_data['planets'] ?? [];
                        // Add Sun and Moon to planets array for iteration if not there
                        if (!isset($planets['sun'])) {
                            $planets['sun'] = $chart->chart_data['sun'];
                        }
                        if (!isset($planets['moon'])) {
                            $planets['moon'] = $chart->chart_data['moon'];
                        }

                        $radius = 36; // % from center
                    @endphp

                    @foreach($planets as $name => $data)
                        @php
                            $sign = $data['sign'] ?? 'Aries';
                            $deg = $data['degree'] ?? 0;
                            $absDeg = ($signs[$sign] ?? 0) + $deg;

                            // Adjust for the chart rotation
                            // The visual position = PlanetAbsolute + ChartRotation
                            $visualDeg = $absDeg + $chartRotation;

                            // Convert polar to cartesian (CSS top/left)
                            // CSS 0 deg is 3 o'clock (Right). 
                            $rad = deg2rad($visualDeg);
                            $left = 50 + ($radius * cos($rad));
                            $top = 50 + ($radius * sin($rad)); // Y is positive down
                        @endphp

                        <!-- Planet Icon -->
                        <div class="absolute w-8 h-8 -ml-4 -mt-4 flex items-center justify-center z-20 hover:scale-150 transition-transform cursor-pointer group"
                            style="left: {{ $left }}%; top: {{ $top }}%;">
                            <!-- Tooltip -->
                            <div
                                class="absolute bottom-full mb-2 hidden group-hover:block bg-indigo-900 text-white text-xs px-2 py-1 rounded whitespace-nowrap z-30">
                                {{ ucfirst($name) }} in {{ $sign }}
                            </div>

                            <!-- Icon -->
                            @if(in_array($name, ['sun', 'moon']))
                                <!-- Use Zodiac for sun/moon as per mock data structure or specific icons? 
                                                                             Actually standard charts use Planet Glyphs. 
                                                                             Since we don't have planet glyphs in 'images/zodiac', we can use the sign icon or a text glyph.
                                                                             Let's use a nice Unicode glyph for now to ensure it looks 'chart-like' if no SVG available,
                                                                             OR use the Sign Icon if that's what we have. 
                                                                             User said 'use zodiac icons'. Let's us the Sign Icon for the planet's location. -->
                                <div
                                    class="w-6 h-6 bg-[#0B1120] rounded-full border border-gold-400 flex items-center justify-center overflow-hidden p-0.5 shadow-lg">
                                    <img src="/images/zodiac/{{ strtolower($sign) }}.png" class="w-full h-full object-contain">
                                </div>
                            @else
                                <div
                                    class="w-5 h-5 bg-[#0B1120] rounded-full border border-indigo-500 flex items-center justify-center overflow-hidden p-0.5 shadow-lg">
                                    <img src="/images/zodiac/{{ strtolower($sign) }}.png" class="w-full h-full object-contain">
                                </div>
                            @endif
                        </div>
                    @endforeach

                    <!-- Inner Hub -->
                    <div
                        class="absolute w-[20%] h-[20%] bg-[#0B1120] rounded-full border border-indigo-800 shadow-[0_0_20px_rgba(79,70,229,0.3)] flex items-center justify-center z-10">
                        <div class="text-center">
                            <span class="block text-[8px] text-indigo-500 uppercase tracking-widest">ASC</span>
                            <img src="/images/zodiac/{{ strtolower($ascSign) }}.png"
                                class="w-8 h-8 object-contain mx-auto mt-1">
                        </div>
                    </div>

                </div>
            </div>

            <!-- Big Three Cards (Cleaned Up) -->
            <div class="space-y-4 order-1 lg:order-2">
                <h2 class="text-xl font-serif font-bold text-white mb-6 flex items-center gap-3">
                    <span class="w-8 h-[1px] bg-gold-500"></span> Ваши Ключи <span
                        class="w-8 h-[1px] bg-gold-500"></span>
                </h2>

                <!-- Sun -->
                <div class="card-dark p-6 flex items-start gap-4 hover:bg-indigo-950/30 transition-colors">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-gold-400/20 to-transparent rounded-full flex items-center justify-center border border-gold-400/30 shrink-0">
                        <img src="/images/zodiac/{{ strtolower($chart->chart_data['sun']['sign'] ?? 'aries') }}.png"
                            class="w-10 h-10 object-contain">
                    </div>
                    <div>
                        <div class="flex items-baseline gap-2 mb-1">
                            <h3 class="text-lg font-bold text-white leading-none">Солнце в
                                {{ $chart->chart_data['sun']['sign'] }}
                            </h3>
                            <span
                                class="px-2 py-0.5 bg-gold-500/10 text-gold-400 text-[10px] font-bold uppercase rounded">Личность</span>
                        </div>
                        <p class="text-xs text-indigo-300 uppercase tracking-wide mb-3">
                            {{ $chart->chart_data['sun']['element'] ?? '' }} •
                            {{ $chart->chart_data['sun']['quality'] ?? '' }}
                        </p>
                        <p class="text-sm text-indigo-100 leading-relaxed">
                            {{ $chart->chart_data['sun']['description'] }}
                        </p>
                    </div>
                </div>

                <!-- Moon -->
                <div class="card-dark p-6 flex items-start gap-4 hover:bg-indigo-950/30 transition-colors">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-indigo-400/20 to-transparent rounded-full flex items-center justify-center border border-indigo-400/30 shrink-0">
                        <img src="/images/zodiac/{{ strtolower($chart->chart_data['moon']['sign'] ?? 'taurus') }}.png"
                            class="w-10 h-10 object-contain">
                    </div>
                    <div>
                        <div class="flex items-baseline gap-2 mb-1">
                            <h3 class="text-lg font-bold text-white leading-none">Луна в
                                {{ $chart->chart_data['moon']['sign'] }}
                            </h3>
                            <span
                                class="px-2 py-0.5 bg-indigo-500/10 text-indigo-300 text-[10px] font-bold uppercase rounded">Эмоции</span>
                        </div>
                        <p class="text-xs text-indigo-300 uppercase tracking-wide mb-3">
                            {{ $chart->chart_data['moon']['element'] ?? '' }} •
                            {{ $chart->chart_data['moon']['quality'] ?? '' }}
                        </p>
                        <p class="text-sm text-indigo-100 leading-relaxed">
                            {{ $chart->chart_data['moon']['description'] }}
                        </p>
                    </div>
                </div>

                <!-- Ascendant -->
                <div class="card-dark p-6 flex items-start gap-4 hover:bg-indigo-950/30 transition-colors">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-purple-400/20 to-transparent rounded-full flex items-center justify-center border border-purple-400/30 shrink-0">
                        <img src="/images/zodiac/{{ strtolower($chart->chart_data['ascendant']['sign'] ?? 'gemini') }}.png"
                            class="w-10 h-10 object-contain">
                    </div>
                    <div>
                        <div class="flex items-baseline gap-2 mb-1">
                            <h3 class="text-lg font-bold text-white leading-none">Асцендент в
                                {{ $chart->chart_data['ascendant']['sign'] }}
                            </h3>
                            <span
                                class="px-2 py-0.5 bg-purple-500/10 text-purple-300 text-[10px] font-bold uppercase rounded">Маска</span>
                        </div>
                        <p class="text-xs text-indigo-300 uppercase tracking-wide mb-3">
                            {{ $chart->chart_data['ascendant']['element'] ?? '' }} •
                            {{ $chart->chart_data['ascendant']['quality'] ?? '' }}
                        </p>
                        <p class="text-sm text-indigo-100 leading-relaxed">
                            {{ $chart->chart_data['ascendant']['description'] }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Planetary Data & Houses (The "Data" Layer) -->
        <div class="grid lg:grid-cols-3 gap-8">

            <!-- Left: Planet Table (Comprehensive) -->
            <div class="lg:col-span-2 card-dark overflow-hidden">
                <div class="px-6 py-4 border-b border-indigo-800/50 bg-indigo-950/20 flex justify-between items-center">
                    <h3 class="text-lg font-serif font-bold text-white">Планеты и Объекты</h3>
                    <span class="text-xs text-indigo-400 uppercase tracking-wider">Swiss Ephemeris</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-indigo-800/30">
                        <thead class="bg-indigo-950/10">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-indigo-400 uppercase tracking-wider">
                                    Планета</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-indigo-400 uppercase tracking-wider">
                                    Знак</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-indigo-400 uppercase tracking-wider">
                                    Дом</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-indigo-400 uppercase tracking-wider">
                                    Градус</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-indigo-800/30">
                            @foreach($chart->chart_data['planets'] as $planet => $data)
                                <tr class="hover:bg-indigo-900/10 transition-colors group">
                                    <td
                                        class="px-6 py-4 whitespace-nowrap capitalize font-medium text-white flex items-center gap-3">
                                        <!-- Use name for icon or generic -->
                                        <span
                                            class="w-2 h-2 rounded-full {{ in_array($planet, ['sun', 'moon', 'ascendant', 'mc']) ? 'bg-gold-400' : 'bg-indigo-500' }}"></span>
                                        {{ $data['name'] ?? ucfirst($planet) }}
                                        @if(isset($data['retrograde']) && $data['retrograde'])
                                            <span class="text-[10px] text-red-400 border border-red-900/50 px-1 rounded ml-2"
                                                title="Retrograde">R</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-indigo-200">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-6 h-6 bg-[#0B1120] rounded-full border border-indigo-800 p-0.5 flex items-center justify-center">
                                                @if(isset($data['sign']))
                                                    <img src="/images/zodiac/{{ strtolower($data['sign']) }}.png"
                                                        class="w-full h-full object-contain">
                                                @endif
                                            </div>
                                            <span class="font-bold text-sm">{{ $data['sign'] ?? '---' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-indigo-300">
                                        <span class="font-mono text-sm">{{ $data['house'] ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-indigo-400 font-mono text-sm">
                                        {{ floor($data['degree']) }}°
                                        {{ round(($data['degree'] - floor($data['degree'])) * 60) }}'
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right: House Cusps (Placidus) -->
            <div class="card-dark overflow-hidden">
                <div class="px-6 py-4 border-b border-indigo-800/50 bg-indigo-950/20">
                    <h3 class="text-lg font-serif font-bold text-white">Дома (Placidus)</h3>
                </div>
                <div class="divide-y divide-indigo-800/30">
                    @foreach($chart->chart_data['houses'] as $house => $data)
                        <div class="px-6 py-3 flex items-center justify-between hover:bg-indigo-900/10 transition-colors">
                            <div class="flex items-center gap-3">
                                <span class="text-xs font-bold text-indigo-500 w-6">{{ $data['label'] ?? $house }}</span>
                                <div class="flex items-center gap-2">
                                    <img src="/images/zodiac/{{ strtolower($data['sign']) }}.png"
                                        class="w-4 h-4 opacity-70">
                                    <span class="text-sm text-indigo-200">{{ $data['sign'] }}</span>
                                </div>
                            </div>
                            <span class="text-xs text-indigo-400 font-mono">
                                {{ floor($data['degree']) }}° {{ round(($data['degree'] - floor($data['degree'])) * 60) }}'
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Section 3: Aspects (The Geometry) -->
        <div class="card-dark overflow-hidden">
            <div class="px-6 py-4 border-b border-indigo-800/50 bg-indigo-950/20">
                <h3 class="text-lg font-serif font-bold text-white">Аспекты</h3>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-px bg-indigo-900/30">
                @foreach($chart->chart_data['aspects'] as $aspect)
                    <div
                        class="bg-[#111827] p-4 hover:bg-indigo-950/30 transition-colors flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-bold text-white">{{ ucfirst($aspect['planet1']) }}</span>
                            <!-- Aspect Symbol/Badge -->
                            @php
                                $colors = match ($aspect['type']) {
                                    'Square', 'Opposition' => 'text-red-400 bg-red-900/20 border-red-900/50',
                                    'Trine', 'Sextile' => 'text-emerald-400 bg-emerald-900/20 border-emerald-900/50',
                                    'Conjunction' => 'text-gold-400 bg-gold-900/20 border-gold-900/50',
                                    default => 'text-indigo-400 bg-indigo-900/20',
                                };
                            @endphp
                            <span class="px-2 py-0.5 text-[10px] uppercase font-bold rounded border {{ $colors }}">
                                {{ $aspect['type'] }}
                            </span>
                            <span class="text-sm font-bold text-white">{{ ucfirst($aspect['planet2']) }}</span>
                        </div>
                        <span class="text-xs text-indigo-500 font-mono">{{ $aspect['orb'] }}°</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Section 4: Detailed Interpretations (The "Report") -->
        <div class="space-y-6">
            <h2 class="text-xl font-serif font-bold text-white flex items-center gap-3">
                <span class="w-8 h-[1px] bg-gold-500"></span> Расшифровка <span class="w-8 h-[1px] bg-gold-500"></span>
            </h2>

            <!-- 1. Personality (ASC & Sun) -->
            <div class="card-dark p-8">
                <h3 class="text-lg font-bold text-gold-400 mb-4 border-l-4 border-gold-500 pl-4">1. Ваша Личность и Эго
                </h3>
                <div class="prose prose-invert max-w-none text-indigo-200">
                    <p class="mb-4"><strong class="text-white">Асцендент в
                            {{ $chart->chart_data['ascendant']['sign'] }}:</strong>
                        {{ $chart->chart_data['ascendant']['description'] }} Это ваша социальная маска, первое
                        впечатление, которое вы производите на мир.</p>
                    <p><strong class="text-white">Солнце в {{ $chart->chart_data['sun']['sign'] }}
                            ({{ $chart->chart_data['sun']['house'] }} дом):</strong>
                        {{ $chart->chart_data['sun']['description'] }} Положение в
                        {{ $chart->chart_data['sun']['house'] }} доме указывает, что вы ярко проявляете себя в сфере
                        карьеры и публичном статусе.
                    </p>
                </div>
            </div>

            <!-- 2. Emotions (Moon) -->
            <div class="card-dark p-8">
                <h3 class="text-lg font-bold text-indigo-400 mb-4 border-l-4 border-indigo-500 pl-4">2. Эмоции и
                    Потребности</h3>
                <div class="prose prose-invert max-w-none text-indigo-200">
                    <p><strong class="text-white">Луна в {{ $chart->chart_data['moon']['sign'] }}
                            ({{ $chart->chart_data['moon']['house'] }} дом):</strong>
                        {{ $chart->chart_data['moon']['description'] }} Ваши реакции инстинктивны, и вам важно
                        чувствовать безопасность через темы этого знака.</p>
                </div>
            </div>

            <!-- 3. Mind & Communication (Mercury) -->
            <div class="card-dark p-8">
                <h3 class="text-lg font-bold text-indigo-300 mb-4 border-l-4 border-indigo-400 pl-4">3. Мышление и
                    Общение</h3>
                <div class="prose prose-invert max-w-none text-indigo-200">
                    <p><strong class="text-white">Меркурий в {{ $chart->chart_data['planets']['mercury']['sign'] }}
                            ({{ $chart->chart_data['planets']['mercury']['house'] }} дом):</strong> Ваш интеллект
                        работает в стиле этого знака. Это определяет, как вы учитесь, говорите и обрабатываете
                        информацию.</p>
                </div>
            </div>

            <!-- 4. Love & Values (Venus) -->
            <div class="card-dark p-8">
                <h3 class="text-lg font-bold text-pink-400 mb-4 border-l-4 border-pink-500 pl-4">4. Любовь и Ценности
                </h3>
                <div class="prose prose-invert max-w-none text-indigo-200">
                    <p><strong class="text-white">Венера в {{ $chart->chart_data['planets']['venus']['sign'] }}
                            ({{ $chart->chart_data['planets']['venus']['house'] }} дом):</strong> В любви вы ищете
                        качества этого знака. Ваше отношение к деньгам и красоте также окрашено этой энергией.</p>
                </div>
            </div>

            <!-- 5. Action & Drive (Mars) -->
            <div class="card-dark p-8">
                <h3 class="text-lg font-bold text-red-500 mb-4 border-l-4 border-red-600 pl-4">5. Энергия и Действия
                </h3>
                <div class="prose prose-invert max-w-none text-indigo-200">
                    <p><strong class="text-white">Марс в {{ $chart->chart_data['planets']['mars']['sign'] }}
                            ({{ $chart->chart_data['planets']['mars']['house'] }} дом):</strong> Ваша воля и способность
                        действовать проявляются через этот знак. Это то, как вы добиваетесь своего.</p>
                </div>
            </div>

            <!-- 6. Karma & Destiny (Nodes) -->
            <!-- 6. Karma & Destiny (Nodes) -->
            @if(isset($chart->chart_data['planets']['node']))
                <div class="card-dark p-8 border border-indigo-500/30 bg-indigo-900/10">
                    <h3 class="text-lg font-bold text-gold-400 mb-4 border-l-4 border-gold-500 pl-4">✨ Кармический Путь</h3>
                    <div class="prose prose-invert max-w-none text-indigo-200">
                        <p><strong class="text-white">Северный Узел в
                                {{ $chart->chart_data['planets']['node']['sign'] ?? '---' }}:</strong> Ваша главная задача
                            воплощения — развить качества этого знака.</p>
                        <p class="mt-2 text-sm text-indigo-400">Вам следует двигаться от привычных моделей Южного Узла
                            (противоположный знак) к новым вершинам Северного.</p>
                    </div>
                </div>
            @endif

            <!-- 7. AI Deep Analysis Report -->
            <div id="ai-report-section" class="mt-12">
                <h2 class="text-2xl font-serif font-bold text-white mb-6 flex items-center gap-3">
                    <span class="w-8 h-[1px] bg-gold-500"></span> 🔮 ИИ Астролог <span
                        class="w-8 h-[1px] bg-gold-500"></span>
                </h2>

                @if($chart->report_status === 'new' || $chart->report_status === 'failed')
                    <div class="card-dark p-8 text-center">
                        <p class="text-indigo-300 mb-6">Получите глубокий персональный анализ вашей карты с помощью
                            искусственного интеллекта.</p>
                        <form action="{{ route('charts.generate-report', $chart) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold py-3 px-8 rounded-full shadow-lg transform transition hover:scale-105">
                                ✨ Сгенерировать Глубокий Анализ (AI)
                            </button>
                        </form>
                        @if($chart->report_status === 'failed')
                            <p class="text-red-400 mt-4 text-sm">Произошла ошибка при генерации. Попробуйте еще раз.</p>
                        @endif
                    </div>
                @elseif($chart->report_status === 'processing')
                    <div class="card-dark p-12 text-center" id="processing-state">
                        <div
                            class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-gold-400 mb-4">
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Звезды говорят...</h3>
                        <p class="text-indigo-400">ИИ анализирует вашу карту. Это займет около 20 секунд.</p>
                        <p class="text-xs text-indigo-600 mt-4">Пожалуйста, не закрывайте страницу.</p>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const pollInterval = setInterval(() => {
                                fetch("{{ route('charts.status', $chart) }}")
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.status === 'completed') {
                                            clearInterval(pollInterval);
                                            window.location.reload();
                                        } else if (data.status === 'failed') {
                                            clearInterval(pollInterval);
                                            window.location.reload();
                                        }
                                    });
                            }, 2000);
                        });
                    </script>
                @elseif($chart->report_status === 'completed' && $chart->report_content)
                    <div class="space-y-6">
                        @foreach($chart->report_content as $key => $content)
                            <div class="card-dark p-8 border-t-4 border-purple-500">
                                <h3 class="text-lg font-bold text-white uppercase tracking-wider mb-4">{{ ucfirst($key) }}</h3>
                                <div class="prose prose-invert max-w-none text-indigo-100 leading-relaxed">
                                    {!! nl2br(e($content)) !!}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

    </div>
</body>

</html>