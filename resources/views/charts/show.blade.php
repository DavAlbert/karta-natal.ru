<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $chart->name }} — Натальная карта</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #0B0E14;
            --bg-secondary: #11161F;
            --bg-tertiary: #1A212E;
            --border: #2A3441;
            --border-light: #384150;
            --text-primary: #F8FAFC;
            --text-secondary: #94A3B8;
            --text-muted: #647887;
            --accent-gold: #EAB308;
            --accent-indigo: #818CF8;
            --accent-green: #22C55E;
            --accent-red: #EF4444;
            --accent-purple: #A855F7;
            --accent-cyan: #06B6D4;
            --accent-pink: #EC4899;
            --accent-orange: #F97316;
            --accent-teal: #14B8A6;
        }
        * { box-sizing: border-box; }
        body {
            background: var(--bg-primary);
            color: var(--text-primary);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            line-height: 1.6;
        }
        .container { max-width: 1600px; margin: 0 auto; padding: 1.5rem; }
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border);
            gap: 1rem;
            flex-wrap: wrap;
        }
        .header-left h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0 0 0.5rem;
            background: linear-gradient(135deg, var(--text-primary) 0%, var(--text-secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .header-meta { display: flex; gap: 1.5rem; color: var(--text-muted); font-size: 0.875rem; flex-wrap: wrap; }
        .header-meta-item { display: flex; align-items: center; gap: 0.5rem; }
        .header-meta-item svg { width: 16px; height: 16px; opacity: 0.7; }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1rem;
            background: var(--bg-tertiary);
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            color: var(--text-secondary);
            font-size: 0.875rem;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn:hover { background: var(--bg-secondary); border-color: var(--border-light); color: var(--text-primary); }
        .btn-gold { background: rgba(234, 179, 8, 0.15); border-color: rgba(234, 179, 8, 0.3); color: var(--accent-gold); }
        .btn-gold:hover { background: rgba(234, 179, 8, 0.25); }
        .main-grid { display: grid; grid-template-columns: 1fr 340px; gap: 2rem; }
        .chart-section { min-width: 0; }
        .sidebar { width: 340px; flex-shrink: 0; display: flex; flex-direction: column; gap: 0.75rem; }
        .chart-card {
            background: linear-gradient(145deg, var(--bg-secondary) 0%, var(--bg-tertiary) 100%);
            border: 1px solid var(--border);
            border-radius: 1rem;
            padding: 1.5rem;
        }
        .chart-card svg { width: 100%; max-width: 480px; height: auto; display: block; margin: 0 auto; }
        .section-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            overflow: hidden;
        }
        .section-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 0.875rem;
            border-bottom: 1px solid var(--border);
            font-weight: 600;
            font-size: 0.8rem;
        }
        .section-header.gold { color: var(--accent-gold); }
        .section-header.indigo { color: var(--accent-indigo); }
        .section-header.purple { color: var(--accent-purple); }
        .section-header.cyan { color: var(--accent-cyan); }
        .section-header.pink { color: var(--accent-pink); }
        .section-header.orange { color: var(--accent-orange); }
        .section-header.teal { color: var(--accent-teal); }
        .section-header.red { color: var(--accent-red); }
        .section-header svg { width: 16px; height: 16px; flex-shrink: 0; }
        .section-body { padding: 0.625rem; }
        /* Chart Info */
        .chart-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            padding: 0.625rem 0.875rem;
            background: var(--bg-tertiary);
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            font-size: 0.75rem;
        }
        .chart-meta-item { display: flex; align-items: center; gap: 0.375rem; color: var(--text-muted); }
        .chart-meta-item span { color: var(--text-primary); font-weight: 500; }
        /* Key Points Grid */
        .key-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.375rem; }
        .key-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem;
            background: var(--bg-tertiary);
            border-radius: 0.375rem;
            font-size: 0.75rem;
        }
        .key-item-icon { width: 1.5rem; height: 1.5rem; display: flex; align-items: center; justify-content: center; }
        .key-item-icon img { width: 1.1rem; height: 1.1rem; object-fit: contain; }
        .key-item-name { font-size: 0.65rem; color: var(--text-muted); }
        .key-item-value { font-weight: 600; font-size: 0.75rem; }
        /* Points Grid */
        .points-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.375rem; }
        .point-item {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.4rem;
            background: var(--bg-tertiary);
            border-radius: 0.375rem;
            font-size: 0.75rem;
        }
        .point-icon { width: 1.5rem; height: 1.5rem; display: flex; align-items: center; justify-content: center; background: rgba(139, 92, 246, 0.1); border-radius: 0.375rem; }
        .point-icon img { width: 1rem; height: 1rem; object-fit: contain; }
        /* Planet List */
        .planet-list { max-height: 180px; overflow-y: auto; }
        .planet-list::-webkit-scrollbar { width: 3px; }
        .planet-list::-webkit-scrollbar-track { background: var(--bg-tertiary); border-radius: 2px; }
        .planet-list::-webkit-scrollbar-thumb { background: var(--border-light); border-radius: 2px; }
        .planet-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 0.5rem;
            border-radius: 0.375rem;
            margin-bottom: 0.2rem;
            font-size: 0.75rem;
        }
        .planet-row:hover { background: var(--bg-tertiary); }
        .planet-row-icon { width: 1.25rem; height: 1.25rem; display: flex; align-items: center; }
        .planet-row-icon img { width: 0.9rem; height: 0.9rem; object-fit: contain; }
        .planet-row-name { flex: 1; font-weight: 500; display: flex; align-items: center; gap: 0.25rem; }
        .planet-row-retro {
            font-size: 0.5rem;
            font-weight: 700;
            padding: 0.1rem 0.25rem;
            background: rgba(239, 68, 68, 0.15);
            color: var(--accent-red);
            border-radius: 0.2rem;
        }
        .planet-row-deg { color: var(--text-muted); font-size: 0.7rem; }
        /* Houses Grid */
        .house-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.3rem; }
        .house-item {
            text-align: center;
            padding: 0.3rem 0.2rem;
            background: var(--bg-tertiary);
            border-radius: 0.375rem;
            font-size: 0.7rem;
        }
        .house-item:hover { background: rgba(99, 102, 241, 0.1); }
        .house-item.angular { border: 1px solid rgba(234, 179, 8, 0.3); background: linear-gradient(145deg, rgba(234, 179, 8, 0.1) 0%, var(--bg-tertiary) 100%); }
        .house-item img { width: 1.1rem; height: 1.1rem; object-fit: contain; display: block; margin: 0 auto 0.15rem; }
        .house-item-num { font-size: 0.55rem; font-weight: 600; color: var(--text-muted); }
        .house-item.angular .house-item-num { color: var(--accent-gold); }
        .house-item-deg { font-size: 0.55rem; color: var(--text-muted); font-family: monospace; }
        /* Elements */
        .elem-row { display: flex; gap: 0.375rem; margin-bottom: 0.35rem; }
        .elem-row:last-child { margin-bottom: 0; }
        .elem-item {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.3rem 0.4rem;
            background: var(--bg-tertiary);
            border-radius: 0.375rem;
            font-size: 0.7rem;
        }
        .elem-icon { font-size: 0.9rem; }
        .elem-name { flex: 1; color: var(--text-secondary); }
        .elem-count { font-weight: 600; font-size: 0.75rem; }
        .elem-bar { height: 3px; background: var(--bg-primary); border-radius: 1.5px; margin-top: 0.2rem; overflow: hidden; }
        .elem-bar-fill { height: 100%; border-radius: 1.5px; transition: width 0.3s ease; }
        /* Qualities & Polarity */
        .qual-row { display: flex; gap: 0.375rem; margin-bottom: 0.35rem; }
        .qual-item { flex: 1; padding: 0.3rem 0.4rem; background: var(--bg-tertiary); border-radius: 0.375rem; text-align: center; font-size: 0.7rem; }
        .qual-count { font-weight: 600; font-size: 0.8rem; }
        .polar-row { display: flex; gap: 0.375rem; }
        .polar-item { flex: 1; display: flex; align-items: center; gap: 0.35rem; padding: 0.35rem 0.4rem; background: var(--bg-tertiary); border-radius: 0.375rem; font-size: 0.7rem; }
        .polar-icon { font-size: 1rem; }
        .polar-name { flex: 1; color: var(--text-secondary); }
        .polar-count { font-weight: 600; }
        /* Aspects */
        .aspect-group { margin-bottom: 0.5rem; }
        .aspect-group:last-child { margin-bottom: 0; }
        .aspect-label {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.6rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.3rem;
        }
        .aspect-label.green { color: var(--accent-green); }
        .aspect-label.red { color: var(--accent-red); }
        .aspect-label.purple { color: var(--accent-purple); }
        .aspect-label .dot { width: 5px; height: 5px; border-radius: 50%; }
        .aspect-label.green .dot { background: var(--accent-green); }
        .aspect-label.red .dot { background: var(--accent-red); }
        .aspect-label.purple .dot { background: var(--accent-purple); }
        .aspect-list { max-height: 130px; overflow-y: auto; }
        .aspect-list::-webkit-scrollbar { width: 3px; }
        .aspect-list::-webkit-scrollbar-track { background: var(--bg-tertiary); border-radius: 2px; }
        .aspect-list::-webkit-scrollbar-thumb { background: var(--border-light); border-radius: 2px; }
        .aspect-row {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.2rem 0.35rem;
            border-radius: 0.25rem;
            font-size: 0.7rem;
            margin-bottom: 0.15rem;
        }
        .aspect-row:hover { background: var(--bg-tertiary); }
        .aspect-symbol { width: 1rem; text-align: center; font-size: 0.8rem; }
        .aspect-symbol.green { color: var(--accent-green); }
        .aspect-symbol.red { color: var(--accent-red); }
        .aspect-symbol.purple { color: var(--accent-purple); }
        .aspect-planets { flex: 1; color: var(--text-secondary); font-size: 0.65rem; }
        .aspect-orb { font-size: 0.6rem; color: var(--text-muted); font-family: monospace; }
        /* Dignities Table */
        .dign-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.25rem; font-size: 0.65rem; }
        .dign-item { display: flex; align-items: center; gap: 0.25rem; padding: 0.25rem 0.35rem; background: var(--bg-tertiary); border-radius: 0.25rem; }
        .dign-planet { width: 2.5rem; font-weight: 500; display: flex; align-items: center; gap: 0.2rem; }
        .dign-planet img { width: 0.8rem; height: 0.8rem; object-fit: contain; }
        .dign-status { margin-left: auto; display: flex; gap: 0.15rem; }
        .dign-dot { width: 5px; height: 5px; border-radius: 50%; }
        .dign-dot.exalt { background: var(--accent-green); }
        .dign-dot.ruler { background: var(--accent-gold); }
        .dign-dot.fall { background: var(--accent-red); }
        .dign-dot.detrim { background: var(--text-muted); }
        /* Vertex */
        .vertex-row { display: flex; justify-content: space-between; padding: 0.4rem 0.5rem; background: var(--bg-tertiary); border-radius: 0.375rem; margin-bottom: 0.35rem; font-size: 0.75rem; }
        .vertex-row:last-child { margin-bottom: 0; }
        .vertex-name { color: var(--text-muted); }
        .vertex-value { font-weight: 600; display: flex; align-items: center; gap: 0.35rem; }
        .vertex-value img { width: 1.1rem; height: 1.1rem; object-fit: contain; }
        /* Midpoints */
        .midpoint-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.35rem; }
        .midpoint-item {
            padding: 0.4rem;
            background: var(--bg-tertiary);
            border-radius: 0.375rem;
            font-size: 0.7rem;
            text-align: center;
        }
        .midpoint-planets { display: flex; align-items: center; justify-content: center; gap: 0.25rem; margin-bottom: 0.2rem; }
        .midpoint-planets img { width: 0.8rem; height: 0.8rem; object-fit: contain; }
        .midpoint-symbol { color: var(--text-muted); font-size: 0.8rem; }
        .midpoint-result { font-weight: 600; color: var(--accent-indigo); }
        /* Patterns */
        .pattern-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            background: var(--bg-tertiary);
            border-radius: 0.5rem;
            margin-bottom: 0.35rem;
            font-size: 0.75rem;
        }
        .pattern-item:last-child { margin-bottom: 0; }
        .pattern-icon { width: 2rem; height: 2rem; display: flex; align-items: center; justify-content: center; border-radius: 0.5rem; }
        .pattern-icon.harmony { background: rgba(34, 197, 94, 0.15); }
        .pattern-icon.stress { background: rgba(239, 68, 68, 0.15); }
        .pattern-icon.neutral { background: rgba(139, 92, 246, 0.15); }
        .pattern-name { font-weight: 600; }
        .pattern-desc { font-size: 0.7rem; color: var(--text-muted); }
        /* Transits */
        .transit-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            background: var(--bg-tertiary);
            border-radius: 0.5rem;
            margin-bottom: 0.35rem;
            font-size: 0.75rem;
        }
        .transit-item:last-child { margin-bottom: 0; }
        .transit-icon { width: 1.75rem; height: 1.75rem; display: flex; align-items: center; justify-content: center; background: var(--bg-secondary); border-radius: 0.375rem; }
        img { width: .transit-icon 1.25rem; height: 1.25rem; object-fit: contain; }
        .transit-info { flex: 1; }
        .transit-planets { font-weight: 600; }
        .transit-desc { font-size: 0.65rem; color: var(--text-muted); }
        .transit-orb { font-size: 0.7rem; color: var(--accent-green); }
        /* Analysis Section */
        .analysis-section { margin-top: 1.5rem; }
        .analysis-title { font-size: 1.1rem; font-weight: 600; margin-bottom: 0.75rem; color: var(--text-primary); }
        .analysis-grid { display: flex; flex-direction: column; gap: 0.75rem; }
        .analysis-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        .analysis-card-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.75rem;
            background: var(--bg-tertiary);
            border-bottom: 1px solid var(--border);
        }
        .analysis-card-header img { width: 1.5rem; height: 1.5rem; object-fit: contain; }
        .analysis-card-title { font-weight: 600; font-size: 0.85rem; }
        .analysis-card-body { padding: 0.875rem; font-size: 0.8rem; color: var(--text-secondary); line-height: 1.7; }
        .analysis-card-body p { margin-bottom: 0.5rem; }
        .analysis-card-body p:last-child { margin-bottom: 0; }
        /* Planet House Analysis */
        .planet-house-item {
            padding: 0.5rem;
            background: var(--bg-tertiary);
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
            font-size: 0.75rem;
        }
        .planet-house-item:last-child { margin-bottom: 0; }
        .planet-house-header { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.35rem; }
        .planet-house-header img { width: 1.25rem; height: 1.25rem; object-fit: contain; }
        .planet-house-name { font-weight: 600; }
        .planet-house-text { font-size: 0.7rem; color: var(--text-muted); line-height: 1.5; }
        /* Tooltip */
        .tooltip {
            position: fixed;
            background: rgba(17, 22, 31, 0.95);
            border: 1px solid rgba(129, 140, 248, 0.3);
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem;
            font-size: 0.7rem;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.15s ease;
            z-index: 1000;
            backdrop-filter: blur(8px);
        }
        .tooltip.show { opacity: 1; }
        .tooltip-name { font-weight: 600; color: var(--accent-gold); margin-bottom: 0.2rem; }
        .tooltip-detail { color: var(--text-secondary); }
        .aspect-line { stroke-linecap: round; opacity: 0.4; }
        @media (max-width: 1400px) {
            .main-grid { grid-template-columns: 1fr; }
            .sidebar { width: 100%; display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 0.75rem; }
        }
        @media (max-width: 768px) {
            .container { padding: 1rem; }
            .header-meta { gap: 0.5rem; }
            .header-left h1 { font-size: 1.5rem; }
            .chart-card { padding: 1rem; }
            .chart-card svg { max-width: 100%; }
            .house-grid { grid-template-columns: repeat(4, 1fr); }
            .sidebar { grid-template-columns: 1fr; }
            .analysis-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="page-header">
            <div class="header-left">
                <h1>{{ $chart->name }}</h1>
                <div class="header-meta">
                    <span class="header-meta-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ $chart->birth_date->format('d.m.Y') }}
                    </span>
                    @if($chart->birth_time)
                    <span class="header-meta-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ \Carbon\Carbon::parse($chart->birth_time)->format('H:i') }}
                    </span>
                    @endif
                    <span class="header-meta-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $chart->birth_place }}
                    </span>
                </div>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <a href="/" class="btn">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Назад
                </a>
                <button class="btn btn-gold">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    PDF
                </button>
            </div>
        </header>

        <div class="main-grid">
            <section class="chart-section">
                <div class="chart-card">
                    @php
                    $signToFile = ['Овен' => 'aries', 'Телец' => 'taurus', 'Близнецы' => 'gemini', 'Рак' => 'cancer', 'Лев' => 'leo', 'Дева' => 'virgo', 'Весы' => 'libra', 'Скорпион' => 'scorpio', 'Стрелец' => 'sagittarius', 'Козерог' => 'capricorn', 'Водолей' => 'aquarius', 'Рыбы' => 'pisces'];
                    $planetFiles = ['sun' => 'sun', 'moon' => 'moon', 'mercury' => 'mercury', 'venus' => 'venus', 'mars' => 'mars', 'jupiter' => 'jupiter', 'saturn' => 'saturn', 'uranus' => 'uranus', 'neptune' => 'neptune', 'pluto' => 'pluto', 'north_node' => 'north_node', 'south_node' => 'south_node', 'chiron' => 'chiron', 'part_fortune' => 'part_fortune', 'vertex' => 'vertex'];
                    $planetNames = ['sun' => 'Солнце', 'moon' => 'Луна', 'mercury' => 'Меркурий', 'venus' => 'Венера', 'mars' => 'Марс', 'jupiter' => 'Юпитер', 'saturn' => 'Сатурн', 'uranus' => 'Уран', 'neptune' => 'Нептун', 'pluto' => 'Плутон', 'north_node' => 'Сев. узел', 'south_node' => 'Южн. узел', 'chiron' => 'Хирон', 'part_fortune' => 'Часть фортуны', 'vertex' => 'Вертекс'];
                    $signOrder = ['Овен', 'Телец', 'Близнецы', 'Рак', 'Лев', 'Дева', 'Весы', 'Скорпион', 'Стрелец', 'Козерог', 'Водолей', 'Рыбы'];
                    $signElems = ['fire' => 'rgba(239, 68, 68, 0.08)', 'earth' => 'rgba(249, 115, 22, 0.08)', 'air' => 'rgba(59, 130, 246, 0.08)', 'water' => 'rgba(139, 92, 246, 0.08)'];
                    $elemMap = ['Овен' => 'fire', 'Телец' => 'earth', 'Близнецы' => 'air', 'Рак' => 'water', 'Лев' => 'fire', 'Дева' => 'earth', 'Весы' => 'air', 'Скорпион' => 'water', 'Стрелец' => 'fire', 'Козерог' => 'earth', 'Водолей' => 'air', 'Рыбы' => 'water'];
                    $qualityMap = ['Овен' => 'cardinal', 'Рак' => 'cardinal', 'Весы' => 'cardinal', 'Козерог' => 'cardinal', 'Телец' => 'fixed', 'Лев' => 'fixed', 'Скорпион' => 'fixed', 'Водолей' => 'fixed', 'Близнецы' => 'mutable', 'Дева' => 'mutable', 'Стрелец' => 'mutable', 'Рыбы' => 'mutable'];
                    $polarityMap = ['Овен' => 'yang', 'Близнецы' => 'yang', 'Лев' => 'yang', 'Весы' => 'yang', 'Стрелец' => 'yang', 'Водолей' => 'yang', 'Телец' => 'yin', 'Рак' => 'yin', 'Дева' => 'yin', 'Скорпион' => 'yin', 'Козерог' => 'yin', 'Рыбы' => 'yin'];
                    function getAbsDeg($s, $d) { $idx = array_search($s, ['Овен','Телец','Близнецы','Рак','Лев','Дева','Весы','Скорпион','Стрелец','Козерог','Водолей','Рыбы']); return ($idx?:0)*30+$d; }
                    function toSvgAngle($deg, $asc) { return 180 - ($deg - $asc); }
                    function polar($cx, $cy, $r, $a) { $rad = $a * M_PI / 180; return ['x' => $cx + $r * cos($rad), 'y' => $cy - $r * sin($rad)]; }
                    $houses = $chart->chart_data['houses'] ?? [];
                    $planets = $chart->chart_data['planets'] ?? [];
                    $aspects = $chart->chart_data['aspects'] ?? [];
                    $ascSign = $houses[1]['sign'] ?? 'Овен'; $ascDeg = $houses[1]['degree'] ?? 0; $ascAbs = getAbsDeg($ascSign, $ascDeg);
                    $mcSign = $houses[10]['sign'] ?? 'Рыбы'; $mcDeg = $houses[10]['degree'] ?? 0; $mcAbs = getAbsDeg($mcSign, $mcDeg);
                    $cx = 350; $cy = 350; $rOuter = 330; $rHouse = 290; $rInner = 100; $rPlanet = 210;
                    $zodiac = []; foreach ($signOrder as $i => $s) { $ang = toSvgAngle($i*30, $ascAbs); $zodiac[$i] = ['file' => $signToFile[$s] ?? strtolower($s), 'pos' => polar($cx,$cy,$rOuter+18,$ang), 'ls' => polar($cx,$cy,$rOuter,$ang), 'le' => polar($cx,$cy,$rOuter-15,$ang)]; }
                    $hLines = []; for($i=1;$i<=12;$i++){ $h=$houses[$i]??['sign'=>'Овен','degree'=>0]; $abs=getAbsDeg($h['sign'],$h['degree']); $ang=toSvgAngle($abs,$ascAbs); $angH=in_array($i,[1,4,7,10]); $hLines[$i] = ['in'=>polar($cx,$cy,$rInner,$ang), 'out'=>polar($cx,$cy,$angH?$rOuter:$rHouse,$ang), 'col'=>$angH?'#FFD700':'#475569','w'=>$angH?2:1]; }
                    $hNums = []; for($i=1;$i<=12;$i++){ $n=$i==12?1:$i+1; $h1=$houses[$i]??['sign'=>'Овен','degree'=>0]; $h2=$houses[$n]??['sign'=>'Овен','degree'=>30]; $d1=getAbsDeg($h1['sign'],$h1['degree']); $d2=getAbsDeg($h2['sign'],$h2['degree']); if($d2<$d1)$d2+=360; $hNums[$i]=polar($cx,$cy,($rHouse+$rInner)/2,toSvgAngle(($d1+$d2)/2,$ascAbs)); }
                    $ascP = polar($cx,$cy,$rOuter+8,toSvgAngle($ascAbs,$ascAbs)); $descP = polar($cx,$cy,$rOuter+8,toSvgAngle($ascAbs+180,$ascAbs)); $mcP = polar($cx,$cy,$rOuter+8,toSvgAngle($mcAbs,$ascAbs)); $icP = polar($cx,$cy,$rOuter+8,toSvgAngle($mcAbs+180,$ascAbs));
                    $pCoords = []; foreach($planets as $k=>$p){ if(is_array($p)) { $abs=getAbsDeg($p['sign']??'Овен',$p['degree']??0); $ang=toSvgAngle($abs,$ascAbs); $pCoords[$k] = ['pos'=>polar($cx,$cy,$rPlanet,$ang), 'deg'=>polar($cx,$cy,$rPlanet+28,$ang)]; } }
                    $aLines = []; foreach($aspects as $a){ $p1=strtolower($a['planet1']??''); $p2=strtolower($a['planet2']??''); $k1=$k2=null; foreach($pCoords as $k=>$v){ if(!$k1&&(strtolower($planetNames[$k]??$k)===$p1))$k1=$k; if(!$k2&&(strtolower($planetNames[$k]??$k)===$p2))$k2=$k; } if($k1&&$k2&&isset($pCoords[$k1],$pCoords[$k2])){ $col=in_array($a['type']??'',['Square','Opposition','Квадрат','Оппозиция'])?'#EF4444':(in_array($a['type']??'',['Trine','Sextile','Трин','Секстиль'])?'#22C55E':'#A855F7'); $aLines[]=['f'=>$pCoords[$k1]['pos'],'t'=>$pCoords[$k2]['pos'],'c'=>$col]; } }
                    $kPoints = []; foreach(['sun','moon','ascendant','mc'] as $pt){ $d=$chart->chart_data[$pt]??($chart->chart_data['planets'][$pt]??null); if($d){ $s=$d['sign']??'Овен'; $deg=floor($d['degree']??0); $min=round(($d['degree']??0-$deg)*60); if($min>=60){$min-=60;$deg++;} $kPoints[$pt]=['n'=>$planetNames[$pt]??ucfirst($pt),'sFile'=>$signToFile[$s]??strtolower($s),'d'=>$deg.'°'.str_pad($min,2,'0',STR_PAD_LEFT)]; } }
                    $elemCount = ['fire'=>0,'earth'=>0,'air'=>0,'water'=>0];
                    $qualityCount = ['cardinal'=>0,'fixed'=>0,'mutable'=>0];
                    $polarityCount = ['yang'=>0,'yin'=>0];
                    foreach($planets as $p) { if(is_array($p) && isset($p['sign'])) { $e = $elemMap[$p['sign']] ?? 'fire'; $q = $qualityMap[$p['sign']] ?? 'cardinal'; $pl = $polarityMap[$p['sign']] ?? 'yang'; if(isset($elemCount[$e])) $elemCount[$e]++; if(isset($qualityCount[$q])) $qualityCount[$q]++; if(isset($polarityCount[$pl])) $polarityCount[$pl]++; } }
                    $totalPlanets = array_sum($elemCount) ?: 1;
                    @endphp

                    <div class="chart-meta">
                        <div class="chart-meta-item">Система: <span>Placidus</span></div>
                        <div class="chart-meta-item">Время: <span>{{ $chart->birth_time ?? '--:--' }}</span></div>
                        <div class="chart-meta-item">Широта: <span>--</span></div>
                        <div class="chart-meta-item">Долгота: <span>--</span></div>
                        <div class="chart-meta-item">LST: <span>--:--</span></div>
                    </div>

                    <div class="tooltip" id="tooltip"></div>
                    <svg viewBox="0 0 700 700">
                        <defs><filter id="glow"><feGaussianBlur stdDeviation="1.5" result="blur"/><feMerge><feMergeNode in="blur"/><feMergeNode in="SourceGraphic"/></feMerge></filter></defs>
                        <circle cx="350" cy="350" r="345" fill="#0B0E14" stroke="#2A3441"/>
                        <circle cx="350" cy="350" r="290" fill="none" stroke="#2A3441"/>
                        <circle cx="350" cy="350" r="100" fill="#080B0E" stroke="#2A3441"/>
                        @for($i=0;$i<12;$i++) @php $s=$signOrder[$i]; $c=$signElems[$elemMap[$s]??'fire']; @endphp
                        <path d="M350,350 L{{ 350+345*cos(deg2rad($i*30-90)) }},{{ 350+345*sin(deg2rad($i*30-90)) }} A345,345 0 0,1 {{ 350+345*cos(deg2rad(($i+1)*30-90)) }},{{ 350+345*sin(deg2rad(($i+1)*30-90)) }} Z" fill="{{ $c }}"/>@endfor
                        @foreach($zodiac as $z)
                        <line x1="{{ $z['ls']['x'] }}" y1="{{ $z['ls']['y'] }}" x2="{{ $z['le']['x'] }}" y2="{{ $z['le']['y'] }}" stroke="#475569" opacity="0.5"/>
                        <image href="/images/zodiac/{{ $z['file'] }}.png" x="{{ $z['pos']['x'] - 11 }}" y="{{ $z['pos']['y'] - 11 }}" width="22" height="22" opacity="0.7"/>
                        @endforeach
                        @foreach($hLines as $l)<line x1="{{ $l['in']['x'] }}" y1="{{ $l['in']['y'] }}" x2="{{ $l['out']['x'] }}" y2="{{ $l['out']['y'] }}" stroke="{{ $l['col'] }}" stroke-width="{{ $l['w'] }}" opacity="0.5"/>@endforeach
                        @foreach($hNums as $n=>$p)<text x="{{ $p['x'] }}" y="{{ $p['y'] }}" text-anchor="middle" dominant-baseline="middle" fill="#818CF8" font-size="10" font-weight="600">{{ $n }}</text>@endforeach
                        <text x="{{ $ascP['x']-26 }}" y="{{ $ascP['y']+2 }}" fill="#EAB308" font-size="8" font-weight="700">ASC</text>
                        <text x="{{ $descP['x']+5 }}" y="{{ $descP['y']+2 }}" fill="#EAB308" font-size="8" font-weight="700">DSC</text>
                        <text x="{{ $mcP['x'] }}" y="{{ $mcP['y']-5 }}" text-anchor="middle" fill="#EAB308" font-size="8" font-weight="700">MC</text>
                        <text x="{{ $icP['x'] }}" y="{{ $icP['y']+11 }}" text-anchor="middle" fill="#EAB308" font-size="8" font-weight="700">IC</text>
                        @foreach($aLines as $a)<line x1="{{ $a['f']['x'] }}" y1="{{ $a['f']['y'] }}" x2="{{ $a['t']['x'] }}" y2="{{ $a['t']['y'] }}" stroke="{{ $a['c'] }}" stroke-width="1.5" class="aspect-line"/>@endforeach
                        @foreach($planets as $k=>$p) @php if(!is_array($p) || !isset($pCoords[$k])) continue; $c=$pCoords[$k]['pos']; $d=$pCoords[$k]['deg']; @endphp
                        <g class="planet" data-name="{{ $planetNames[$k] ?? ucfirst($k) }}" data-sign="{{ $p['sign'] ?? '' }} {{ floor($p['degree'] ?? 0) }}°" data-house="{{ $p['house'] ?? '-' }}">
                            @if(isset($planetFiles[$k]))<image href="/images/planets/{{ $planetFiles[$k] }}.png" x="{{ $c['x']-13 }}" y="{{ $c['y']-13 }}" width="26" height="26"/>@endif
                            <text x="{{ $d['x'] }}" y="{{ $d['y'] }}" text-anchor="middle" dominant-baseline="middle" fill="#94A3B8" font-size="8">{{ floor($p['degree'] ?? 0) }}°</text>
                        </g>@endforeach
                        <text x="350" y="347" text-anchor="middle" fill="#647887" font-size="7">PLACIDUS</text>
                        <text x="350" y="359" text-anchor="middle" fill="#647887" font-size="8">{{ $chart->birth_date->format('d.m.Y') }}</text>
                    </svg>
                </div>

                <!-- Character Analysis - All visible -->
                <div class="analysis-section">
                    <h2 class="analysis-title">Характер и личность</h2>
                    <div class="analysis-grid">
                        <div class="analysis-card">
                            <div class="analysis-card-header">
                                <img src="/images/zodiac/{{ $signToFile[$houses[1]['sign'] ?? 'aries'] }}.png" alt="">
                                <span class="analysis-card-title">Общая характеристика</span>
                            </div>
                            <div class="analysis-card-body">
                                <p>Ваша натальная карта раскрывает уникальную структуру личности, где каждая планета занимает своё место в определённом доме и знаке. {{ $houses[1]['sign'] ?? 'Овен' }} на Ascendant создаёт первое впечатление, которое вы производите на окружающих — это ваша маска, способ самопрезентации.</p>
                                <p>Доминирование стихии {{ array_keys($elemCount, max($elemCount))[0] ?? 'огня' }} говорит о вашем темпераменте и подходе к жизни. Вы стремитесь к активным действиям и не боитесь брать инициативу в свои руки.</p>
                            </div>
                        </div>
                        <div class="analysis-card">
                            <div class="analysis-card-header">
                                <img src="/images/planets/sun.png" alt="">
                                <span class="analysis-card-title">Солнце — Ядро личности</span>
                            </div>
                            <div class="analysis-card-body">
                                <p>Солнце в вашей карте указывает на основную жизненную энергию, волю и творческий потенциал. Это ваше «Я» — то, что составляет ядро вашей личности, центральный фокус самореализации.</p>
                                <p>Солнце в знаке {{ $planets['sun']['sign'] ?? 'Овна' }} говорит о вашем стиле самовыражения. Вы стремитесь к признанию, хотите быть замеченным и оценённым своими талантами и способностями.</p>
                            </div>
                        </div>
                        <div class="analysis-card">
                            <div class="analysis-card-header">
                                <img src="/images/zodiac/{{ $signToFile[$houses[4]['sign'] ?? 'cancer'] }}.png" alt="">
                                <span class="analysis-card-title">Луна — Эмоции и интуиция</span>
                            </div>
                            <div class="analysis-card-body">
                                <p>Луна отражает вашу эмоциональную природу, подсознательные реакции и потребность в безопасности. Это ваша внутренняя жизнь, чувствительность и способность приспосабливаться к обстоятельствам.</p>
                                <p>Ваша Луна в {{ $planets['moon']['sign'] ?? 'Раке' }} показывает, как вы реагируете на эмоциональные ситуации. Вы обладаете развитой интуицией и глубокой эмоциональной памятью.</p>
                            </div>
                        </div>
                        <div class="analysis-card">
                            <div class="analysis-card-header">
                                <img src="/images/planets/mercury.png" alt="">
                                <span class="analysis-card-title">Меркурий — Мышление и общение</span>
                            </div>
                            <div class="analysis-card-body">
                                <p>Меркурий управляет мыслительными процессами, способом получения и передачи информации. Это ваш ум, речь, способность к обучению и анализу.</p>
                                <p>Расположение Меркурия в {{ $planets['mercury']['sign'] ?? 'Близнецах' }} делает вас гибким мыслителем, способным быстро адаптировать свои идеи и находить общий язык с разными людьми.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Planets in Houses - All visible -->
                <div class="analysis-section">
                    <h2 class="analysis-title">Планеты в домах</h2>
                    <div class="analysis-grid">
                        @foreach(['sun','moon','mercury','venus','mars','jupiter','saturn'] as $planet)
                        @if(isset($planets[$planet]))
                        <div class="analysis-card">
                            <div class="analysis-card-header">
                                <img src="/images/planets/{{ $planetFiles[$planet] }}.png" alt="">
                                <span class="analysis-card-title">{{ $planetNames[$planet] }} в {{ $planets[$planet]['house'] ?? '-' }} доме</span>
                            </div>
                            <div class="analysis-card-body">
                                <p>{{ $planetNames[$planet] }} в {{ $planets[$planet]['house'] ?? '-' }} доме указывает на сферу жизни, где проявляется влияние этой планеты. Это область, куда вы направляете энергию и где можете достичь наибольших успехов.</p>
                                <p>В этом доме {{ $planetNames[$planet] }} даёт возможность реализовать свой потенциал через практические действия и конкретный опыт.</p>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>

                <!-- Patterns & Transits - All visible -->
                <div class="analysis-section">
                    <h2 class="analysis-title">Паттерны и транзиты</h2>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <div class="section-card">
                            <div class="section-header purple">
                                <svg fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>
                                Астрологические паттерны
                            </div>
                            <div class="section-body">
                                <div class="pattern-item">
                                    <div class="pattern-icon harmony"><img src="/images/zodiac/leo.png" width="20" alt="△"></div>
                                    <div>
                                        <div class="pattern-name">Большой трин (Grand Trine)</div>
                                        <div class="pattern-desc">Гармоничная связь трёх планет в одной стихии создаёт природный талант и лёгкость в проявлении энергии.</div>
                                    </div>
                                </div>
                                <div class="pattern-item">
                                    <div class="pattern-icon stress"><span style="color:#EF4444;font-weight:bold">□</span></div>
                                    <div>
                                        <div class="pattern-name">Т-квадрат (T-Square)</div>
                                        <div class="pattern-desc">Напряжённая конфигурация, указывающая на зоны напряжения и необходимость преодоления препятствий.</div>
                                    </div>
                                </div>
                                <div class="pattern-item">
                                    <div class="pattern-icon neutral" style="font-size:0.7rem;font-weight:bold;color:#A855F7">YOD</div>
                                    <div>
                                        <div class="pattern-name">Йод — Палец Судьбы</div>
                                        <div class="pattern-desc">Редкая конфигурация, указывающая на судьбоносные решения и события.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="section-card">
                            <div class="section-header red">
                                <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2v2m0 16v2M4.93 4.93l1.41 1.41m11.32 11.32l1.41 1.41M2 12h2m16 0h2M4.93 19.07l1.41-1.41m11.32-11.32l1.41-1.41"/></svg>
                                Текущие транзиты
                            </div>
                            <div class="section-body">
                                <div class="transit-item">
                                    <div class="transit-icon"><img src="/images/planets/jupiter.png" alt=""></div>
                                    <div class="transit-info">
                                        <div class="transit-planets">Юпитер ↔ Солнце</div>
                                        <div class="transit-desc">Расширение возможностей, оптимизм, благоприятное время для новых начинаний</div>
                                    </div>
                                    <div class="transit-orb" style="color:#22C55E">+2°</div>
                                </div>
                                <div class="transit-item">
                                    <div class="transit-icon"><img src="/images/planets/saturn.png" alt=""></div>
                                    <div class="transit-info">
                                        <div class="transit-planets">Сатурн ⚹ Марс</div>
                                        <div class="transit-desc">Проверка силы воли и дисциплины, требуется терпение</div>
                                    </div>
                                    <div class="transit-orb" style="color:#EF4444">-1°</div>
                                </div>
                                <div class="transit-item">
                                    <div class="transit-icon"><img src="/images/planets/neptune.png" alt=""></div>
                                    <div class="transit-info">
                                        <div class="transit-planets">Нептун ○ Луна</div>
                                        <div class="transit-desc">Повышенная интуиция, творческое вдохновение</div>
                                    </div>
                                    <div class="transit-orb" style="color:#A855F7">+0°</div>
                                </div>
                            </div>
                        </div>
                        <div class="section-card">
                            <div class="section-header orange">
                                <svg fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/></svg>
                                Солнечное возвращение
                            </div>
                            <div class="section-body">
                                <p style="font-size: 0.8rem; color: var(--text-secondary); margin: 0; line-height: 1.6;">
                                    Следующий день рождения принесёт важные изменения в сфере карьеры. Это время благоприятно для принятия решений, которые определят ваш путь на ближайший год.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Synastry & Compatibility - All visible -->
                <div class="analysis-section">
                    <h2 class="analysis-title">Совместимость и отношения</h2>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <div class="section-card">
                            <div class="section-header pink">
                                <svg fill="currentColor" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                                Синастрия
                            </div>
                            <div class="section-body">
                                <p style="font-size: 0.8rem; color: var(--text-secondary); margin: 0 0 0.75rem; line-height: 1.6;">
                                    Синастрия показывает, как взаимодействуют энергии двух людей. Сравните положение планет — где планеты одного аспектируют планеты другого, возникают зоны гармонии или напряжения.
                                </p>
                                <button class="btn" style="width: 100%; justify-content: center;">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Добавить карту партнёра
                                </button>
                            </div>
                        </div>
                        <div class="section-card">
                            <div class="section-header teal">
                                <svg fill="currentColor" viewBox="0 0 24 24"><path d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Композитная карта
                            </div>
                            <div class="section-body">
                                <p style="font-size: 0.8rem; color: var(--text-secondary); margin: 0; line-height: 1.6;">
                                    Объединяет две карты в одну, показывая «третью сущность» отношений — их характер, сильные стороны и вызовы.
                                </p>
                            </div>
                        </div>
                        <div class="section-card">
                            <div class="section-header gold">
                                <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                Часть Фортуны
                            </div>
                            <div class="section-body">
                                <p style="font-size: 0.8rem; color: var(--text-secondary); margin: 0; line-height: 1.6;">
                                    Указывает на область жизни, где вы можете достичь наибольшего успеха и удовлетворения. Зона удачи и гармонии с собой.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <aside class="sidebar">
                <!-- Key Points -->
                @if(count($kPoints) > 0)
                <div class="section-card">
                    <div class="section-header gold">
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
                        Ключевые точки
                    </div>
                    <div class="section-body">
                        <div class="key-grid">
                            @foreach($kPoints as $p)
                            <div class="key-item">
                                <div class="key-item-icon"><img src="/images/zodiac/{{ $p['sFile'] }}.png" alt=""></div>
                                <div>
                                    <div class="key-item-name">{{ $p['n'] }}</div>
                                    <div class="key-item-value">{{ $p['d'] }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Additional Points -->
                <div class="section-card">
                    <div class="section-header pink">
                        <svg fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/></svg>
                        Доп. точки
                    </div>
                    <div class="section-body">
                        <div class="points-grid">
                            @php
                            $addPoints = [];
                            if(isset($planets['north_node'])) { $nn = $planets['north_node']; $addPoints[] = ['name'=>'С. узел','file'=>'north_node','deg'=>floor($nn['degree']??0)]; }
                            if(isset($planets['south_node'])) { $sn = $planets['south_node']; $addPoints[] = ['name'=>'Ю. узел','file'=>'south_node','deg'=>floor($sn['degree']??0)]; }
                            if(isset($planets['chiron'])) { $ch = $planets['chiron']; $addPoints[] = ['name'=>'Хирон','file'=>'chiron','deg'=>floor($ch['degree']??0)]; }
                            if(isset($planets['part_fortune'])) { $pf = $planets['part_fortune']; $addPoints[] = ['name'=>'Ч. фортуны','file'=>'part_fortune','deg'=>floor($pf['degree']??0)]; }
                            @endphp
                            @foreach($addPoints as $pt)
                            <div class="point-item">
                                <div class="point-icon"><img src="/images/planets/{{ $pt['file'] }}.png" alt=""></div>
                                <div>
                                    <div class="key-item-name">{{ $pt['name'] }}</div>
                                    <div class="key-item-value">{{ $pt['deg'] }}°</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Planets -->
                <div class="section-card">
                    <div class="section-header indigo">
                        <svg fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/></svg>
                        Планеты
                    </div>
                    <div class="section-body">
                        <div class="planet-list">
                            @foreach($planets as $k => $p)
                            @if(!is_array($p)) @continue @endif
                            <div class="planet-row">
                                <div class="planet-row-icon">@if(isset($planetFiles[$k]))<img src="/images/planets/{{ $planetFiles[$k] }}.png" alt="">@endif</div>
                                <div class="planet-row-name">
                                    {{ $planetNames[$k] ?? ucfirst($k) }}
                                    @if(($p['retrograde'] ?? false))<span class="planet-row-retro">R</span>@endif
                                </div>
                                <div class="planet-row-deg">{{ $p['house'] ?? '-' }} · {{ floor($p['degree'] ?? 0) }}°</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Houses -->
                <div class="section-card">
                    <div class="section-header cyan">
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M3 13h1v7c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-7h1v-3h-18v3h-1V6c0-1.1.9-2 2-2h12c1.1 0 2 .9 2 2v7z"/></svg>
                        Дома
                    </div>
                    <div class="section-body">
                        <div class="house-grid">
                            @for($i = 1; $i <= 12; $i++)
                            @php $h = $houses[$i] ?? ['sign' => 'Овен', 'degree' => 0]; $file = $signToFile[$h['sign']] ?? 'aries'; @endphp
                            <div class="house-item {{ in_array($i, [1,4,7,10]) ? 'angular' : '' }}">
                                <img src="/images/zodiac/{{ $file }}.png" alt="">
                                <div class="house-item-num">{{ $i }}</div>
                                <div class="house-item-deg">{{ floor($h['degree']) }}°</div>
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- Elements -->
                <div class="section-card">
                    <div class="section-header orange">
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2c-1.1 0-2 .9-2 2v4c0 1.1.9 2 2 2s2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
                        Стихии
                    </div>
                    <div class="section-body">
                        @php $firePct = round($elemCount['fire'] / $totalPlanets * 100); $earthPct = round($elemCount['earth'] / $totalPlanets * 100); $airPct = round($elemCount['air'] / $totalPlanets * 100); $waterPct = round($elemCount['water'] / $totalPlanets * 100); @endphp
                        <div class="elem-row">
                            <div class="elem-item fire"><span class="elem-icon">🔥</span><span class="elem-name">Огонь</span><span class="elem-count">{{ $elemCount['fire'] }}</span></div>
                            <div class="elem-bar" style="flex:1"><div class="elem-bar-fill" style="width:{{ $firePct }}%;background:#EF4444"></div></div>
                        </div>
                        <div class="elem-row">
                            <div class="elem-item earth"><span class="elem-icon">🌍</span><span class="elem-name">Земля</span><span class="elem-count">{{ $elemCount['earth'] }}</span></div>
                            <div class="elem-bar" style="flex:1"><div class="elem-bar-fill" style="width:{{ $earthPct }}%;background:#F97316"></div></div>
                        </div>
                        <div class="elem-row">
                            <div class="elem-item air"><span class="elem-icon">💨</span><span class="elem-name">Воздух</span><span class="elem-count">{{ $elemCount['air'] }}</span></div>
                            <div class="elem-bar" style="flex:1"><div class="elem-bar-fill" style="width:{{ $airPct }}%;background:#3B82F6"></div></div>
                        </div>
                        <div class="elem-row">
                            <div class="elem-item water"><span class="elem-icon">💧</span><span class="elem-name">Вода</span><span class="elem-count">{{ $elemCount['water'] }}</span></div>
                            <div class="elem-bar" style="flex:1"><div class="elem-bar-fill" style="width:{{ $waterPct }}%;background:#8B5CF6"></div></div>
                        </div>
                    </div>
                </div>

                <!-- Qualities & Polarity -->
                <div class="section-card">
                    <div class="section-header purple">
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        Качества
                    </div>
                    <div class="section-body">
                        <div class="qual-row">
                            <div class="qual-item"><span class="qual-count">{{ $qualityCount['cardinal'] }}</span><br>K</div>
                            <div class="qual-item"><span class="qual-count">{{ $qualityCount['fixed'] }}</span><br>F</div>
                            <div class="qual-item"><span class="qual-count">{{ $qualityCount['mutable'] }}</span><br>M</div>
                        </div>
                        <div class="polar-row">
                            <div class="polar-item"><span class="polar-icon" style="color:#3B82F6">☀</span><span class="polar-name">Ян</span><span class="polar-count">{{ $polarityCount['yang'] }}</span></div>
                            <div class="polar-item"><span style="color:#EC4899">☽</span><span class="polar-name">Инь</span><span class="polar-count">{{ $polarityCount['yin'] }}</span></div>
                        </div>
                    </div>
                </div>

                <!-- Vertex -->
                <div class="section-card">
                    <div class="section-header teal">
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2v4m0 12v4M4.93 4.93l2.83 2.83m8.48 8.48l2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83m8.48-8.48l2.83-2.83"/></svg>
                        Vertex / Anti-Vertex
                    </div>
                    <div class="section-body">
                        <div class="vertex-row">
                            <span class="vertex-name">Вертекс</span>
                            <span class="vertex-value">
                                <img src="/images/zodiac/{{ $signToFile[$houses[5]['sign'] ?? 'virgo'] }}.png" alt="">
                                {{ floor($houses[5]['degree'] ?? 0) }}°
                            </span>
                        </div>
                        <div class="vertex-row">
                            <span class="vertex-name">Антивертекс</span>
                            <span class="vertex-value">
                                <img src="/images/zodiac/{{ $signToFile[$houses[11]['sign'] ?? 'pisces'] }}.png" alt="">
                                {{ floor($houses[11]['degree'] ?? 0) }}°
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Aspects -->
                @if(!empty($aspects))
                @php $harmony=['Trine','Sextile','Conjunction','Трин','Секстиль','Соединение']; $stress=['Square','Opposition','Квадрат','Оппозиция']; $sym=['Conjunction'=>'☌','Opposition'=>'☍','Square'=>'□','Trine'=>'△','Sextile'=>'⚹','Соединение'=>'☌','Оппозиция'=>'☍','Квадрат'=>'□','Трин'=>'△','Секстиль'=>'⚹']; $hasH=false;$hasS=false;$hasO=false; foreach($aspects as $a){ if(in_array($a['type']??'', $harmony))$hasH=true; elseif(in_array($a['type']??'', $stress))$hasS=true; else $hasO=true; } @endphp
                <div class="section-card">
                    <div class="section-header purple">
                        <svg fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>
                        Аспекты
                    </div>
                    <div class="section-body">
                        <div class="aspect-list">
                            @if($hasH)
                            <div class="aspect-group">
                                <div class="aspect-label green"><span class="dot"></span>Гармоничные</div>
                                @foreach($aspects as $a) @if(in_array($a['type']??'', $harmony))
                                <div class="aspect-row"><span class="aspect-symbol green">{{ $sym[$a['type']]??'•' }}</span><span class="aspect-planets">{{ $a['planet1'] }} — {{ $a['planet2'] }}</span><span class="aspect-orb">{{ $a['orb']??'' }}°</span></div>
                                @endif @endforeach
                            </div>
                            @endif
                            @if($hasS)
                            <div class="aspect-group">
                                <div class="aspect-label red"><span class="dot"></span>Напряженные</div>
                                @foreach($aspects as $a) @if(in_array($a['type']??'', $stress))
                                <div class="aspect-row"><span class="aspect-symbol red">{{ $sym[$a['type']]??'•' }}</span><span class="aspect-planets">{{ $a['planet1'] }} — {{ $a['planet2'] }}</span><span class="aspect-orb">{{ $a['orb']??'' }}°</span></div>
                                @endif @endforeach
                            </div>
                            @endif
                            @if($hasO)
                            <div class="aspect-group">
                                <div class="aspect-label purple"><span class="dot"></span>Прочие</div>
                                @foreach($aspects as $a) @if(!in_array($a['type']??'', array_merge($harmony,$stress)))
                                <div class="aspect-row"><span class="aspect-symbol purple">{{ $sym[$a['type']]??'•' }}</span><span class="aspect-planets">{{ $a['planet1'] }} — {{ $a['planet2'] }}</span><span class="aspect-orb">{{ $a['orb']??'' }}°</span></div>
                                @endif @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Midpoints -->
                <div class="section-card">
                    <div class="section-header red">
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M19 14l-7 7-7-7"/></svg>
                        Мидпойнты
                    </div>
                    <div class="section-body">
                        <div class="midpoint-grid">
                            <div class="midpoint-item">
                                <div class="midpoint-planets">
                                    <img src="/images/planets/sun.png" alt="">
                                    <span class="midpoint-symbol">+</span>
                                    <img src="/images/planets/moon.png" alt="">
                                </div>
                                <div class="midpoint-result">♈ 15°</div>
                            </div>
                            <div class="midpoint-item">
                                <div class="midpoint-planets">
                                    <img src="/images/planets/venus.png" alt="">
                                    <span class="midpoint-symbol">+</span>
                                    <img src="/images/planets/mars.png" alt="">
                                </div>
                                <div class="midpoint-result">♉ 22°</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dignities -->
                <div class="section-card">
                    <div class="section-header gold">
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        Достоинства
                    </div>
                    <div class="section-body">
                        <div class="dign-grid">
                            @php
                            $dignData = [
                                ['sun','☀', 'Ruler', 'Exalt'],
                                ['moon','☾', 'Fall', 'Detrim'],
                                ['mercury','☿', 'Ruler', 'Exalt'],
                                ['venus','♀', 'Ruler', 'Fall'],
                                ['mars','♂', 'Ruler', 'Fall'],
                                ['jupiter','♃', 'Exalt', 'Detrim'],
                                ['saturn','♄', 'Ruler', 'Exalt'],
                            ];
                            foreach($dignData as $d):
                            @endphp
                            <div class="dign-item">
                                <div class="dign-planet"><img src="/images/planets/{{ $d[0] }}.png" alt="">{{ $d[1] }}</div>
                                <div class="dign-status">
                                    <span class="dign-dot ruler" title="Господин"></span>
                                    <span class="dign-dot exalt" title="Экзальтация"></span>
                                </div>
                            </div>
                            @php endforeach; @endphp
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <script>
        // Tooltip
        const tooltip = document.getElementById('tooltip');
        document.querySelectorAll('.planet').forEach(g => {
            g.addEventListener('mouseenter', () => {
                tooltip.innerHTML = `<div class="tooltip-name">${g.dataset.name}</div><div class="tooltip-detail">${g.dataset.sign}</div><div class="tooltip-detail">${g.dataset.house} дом</div>`;
                tooltip.classList.add('show');
            });
            g.addEventListener('mousemove', (e) => {
                tooltip.style.left = (e.clientX + 12) + 'px';
                tooltip.style.top = (e.clientY - 40) + 'px';
            });
            g.addEventListener('mouseleave', () => tooltip.classList.remove('show'));
        });
    </script>
</body>
</html>
