<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $chart->name }} — Натальная карта</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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

        /* Container */
        .container { max-width: 1400px; margin: 0 auto; padding: 1rem; }

        /* Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border);
            margin-bottom: 0.75rem;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .header-center { text-align: center; flex: 1; }
        .header-center h1 {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
            color: var(--text-primary);
        }
        .header-meta {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
        }

        /* Key Stats Badges */
        .key-stats {
            display: flex;
            justify-content: center;
            gap: 0.75rem;
            flex-wrap: wrap;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border);
            margin-bottom: 1rem;
        }
        .key-badge {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 0.75rem;
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 2rem;
            font-size: 0.8rem;
        }
        .key-badge img { width: 1.25rem; height: 1.25rem; }
        .key-badge-label { color: var(--text-muted); font-size: 0.7rem; }
        .key-badge-value { font-weight: 600; color: var(--text-primary); }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.875rem;
            background: var(--bg-tertiary);
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            color: var(--text-secondary);
            font-size: 0.8rem;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn:hover { background: var(--bg-secondary); border-color: var(--border-light); color: var(--text-primary); }
        .btn-gold { background: rgba(234, 179, 8, 0.15); border-color: rgba(234, 179, 8, 0.3); color: var(--accent-gold); }
        .btn-gold:hover { background: rgba(234, 179, 8, 0.25); }

        /* Tab Navigation */
        .tab-nav {
            display: flex;
            gap: 0.25rem;
            background: var(--bg-secondary);
            padding: 0.25rem;
            border-radius: 0.75rem;
            margin-bottom: 1.25rem;
        }
        .tab-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            background: transparent;
            border: none;
            border-radius: 0.5rem;
            color: var(--text-muted);
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .tab-btn svg { width: 1.25rem; height: 1.25rem; flex-shrink: 0; }
        .tab-btn:hover { color: var(--text-secondary); background: rgba(255,255,255,0.03); }
        .tab-btn.active {
            background: var(--bg-tertiary);
            color: var(--text-primary);
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .tab-btn.active svg { color: var(--accent-gold); }
        .tab-btn-text { display: inline; }

        /* Tab Content */
        .tab-content { display: none; }
        .tab-content.active { display: block; animation: fadeIn 0.2s ease; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        /* Overview Grid - Chart Left, Details Right */
        .overview-grid {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 1.5rem;
            align-items: start;
        }
        .chart-column {
            /* No sticky - scrolls with page */
        }

        /* Chart Card */
        .chart-card {
            background: linear-gradient(145deg, var(--bg-secondary) 0%, var(--bg-tertiary) 100%);
            border: 1px solid var(--border);
            border-radius: 1rem;
            padding: 1rem;
        }
        .chart-card svg { width: 100%; max-width: 500px; height: auto; display: block; margin: 0 auto; }

        /* Elements Summary */
        .elements-summary {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.5rem;
            margin-top: 1rem;
        }
        .element-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            padding: 0.5rem;
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            font-size: 0.75rem;
        }
        .element-badge .icon { font-size: 0.9rem; }
        .element-badge .count { font-weight: 600; }
        .element-badge.fire { border-color: rgba(239, 68, 68, 0.3); background: rgba(239, 68, 68, 0.1); }
        .element-badge.earth { border-color: rgba(249, 115, 22, 0.3); background: rgba(249, 115, 22, 0.1); }
        .element-badge.air { border-color: rgba(59, 130, 246, 0.3); background: rgba(59, 130, 246, 0.1); }
        .element-badge.water { border-color: rgba(139, 92, 246, 0.3); background: rgba(139, 92, 246, 0.1); }

        /* AI Summary */
        .ai-summary {
            background: var(--bg-secondary);
            border: 1px solid rgba(129, 140, 248, 0.2);
            border-radius: 0.75rem;
            padding: 1rem;
            margin-top: 1rem;
            font-size: 0.85rem;
            color: var(--text-secondary);
            line-height: 1.7;
        }
        .ai-summary-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            color: var(--accent-indigo);
            font-weight: 600;
            font-size: 0.8rem;
        }

        /* Sidebar / Details Panel */
        .details-panel {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        /* Section Card */
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
        .section-header img { width: 1rem; height: 1rem; }
        .section-header svg { width: 1rem; height: 1rem; }
        .section-body { padding: 0.625rem; }

        /* Planets List */
        .planet-list { max-height: 200px; overflow-y: auto; }
        .planet-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
        }
        .planet-row:hover { background: var(--bg-tertiary); }
        .planet-row img { width: 1rem; height: 1rem; }
        .planet-row-name { flex: 1; font-weight: 500; display: flex; align-items: center; gap: 0.25rem; }
        .planet-row-retro {
            font-size: 0.5rem;
            font-weight: 700;
            padding: 0.1rem 0.25rem;
            background: rgba(239, 68, 68, 0.15);
            color: var(--accent-red);
            border-radius: 0.2rem;
        }
        .planet-row-deg { color: var(--text-muted); font-size: 0.7rem; font-family: monospace; }

        /* Houses Grid */
        .houses-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.35rem;
        }
        .house-item {
            text-align: center;
            padding: 0.35rem;
            background: var(--bg-tertiary);
            border: 1px solid var(--border);
            border-radius: 0.375rem;
        }
        .house-item.angular {
            border-color: rgba(234, 179, 8, 0.4);
            background: linear-gradient(145deg, rgba(234, 179, 8, 0.1) 0%, var(--bg-tertiary) 100%);
        }
        .house-item img { width: 1.1rem; height: 1.1rem; margin: 0 auto; display: block; }
        .house-num { font-size: 0.6rem; font-weight: 600; color: var(--text-muted); }
        .house-item.angular .house-num { color: var(--accent-gold); }
        .house-deg { font-size: 0.6rem; color: var(--text-muted); font-family: monospace; }

        /* Aspects List */
        .aspects-list { max-height: 150px; overflow-y: auto; }
        .aspect-row {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.25rem 0.4rem;
            border-radius: 0.25rem;
            font-size: 0.7rem;
        }
        .aspect-row:hover { background: var(--bg-tertiary); }
        .aspect-symbol { width: 1rem; text-align: center; font-size: 0.85rem; }
        .aspect-symbol.green { color: var(--accent-green); }
        .aspect-symbol.red { color: var(--accent-red); }
        .aspect-symbol.purple { color: var(--accent-purple); }
        .aspect-planets { flex: 1; color: var(--text-secondary); font-size: 0.65rem; }
        .aspect-orb { font-size: 0.6rem; color: var(--text-muted); font-family: monospace; }

        /* Elements Compact */
        .elem-row { display: flex; gap: 0.35rem; margin-bottom: 0.35rem; }
        .elem-item {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.3rem 0.4rem;
            background: var(--bg-tertiary);
            border-radius: 0.375rem;
            font-size: 0.7rem;
        }
        .elem-icon { font-size: 0.85rem; }
        .elem-name { flex: 1; color: var(--text-secondary); }
        .elem-count { font-weight: 600; }

        /* Analysis Cards Grid */
        .analysis-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 0.75rem;
            margin-bottom: 1rem;
        }
        .analysis-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            overflow: hidden;
        }
        .analysis-card-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 0.875rem;
            background: var(--bg-tertiary);
            border-bottom: 1px solid var(--border);
        }
        .analysis-card-header img { width: 1.25rem; height: 1.25rem; }
        .analysis-card-title { font-weight: 600; font-size: 0.85rem; }
        .analysis-card-body {
            padding: 0.875rem;
            font-size: 0.8rem;
            color: var(--text-secondary);
            line-height: 1.7;
        }

        /* Accordion */
        .accordion { margin-bottom: 0.5rem; }
        .accordion-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            width: 100%;
            padding: 0.875rem 1rem;
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            color: var(--text-primary);
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: left;
        }
        .accordion-header:hover { background: var(--bg-tertiary); border-color: var(--border-light); }
        .accordion-header .icon { font-size: 1.1rem; }
        .accordion-header .arrow {
            margin-left: auto;
            transition: transform 0.2s ease;
            color: var(--text-muted);
        }
        .accordion-header[aria-expanded="true"] .arrow { transform: rotate(180deg); }
        .accordion-content {
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.3s ease;
        }
        .accordion-content.open { max-height: 2000px; }
        .accordion-inner {
            padding: 1rem;
            background: var(--bg-tertiary);
            border: 1px solid var(--border);
            border-top: none;
            border-radius: 0 0 0.75rem 0.75rem;
        }

        /* Chat Tab */
        .chat-container {
            width: 100%;
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 1rem;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: calc(100vh - 280px);
            min-height: 400px;
        }
        .chat-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem;
            background: var(--bg-tertiary);
            border-bottom: 1px solid var(--border);
        }
        .chat-header svg { width: 1.5rem; height: 1.5rem; color: var(--accent-gold); }
        .chat-title { font-weight: 600; font-size: 1rem; }
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        .chat-message {
            max-width: 85%;
            padding: 0.75rem 1rem;
            border-radius: 1rem;
            font-size: 0.875rem;
            line-height: 1.6;
        }
        .chat-message.user {
            align-self: flex-end;
            background: var(--accent-indigo);
            color: white;
            border-bottom-right-radius: 0.25rem;
        }
        .chat-message.assistant {
            align-self: flex-start;
            background: var(--bg-tertiary);
            border: 1px solid var(--border);
            border-bottom-left-radius: 0.25rem;
            color: var(--text-secondary);
        }
        .chat-message.assistant strong { color: var(--accent-gold); }
        .chat-message.typing { font-style: italic; color: var(--text-muted); }
        .chat-templates {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            padding: 1rem;
            border-top: 1px solid var(--border);
            background: var(--bg-tertiary);
        }
        .chat-template-btn {
            padding: 0.5rem 0.875rem;
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 1.5rem;
            color: var(--text-secondary);
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .chat-template-btn:hover {
            border-color: var(--accent-indigo);
            color: var(--text-primary);
            background: rgba(129, 140, 248, 0.1);
        }
        .chat-input-container {
            display: flex;
            gap: 0.5rem;
            padding: 1rem;
            border-top: 1px solid var(--border);
        }
        .chat-input {
            flex: 1;
            background: var(--bg-tertiary);
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            color: var(--text-primary);
            font-size: 0.875rem;
            resize: none;
            max-height: 100px;
            font-family: inherit;
        }
        .chat-input:focus { outline: none; border-color: var(--accent-indigo); }
        .chat-input::placeholder { color: var(--text-muted); }
        .chat-send {
            padding: 0.75rem 1rem;
            background: var(--accent-indigo);
            border: none;
            border-radius: 0.75rem;
            color: white;
            cursor: pointer;
            transition: all 0.2s;
        }
        .chat-send:hover { background: #6366f1; }
        .chat-send:disabled { opacity: 0.5; cursor: not-allowed; }

        /* Tooltip */
        .tooltip {
            position: fixed;
            background: rgba(17, 22, 31, 0.95);
            border: 1px solid rgba(129, 140, 248, 0.3);
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem;
            font-size: 0.75rem;
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

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: var(--bg-tertiary); border-radius: 3px; }
        ::-webkit-scrollbar-thumb { background: var(--border-light); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-muted); }

        /* Responsive */
        @media (max-width: 1024px) {
            .overview-grid {
                grid-template-columns: 1fr;
            }
            .chart-column {
                position: relative;
                top: 0;
            }
            .details-panel {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 0.75rem;
            }
        }
        @media (max-width: 768px) {
            .container { padding: 0.5rem; }
            .page-header { padding: 0.5rem 0; margin-bottom: 0.5rem; }
            .page-header .btn { padding: 0.4rem 0.6rem; font-size: 0.75rem; }
            .page-header .btn span { display: none; }
            .header-center h1 { font-size: 1rem; }
            .header-meta { font-size: 0.7rem; }
            .key-stats { gap: 0.35rem; padding: 0.5rem 0; margin-bottom: 0.75rem; }
            .key-badge { padding: 0.25rem 0.5rem; font-size: 0.65rem; gap: 0.25rem; }
            .key-badge img { width: 1rem; height: 1rem; }
            .key-badge-label { display: none; }
            .tab-nav { margin-bottom: 1rem; padding: 0.2rem; border-radius: 0.5rem; }
            .tab-btn { padding: 0.6rem 0.5rem; font-size: 0.75rem; gap: 0.35rem; flex-direction: column; }
            .tab-btn svg { width: 1.5rem; height: 1.5rem; }
            .tab-btn-text { font-size: 0.65rem; }
            .elements-summary { grid-template-columns: repeat(4, 1fr); gap: 0.35rem; }
            .element-badge { padding: 0.35rem; font-size: 0.7rem; flex-direction: column; gap: 0.15rem; }
            .element-badge .icon { font-size: 1rem; }
            .houses-grid { grid-template-columns: repeat(4, 1fr); gap: 0.25rem; }
            .house-item { padding: 0.25rem; }
            .house-item img { width: 0.9rem; height: 0.9rem; }
            .analysis-grid { grid-template-columns: 1fr; }
            .chat-container { height: calc(100vh - 220px); min-height: 350px; border-radius: 0.75rem; }
            .chat-messages { padding: 0.75rem; }
            .chat-message { padding: 0.5rem 0.75rem; font-size: 0.8rem; }
            .chat-templates { padding: 0.5rem; gap: 0.35rem; }
            .chat-template-btn { padding: 0.35rem 0.5rem; font-size: 0.7rem; }
            .chat-input-container { padding: 0.5rem; }
            .section-card { border-radius: 0.5rem; }
            .section-header { padding: 0.5rem 0.625rem; font-size: 0.75rem; }
            .section-body { padding: 0.5rem; }
            .planet-row { padding: 0.25rem 0.35rem; font-size: 0.7rem; }
            .accordion-header { padding: 0.75rem; font-size: 0.85rem; }
            .accordion-inner { padding: 0.75rem; }
            .ai-summary { padding: 0.75rem; font-size: 0.8rem; }
        }

        @media (max-width: 480px) {
            .key-stats { justify-content: space-between; }
            .key-badge { flex: 1; justify-content: center; min-width: 0; }
            .key-badge-value { font-size: 0.6rem; }
            .tab-btn-text { display: none; }
            .tab-btn { padding: 0.75rem 0.5rem; }
            .tab-btn svg { width: 1.75rem; height: 1.75rem; }
        }
    </style>
</head>
<body x-data="{ activeTab: 'overview' }">
    @php
    // ==================== DATA PREPARATION ====================
    $signToFile = ['Овен' => 'aries', 'Телец' => 'taurus', 'Близнецы' => 'gemini', 'Рак' => 'cancer', 'Лев' => 'leo', 'Дева' => 'virgo', 'Весы' => 'libra', 'Скорпион' => 'scorpio', 'Стрелец' => 'sagittarius', 'Козерог' => 'capricorn', 'Водолей' => 'aquarius', 'Рыбы' => 'pisces'];
    $planetFiles = ['sun' => 'sun', 'moon' => 'moon', 'mercury' => 'mercury', 'venus' => 'venus', 'mars' => 'mars', 'jupiter' => 'jupiter', 'saturn' => 'saturn', 'uranus' => 'uranus', 'neptune' => 'neptune', 'pluto' => 'pluto', 'north_node' => 'north_node', 'south_node' => 'south_node', 'chiron' => 'chiron', 'part_fortune' => 'part_fortune', 'vertex' => 'vertex'];
    $planetNames = ['sun' => 'Солнце', 'moon' => 'Луна', 'mercury' => 'Меркурий', 'venus' => 'Венера', 'mars' => 'Марс', 'jupiter' => 'Юпитер', 'saturn' => 'Сатурн', 'uranus' => 'Уран', 'neptune' => 'Нептун', 'pluto' => 'Плутон', 'north_node' => 'Сев. узел', 'south_node' => 'Южн. узел', 'chiron' => 'Хирон', 'part_fortune' => 'Колесо фортуны', 'vertex' => 'Вертекс'];
    $signOrder = ['Овен', 'Телец', 'Близнецы', 'Рак', 'Лев', 'Дева', 'Весы', 'Скорпион', 'Стрелец', 'Козерог', 'Водолей', 'Рыбы'];
    $signElems = ['fire' => 'rgba(239, 68, 68, 0.08)', 'earth' => 'rgba(249, 115, 22, 0.08)', 'air' => 'rgba(59, 130, 246, 0.08)', 'water' => 'rgba(139, 92, 246, 0.08)'];
    $elemMap = ['Овен' => 'fire', 'Телец' => 'earth', 'Близнецы' => 'air', 'Рак' => 'water', 'Лев' => 'fire', 'Дева' => 'earth', 'Весы' => 'air', 'Скорпион' => 'water', 'Стрелец' => 'fire', 'Козерог' => 'earth', 'Водолей' => 'air', 'Рыбы' => 'water'];
    $qualityMap = ['Овен' => 'cardinal', 'Рак' => 'cardinal', 'Весы' => 'cardinal', 'Козерог' => 'cardinal', 'Телец' => 'fixed', 'Лев' => 'fixed', 'Скорпион' => 'fixed', 'Водолей' => 'fixed', 'Близнецы' => 'mutable', 'Дева' => 'mutable', 'Стрелец' => 'mutable', 'Рыбы' => 'mutable'];
    $polarityMap = ['Овен' => 'yang', 'Близнецы' => 'yang', 'Лев' => 'yang', 'Весы' => 'yang', 'Стрелец' => 'yang', 'Водолей' => 'yang', 'Телец' => 'yin', 'Рак' => 'yin', 'Дева' => 'yin', 'Скорпион' => 'yin', 'Козерог' => 'yin', 'Рыбы' => 'yin'];

    function getAbsDeg($s, $d) { $idx = array_search($s, ['Овен','Телец','Близнецы','Рак','Лев','Дева','Весы','Скорпион','Стрелец','Козерог','Водолей','Рыбы']); return ($idx?:0)*30+$d; }
    function toSvgAngle($deg, $asc) { return 180 - ($deg - $asc); }
    function polar($cx, $cy, $r, $a) { $rad = deg2rad($a); return ['x' => $cx + $r * cos($rad), 'y' => $cy - $r * sin($rad)]; }

    $houses = $chart->chart_data['houses'] ?? [];
    $planets = $chart->chart_data['planets'] ?? [];
    $aspects = $chart->chart_data['aspects'] ?? [];

    $ascSign = $houses[1]['sign'] ?? 'Овен';
    $ascDeg = $houses[1]['degree'] ?? 0;
    $ascAbs = getAbsDeg($ascSign, $ascDeg);
    $mcSign = $houses[10]['sign'] ?? 'Козерог';
    $mcDeg = $houses[10]['degree'] ?? 0;
    $mcAbs = getAbsDeg($mcSign, $mcDeg);

    $sunSign = $planets['sun']['sign'] ?? 'Овен';
    $moonSign = $planets['moon']['sign'] ?? 'Рак';

    // SVG Calculations - centered at 400,400 with viewBox 800x800
    $cx = 400; $cy = 400; $rOuter = 330; $rHouse = 290; $rInner = 100; $rPlanet = 210;
    $zodiac = []; foreach ($signOrder as $i => $s) { $ang = toSvgAngle($i*30, $ascAbs); $zodiac[$i] = ['file' => $signToFile[$s] ?? strtolower($s), 'pos' => polar($cx,$cy,$rOuter+18,$ang), 'ls' => polar($cx,$cy,$rOuter,$ang), 'le' => polar($cx,$cy,$rOuter-15,$ang)]; }
    $hLines = []; for($i=1;$i<=12;$i++){ $h=$houses[$i]??['sign'=>'Овен','degree'=>0]; $abs=getAbsDeg($h['sign'],$h['degree']); $ang=toSvgAngle($abs,$ascAbs); $angH=in_array($i,[1,4,7,10]); $hLines[$i] = ['in'=>polar($cx,$cy,$rInner,$ang), 'out'=>polar($cx,$cy,$angH?$rOuter:$rHouse,$ang), 'col'=>$angH?'#FFD700':'#475569','w'=>$angH?2:1]; }
    $hNums = []; for($i=1;$i<=12;$i++){ $n=$i==12?1:$i+1; $h1=$houses[$i]??['sign'=>'Овен','degree'=>0]; $h2=$houses[$n]??['sign'=>'Овен','degree'=>30]; $d1=getAbsDeg($h1['sign'],$h1['degree']); $d2=getAbsDeg($h2['sign'],$h2['degree']); if($d2<$d1)$d2+=360; $hNums[$i]=polar($cx,$cy,($rHouse+$rInner)/2,toSvgAngle(($d1+$d2)/2,$ascAbs)); }
    $ascP = polar($cx,$cy,$rOuter+8,toSvgAngle($ascAbs,$ascAbs)); $descP = polar($cx,$cy,$rOuter+8,toSvgAngle($ascAbs+180,$ascAbs)); $mcP = polar($cx,$cy,$rOuter+8,toSvgAngle($mcAbs,$ascAbs)); $icP = polar($cx,$cy,$rOuter+8,toSvgAngle($mcAbs+180,$ascAbs));
    $pCoords = []; foreach($planets as $k=>$p){ if(is_array($p)) { $abs=getAbsDeg($p['sign']??'Овен',$p['degree']??0); $ang=toSvgAngle($abs,$ascAbs); $pCoords[$k] = ['pos'=>polar($cx,$cy,$rPlanet,$ang), 'deg'=>polar($cx,$cy,$rPlanet+28,$ang)]; } }
    $aLines = []; foreach($aspects as $a){ $p1=strtolower($a['planet1']??''); $p2=strtolower($a['planet2']??''); $k1=$k2=null; foreach($pCoords as $k=>$v){ if(!$k1&&(strtolower($planetNames[$k]??$k)===$p1))$k1=$k; if(!$k2&&(strtolower($planetNames[$k]??$k)===$p2))$k2=$k; } if($k1&&$k2&&isset($pCoords[$k1],$pCoords[$k2])){ $col=in_array($a['type']??'',['Square','Opposition','Квадрат','Оппозиция'])?'#EF4444':(in_array($a['type']??'',['Trine','Sextile','Трин','Секстиль'])?'#22C55E':'#A855F7'); $aLines[]=['f'=>$pCoords[$k1]['pos'],'t'=>$pCoords[$k2]['pos'],'c'=>$col]; } }

    // Element counts
    $elemCount = ['fire'=>0,'earth'=>0,'air'=>0,'water'=>0];
    $qualityCount = ['cardinal'=>0,'fixed'=>0,'mutable'=>0];
    $polarityCount = ['yang'=>0,'yin'=>0];
    foreach($planets as $p) { if(is_array($p) && isset($p['sign'])) { $e = $elemMap[$p['sign']] ?? 'fire'; $q = $qualityMap[$p['sign']] ?? 'cardinal'; $pl = $polarityMap[$p['sign']] ?? 'yang'; if(isset($elemCount[$e])) $elemCount[$e]++; if(isset($qualityCount[$q])) $qualityCount[$q]++; if(isset($polarityCount[$pl])) $polarityCount[$pl]++; } }
    $totalPlanets = array_sum($elemCount) ?: 1;

    // Aspect helpers
    $aspectSymbols = ['Conjunction'=>'☌','Opposition'=>'☍','Square'=>'□','Trine'=>'△','Sextile'=>'⚹','Соединение'=>'☌','Оппозиция'=>'☍','Квадрат'=>'□','Трин'=>'△','Секстиль'=>'⚹'];
    $harmonyAspects = ['Trine','Sextile','Conjunction','Трин','Секстиль','Соединение'];
    $stressAspects = ['Square','Opposition','Квадрат','Оппозиция'];
    @endphp

    @if(isset($showEmailBanner) && $showEmailBanner)
    <!-- Email Sent Banner -->
    <div class="fixed top-4 left-4 right-4 z-50">
        <div class="bg-emerald-900/90 backdrop-blur-sm border border-emerald-700/50 rounded-xl p-4 flex items-center justify-between max-w-xl mx-auto">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-emerald-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <div>
                    <p class="text-white font-medium">Ссылка отправлена на почту!</p>
                    <p class="text-emerald-300 text-sm">Сохраните ссылку для доступа к карте</p>
                </div>
            </div>
            <a href="/" class="text-emerald-400 hover:text-white transition-colors text-sm">
                На главную
            </a>
        </div>
    </div>
    @endif

    <div class="container">
        <!-- ==================== HEADER ==================== -->
        <header class="page-header">
            <a href="/" class="btn">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Назад</span>
            </a>
            <div class="header-center">
                <h1>{{ $chart->name }}</h1>
                <div class="header-meta">
                    {{ $chart->birth_date->format('d.m.Y') }}
                    @if($chart->birth_time) • {{ \Carbon\Carbon::parse($chart->birth_time)->format('H:i') }}@endif
                    • {{ $chart->birth_place }}
                </div>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                @if(!isset($showEmailBanner) || !$showEmailBanner)
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Выйти</span>
                    </button>
                </form>
                @endif
                <button class="btn btn-gold">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>PDF</span>
                </button>
            </div>
        </header>

        <!-- ==================== KEY STATS ==================== -->
        <div class="key-stats">
            <div class="key-badge">
                <img src="/images/planets/sun.png" alt="">
                <span class="key-badge-label">Солнце</span>
                <img src="/images/zodiac/{{ $signToFile[$sunSign] ?? 'aries' }}.png" alt="">
                <span class="key-badge-value">{{ $sunSign }}</span>
            </div>
            <div class="key-badge">
                <img src="/images/planets/moon.png" alt="">
                <span class="key-badge-label">Луна</span>
                <img src="/images/zodiac/{{ $signToFile[$moonSign] ?? 'cancer' }}.png" alt="">
                <span class="key-badge-value">{{ $moonSign }}</span>
            </div>
            <div class="key-badge">
                <span class="key-badge-label">ASC</span>
                <img src="/images/zodiac/{{ $signToFile[$ascSign] ?? 'aries' }}.png" alt="">
                <span class="key-badge-value">{{ $ascSign }}</span>
            </div>
            <div class="key-badge">
                <span class="key-badge-label">MC</span>
                <img src="/images/zodiac/{{ $signToFile[$mcSign] ?? 'capricorn' }}.png" alt="">
                <span class="key-badge-value">{{ $mcSign }}</span>
            </div>
        </div>

        <!-- ==================== TAB NAVIGATION ==================== -->
        <nav class="tab-nav">
            <button class="tab-btn" :class="{ 'active': activeTab === 'overview' }" @click="activeTab = 'overview'">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><path stroke-width="2" d="M12 6v6l4 2"/></svg>
                <span class="tab-btn-text">Обзор</span>
            </button>
            <button class="tab-btn" :class="{ 'active': activeTab === 'analysis' }" @click="activeTab = 'analysis'">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span class="tab-btn-text">Анализ</span>
            </button>
            <button class="tab-btn" :class="{ 'active': activeTab === 'chat' }" @click="activeTab = 'chat'">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                <span class="tab-btn-text">Чат с ИИ</span>
            </button>
        </nav>

        <!-- ==================== TAB 1: OVERVIEW ==================== -->
        <div class="tab-content" :class="{ 'active': activeTab === 'overview' }">
            <div class="overview-grid">
                <!-- Left: Chart (sticky) -->
                <div class="chart-column">
                    <div class="chart-card">
                        <div class="tooltip" id="tooltip"></div>
                        <svg viewBox="0 0 800 800">
                            <defs><filter id="glow"><feGaussianBlur stdDeviation="1.5" result="blur"/><feMerge><feMergeNode in="blur"/><feMergeNode in="SourceGraphic"/></feMerge></filter></defs>
                            <circle cx="400" cy="400" r="345" fill="#0B0E14" stroke="#2A3441"/>
                            <circle cx="400" cy="400" r="290" fill="none" stroke="#2A3441"/>
                            <circle cx="400" cy="400" r="100" fill="#080B0E" stroke="#2A3441"/>
                            @for($i=0;$i<12;$i++) @php $s=$signOrder[$i]; $c=$signElems[$elemMap[$s]??'fire']; @endphp
                            <path d="M400,400 L{{ 400+345*cos(deg2rad($i*30-90)) }},{{ 400+345*sin(deg2rad($i*30-90)) }} A345,345 0 0,1 {{ 400+345*cos(deg2rad(($i+1)*30-90)) }},{{ 400+345*sin(deg2rad(($i+1)*30-90)) }} Z" fill="{{ $c }}"/>@endfor
                            @foreach($zodiac as $z)
                            <line x1="{{ $z['ls']['x'] }}" y1="{{ $z['ls']['y'] }}" x2="{{ $z['le']['x'] }}" y2="{{ $z['le']['y'] }}" stroke="#475569" opacity="0.5"/>
                            <image href="/images/zodiac/{{ $z['file'] }}.png" x="{{ $z['pos']['x'] - 33 }}" y="{{ $z['pos']['y'] - 33 }}" width="66" height="66" opacity="0.8"/>
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
                            <text x="400" y="397" text-anchor="middle" fill="#647887" font-size="7">PLACIDUS</text>
                            <text x="400" y="409" text-anchor="middle" fill="#647887" font-size="8">{{ $chart->birth_date->format('d.m.Y') }}</text>
                        </svg>
                    </div>

                    <!-- Elements Summary -->
                    <div class="elements-summary">
                        <div class="element-badge fire">
                            <span class="icon">🔥</span>
                            <span class="count">{{ $elemCount['fire'] }}</span>
                        </div>
                        <div class="element-badge earth">
                            <span class="icon">🌍</span>
                            <span class="count">{{ $elemCount['earth'] }}</span>
                        </div>
                        <div class="element-badge air">
                            <span class="icon">💨</span>
                            <span class="count">{{ $elemCount['air'] }}</span>
                        </div>
                        <div class="element-badge water">
                            <span class="icon">💧</span>
                            <span class="count">{{ $elemCount['water'] }}</span>
                        </div>
                    </div>

                    <!-- AI Summary -->
                    @if($chart->hasAiReport() && $chart->getAiCharacterAnalysis())
                    <div class="ai-summary">
                        <div class="ai-summary-header">
                            <svg fill="currentColor" viewBox="0 0 24 24" width="16" height="16"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
                            Краткое резюме
                        </div>
                        <p style="margin: 0;">{{ Str::limit($chart->getAiCharacterAnalysis(), 350) }}</p>
                    </div>
                    @endif
                </div>

                <!-- Right: Details Panel -->
                <div class="details-panel">
                    <!-- Planets -->
                    <div class="section-card">
                        <div class="section-header" style="color: var(--accent-indigo);">
                            <svg fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/></svg>
                            Планеты
                        </div>
                        <div class="section-body">
                            <div class="planet-list">
                                @foreach($planets as $k => $p)
                                @if(!is_array($p)) @continue @endif
                                <div class="planet-row">
                                    @if(isset($planetFiles[$k]))<img src="/images/planets/{{ $planetFiles[$k] }}.png" alt="">@endif
                                    <div class="planet-row-name">
                                        {{ $planetNames[$k] ?? ucfirst($k) }}
                                        @if($p['retrograde'] ?? false)<span class="planet-row-retro">R</span>@endif
                                    </div>
                                    <div class="planet-row-deg">{{ $p['sign'] ?? '-' }} {{ floor($p['degree'] ?? 0) }}°</div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Houses -->
                    <div class="section-card">
                        <div class="section-header" style="color: var(--accent-cyan);">
                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M3 13h1v7c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-7h1"/></svg>
                            Дома
                        </div>
                        <div class="section-body">
                            <div class="houses-grid">
                                @for($i = 1; $i <= 12; $i++)
                                @php $h = $houses[$i] ?? ['sign' => 'Овен', 'degree' => 0]; $file = $signToFile[$h['sign']] ?? 'aries'; @endphp
                                <div class="house-item {{ in_array($i, [1,4,7,10]) ? 'angular' : '' }}">
                                    <img src="/images/zodiac/{{ $file }}.png" alt="">
                                    <div class="house-num">{{ $i }}</div>
                                    <div class="house-deg">{{ floor($h['degree']) }}°</div>
                                </div>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <!-- Aspects -->
                    @if(!empty($aspects))
                    <div class="section-card">
                        <div class="section-header" style="color: var(--accent-purple);">
                            <svg fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>
                            Аспекты
                        </div>
                        <div class="section-body">
                            <div class="aspects-list">
                                @foreach($aspects as $a)
                                @php
                                    $isHarmony = in_array($a['type'] ?? '', $harmonyAspects);
                                    $isTension = in_array($a['type'] ?? '', $stressAspects);
                                    $colorClass = $isHarmony ? 'green' : ($isTension ? 'red' : 'purple');
                                @endphp
                                <div class="aspect-row">
                                    <span class="aspect-symbol {{ $colorClass }}">{{ $aspectSymbols[$a['type']] ?? '•' }}</span>
                                    <span class="aspect-planets">{{ $a['planet1'] ?? '' }} — {{ $a['planet2'] ?? '' }}</span>
                                    <span class="aspect-orb">{{ $a['orb'] ?? '' }}°</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Elements & Qualities -->
                    <div class="section-card">
                        <div class="section-header" style="color: var(--accent-orange);">
                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2c-1.1 0-2 .9-2 2v4c0 1.1.9 2 2 2s2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
                            Баланс
                        </div>
                        <div class="section-body">
                            <div style="display: flex; gap: 0.5rem; margin-bottom: 0.5rem;">
                                <div style="flex:1; text-align:center; padding:0.35rem; background:var(--bg-tertiary); border-radius:0.375rem; font-size:0.7rem;">
                                    <div style="font-weight:600;">{{ $qualityCount['cardinal'] }}</div>
                                    <div style="color:var(--text-muted);">Кард.</div>
                                </div>
                                <div style="flex:1; text-align:center; padding:0.35rem; background:var(--bg-tertiary); border-radius:0.375rem; font-size:0.7rem;">
                                    <div style="font-weight:600;">{{ $qualityCount['fixed'] }}</div>
                                    <div style="color:var(--text-muted);">Фикс.</div>
                                </div>
                                <div style="flex:1; text-align:center; padding:0.35rem; background:var(--bg-tertiary); border-radius:0.375rem; font-size:0.7rem;">
                                    <div style="font-weight:600;">{{ $qualityCount['mutable'] }}</div>
                                    <div style="color:var(--text-muted);">Мут.</div>
                                </div>
                            </div>
                            <div style="display: flex; gap: 0.5rem;">
                                <div style="flex:1; display:flex; align-items:center; gap:0.35rem; padding:0.35rem 0.5rem; background:var(--bg-tertiary); border-radius:0.375rem; font-size:0.7rem;">
                                    <span style="color:#3B82F6">☀</span>
                                    <span style="color:var(--text-muted)">Ян</span>
                                    <span style="font-weight:600; margin-left:auto;">{{ $polarityCount['yang'] }}</span>
                                </div>
                                <div style="flex:1; display:flex; align-items:center; gap:0.35rem; padding:0.35rem 0.5rem; background:var(--bg-tertiary); border-radius:0.375rem; font-size:0.7rem;">
                                    <span style="color:#EC4899">☽</span>
                                    <span style="color:var(--text-muted)">Инь</span>
                                    <span style="font-weight:600; margin-left:auto;">{{ $polarityCount['yin'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== TAB 2: ANALYSIS ==================== -->
        <div class="tab-content" :class="{ 'active': activeTab === 'analysis' }">
            <!-- Character & Personality -->
            @if($chart->hasAiReport() && $chart->getAiCharacterAnalysis())
            <div class="section-card" style="margin-bottom: 1rem;">
                <div class="section-header" style="color: var(--accent-indigo); font-size: 0.9rem;">
                    <svg fill="currentColor" viewBox="0 0 24 24" width="20" height="20"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
                    Характер и личность
                </div>
                <div class="section-body" style="font-size: 0.875rem; color: var(--text-secondary); line-height: 1.8; padding: 1rem;">
                    {!! nl2br(e($chart->getAiCharacterAnalysis())) !!}
                </div>
            </div>
            @endif

            <!-- Sun, Moon, Ascendant Cards -->
            <div class="analysis-grid">
                <!-- Sun -->
                @php $sunMeaning = $chart->getPlanetMeaning('sun'); $sunSignMeaning = $chart->getSignMeaning($sunSign); @endphp
                <div class="analysis-card">
                    <div class="analysis-card-header">
                        <img src="/images/planets/sun.png" alt="">
                        <span class="analysis-card-title">Солнце в {{ $sunSign }}</span>
                    </div>
                    <div class="analysis-card-body">
                        @if($sunMeaning)<p>{{ $sunMeaning->description }}</p>@endif
                        @if($sunSignMeaning)<p>{{ $sunSignMeaning->characteristics }}</p>@endif
                    </div>
                </div>

                <!-- Moon -->
                @php $moonMeaning = $chart->getPlanetMeaning('moon'); $moonSignMeaning = $chart->getSignMeaning($moonSign); @endphp
                <div class="analysis-card">
                    <div class="analysis-card-header">
                        <img src="/images/planets/moon.png" alt="">
                        <span class="analysis-card-title">Луна в {{ $moonSign }}</span>
                    </div>
                    <div class="analysis-card-body">
                        @if($moonMeaning)<p>{{ $moonMeaning->description }}</p>@endif
                        @if($moonSignMeaning)<p>{{ $moonSignMeaning->characteristics }}</p>@endif
                    </div>
                </div>

                <!-- Ascendant -->
                @php $ascMeaning = $chart->getSignMeaning($ascSign); @endphp
                <div class="analysis-card">
                    <div class="analysis-card-header">
                        <img src="/images/zodiac/{{ $signToFile[$ascSign] ?? 'aries' }}.png" alt="">
                        <span class="analysis-card-title">Асцендент в {{ $ascSign }}</span>
                    </div>
                    <div class="analysis-card-body">
                        @if($ascMeaning)<p>{{ $ascMeaning->characteristics }}</p>@else
                        <p>{{ $ascSign }} на Асцендент создаёт первое впечатление, которое вы производите на окружающих.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Analysis Sections (always open) -->
            <div class="analysis-grid" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));">
                <!-- Career -->
                <div class="analysis-card">
                    <div class="analysis-card-header">
                        <span style="font-size: 1.1rem;">💼</span>
                        <span class="analysis-card-title">Карьера и профессия</span>
                    </div>
                    <div class="analysis-card-body">
                        @php $houseMeaning10 = $chart->getHouseMeaning(10); @endphp
                        <p><strong style="color: var(--accent-gold);">MC в {{ $mcSign }}:</strong>
                        @if($houseMeaning10) {{ $houseMeaning10->general }}@else
                        Ваша карьера и общественное положение связаны с качествами знака {{ $mcSign }}.
                        @endif</p>
                    </div>
                </div>

                <!-- Love -->
                <div class="analysis-card">
                    <div class="analysis-card-header">
                        <span style="font-size: 1.1rem;">❤️</span>
                        <span class="analysis-card-title">Любовь и отношения</span>
                    </div>
                    <div class="analysis-card-body">
                        @php $venusMeaning = $chart->getPlanetMeaning('venus'); $venusSign = $planets['venus']['sign'] ?? 'Телец'; @endphp
                        <p><strong style="color: var(--accent-pink);">Венера в {{ $venusSign }}:</strong>
                        @if($venusMeaning) {{ $venusMeaning->description }}@else
                        Венера определяет ваш стиль любви и то, что вы цените в отношениях.
                        @endif</p>
                    </div>
                </div>

                <!-- Karma -->
                <div class="analysis-card">
                    <div class="analysis-card-header">
                        <span style="font-size: 1.1rem;">🔮</span>
                        <span class="analysis-card-title">Кармические уроки</span>
                    </div>
                    <div class="analysis-card-body">
                        @if(isset($planets['north_node']))
                        @php $nnSign = $planets['north_node']['sign'] ?? ''; @endphp
                        <p><strong style="color: var(--accent-purple);">Северный узел в {{ $nnSign }}:</strong>
                        Указывает направление духовного роста — качества, которые нужно развивать.</p>
                        @endif
                        @if(isset($planets['chiron']))
                        @php $chSign = $planets['chiron']['sign'] ?? ''; @endphp
                        <p style="margin-top: 0.75rem;"><strong style="color: var(--accent-teal);">Хирон в {{ $chSign }}:</strong>
                        «Раненый целитель» — область глубокой раны, которая становится вашим даром.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Planets in Houses -->
            <div class="section-card" style="margin-top: 1rem;">
                <div class="section-header" style="color: var(--accent-cyan);">
                    <span style="font-size: 1rem;">🏠</span>
                    Планеты в домах
                </div>
                <div class="section-body">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 0.75rem;">
                        @foreach(['sun','moon','mercury','venus','mars','jupiter','saturn'] as $planet)
                        @if(isset($planets[$planet]))
                        @php
                        $pMeaning = $chart->getPlanetMeaning($planet);
                        $houseNum = $planets[$planet]['house'] ?? 1;
                        $hMeaning = $chart->getHouseMeaning($houseNum);
                        @endphp
                        <div style="padding: 0.75rem; background: var(--bg-tertiary); border-radius: 0.5rem; border: 1px solid var(--border);">
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.35rem;">
                                <img src="/images/planets/{{ $planetFiles[$planet] }}.png" alt="" style="width: 1.25rem; height: 1.25rem;">
                                <strong style="color: var(--text-primary); font-size: 0.85rem;">{{ $planetNames[$planet] }} в {{ $houseNum }} доме</strong>
                            </div>
                            <p style="font-size: 0.8rem; color: var(--text-secondary); line-height: 1.6; margin: 0;">
                                @if($hMeaning) {{ Str::limit($hMeaning->general, 120) }}@else
                                Проявление энергии {{ $planetNames[$planet] }} в сфере {{ $houseNum }} дома.
                                @endif
                            </p>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== TAB 3: CHAT ==================== -->
        <div class="tab-content" :class="{ 'active': activeTab === 'chat' }">
            <div class="chat-container">
                <div class="chat-header">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
                    <span class="chat-title">Спросите астролога ИИ</span>
                </div>
                <div class="chat-messages" id="chat-messages">
                    @forelse($chatMessages as $msg)
                        <div class="chat-message {{ $msg->role }}" data-raw="{{ e($msg->content) }}">
                            @if($msg->role === 'assistant')
                                {!! nl2br(e($msg->content)) !!}
                            @else
                                {{ $msg->content }}
                            @endif
                        </div>
                    @empty
                        <div class="chat-message assistant">
                            Привет! Задайте любой вопрос о вашей натальной карте. Я помогу разобраться в аспектах, планетах и домах.
                        </div>
                    @endforelse
                </div>
                <div class="chat-templates">
                    @foreach($templates as $key => $template)
                    <button class="chat-template-btn" data-prompt="{{ $template['prompt'] }}">
                        {{ $template['icon'] }} {{ $template['title'] }}
                    </button>
                    @endforeach
                </div>
                <form id="chat-form" class="chat-input-container">
                    @csrf
                    <textarea id="chat-input" class="chat-input" placeholder="Введите ваш вопрос..." rows="1"></textarea>
                    <button type="submit" class="chat-send" id="chat-send">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19V5M5 12l7-7 7 7"/>
                        </svg>
                    </button>
                </form>
            </div>
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

        // Chat
        const chatMessages = document.getElementById('chat-messages');
        const chatForm = document.getElementById('chat-form');
        const chatInput = document.getElementById('chat-input');
        const chatSend = document.getElementById('chat-send');
        const chatTemplateBtns = document.querySelectorAll('.chat-template-btn');

        // Auto-resize textarea
        chatInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 100) + 'px';
        });

        // Template click
        chatTemplateBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                chatInput.value = this.dataset.prompt;
                chatInput.focus();
            });
        });

        // Simple markdown parser
        function parseMarkdown(text) {
            return text
                .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
                .replace(/\*(.+?)\*/g, '<em>$1</em>')
                .replace(/^\d+\.\s+/gm, '<br>• ')
                .replace(/^-\s+/gm, '<br>• ')
                .replace(/\n\n/g, '<br><br>')
                .replace(/\n/g, '<br>');
        }

        // Add message
        function addChatMessage(content, role) {
            const div = document.createElement('div');
            div.className = `chat-message ${role}`;
            div.dataset.raw = content;
            div.innerHTML = role === 'assistant' ? parseMarkdown(content) : content;
            chatMessages.appendChild(div);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function showTyping() {
            const div = document.createElement('div');
            div.className = 'chat-message assistant typing';
            div.id = 'typing-indicator';
            div.textContent = 'Думаю...';
            chatMessages.appendChild(div);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function hideTyping() {
            const typing = document.getElementById('typing-indicator');
            if (typing) typing.remove();
        }

        // Send message
        async function sendChatMessage(message) {
            addChatMessage(message, 'user');
            chatInput.value = '';
            chatInput.style.height = 'auto';
            showTyping();
            chatSend.disabled = true;

            try {
                const response = await fetch(`/charts/{{ $chart->id }}/chat`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message })
                });

                hideTyping();

                if (response.ok) {
                    const data = await response.json();
                    addChatMessage(data.message, 'assistant');
                } else {
                    addChatMessage('Ошибка. Попробуйте позже.', 'assistant');
                }
            } catch (error) {
                hideTyping();
                addChatMessage('Ошибка соединения.', 'assistant');
            }

            chatSend.disabled = false;
        }

        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const message = chatInput.value.trim();
            if (!message) return;
            await sendChatMessage(message);
        });

        chatInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                const message = chatInput.value.trim();
                if (message) sendChatMessage(message);
            }
        });

        // Scroll to bottom on load
        chatMessages.scrollTop = chatMessages.scrollHeight;
    </script>
</body>
</html>
