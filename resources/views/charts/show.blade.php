<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $chart->name }} — {{ __('astrology.chart_title') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.1.0/js/all.min.js"></script>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <!-- Yandex.Metrika counter -->
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
    <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{{ config('services.google_analytics.id') }}');</script>
    @endif

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
        @keyframes compat-spin { to { transform: rotate(360deg); } }
        @keyframes compat-pulse { 0%, 100% { opacity: 0.5; } 50% { opacity: 1; } }
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
            .page-header .btn .lang-flag { display: inline !important; }
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
        .ai-section-collapsed { display: none; }
        .tab-btn { position: relative; }
        .tab-dot {
            position: absolute; top: 6px; right: 6px;
            width: 7px; height: 7px; border-radius: 50%;
            background: #818cf8;
            box-shadow: 0 0 6px 2px rgba(129,140,248,0.6);
            animation: dot-pulse 2s ease-in-out infinite;
        }
        .tab-new { animation: tab-glow 2.5s ease-in-out infinite; }
        @keyframes dot-pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.8); }
        }
        @keyframes tab-glow {
            0%, 100% { box-shadow: none; }
            50% { box-shadow: inset 0 0 12px rgba(129,140,248,0.15); }
        }
        .ai-report-content h1, .ai-report-content h2, .ai-report-content h3 { color: var(--text-primary); margin: 0.75rem 0 0.35rem; font-size: 0.95rem; font-weight: 600; }
        .ai-report-content h3 { font-size: 0.9rem; }
        .ai-report-content p { margin: 0 0 0.6rem; }
        .ai-report-content ul, .ai-report-content ol { margin: 0.25rem 0 0.6rem 1.25rem; padding: 0; }
        .ai-report-content li { margin-bottom: 0.25rem; }
        .ai-report-content strong { color: var(--text-primary); }
        .ai-report-content em { color: var(--accent-indigo); font-style: italic; }

        /* Daily Horoscope Card */
        .horoscope-card {
            position: relative; overflow: hidden;
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 1rem;
            margin-bottom: 1rem;
        }
        .horoscope-top {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.875rem 1rem;
        }
        .horoscope-sign-img { width: 2.5rem; height: 2.5rem; flex-shrink: 0; filter: drop-shadow(0 0 6px rgba(129,140,248,0.35)); }
        .horoscope-info { flex: 1; min-width: 0; }
        .horoscope-info-row { display: flex; align-items: center; gap: 0.4rem; }
        .horoscope-title { font-size: 0.85rem; font-weight: 700; color: var(--text-primary); }
        .horoscope-live { width: 6px; height: 6px; border-radius: 50%; background: #22C55E; box-shadow: 0 0 6px rgba(34,197,94,0.5); animation: h-pulse 2s ease-in-out infinite; }
        @keyframes h-pulse { 0%,100% { opacity:1; } 50% { opacity:0.4; } }
        .horoscope-meta { font-size: 0.7rem; color: var(--text-muted); margin-top: 0.1rem; }
        .horoscope-text { font-size: 0.75rem; color: var(--text-secondary); line-height: 1.45; margin-top: 0.3rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .horoscope-score-big { flex-shrink: 0; text-align: center; }
        .horoscope-score-num { font-size: 1.75rem; font-weight: 800; color: var(--accent-indigo); line-height: 1; }
        .horoscope-score-num small { font-size: 0.75rem; opacity: 0.5; }
        .horoscope-score-label { font-size: 0.55rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; }

        /* Scores row */
        .horoscope-scores-row {
            display: flex; gap: 0.5rem;
            padding: 0 1rem 0.75rem;
        }
        .h-score-pill {
            flex: 1;
            display: flex; align-items: center; gap: 0.4rem;
            padding: 0.4rem 0.6rem;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 0.5rem;
        }
        .h-score-emoji { font-size: 0.85rem; line-height: 1; }
        .h-score-info { display: flex; flex-direction: column; }
        .h-score-label { font-size: 0.6rem; color: var(--text-muted); line-height: 1; }
        .h-score-bar {
            width: 3.5rem; height: 3px; background: rgba(255,255,255,0.06);
            border-radius: 2px; margin-top: 3px; overflow: hidden;
        }
        .h-score-fill { height: 100%; border-radius: 2px; transition: width 1s ease; }
        .h-score-fill.love { background: linear-gradient(90deg, #EC4899, #F472B6); }
        .h-score-fill.career { background: linear-gradient(90deg, #F97316, #FBBF24); }
        .h-score-fill.health { background: linear-gradient(90deg, #22C55E, #34D399); }
        .h-score-fill.luck { background: linear-gradient(90deg, #818CF8, #A78BFA); }
        .h-score-val { font-size: 0.7rem; font-weight: 700; line-height: 1; }
        .h-score-val.love { color: #EC4899; }
        .h-score-val.career { color: #F97316; }
        .h-score-val.health { color: #22C55E; }
        .h-score-val.luck { color: #818CF8; }

        /* Countdown bar */
        .horoscope-countdown-bar {
            display: flex; align-items: center; justify-content: center; gap: 0.75rem;
            padding: 0.6rem 1rem;
            background: linear-gradient(90deg, rgba(129,140,248,0.06) 0%, rgba(168,85,247,0.08) 50%, rgba(129,140,248,0.06) 100%);
            border-top: 1px solid var(--border);
        }
        .hcb-label { font-size: 0.65rem; color: var(--text-muted); white-space: nowrap; }
        .hcb-timer { display: flex; align-items: center; gap: 0.2rem; }
        .hcb-digit {
            font-size: 0.95rem; font-weight: 800; color: var(--text-primary);
            font-variant-numeric: tabular-nums;
            background: rgba(129,140,248,0.12);
            border: 1px solid rgba(129,140,248,0.15);
            padding: 0.15rem 0.35rem; border-radius: 0.3rem;
            min-width: 1.5rem; text-align: center; line-height: 1.2;
        }
        .hcb-sep { font-size: 0.85rem; font-weight: 700; color: var(--accent-indigo); opacity: 0.5; animation: hcb-blink 1s step-end infinite; }
        @keyframes hcb-blink { 50% { opacity: 0.15; } }
        .hcb-unit { font-size: 0.5rem; color: var(--text-muted); text-transform: uppercase; margin-left: -0.05rem; }
        .hcb-link {
            margin-left: auto;
            font-size: 0.7rem; font-weight: 600; color: var(--accent-indigo); text-decoration: none; white-space: nowrap;
            transition: color 0.15s;
        }
        .hcb-link:hover { color: var(--text-primary); }

        @media (max-width: 768px) {
            .horoscope-scores-row { flex-wrap: wrap; gap: 0.35rem; padding: 0 0.875rem 0.75rem; }
            .h-score-pill { min-width: calc(50% - 0.2rem); }
        }
        @media (max-width: 480px) {
            .horoscope-top { gap: 0.5rem; padding: 0.75rem; }
            .horoscope-sign-img { width: 2rem; height: 2rem; }
            .horoscope-title { font-size: 0.8rem; }
            .horoscope-text { -webkit-line-clamp: 1; }
            .horoscope-score-num { font-size: 1.4rem; }
            .horoscope-scores .hs-icon { display: none; }
            .hcb-digit { font-size: 0.85rem; min-width: 1.3rem; padding: 0.1rem 0.25rem; }
        }
    </style>
</head>
<body x-data="chartPage()" x-init="init()">
    @php
    // ==================== DATA PREPARATION ====================
    // Mapping from Russian sign names (stored in DB) to keys
    $signToKey = ['Овен' => 'aries', 'Телец' => 'taurus', 'Близнецы' => 'gemini', 'Рак' => 'cancer', 'Лев' => 'leo', 'Дева' => 'virgo', 'Весы' => 'libra', 'Скорпион' => 'scorpio', 'Стрелец' => 'sagittarius', 'Козерог' => 'capricorn', 'Водолей' => 'aquarius', 'Рыбы' => 'pisces'];
    $signToFile = $signToKey; // Same mapping for file names

    // Localized sign names (Russian sign -> localized name)
    $signNames = [];
    foreach ($signToKey as $ru => $key) {
        $signNames[$ru] = __('astrology.sign_' . $key);
    }

    $planetFiles = ['sun' => 'sun', 'moon' => 'moon', 'mercury' => 'mercury', 'venus' => 'venus', 'mars' => 'mars', 'jupiter' => 'jupiter', 'saturn' => 'saturn', 'uranus' => 'uranus', 'neptune' => 'neptune', 'pluto' => 'pluto', 'north_node' => 'north_node', 'south_node' => 'south_node', 'chiron' => 'chiron', 'part_fortune' => 'part_fortune', 'vertex' => 'vertex'];

    // Localized planet names
    $planetNames = [
        'sun' => __('astrology.planet_sun'),
        'moon' => __('astrology.planet_moon'),
        'mercury' => __('astrology.planet_mercury'),
        'venus' => __('astrology.planet_venus'),
        'mars' => __('astrology.planet_mars'),
        'jupiter' => __('astrology.planet_jupiter'),
        'saturn' => __('astrology.planet_saturn'),
        'uranus' => __('astrology.planet_uranus'),
        'neptune' => __('astrology.planet_neptune'),
        'pluto' => __('astrology.planet_pluto'),
        'north_node' => __('astrology.planet_north_node'),
        'south_node' => __('astrology.planet_south_node'),
        'chiron' => __('astrology.planet_chiron'),
        'part_fortune' => __('astrology.planet_part_fortune'),
        'vertex' => __('astrology.planet_vertex'),
    ];

    // Russian planet names for aspect matching (data is stored in Russian)
    $planetNamesRuToKey = ['Солнце' => 'sun', 'Луна' => 'moon', 'Меркурий' => 'mercury', 'Венера' => 'venus', 'Марс' => 'mars', 'Юпитер' => 'jupiter', 'Сатурн' => 'saturn', 'Уран' => 'uranus', 'Нептун' => 'neptune', 'Плутон' => 'pluto', 'Сев. узел' => 'north_node', 'Южн. узел' => 'south_node', 'Хирон' => 'chiron', 'Колесо фортуны' => 'part_fortune', 'Вертекс' => 'vertex', 'Асцендент' => 'ascendant', 'MC' => 'mc'];

    $signOrderRu = ['Овен', 'Телец', 'Близнецы', 'Рак', 'Лев', 'Дева', 'Весы', 'Скорпион', 'Стрелец', 'Козерог', 'Водолей', 'Рыбы'];
    $signOrder = $signOrderRu; // Keep for internal calculations
    $signElems = ['fire' => 'rgba(239, 68, 68, 0.08)', 'earth' => 'rgba(249, 115, 22, 0.08)', 'air' => 'rgba(59, 130, 246, 0.08)', 'water' => 'rgba(139, 92, 246, 0.08)'];
    $elemMap = ['Овен' => 'fire', 'Телец' => 'earth', 'Близнецы' => 'air', 'Рак' => 'water', 'Лев' => 'fire', 'Дева' => 'earth', 'Весы' => 'air', 'Скорпион' => 'water', 'Стрелец' => 'fire', 'Козерог' => 'earth', 'Водолей' => 'air', 'Рыбы' => 'water'];
    $qualityMap = ['Овен' => 'cardinal', 'Рак' => 'cardinal', 'Весы' => 'cardinal', 'Козерог' => 'cardinal', 'Телец' => 'fixed', 'Лев' => 'fixed', 'Скорпион' => 'fixed', 'Водолей' => 'fixed', 'Близнецы' => 'mutable', 'Дева' => 'mutable', 'Стрелец' => 'mutable', 'Рыбы' => 'mutable'];
    $polarityMap = ['Овен' => 'yang', 'Близнецы' => 'yang', 'Лев' => 'yang', 'Весы' => 'yang', 'Стрелец' => 'yang', 'Водолей' => 'yang', 'Телец' => 'yin', 'Рак' => 'yin', 'Дева' => 'yin', 'Скорпион' => 'yin', 'Козерог' => 'yin', 'Рыбы' => 'yin'];

    // Helper to translate sign name from Russian to localized
    function translateSign($ruSign, $signNames) { return $signNames[$ruSign] ?? $ruSign; }
    // Helper to translate planet name from Russian to localized
    function translatePlanet($ruPlanet, $planetNamesRuToKey, $planetNames) {
        $key = $planetNamesRuToKey[$ruPlanet] ?? null;
        return $key ? ($planetNames[$key] ?? $ruPlanet) : $ruPlanet;
    }

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
                    <p class="text-white font-medium">{{ __('astrology.link_sent') }}</p>
                    <p class="text-emerald-300 text-sm">{{ __('astrology.link_save_hint') }}</p>
                </div>
            </div>
            <a href="/" class="text-emerald-400 hover:text-white transition-colors text-sm">
                {{ __('astrology.btn_home') }}
            </a>
        </div>
    </div>
    @endif

    <div class="container">
        <!-- ==================== HEADER ==================== -->
        <header class="page-header">
            <a href="/" class="btn">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>{{ __('astrology.btn_back') }}</span>
            </a>
            <div class="header-center">
                <h1>{{ $chart->name }}</h1>
                <div class="header-meta">
                    {{ $chart->birth_date->format('d.m.Y') }}
                    @if($chart->birth_time) • {{ \Carbon\Carbon::parse($chart->birth_time)->format('H:i') }}@endif
                    • {{ $chart->birth_place }}
                </div>
            </div>
            <div style="display: flex; gap: 0.5rem; align-items: center;">
                @php
                    $chartLocales = config('app.available_locales', ['en']);
                    $chartLangFlags = ['en' => '🇬🇧', 'ru' => '🇷🇺', 'es' => '🇪🇸', 'fr' => '🇫🇷', 'pt' => '🇧🇷', 'hi' => '🇮🇳'];
                    $chartBaseUrl = rtrim(config('app.url', request()->getSchemeAndHttpHost()), '/');
                    $chartCurrentLocale = app()->getLocale();
                @endphp
                <div x-data="{ langOpen: false }" style="position: relative;">
                    <button @click="langOpen = !langOpen" @click.outside="langOpen = false" class="btn" style="gap: 0.25rem;">
                        <span class="lang-flag" style="font-size: 1rem; line-height: 1;">{{ $chartLangFlags[$chartCurrentLocale] ?? '🌐' }}</span>
                        <span class="lang-code uppercase" style="font-size: 0.7rem;">{{ $chartCurrentLocale }}</span>
                    </button>
                    <div x-show="langOpen" x-transition style="position: absolute; right: 0; top: 100%; margin-top: 0.25rem; width: 10rem; background: var(--bg-secondary); border: 1px solid var(--border); border-radius: 0.5rem; overflow: hidden; z-index: 50;">
                        @foreach($chartLocales as $loc)
                        <a href="{{ $chartBaseUrl }}{{ $loc === 'en' ? '' : '/' . $loc }}/charts/{{ $chart->id }}"
                           style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 0.75rem; font-size: 0.8rem; color: {{ $loc === $chartCurrentLocale ? 'var(--accent-indigo)' : 'var(--text-secondary)' }}; background: {{ $loc === $chartCurrentLocale ? 'rgba(99,102,241,0.1)' : 'transparent' }}; text-decoration: none; transition: background 0.15s;"
                           onmouseover="this.style.background='rgba(99,102,241,0.08)'" onmouseout="this.style.background='{{ $loc === $chartCurrentLocale ? 'rgba(99,102,241,0.1)' : 'transparent' }}'">
                            <span>{{ $chartLangFlags[$loc] ?? '🌐' }}</span>
                            {{ __('common.lang_' . $loc) }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @if(!isset($showEmailBanner) || !$showEmailBanner)
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>{{ __('astrology.btn_logout') }}</span>
                    </button>
                </form>
                @endif
                <!-- <a href="#" class="btn btn-gold">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <span>Редактировать</span>
                </a> -->
            </div>
        </header>

        <!-- ==================== KEY STATS ==================== -->
        <div class="key-stats">
            <div class="key-badge">
                <img src="/images/planets/sun.webp" alt="">
                <span class="key-badge-label">{{ __('astrology.planet_sun') }}</span>
                <img src="/images/zodiac/{{ $signToFile[$sunSign] ?? 'aries' }}.webp" alt="">
                <span class="key-badge-value">{{ $signNames[$sunSign] ?? $sunSign }}</span>
            </div>
            <div class="key-badge">
                <img src="/images/planets/moon.webp" alt="">
                <span class="key-badge-label">{{ __('astrology.planet_moon') }}</span>
                <img src="/images/zodiac/{{ $signToFile[$moonSign] ?? 'cancer' }}.webp" alt="">
                <span class="key-badge-value">{{ $signNames[$moonSign] ?? $moonSign }}</span>
            </div>
            <div class="key-badge">
                <span class="key-badge-label">ASC</span>
                <img src="/images/zodiac/{{ $signToFile[$ascSign] ?? 'aries' }}.webp" alt="">
                <span class="key-badge-value">{{ $signNames[$ascSign] ?? $ascSign }}</span>
            </div>
            <div class="key-badge">
                <span class="key-badge-label">MC</span>
                <img src="/images/zodiac/{{ $signToFile[$mcSign] ?? 'capricorn' }}.webp" alt="">
                <span class="key-badge-value">{{ $signNames[$mcSign] ?? $mcSign }}</span>
            </div>
        </div>

        <!-- ==================== DAILY HOROSCOPE ==================== -->
        @if(isset($horoscope) && $horoscope && $sunSignKey)
        @php
            $hContent = $horoscope->content ?? [];
            $hScores = $hContent['scores'] ?? ['overall' => 75, 'love' => 70, 'career' => 80, 'health' => 75];
            $hSignName = __('astrology.sign_' . $sunSignKey);
            $hDate = now()->locale(app()->getLocale())->translatedFormat('j F');
            $hOverall = $hScores['overall'] ?? 75;
            $hCircumference = 2 * 3.14159 * 18;
            $hOffset = $hCircumference - ($hCircumference * $hOverall / 100);
        @endphp
        <div class="horoscope-card" x-data="horoscopeCountdown()">
            {{-- Top: sign icon + text + overall score --}}
            <div class="horoscope-top">
                <img src="/images/zodiac/{{ $sunSignKey }}.webp" alt="{{ $hSignName }}" class="horoscope-sign-img">
                <div class="horoscope-info">
                    <div class="horoscope-info-row">
                        <span class="horoscope-title">{{ __('common.daily_horoscope_title') }}</span>
                        <span class="horoscope-live"></span>
                    </div>
                    <div class="horoscope-meta">{{ $hSignName }} · {{ $hDate }}</div>
                    @if(!empty($hContent['overview']))
                    <div class="horoscope-text">{{ Str::limit($hContent['overview'], 180) }}</div>
                    @endif
                </div>
                <div class="horoscope-score-big">
                    <div class="horoscope-score-num">{{ $hOverall }}<small>%</small></div>
                    <div class="horoscope-score-label">{{ __('common.daily_score') }}</div>
                </div>
            </div>

            {{-- Score pills with bars --}}
            <div class="horoscope-scores-row">
                <div class="h-score-pill">
                    <span class="h-score-emoji">❤️</span>
                    <div class="h-score-info">
                        <span class="h-score-label">{{ __('common.daily_love') }}</span>
                        <div class="h-score-bar"><div class="h-score-fill love" style="width: {{ $hScores['love'] ?? 70 }}%"></div></div>
                    </div>
                    <span class="h-score-val love">{{ $hScores['love'] ?? 70 }}%</span>
                </div>
                <div class="h-score-pill">
                    <span class="h-score-emoji">💼</span>
                    <div class="h-score-info">
                        <span class="h-score-label">{{ __('common.daily_career') }}</span>
                        <div class="h-score-bar"><div class="h-score-fill career" style="width: {{ $hScores['career'] ?? 80 }}%"></div></div>
                    </div>
                    <span class="h-score-val career">{{ $hScores['career'] ?? 80 }}%</span>
                </div>
                <div class="h-score-pill">
                    <span class="h-score-emoji">🍀</span>
                    <div class="h-score-info">
                        <span class="h-score-label">{{ __('common.daily_luck') }}</span>
                        <div class="h-score-bar"><div class="h-score-fill luck" style="width: {{ $hScores['luck'] ?? 65 }}%"></div></div>
                    </div>
                    <span class="h-score-val luck">{{ $hScores['luck'] ?? 65 }}%</span>
                </div>
                <div class="h-score-pill">
                    <span class="h-score-emoji">✨</span>
                    <div class="h-score-info">
                        <span class="h-score-label">{{ __('common.daily_health') }}</span>
                        <div class="h-score-bar"><div class="h-score-fill health" style="width: {{ $hScores['health'] ?? 75 }}%"></div></div>
                    </div>
                    <span class="h-score-val health">{{ $hScores['health'] ?? 75 }}%</span>
                </div>
            </div>

            {{-- Countdown bar --}}
            <div class="horoscope-countdown-bar">
                <span class="hcb-label">{{ __('common.daily_updates_in') }}</span>
                <div class="hcb-timer">
                    <span class="hcb-digit" x-text="hours">00</span><span class="hcb-unit">{{ __('common.daily_h') }}</span>
                    <span class="hcb-sep">:</span>
                    <span class="hcb-digit" x-text="minutes">00</span><span class="hcb-unit">{{ __('common.daily_m') }}</span>
                    <span class="hcb-sep">:</span>
                    <span class="hcb-digit" x-text="seconds">00</span><span class="hcb-unit">{{ __('common.daily_s') }}</span>
                </div>
                @if(Route::has('horoscope.sign'))
                <a href="{{ locale_route('horoscope.sign', ['sign' => $sunSignKey]) }}" target="_blank" class="hcb-link">{{ __('common.daily_read_more') }} →</a>
                @endif
            </div>
        </div>
        @endif

        <!-- ==================== TAB NAVIGATION ==================== -->
        <nav class="tab-nav">
            <button class="tab-btn" :class="{ 'active': activeTab === 'overview' }" @click="visitTab('overview')">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><path stroke-width="2" d="M12 6v6l4 2"/></svg>
                <span class="tab-btn-text">{{ __('astrology.tab_overview') }}</span>
            </button>
            <button class="tab-btn" :class="{ 'active': activeTab === 'analysis', 'tab-new': !visited.analysis }" @click="visitTab('analysis')">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span class="tab-btn-text">{{ __('astrology.tab_analysis') }}</span>
                <span x-show="!visited.analysis" class="tab-dot"></span>
            </button>
            <button class="tab-btn" :class="{ 'active': activeTab === 'chat', 'tab-new': !visited.chat }" @click="visitTab('chat')">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                <span class="tab-btn-text">{{ __('astrology.tab_chat') }}</span>
                <span x-show="!visited.chat" class="tab-dot"></span>
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
                            <image href="/images/zodiac/{{ $z['file'] }}.webp" x="{{ $z['pos']['x'] - 33 }}" y="{{ $z['pos']['y'] - 33 }}" width="66" height="66" opacity="0.8"/>
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
                                @if(isset($planetFiles[$k]))<image href="/images/planets/{{ $planetFiles[$k] }}.webp" x="{{ $c['x']-13 }}" y="{{ $c['y']-13 }}" width="26" height="26"/>@endif
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
                            {{ __('astrology.section_summary') }}
                        </div>
                        <div class="ai-report-content" style="margin: 0;">{!! Str::markdown($chart->getAiCharacterAnalysis()) !!}</div>
                    </div>
                    @endif
                </div>

                <!-- Right: Details Panel -->
                <div class="details-panel">
                    <!-- Planets -->
                    <div class="section-card">
                        <div class="section-header" style="color: var(--accent-indigo);">
                            <svg fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/></svg>
                            {{ __('astrology.section_planets') }}
                        </div>
                        <div class="section-body">
                            <div class="planet-list">
                                @foreach($planets as $k => $p)
                                @if(!is_array($p)) @continue @endif
                                <div class="planet-row">
                                    @if(isset($planetFiles[$k]))<img src="/images/planets/{{ $planetFiles[$k] }}.webp" alt="">@endif
                                    <div class="planet-row-name">
                                        {{ $planetNames[$k] ?? ucfirst($k) }}
                                        @if($p['retrograde'] ?? false)<span class="planet-row-retro">R</span>@endif
                                    </div>
                                    <div class="planet-row-deg">{{ $signNames[$p['sign'] ?? ''] ?? ($p['sign'] ?? '-') }} {{ floor($p['degree'] ?? 0) }}°</div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Houses -->
                    <div class="section-card">
                        <div class="section-header" style="color: var(--accent-cyan);">
                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M3 13h1v7c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-7h1"/></svg>
                            {{ __('astrology.section_houses') }}
                        </div>
                        <div class="section-body">
                            <div class="houses-grid">
                                @for($i = 1; $i <= 12; $i++)
                                @php $h = $houses[$i] ?? ['sign' => 'Овен', 'degree' => 0]; $file = $signToFile[$h['sign']] ?? 'aries'; @endphp
                                <div class="house-item {{ in_array($i, [1,4,7,10]) ? 'angular' : '' }}">
                                    <img src="/images/zodiac/{{ $file }}.webp" alt="">
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
                            {{ __('astrology.section_aspects') }}
                        </div>
                        <div class="section-body">
                            <div class="aspects-list">
                                @foreach($aspects as $a)
                                @php
                                    $isHarmony = in_array($a['type'] ?? '', $harmonyAspects);
                                    $isTension = in_array($a['type'] ?? '', $stressAspects);
                                    $colorClass = $isHarmony ? 'green' : ($isTension ? 'red' : 'purple');
                                @endphp
                                @php
                                    $p1Key = $planetNamesRuToKey[$a['planet1'] ?? ''] ?? null;
                                    $p2Key = $planetNamesRuToKey[$a['planet2'] ?? ''] ?? null;
                                    $p1Name = $p1Key ? ($planetNames[$p1Key] ?? $a['planet1']) : ($a['planet1'] ?? '');
                                    $p2Name = $p2Key ? ($planetNames[$p2Key] ?? $a['planet2']) : ($a['planet2'] ?? '');
                                @endphp
                                <div class="aspect-row">
                                    <span class="aspect-symbol {{ $colorClass }}">{{ $aspectSymbols[$a['type']] ?? '•' }}</span>
                                    <span class="aspect-planets">{{ $p1Name }} — {{ $p2Name }}</span>
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
                            {{ __('astrology.section_balance') }}
                        </div>
                        <div class="section-body">
                            <div style="display: flex; gap: 0.5rem; margin-bottom: 0.5rem;">
                                <div style="flex:1; text-align:center; padding:0.35rem; background:var(--bg-tertiary); border-radius:0.375rem; font-size:0.7rem;">
                                    <div style="font-weight:600;">{{ $qualityCount['cardinal'] }}</div>
                                    <div style="color:var(--text-muted);">{{ __('astrology.balance_cardinal') }}</div>
                                </div>
                                <div style="flex:1; text-align:center; padding:0.35rem; background:var(--bg-tertiary); border-radius:0.375rem; font-size:0.7rem;">
                                    <div style="font-weight:600;">{{ $qualityCount['fixed'] }}</div>
                                    <div style="color:var(--text-muted);">{{ __('astrology.balance_fixed') }}</div>
                                </div>
                                <div style="flex:1; text-align:center; padding:0.35rem; background:var(--bg-tertiary); border-radius:0.375rem; font-size:0.7rem;">
                                    <div style="font-weight:600;">{{ $qualityCount['mutable'] }}</div>
                                    <div style="color:var(--text-muted);">{{ __('astrology.balance_mutable') }}</div>
                                </div>
                            </div>
                            <div style="display: flex; gap: 0.5rem;">
                                <div style="flex:1; display:flex; align-items:center; gap:0.35rem; padding:0.35rem 0.5rem; background:var(--bg-tertiary); border-radius:0.375rem; font-size:0.7rem;">
                                    <span style="color:#3B82F6">☀</span>
                                    <span style="color:var(--text-muted)">{{ __('astrology.balance_yang') }}</span>
                                    <span style="font-weight:600; margin-left:auto;">{{ $polarityCount['yang'] }}</span>
                                </div>
                                <div style="flex:1; display:flex; align-items:center; gap:0.35rem; padding:0.35rem 0.5rem; background:var(--bg-tertiary); border-radius:0.375rem; font-size:0.7rem;">
                                    <span style="color:#EC4899">☽</span>
                                    <span style="color:var(--text-muted)">{{ __('astrology.balance_yin') }}</span>
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

            <!-- Language hint when report was generated in a different language -->
            @if($chart->hasAiReport() && $chart->locale && $chart->locale !== app()->getLocale())
            <div style="background: rgba(99,102,241,0.08); border: 1px solid rgba(99,102,241,0.2); border-radius: 0.75rem; padding: 0.75rem 1rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.65rem;">
                <span style="font-size: 1rem;">🌐</span>
                <span style="font-size: 0.8rem; color: var(--text-muted);">{{ __('astrology.report_language_hint', ['language' => __('astrology.language_' . $chart->locale)]) }}</span>
            </div>
            @endif

            @php
                $aiSections = [
                    ['key' => 'identity', 'method' => 'getAiCharacterAnalysis', 'label' => 'astrology.section_character', 'icon' => '👤', 'color' => 'var(--accent-indigo)'],
                    ['key' => 'strengths_weaknesses', 'method' => 'getAiStrengthsWeaknesses', 'label' => 'astrology.section_strengths', 'icon' => '💪', 'color' => 'var(--accent-gold)'],
                    ['key' => 'love', 'method' => 'getAiLoveAnalysis', 'label' => 'astrology.section_love', 'icon' => '❤️', 'color' => 'var(--accent-pink, #ec4899)'],
                    ['key' => 'career', 'method' => 'getAiCareerAnalysis', 'label' => 'astrology.section_career', 'icon' => '💼', 'color' => 'var(--accent-gold)'],
                    ['key' => 'health', 'method' => 'getAiHealthAnalysis', 'label' => 'astrology.section_health', 'icon' => '🏥', 'color' => 'var(--accent-teal, #14b8a6)'],
                    ['key' => 'karma', 'method' => 'getAiKarmaAnalysis', 'label' => 'astrology.section_karma', 'icon' => '🔮', 'color' => 'var(--accent-purple, #a855f7)'],
                    ['key' => 'forecast', 'method' => 'getAiForecast', 'label' => 'astrology.section_forecast', 'icon' => '🌟', 'color' => 'var(--accent-cyan, #06b6d4)'],
                ];
            @endphp

            <!-- AI Report Sections -->
            @if($chart->hasAiReport())
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem; padding: 0.5rem 0;">
                <span style="display: inline-flex; align-items: center; gap: 0.35rem; background: linear-gradient(135deg, rgba(99,102,241,0.15), rgba(168,85,247,0.15)); border: 1px solid rgba(99,102,241,0.25); border-radius: 2rem; padding: 0.3rem 0.75rem; font-size: 0.7rem; color: var(--accent-indigo); font-weight: 500;">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
                    {{ __('astrology.ai_generated_label') }}
                </span>
                <span style="font-size: 0.7rem; color: var(--text-muted);">{{ __('astrology.ai_generated_hint') }}</span>
            </div>
            @foreach($aiSections as $section)
                @php $sectionContent = $chart->{$section['method']}(); @endphp
                @if($sectionContent)
                <div class="section-card" style="margin-bottom: 0.75rem;">
                    <div class="section-header" style="color: {{ $section['color'] }}; font-size: 0.9rem; cursor: pointer; user-select: none;" onclick="this.parentElement.querySelector('.section-body').classList.toggle('ai-section-collapsed')">
                        <span style="font-size: 1.1rem;">{{ $section['icon'] }}</span>
                        {{ __($section['label']) }}
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-left: auto; transition: transform 0.2s;"><path d="M6 9l6 6 6-6"/></svg>
                    </div>
                    <div class="section-body ai-report-content" style="font-size: 0.875rem; color: var(--text-secondary); line-height: 1.8; padding: 0.75rem 1rem;">
                        {!! Str::markdown(is_array($sectionContent) ? json_encode($sectionContent, JSON_UNESCAPED_UNICODE) : $sectionContent) !!}
                    </div>
                </div>
                @endif
            @endforeach
            @endif

            <!-- Sun, Moon, Ascendant Cards -->
            <div class="analysis-grid">
                <!-- Sun -->
                @php $sunMeaning = $chart->getPlanetMeaning('sun'); $sunSignMeaning = $chart->getSignMeaning($sunSign); @endphp
                <div class="analysis-card">
                    <div class="analysis-card-header">
                        <img src="/images/planets/sun.webp" alt="">
                        <span class="analysis-card-title">{{ __('astrology.sun_in', ['sign' => $signNames[$sunSign] ?? $sunSign]) }}</span>
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
                        <img src="/images/planets/moon.webp" alt="">
                        <span class="analysis-card-title">{{ __('astrology.moon_in', ['sign' => $signNames[$moonSign] ?? $moonSign]) }}</span>
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
                        <img src="/images/zodiac/{{ $signToFile[$ascSign] ?? 'aries' }}.webp" alt="">
                        <span class="analysis-card-title">{{ __('astrology.asc_in', ['sign' => $signNames[$ascSign] ?? $ascSign]) }}</span>
                    </div>
                    <div class="analysis-card-body">
                        @if($ascMeaning)<p>{{ $ascMeaning->characteristics }}</p>@else
                        <p>{{ __('astrology.fallback_asc', ['sign' => $signNames[$ascSign] ?? $ascSign]) }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Planets in Houses -->
            <div class="section-card" style="margin-top: 1rem;">
                <div class="section-header" style="color: var(--accent-cyan);">
                    <span style="font-size: 1rem;">🏠</span>
                    {{ __('astrology.section_planets_in_houses') }}
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
                                <img src="/images/planets/{{ $planetFiles[$planet] }}.webp" alt="" style="width: 1.25rem; height: 1.25rem;">
                                <strong style="color: var(--text-primary); font-size: 0.85rem;">{{ __('astrology.planet_in_house', ['planet' => $planetNames[$planet], 'house' => $houseNum]) }}</strong>
                            </div>
                            <p style="font-size: 0.8rem; color: var(--text-secondary); line-height: 1.6; margin: 0;">
                                @if($hMeaning) {{ Str::limit($hMeaning->general, 120) }}@else
                                {{ __('astrology.fallback_planet_in_house', ['planet' => $planetNames[$planet], 'house' => $houseNum]) }}
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
                    <span class="chat-title">{{ __('astrology.chat_ask_ai') }}</span>
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
                            {{ __('astrology.chat_welcome') }}
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
                    <textarea id="chat-input" class="chat-input" placeholder="{{ __('astrology.chat_placeholder') }}" rows="1"></textarea>
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
                tooltip.innerHTML = `<div class="tooltip-name">${g.dataset.name}</div><div class="tooltip-detail">${g.dataset.sign}</div><div class="tooltip-detail">${g.dataset.house} {{ __('astrology.house_label') }}</div>`;
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
            div.textContent = '{{ __('astrology.thinking') }}';
            chatMessages.appendChild(div);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function hideTyping() {
            const typing = document.getElementById('typing-indicator');
            if (typing) typing.remove();
        }

        // Send message with queue polling
        async function sendChatMessage(message) {
            addChatMessage(message, 'user');
            chatInput.value = '';
            chatInput.style.height = 'auto';
            showTyping();
            chatSend.disabled = true;

            try {
                const response = await fetch(`{{ route('charts.chat.send', ['natalChart' => $chart->id]) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message })
                });

                if (response.ok) {
                    const data = await response.json();
                    // Poll for response
                    await pollChatResponse(data.message_id);
                } else {
                    hideTyping();
                    addChatMessage('{{ __("common.chat_error") }}', 'assistant');
                }
            } catch (error) {
                hideTyping();
                addChatMessage('{{ __("common.chat_connection_error") }}', 'assistant');
            }

            chatSend.disabled = false;
        }

        // Poll for chat response
        async function pollChatResponse(messageId) {
            const maxAttempts = 120; // 120 seconds (2 minutes) max
            let attempts = 0;

            while (attempts < maxAttempts) {
                try {
                    const statusUrl = `{{ route('charts.chat.status', ['natalChart' => $chart->id, 'chatMessage' => '__MSG_ID__']) }}`.replace('__MSG_ID__', messageId);
                    const response = await fetch(statusUrl, {
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });

                    if (response.ok) {
                        const data = await response.json();

                        if (data.status === 'completed' || data.status === 'failed') {
                            hideTyping();
                            addChatMessage(data.content || '{{ __("common.chat_response_error") }}', 'assistant');
                            return;
                        }
                    }
                } catch (e) {
                    console.error('Poll error:', e);
                }

                attempts++;
                await new Promise(r => setTimeout(r, 1000)); // Wait 1 second
            }

            hideTyping();
            addChatMessage('{{ __("common.chat_timeout") }}', 'assistant');
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
    <script>
    function horoscopeCountdown() {
        return {
            hours: '00', minutes: '00', seconds: '00',
            init() {
                this.tick();
                setInterval(() => this.tick(), 1000);
            },
            tick() {
                const now = new Date();
                const nextUpdate = new Date(Date.UTC(now.getUTCFullYear(), now.getUTCMonth(), now.getUTCDate(), 0, 5, 0));
                if (now >= nextUpdate) {
                    nextUpdate.setUTCDate(nextUpdate.getUTCDate() + 1);
                }
                const diff = nextUpdate - now;
                this.hours = String(Math.floor(diff / 3600000)).padStart(2, '0');
                this.minutes = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
                this.seconds = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
            }
        };
    }

    function chartPage() {
        const storageKey = 'chart_tabs_{{ $chart->id }}';
        const saved = JSON.parse(localStorage.getItem(storageKey) || '{}');
        const startTab = new URLSearchParams(window.location.search).get('tab') || 'overview';
        saved[startTab] = true;
        localStorage.setItem(storageKey, JSON.stringify(saved));
        return {
            activeTab: startTab,
            visited: { overview: !!saved.overview, analysis: !!saved.analysis, chat: !!saved.chat },
            init() {},
            visitTab(tab) {
                this.activeTab = tab;
                this.visited[tab] = true;
                const s = JSON.parse(localStorage.getItem(storageKey) || '{}');
                s[tab] = true;
                localStorage.setItem(storageKey, JSON.stringify(s));
            }
        };
    }
    </script>
</body>
</html>
