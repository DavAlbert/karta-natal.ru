@php
    $signData = [
        'Овен' => ['img' => 'aries', 'symbol' => '♈', 'start' => 0],
        'Телец' => ['img' => 'taurus', 'symbol' => '♉', 'start' => 30],
        'Близнецы' => ['img' => 'gemini', 'symbol' => '♊', 'start' => 60],
        'Рак' => ['img' => 'cancer', 'symbol' => '♋', 'start' => 90],
        'Лев' => ['img' => 'leo', 'symbol' => '♌', 'start' => 120],
        'Дева' => ['img' => 'virgo', 'symbol' => '♍', 'start' => 150],
        'Весы' => ['img' => 'libra', 'symbol' => '♎', 'start' => 180],
        'Скорпион' => ['img' => 'scorpio', 'symbol' => '♏', 'start' => 210],
        'Стрелец' => ['img' => 'sagittarius', 'symbol' => '♐', 'start' => 240],
        'Козерог' => ['img' => 'capricorn', 'symbol' => '♑', 'start' => 270],
        'Водолей' => ['img' => 'aquarius', 'symbol' => '♒', 'start' => 300],
        'Рыбы' => ['img' => 'pisces', 'symbol' => '♓', 'start' => 330],
    ];

    $planetData = [
        'sun' => ['symbol' => '☉', 'name' => 'Солнце', 'color' => '#F59E0B'],
        'moon' => ['symbol' => '☽', 'name' => 'Луна', 'color' => '#94A3B8'],
        'mercury' => ['symbol' => '☿', 'name' => 'Меркурий', 'color' => '#8B5CF6'],
        'venus' => ['symbol' => '♀', 'name' => 'Венера', 'color' => '#EC4899'],
        'mars' => ['symbol' => '♂', 'name' => 'Марс', 'color' => '#EF4444'],
        'jupiter' => ['symbol' => '♃', 'name' => 'Юпитер', 'color' => '#3B82F6'],
        'saturn' => ['symbol' => '♄', 'name' => 'Сатурн', 'color' => '#6B7280'],
        'uranus' => ['symbol' => '♅', 'name' => 'Уран', 'color' => '#06B6D4'],
        'neptune' => ['symbol' => '♆', 'name' => 'Нептун', 'color' => '#6366F1'],
        'pluto' => ['symbol' => '♇', 'name' => 'Плутон', 'color' => '#78716C'],
        'node' => ['symbol' => '☊', 'name' => 'Сев.Узел', 'color' => '#A855F7'],
    ];

    $aspectData = [
        'Соединение' => ['symbol' => '☌', 'color' => '#A855F7', 'dash' => ''],
        'Оппозиция' => ['symbol' => '☍', 'color' => '#EF4444', 'dash' => ''],
        'Трин' => ['symbol' => '△', 'color' => '#10B981', 'dash' => ''],
        'Квадрат' => ['symbol' => '□', 'color' => '#EF4444', 'dash' => ''],
        'Секстиль' => ['symbol' => '⚹', 'color' => '#10B981', 'dash' => '4,4'],
    ];

    // Calculate house cusps in absolute degrees
    $houseCusps = [];
    foreach ($chart->chart_data['houses'] as $num => $data) {
        $sign = $data['sign'] ?? 'Овен';
        $deg = $data['degree'] ?? 0;
        $houseCusps[$num] = ($signData[$sign]['start'] ?? 0) + $deg;
    }

    // ASC is house 1 cusp
    $ascAbsolute = $houseCusps[1] ?? 0;

    // Function to convert zodiac longitude to chart position (ASC on left)
    // In a natal chart, ASC is at 9 o'clock (180° in SVG coordinates)

    // Calculate planet positions
    $planetPositions = [];
    foreach ($chart->chart_data['planets'] ?? [] as $key => $data) {
        $sign = $data['sign'] ?? 'Овен';
        $deg = $data['degree'] ?? 0;
        $absolute = ($signData[$sign]['start'] ?? 0) + $deg;
        $planetPositions[$key] = [
            'absolute' => $absolute,
            'sign' => $sign,
            'degree' => $deg,
            'house' => $data['house'] ?? 1,
            'name' => $data['name'] ?? $planetData[$key]['name'] ?? $key,
        ];
    }
@endphp
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $chart->name }} — Натальная Карта</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root { --bg: #0a0e1a; --card: #0d1322; --gold: #d4a855; }
        body { background: var(--bg); color: #e2e8f0; font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Cinzel', serif; }
        .card { background: var(--card); border: 1px solid rgba(99,102,241,0.12); border-radius: 8px; }
        .gold { color: var(--gold); }

        /* Chart styles */
        .chart-container { position: relative; }
        .chart-svg { width: 100%; height: auto; }
        .planet-symbol { font-family: 'Segoe UI Symbol', 'DejaVu Sans', sans-serif; }
        .sign-symbol { font-family: 'Segoe UI Symbol', 'DejaVu Sans', sans-serif; }

        /* Tooltip */
        .chart-tooltip {
            position: absolute;
            background: rgba(15, 23, 42, 0.95);
            border: 1px solid rgba(99,102,241,0.3);
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 12px;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.15s;
            z-index: 100;
            white-space: nowrap;
        }
        .chart-tooltip.visible { opacity: 1; }
    </style>
</head>
<body class="min-h-screen">

    <!-- Header -->
    <header class="border-b border-indigo-900/30 bg-[#0a0e1a]/95 backdrop-blur sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 py-3 flex justify-between items-center">
            <a href="/" class="font-display text-lg tracking-widest text-white">ASTRO<span class="gold">CHART</span></a>
            <div class="flex items-center gap-4 text-sm text-indigo-300">
                <span>{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button class="hover:text-white transition">Выйти</button>
                </form>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-6">

        <!-- Profile -->
        <div class="mb-6">
            <h1 class="font-display text-2xl text-white mb-1">{{ $chart->name }}</h1>
            <p class="text-indigo-400 text-sm">
                {{ $chart->birth_date->format('d.m.Y') }}, {{ $chart->birth_time ? \Carbon\Carbon::parse($chart->birth_time)->format('H:i') : '—' }} · {{ $chart->birth_place }}
            </p>
        </div>

        <div class="grid lg:grid-cols-3 gap-6">

            <!-- LEFT: Chart Wheel -->
            <div class="lg:col-span-2">
                <div class="card p-4">
                    <div class="chart-container" id="chartContainer">
                        <div class="chart-tooltip" id="chartTooltip"></div>

                        <svg viewBox="0 0 600 600" class="chart-svg" id="natalChart">
                            <defs>
                                <!-- Gradient for outer ring -->
                                <linearGradient id="ringGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" style="stop-color:#1e1b4b;stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:#0f172a;stop-opacity:1" />
                                </linearGradient>
                            </defs>

                            @php
                                $cx = 300;
                                $cy = 300;
                                $outerR = 280;
                                $signR = 250;
                                $houseOuterR = 220;
                                $houseInnerR = 90;
                                $planetR = 170;
                                $aspectR = 85;

                                // Convert absolute zodiac degree to SVG angle
                                // ASC should be at 180° (left side, 9 o'clock)
                                function toSvgAngle($zodiacDeg, $ascAbsolute) {
                                    return 180 - ($zodiacDeg - $ascAbsolute);
                                }

                                function toRadians($deg) {
                                    return $deg * M_PI / 180;
                                }

                                function polarToCartesian($cx, $cy, $r, $angleDeg) {
                                    $rad = toRadians($angleDeg);
                                    return [
                                        'x' => $cx + $r * cos($rad),
                                        'y' => $cy - $r * sin($rad)
                                    ];
                                }

                                function describeArc($cx, $cy, $r, $startAngle, $endAngle) {
                                    $start = polarToCartesian($cx, $cy, $r, $startAngle);
                                    $end = polarToCartesian($cx, $cy, $r, $endAngle);
                                    $largeArc = abs($endAngle - $startAngle) > 180 ? 1 : 0;
                                    return "M {$start['x']} {$start['y']} A $r $r 0 $largeArc 0 {$end['x']} {$end['y']}";
                                }
                            @endphp

                            <!-- Background circles -->
                            <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $outerR }}" fill="url(#ringGradient)" stroke="#312e81" stroke-width="1"/>
                            <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $signR }}" fill="none" stroke="#312e81" stroke-width="1"/>
                            <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $houseOuterR }}" fill="#0a0e1a" stroke="#312e81" stroke-width="1"/>
                            <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $houseInnerR }}" fill="#070b14" stroke="#312e81" stroke-width="1"/>

                            <!-- Zodiac sign divisions (30° each) -->
                            @foreach($signData as $signName => $sign)
                                @php
                                    $startDeg = $sign['start'];
                                    $endDeg = $startDeg + 30;
                                    $svgStart = toSvgAngle($startDeg, $ascAbsolute);
                                    $svgEnd = toSvgAngle($endDeg, $ascAbsolute);
                                    $svgMid = toSvgAngle($startDeg + 15, $ascAbsolute);

                                    // Line at sign boundary
                                    $lineOuter = polarToCartesian($cx, $cy, $outerR, $svgStart);
                                    $lineInner = polarToCartesian($cx, $cy, $signR, $svgStart);

                                    // Symbol position
                                    $symbolPos = polarToCartesian($cx, $cy, ($outerR + $signR) / 2, $svgMid);
                                @endphp
                                <line x1="{{ $lineInner['x'] }}" y1="{{ $lineInner['y'] }}"
                                      x2="{{ $lineOuter['x'] }}" y2="{{ $lineOuter['y'] }}"
                                      stroke="#4338ca" stroke-width="1" opacity="0.5"/>
                                <text x="{{ $symbolPos['x'] }}" y="{{ $symbolPos['y'] }}"
                                      text-anchor="middle" dominant-baseline="middle"
                                      class="sign-symbol" fill="#818cf8" font-size="18">{{ $sign['symbol'] }}</text>
                            @endforeach

                            <!-- House cusps -->
                            @foreach($houseCusps as $num => $cuspDeg)
                                @php
                                    $svgAngle = toSvgAngle($cuspDeg, $ascAbsolute);
                                    $outer = polarToCartesian($cx, $cy, $houseOuterR, $svgAngle);
                                    $inner = polarToCartesian($cx, $cy, $houseInnerR, $svgAngle);

                                    $isAngular = in_array($num, [1, 4, 7, 10]);
                                    $strokeWidth = $isAngular ? 2 : 1;
                                    $strokeColor = $isAngular ? '#d4a855' : '#4338ca';
                                    $strokeOpacity = $isAngular ? 0.8 : 0.4;

                                    // Extend angular house lines to sign ring
                                    if ($isAngular) {
                                        $outer = polarToCartesian($cx, $cy, $signR, $svgAngle);
                                    }

                                    // House number position (middle of house)
                                    $nextNum = $num == 12 ? 1 : $num + 1;
                                    $nextCusp = $houseCusps[$nextNum] ?? $cuspDeg + 30;
                                    if ($nextCusp < $cuspDeg) $nextCusp += 360;
                                    $midCusp = $cuspDeg + ($nextCusp - $cuspDeg) / 2;
                                    $numAngle = toSvgAngle($midCusp, $ascAbsolute);
                                    $numPos = polarToCartesian($cx, $cy, ($houseOuterR + $houseInnerR) / 2, $numAngle);
                                @endphp
                                <line x1="{{ $inner['x'] }}" y1="{{ $inner['y'] }}"
                                      x2="{{ $outer['x'] }}" y2="{{ $outer['y'] }}"
                                      stroke="{{ $strokeColor }}" stroke-width="{{ $strokeWidth }}" opacity="{{ $strokeOpacity }}"/>
                                <text x="{{ $numPos['x'] }}" y="{{ $numPos['y'] }}"
                                      text-anchor="middle" dominant-baseline="middle"
                                      fill="#6366f1" font-size="11" opacity="0.6">{{ $num }}</text>
                            @endforeach

                            <!-- Axis labels -->
                            @php
                                $ascPos = polarToCartesian($cx, $cy, $signR + 8, 180);
                                $dscPos = polarToCartesian($cx, $cy, $signR + 8, 0);
                                $mcAngle = toSvgAngle($houseCusps[10] ?? 0, $ascAbsolute);
                                $mcPos = polarToCartesian($cx, $cy, $signR + 8, $mcAngle);
                                $icAngle = toSvgAngle($houseCusps[4] ?? 0, $ascAbsolute);
                                $icPos = polarToCartesian($cx, $cy, $signR + 8, $icAngle);
                            @endphp
                            <text x="{{ $ascPos['x'] - 18 }}" y="{{ $ascPos['y'] }}" fill="#d4a855" font-size="10" font-weight="600">ASC</text>
                            <text x="{{ $dscPos['x'] + 5 }}" y="{{ $dscPos['y'] }}" fill="#d4a855" font-size="10" font-weight="600">DSC</text>
                            <text x="{{ $mcPos['x'] }}" y="{{ $mcPos['y'] - 5 }}" text-anchor="middle" fill="#d4a855" font-size="10" font-weight="600">MC</text>
                            <text x="{{ $icPos['x'] }}" y="{{ $icPos['y'] + 12 }}" text-anchor="middle" fill="#d4a855" font-size="10" font-weight="600">IC</text>

                            <!-- Aspect lines -->
                            <g id="aspectLines">
                                @foreach($chart->chart_data['aspects'] ?? [] as $aspect)
                                    @php
                                        $p1Key = null;
                                        $p2Key = null;
                                        foreach ($planetPositions as $key => $pos) {
                                            if ($pos['name'] === $aspect['planet1']) $p1Key = $key;
                                            if ($pos['name'] === $aspect['planet2']) $p2Key = $key;
                                        }
                                        if (!$p1Key || !$p2Key) continue;

                                        $p1Angle = toSvgAngle($planetPositions[$p1Key]['absolute'], $ascAbsolute);
                                        $p2Angle = toSvgAngle($planetPositions[$p2Key]['absolute'], $ascAbsolute);
                                        $p1Pos = polarToCartesian($cx, $cy, $aspectR, $p1Angle);
                                        $p2Pos = polarToCartesian($cx, $cy, $aspectR, $p2Angle);

                                        $aspectInfo = $aspectData[$aspect['type']] ?? ['color' => '#6366f1', 'dash' => ''];
                                    @endphp
                                    <line x1="{{ $p1Pos['x'] }}" y1="{{ $p1Pos['y'] }}"
                                          x2="{{ $p2Pos['x'] }}" y2="{{ $p2Pos['y'] }}"
                                          stroke="{{ $aspectInfo['color'] }}" stroke-width="1"
                                          stroke-dasharray="{{ $aspectInfo['dash'] }}" opacity="0.6"/>
                                @endforeach
                            </g>

                            <!-- Planets -->
                            <g id="planets">
                                @php
                                    // Sort planets by degree to handle clustering
                                    $sortedPlanets = $planetPositions;
                                    uasort($sortedPlanets, fn($a, $b) => $a['absolute'] <=> $b['absolute']);

                                    // Simple clustering avoidance
                                    $placedAngles = [];
                                    $minGap = 8;
                                @endphp
                                @foreach($sortedPlanets as $key => $pos)
                                    @php
                                        $svgAngle = toSvgAngle($pos['absolute'], $ascAbsolute);

                                        // Adjust for clustering
                                        foreach ($placedAngles as $placed) {
                                            if (abs($svgAngle - $placed) < $minGap) {
                                                $svgAngle = $placed + $minGap;
                                            }
                                        }
                                        $placedAngles[] = $svgAngle;

                                        $planetPos = polarToCartesian($cx, $cy, $planetR, $svgAngle);
                                        $pData = $planetData[$key] ?? ['symbol' => '?', 'color' => '#6366f1'];

                                        // Degree label position (slightly outside)
                                        $degPos = polarToCartesian($cx, $cy, $planetR + 22, $svgAngle);
                                        $degText = floor($pos['degree']) . '°';
                                    @endphp
                                    <g class="planet-group" data-planet="{{ $key }}"
                                       data-info="{{ $pos['name'] }}: {{ $pos['sign'] }} {{ floor($pos['degree']) }}°{{ str_pad(round(($pos['degree'] - floor($pos['degree'])) * 60), 2, '0', STR_PAD_LEFT) }}' ({{ $pos['house'] }} дом)">
                                        <circle cx="{{ $planetPos['x'] }}" cy="{{ $planetPos['y'] }}" r="14"
                                                fill="#0a0e1a" stroke="{{ $pData['color'] }}" stroke-width="1.5"/>
                                        <text x="{{ $planetPos['x'] }}" y="{{ $planetPos['y'] }}"
                                              text-anchor="middle" dominant-baseline="middle"
                                              class="planet-symbol" fill="{{ $pData['color'] }}" font-size="14"
                                              style="cursor: pointer;">{{ $pData['symbol'] }}</text>
                                        <text x="{{ $degPos['x'] }}" y="{{ $degPos['y'] }}"
                                              text-anchor="middle" dominant-baseline="middle"
                                              fill="#6366f1" font-size="9" opacity="0.7">{{ $degText }}</text>
                                    </g>
                                @endforeach
                            </g>

                            <!-- Center info -->
                            <text x="{{ $cx }}" y="{{ $cy - 8 }}" text-anchor="middle" fill="#6366f1" font-size="9" opacity="0.5">PLACIDUS</text>
                            <text x="{{ $cx }}" y="{{ $cy + 8 }}" text-anchor="middle" fill="#6366f1" font-size="9" opacity="0.5">{{ $chart->birth_date->format('d.m.Y') }}</text>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Data panels -->
            <div class="space-y-4">

                <!-- Big Three -->
                <div class="card p-4">
                    <h3 class="text-xs uppercase tracking-wider text-indigo-500 mb-3">Основные точки</h3>
                    @php
                        $keyPoints = [
                            ['key' => 'sun', 'label' => 'Солнце'],
                            ['key' => 'moon', 'label' => 'Луна'],
                            ['key' => 'ascendant', 'label' => 'ASC'],
                        ];
                    @endphp
                    @foreach($keyPoints as $point)
                        @php
                            $data = $chart->chart_data[$point['key']] ?? null;
                            $sign = $data['sign'] ?? '—';
                            $deg = isset($data['degree']) ? floor($data['degree']) . '°' . str_pad(round(($data['degree'] - floor($data['degree'])) * 60), 2, '0', STR_PAD_LEFT) . "'" : '';
                        @endphp
                        <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-indigo-900/30' : '' }}">
                            <div class="flex items-center gap-2">
                                <span class="text-lg" style="color: {{ $planetData[$point['key']]['color'] ?? '#d4a855' }}">{{ $planetData[$point['key']]['symbol'] ?? '↑' }}</span>
                                <span class="text-white text-sm">{{ $point['label'] }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-indigo-300 text-sm">{{ $sign }}</span>
                                <span class="text-indigo-500 text-xs font-mono">{{ $deg }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- All Planets -->
                <div class="card p-4">
                    <h3 class="text-xs uppercase tracking-wider text-indigo-500 mb-3">Планеты</h3>
                    <div class="space-y-1 text-sm max-h-64 overflow-y-auto">
                        @foreach($chart->chart_data['planets'] ?? [] as $key => $data)
                            @php
                                $sign = $data['sign'] ?? '—';
                                $deg = isset($data['degree']) ? floor($data['degree']) . '°' . str_pad(round(($data['degree'] - floor($data['degree'])) * 60), 2, '0', STR_PAD_LEFT) . "'" : '';
                                $house = $data['house'] ?? '—';
                                $pData = $planetData[$key] ?? ['symbol' => '?', 'color' => '#6366f1'];
                            @endphp
                            <div class="flex items-center py-1.5 hover:bg-indigo-900/20 px-2 -mx-2 rounded transition">
                                <span class="w-6 text-center" style="color: {{ $pData['color'] }}">{{ $pData['symbol'] }}</span>
                                <span class="text-white flex-1 ml-2">{{ $data['name'] ?? $key }}</span>
                                <span class="text-indigo-400 w-20">{{ $sign }}</span>
                                <span class="text-indigo-500 font-mono text-xs w-14 text-right">{{ $deg }}</span>
                                <span class="text-indigo-600 text-xs w-8 text-right">{{ $house }}д</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Houses -->
                <div class="card p-4">
                    <h3 class="text-xs uppercase tracking-wider text-indigo-500 mb-3">Дома</h3>
                    <div class="grid grid-cols-4 gap-1 text-xs">
                        @foreach($chart->chart_data['houses'] ?? [] as $num => $data)
                            @php
                                $sign = $data['sign'] ?? '—';
                                $deg = isset($data['degree']) ? floor($data['degree']) . '°' : '';
                                $isAngular = in_array($num, [1, 4, 7, 10]);
                            @endphp
                            <div class="text-center py-2 rounded {{ $isAngular ? 'bg-amber-900/20' : 'bg-indigo-950/30' }}">
                                <div class="{{ $isAngular ? 'text-amber-400' : 'text-indigo-500' }} font-medium">{{ $num }}</div>
                                <div class="text-indigo-300">{{ $signData[$sign]['symbol'] ?? '?' }}</div>
                                <div class="text-indigo-500">{{ $deg }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

        <!-- Aspects Table -->
        <div class="card p-4 mt-6">
            <h3 class="text-xs uppercase tracking-wider text-indigo-500 mb-4">Аспекты</h3>
            @php
                $harmonyAspects = array_filter($chart->chart_data['aspects'] ?? [], fn($a) => in_array($a['type'], ['Трин', 'Секстиль', 'Соединение']));
                $tensionAspects = array_filter($chart->chart_data['aspects'] ?? [], fn($a) => in_array($a['type'], ['Квадрат', 'Оппозиция']));
            @endphp
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                        <span class="text-emerald-400 text-sm">Гармоничные ({{ count($harmonyAspects) }})</span>
                    </div>
                    <div class="space-y-1 text-sm max-h-40 overflow-y-auto">
                        @foreach($harmonyAspects as $aspect)
                            <div class="flex items-center gap-2 py-1 px-2 bg-emerald-950/20 rounded">
                                <span class="text-emerald-400 w-4">{{ $aspectData[$aspect['type']]['symbol'] ?? '•' }}</span>
                                <span class="text-white">{{ $aspect['planet1'] }}</span>
                                <span class="text-indigo-600">—</span>
                                <span class="text-white">{{ $aspect['planet2'] }}</span>
                                <span class="text-indigo-500 text-xs ml-auto">{{ $aspect['type'] }} {{ $aspect['orb'] }}°</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                        <span class="text-red-400 text-sm">Напряженные ({{ count($tensionAspects) }})</span>
                    </div>
                    <div class="space-y-1 text-sm max-h-40 overflow-y-auto">
                        @foreach($tensionAspects as $aspect)
                            <div class="flex items-center gap-2 py-1 px-2 bg-red-950/20 rounded">
                                <span class="text-red-400 w-4">{{ $aspectData[$aspect['type']]['symbol'] ?? '•' }}</span>
                                <span class="text-white">{{ $aspect['planet1'] }}</span>
                                <span class="text-indigo-600">—</span>
                                <span class="text-white">{{ $aspect['planet2'] }}</span>
                                <span class="text-indigo-500 text-xs ml-auto">{{ $aspect['type'] }} {{ $aspect['orb'] }}°</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- AI Report -->
        <div class="card p-5 mt-6">
            <h3 class="text-xs uppercase tracking-wider text-indigo-500 mb-4">Интерпретация</h3>

            @if($chart->report_status === 'completed' && $chart->report_content)
                @php
                    $sectionTitles = [
                        'identity' => 'Личность', 'love' => 'Отношения', 'career' => 'Карьера',
                        'karma' => 'Кармические задачи', 'forecast' => 'Прогноз'
                    ];
                @endphp
                <div class="space-y-5">
                    @foreach($chart->report_content as $key => $content)
                        <div class="border-l-2 border-indigo-700 pl-4">
                            <h4 class="text-indigo-300 font-medium mb-2 text-sm">{{ $sectionTitles[$key] ?? $key }}</h4>
                            <p class="text-indigo-100/80 leading-relaxed text-sm">{!! nl2br(e($content)) !!}</p>
                        </div>
                    @endforeach
                </div>
            @elseif($chart->report_status === 'processing')
                <div class="text-center py-6">
                    <div class="inline-block animate-spin rounded-full h-6 w-6 border-2 border-indigo-500 border-t-transparent mb-3"></div>
                    <p class="text-indigo-400 text-sm">Генерируем интерпретацию...</p>
                </div>
                <script>
                    setInterval(() => {
                        fetch("{{ route('charts.status', $chart) }}")
                            .then(r => r.json())
                            .then(d => { if (d.status === 'completed' || d.status === 'failed') location.reload(); });
                    }, 3000);
                </script>
            @else
                <div class="text-center py-4">
                    <form action="{{ route('charts.generate-report', $chart) }}" method="POST">
                        @csrf
                        <button class="bg-indigo-600 hover:bg-indigo-500 text-white px-5 py-2 rounded text-sm font-medium transition">
                            Сгенерировать интерпретацию
                        </button>
                    </form>
                </div>
            @endif
        </div>

    </main>

    <footer class="border-t border-indigo-900/20 py-4 mt-8">
        <div class="max-w-6xl mx-auto px-4 text-center text-xs text-indigo-600">
            AstroChart © {{ date('Y') }} · Placidus House System · Swiss Ephemeris
        </div>
    </footer>

    <script>
        // Tooltip for planets
        const tooltip = document.getElementById('chartTooltip');
        const container = document.getElementById('chartContainer');

        document.querySelectorAll('.planet-group').forEach(group => {
            group.addEventListener('mouseenter', (e) => {
                const info = group.dataset.info;
                tooltip.textContent = info;
                tooltip.classList.add('visible');
            });

            group.addEventListener('mousemove', (e) => {
                const rect = container.getBoundingClientRect();
                tooltip.style.left = (e.clientX - rect.left + 10) + 'px';
                tooltip.style.top = (e.clientY - rect.top - 30) + 'px';
            });

            group.addEventListener('mouseleave', () => {
                tooltip.classList.remove('visible');
            });
        });
    </script>

</body>
</html>
