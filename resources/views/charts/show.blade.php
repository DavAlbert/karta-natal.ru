<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $chart->name }} — Натальная карта</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.1.0/js/all.min.js"></script>
    <script src="https://smartcaptcha.yandexcloud.net/captcha.js" defer></script>
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

        /* Compatibility Tab Styles */
        .compat-container {
            max-width: 900px;
            margin: 0 auto;
        }

        /* Actions Bar */
        .compat-actions-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.25rem;
            padding: 1rem 1.25rem;
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            flex-wrap: wrap;
        }
        .compat-partner-selector {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .compat-partner-selector label {
            font-size: 0.9rem;
            color: var(--text-secondary);
            font-weight: 500;
        }
        .compat-partner-selector select {
            padding: 0.5rem 2rem 0.5rem 0.75rem;
            background: var(--bg-tertiary);
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            color: var(--text-primary);
            font-size: 0.9rem;
            cursor: pointer;
            min-width: 180px;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%239CA3AF' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10l-5 5z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
        }
        .compat-partner-selector select:focus {
            outline: none;
            border-color: var(--accent-purple);
        }
        .compat-new-partner-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1rem;
            background: linear-gradient(135deg, #EC4899, #8B5CF6);
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .compat-new-partner-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);
        }
        @media (max-width: 500px) {
            .compat-actions-bar {
                flex-direction: column;
                align-items: stretch;
            }
            .compat-partner-selector {
                flex-direction: column;
                align-items: stretch;
            }
            .compat-partner-selector select {
                width: 100%;
            }
        }

        /* Hero Section for Form */
        .compat-hero {
            text-align: center;
            padding: 2rem 1.5rem;
            background: linear-gradient(135deg, rgba(236, 72, 153, 0.12), rgba(139, 92, 246, 0.08));
            border-radius: 1.25rem;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(236, 72, 153, 0.15);
        }
        .compat-hero-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.25rem;
            background: linear-gradient(135deg, #EC4899, #8B5CF6);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            box-shadow: 0 8px 32px rgba(236, 72, 153, 0.3);
        }
        .compat-hero h2 {
            font-family: 'Cinzel', serif;
            font-size: 1.5rem;
            margin: 0 0 0.5rem;
            color: var(--text-primary);
        }
        .compat-hero p {
            color: var(--text-secondary);
            font-size: 0.95rem;
            margin: 0;
            max-width: 400px;
            margin: 0 auto;
            line-height: 1.6;
        }
        @media (max-width: 500px) {
            .compat-hero {
                padding: 1.5rem 1rem;
            }
            .compat-hero-icon {
                width: 64px;
                height: 64px;
                font-size: 1.5rem;
            }
            .compat-hero h2 {
                font-size: 1.25rem;
            }
        }

        /* Form Card */
        .compat-form {
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 1rem;
            padding: 1.75rem;
        }
        @media (max-width: 500px) {
            .compat-form {
                padding: 1.25rem;
            }
        }
        .compat-form-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-primary);
        }
        .compat-form-title i { color: var(--accent-pink); }
        .compat-form-subtitle {
            color: var(--text-secondary);
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }
        .compat-checkbox {
            display: flex;
            align-items: flex-start;
            gap: 0.625rem;
            cursor: pointer;
            font-size: 0.8rem;
            color: var(--text-muted);
            line-height: 1.6;
            padding: 0.75rem;
            background: var(--bg-tertiary);
            border-radius: 0.5rem;
            border: 1px solid var(--border);
            transition: border-color 0.2s;
        }
        .compat-checkbox:hover {
            border-color: var(--accent-indigo);
        }
        .compat-checkbox input {
            margin-top: 2px;
            flex-shrink: 0;
            accent-color: var(--accent-indigo);
            width: 16px;
            height: 16px;
        }
        .compat-checkbox a {
            color: var(--accent-indigo);
            text-decoration: underline;
        }
        .compat-checkbox a:hover { color: var(--accent-gold); }
        .form-row {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }
        @media (min-width: 500px) {
            .form-row.two-col { grid-template-columns: 1fr 1fr; }
        }
        .form-group { }
        .form-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 0.75rem 1rem;
            background: var(--bg-tertiary);
            border: 1px solid var(--border);
            border-radius: 0.625rem;
            color: var(--text-primary);
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: var(--accent-indigo);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }
        .form-group input::placeholder { color: var(--text-muted); }

        /* Gender Buttons */
        .gender-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }
        .gender-btn { cursor: pointer; }
        .gender-btn input { display: none; }
        .gender-btn-inner {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.625rem;
            padding: 0.875rem 1rem;
            border-radius: 0.625rem;
            border: 2px solid var(--border);
            background: var(--bg-tertiary);
            transition: all 0.2s;
        }
        .gender-btn-inner:hover {
            border-color: var(--accent-indigo);
            background: rgba(99, 102, 241, 0.08);
        }
        .gender-btn input:checked + .gender-btn-inner {
            border-color: var(--accent-pink);
            background: rgba(236, 72, 153, 0.12);
        }
        .gender-btn input:checked + .gender-btn-inner i { color: var(--accent-pink); }
        .gender-btn-inner i { font-size: 1.125rem; color: var(--text-muted); transition: color 0.2s; }
        .gender-btn-inner span { font-size: 0.9rem; font-weight: 500; color: var(--text-primary); }

        /* Date/Time Dropdowns */
        .date-dropdowns, .time-dropdowns {
            display: grid;
            gap: 0.5rem;
        }
        .date-dropdowns { grid-template-columns: 1fr 1.5fr 1fr; }
        .time-dropdowns { grid-template-columns: 1fr 1fr; }
        .date-dropdowns select, .time-dropdowns select {
            padding: 0.75rem 0.5rem;
            background: var(--bg-tertiary);
            border: 1px solid var(--border);
            border-radius: 0.625rem;
            color: var(--text-primary);
            font-size: 0.9rem;
            transition: all 0.2s;
            cursor: pointer;
            text-align: center;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23818CF8' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.25em 1.25em;
            padding-right: 2rem;
        }
        .date-dropdowns select:focus, .time-dropdowns select:focus {
            outline: none;
            border-color: var(--accent-indigo);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }
        .date-dropdowns select option, .time-dropdowns select option {
            background: var(--bg-tertiary);
            color: var(--text-primary);
        }
        .time-unknown-checkbox {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
            cursor: pointer;
            font-size: 0.8rem;
            color: var(--text-muted);
        }
        .time-unknown-checkbox:hover { color: var(--text-secondary); }
        .time-unknown-checkbox input {
            width: auto;
            margin: 0;
            cursor: pointer;
        }
        @media (max-width: 480px) {
            .date-dropdowns {
                grid-template-columns: 1fr 1fr;
            }
            .date-dropdowns select:nth-child(2) { /* Month */
                grid-column: 1 / -1;
                order: -1;
            }
            .date-dropdowns select, .time-dropdowns select {
                font-size: 16px; /* Prevent zoom on iOS */
                min-height: 48px;
                padding: 0.5rem 0.25rem;
                padding-right: 1.5rem;
                background-size: 1em 1em;
            }
        }

        .compat-submit {
            width: 100%;
            padding: 1rem 1.5rem;
            background: linear-gradient(135deg, #EC4899, #8B5CF6);
            border: none;
            border-radius: 0.75rem;
            color: white;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.25s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.625rem;
            box-shadow: 0 4px 20px rgba(236, 72, 153, 0.35);
            margin-top: 0.5rem;
        }
        .compat-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(236, 72, 153, 0.45);
        }
        .compat-submit:disabled { opacity: 0.5; cursor: not-allowed; transform: none; box-shadow: none; }

        /* Compatibility Result */
        .compat-result {
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 1.25rem;
            overflow: hidden;
        }
        .compat-result-header {
            padding: 2rem 1.5rem;
            background: linear-gradient(135deg, rgba(236, 72, 153, 0.15), rgba(139, 92, 246, 0.1));
            border-bottom: 1px solid var(--border);
            text-align: center;
        }
        @media (max-width: 500px) {
            .compat-result-header {
                padding: 1.5rem 1rem;
            }
        }
        .compat-score-circle {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: conic-gradient(var(--score-color) calc(var(--score) * 3.6deg), rgba(255,255,255,0.08) 0);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            position: relative;
            margin-bottom: 1rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }
        @media (max-width: 500px) {
            .compat-score-circle {
                width: 120px;
                height: 120px;
            }
        }
        .compat-score-circle::before {
            content: '';
            position: absolute;
            inset: 10px;
            background: linear-gradient(135deg, var(--bg-secondary), var(--bg-tertiary));
            border-radius: 50%;
        }
        .compat-score-value {
            position: relative;
            font-size: 3rem;
            font-weight: 700;
        }
        @media (max-width: 500px) {
            .compat-score-value {
                font-size: 2.5rem;
            }
        }
        .compat-score-label {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.75rem;
        }
        .compat-partner-info {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 0.5rem 0.75rem;
            font-size: 0.9rem;
            color: var(--text-secondary);
        }
        @media (max-width: 500px) {
            .compat-partner-info {
                flex-direction: column;
                gap: 0.5rem;
            }
            .compat-partner-info span:not(.compat-status-badge) {
                display: none;
            }
            .compat-partner-info span:first-child,
            .compat-partner-info .compat-status-badge {
                display: inline;
            }
        }
        .compat-status-badge {
            padding: 0.375rem 0.875rem;
            border-radius: 2rem;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .compat-status-pending { background: rgba(234, 179, 8, 0.15); color: var(--accent-gold); border: 1px solid rgba(234, 179, 8, 0.3); }
        .compat-status-completed { background: rgba(34, 197, 94, 0.15); color: var(--accent-green); border: 1px solid rgba(34, 197, 94, 0.3); }
        .compat-result-body { padding: 1.5rem; }
        @media (max-width: 500px) {
            .compat-result-body { padding: 1.25rem; }
        }

        /* Pending Message */
        .compat-pending-msg {
            text-align: center;
            padding: 1.5rem;
            background: linear-gradient(135deg, rgba(234, 179, 8, 0.08), rgba(234, 179, 8, 0.04));
            border: 1px solid rgba(234, 179, 8, 0.2);
            border-radius: 0.75rem;
            margin-bottom: 1rem;
        }
        .compat-pending-msg p {
            margin: 0 0 1rem;
            color: var(--text-secondary);
            line-height: 1.6;
        }
        .compat-pending-msg strong {
            color: var(--accent-gold);
        }
        .compat-refresh-btn {
            padding: 0.625rem 1.25rem;
            background: var(--accent-gold);
            color: #000;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }
        .compat-refresh-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(234, 179, 8, 0.3);
        }

        /* Scores Grid */
        .compat-scores-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.875rem;
        }
        @media (max-width: 500px) {
            .compat-scores-grid {
                grid-template-columns: 1fr;
            }
        }
        .compat-score-item {
            padding: 1rem;
            background: var(--bg-tertiary);
            border-radius: 0.75rem;
            border: 1px solid var(--border);
        }
        .compat-score-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        .compat-score-name {
            color: var(--text-secondary);
            font-size: 0.85rem;
            font-weight: 500;
        }
        .compat-score-val {
            font-size: 0.9rem;
            font-weight: 700;
        }
        .compat-progress {
            height: 8px;
            background: var(--bg-secondary);
            border-radius: 4px;
            overflow: hidden;
        }
        .compat-progress-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.8s ease-out;
        }

        /* Sections */
        .compat-section {
            margin-top: 1.5rem;
            padding: 1.25rem;
            background: var(--bg-tertiary);
            border-radius: 0.75rem;
            border: 1px solid var(--border);
        }
        .compat-section-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.625rem;
        }
        .compat-list-item {
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            color: var(--text-secondary);
            display: flex;
            align-items: flex-start;
            gap: 0.625rem;
            line-height: 1.5;
        }
        .compat-list-item:last-child { margin-bottom: 0; }
        .compat-list-item svg { flex-shrink: 0; width: 1rem; height: 1rem; margin-top: 0.2rem; }
        .compat-strengths .compat-list-item {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.15);
        }
        .compat-strengths .compat-list-item svg { color: var(--accent-green); }
        .compat-challenges .compat-list-item {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.15);
        }
        .compat-challenges .compat-list-item svg { color: var(--accent-red); }
        .compat-recommendations .compat-list-item {
            background: rgba(234, 179, 8, 0.1);
            border: 1px solid rgba(234, 179, 8, 0.15);
        }
        .compat-recommendations .compat-list-item svg { color: var(--accent-gold); }

        .compat-new-btn {
            width: 100%;
            margin-top: 1.5rem;
            padding: 0.875rem 1.25rem;
            background: transparent;
            border: 2px solid var(--border);
            border-radius: 0.75rem;
            color: var(--text-secondary);
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .compat-new-btn:hover {
            border-color: var(--accent-pink);
            color: var(--accent-pink);
            background: rgba(236, 72, 153, 0.05);
        }
        .compat-new-btn i { font-size: 0.8rem; }

        /* City Search */
        .city-search-container { position: relative; }
        .city-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: var(--bg-tertiary);
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            max-height: 200px;
            overflow-y: auto;
            z-index: 100;
            margin-top: 0.25rem;
        }
        .city-result-item {
            padding: 0.5rem 0.75rem;
            cursor: pointer;
            font-size: 0.85rem;
            border-bottom: 1px solid var(--border);
        }
        .city-result-item:last-child { border-bottom: none; }
        .city-result-item:hover { background: var(--bg-secondary); }
        .city-result-country { color: var(--text-muted); font-size: 0.75rem; }
        .city-hint {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 0.375rem;
        }
        .city-warning {
            font-size: 0.75rem;
            color: var(--accent-orange);
            margin-top: 0.375rem;
        }
        .city-warning i { margin-right: 0.25rem; }
        .city-details {
            margin-top: 0.5rem;
            padding: 0.625rem 0.75rem;
            background: rgba(99, 102, 241, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: 0.5rem;
            font-size: 0.75rem;
            color: var(--text-secondary);
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.5rem;
        }
        .city-details span:first-child { color: var(--text-primary); font-weight: 500; }
        .city-details-sep { color: var(--accent-indigo); opacity: 0.5; }
        .city-details-coords { font-family: monospace; color: var(--text-muted); }

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
<body x-data="{ activeTab: new URLSearchParams(window.location.search).get('tab') || localStorage.getItem('activeTab') || 'overview' }" x-init="localStorage.removeItem('activeTab')">
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
                <span class="key-badge-label">Солнце</span>
                <img src="/images/zodiac/{{ $signToFile[$sunSign] ?? 'aries' }}.webp" alt="">
                <span class="key-badge-value">{{ $sunSign }}</span>
            </div>
            <div class="key-badge">
                <img src="/images/planets/moon.webp" alt="">
                <span class="key-badge-label">Луна</span>
                <img src="/images/zodiac/{{ $signToFile[$moonSign] ?? 'cancer' }}.webp" alt="">
                <span class="key-badge-value">{{ $moonSign }}</span>
            </div>
            <div class="key-badge">
                <span class="key-badge-label">ASC</span>
                <img src="/images/zodiac/{{ $signToFile[$ascSign] ?? 'aries' }}.webp" alt="">
                <span class="key-badge-value">{{ $ascSign }}</span>
            </div>
            <div class="key-badge">
                <span class="key-badge-label">MC</span>
                <img src="/images/zodiac/{{ $signToFile[$mcSign] ?? 'capricorn' }}.webp" alt="">
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
            <button class="tab-btn" :class="{ 'active': activeTab === 'compatibility' }" @click="activeTab = 'compatibility'">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <span class="tab-btn-text">Совместимость</span>
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
                                    @if(isset($planetFiles[$k]))<img src="/images/planets/{{ $planetFiles[$k] }}.webp" alt="">@endif
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
                        <img src="/images/planets/sun.webp" alt="">
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
                        <img src="/images/planets/moon.webp" alt="">
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
                        <img src="/images/zodiac/{{ $signToFile[$ascSign] ?? 'aries' }}.webp" alt="">
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
                                <img src="/images/planets/{{ $planetFiles[$planet] }}.webp" alt="" style="width: 1.25rem; height: 1.25rem;">
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

        <!-- ==================== TAB 4: COMPATIBILITY ==================== -->
        <div class="tab-content" :class="{ 'active': activeTab === 'compatibility' }" x-data="compatibilityTab()" x-init="loadCompatibility()">
            <div class="compat-container">
                <!-- Loading State -->
                <div x-show="loading" style="text-align: center; padding: 3rem;">
                    <p style="color: var(--text-muted);">Загрузка...</p>
                </div>

                <!-- No Partners Yet OR Show Form Mode - Show Form -->
                <div x-show="!loading && (partners.length === 0 || showForm)" x-cloak>
                    <!-- Partner selector when in form mode with existing partners -->
                    <div x-show="partners.length > 0" class="compat-actions-bar" style="margin-bottom: 1rem;">
                        <div class="compat-partner-selector">
                            <label>Или выбрать:</label>
                            <select @change="if($event.target.value) { selectPartnerById($event.target.value); showForm = false; } $event.target.value = '';">
                                <option value="">— Выбрать партнёра —</option>
                                <template x-for="partner in partners" :key="partner.id">
                                    <option :value="partner.id" x-text="partner.partner_name + ' (' + (partner.overall_score ? partner.overall_score + '%' : 'ожидание') + ')'"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <div class="compat-form">
                        <div class="compat-form-title">
                            <i class="fas fa-heart"></i>
                            Новый партнёр
                        </div>
                        <p style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 1.25rem;">
                            Введите данные партнёра для анализа совместимости.
                        </p>
                        <form @submit.prevent="submitForm()">
                            <div class="form-row two-col">
                                <div class="form-group">
                                    <label>Имя партнёра *</label>
                                    <input type="text" x-model="form.partner_name" placeholder="Например: Анна" required>
                                </div>
                                <div class="form-group">
                                    <label>Email партнёра *</label>
                                    <input type="email" x-model="form.partner_email" placeholder="partner@email.com" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Пол партнёра *</label>
                                    <div class="gender-buttons">
                                        <label class="gender-btn">
                                            <input type="radio" x-model="form.partner_gender" value="male" required>
                                            <div class="gender-btn-inner">
                                                <i class="fas fa-mars"></i>
                                                <span>Мужской</span>
                                            </div>
                                        </label>
                                        <label class="gender-btn">
                                            <input type="radio" x-model="form.partner_gender" value="female" required>
                                            <div class="gender-btn-inner">
                                                <i class="fas fa-venus"></i>
                                                <span>Женский</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!-- Дата рождения - Dropdowns -->
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Дата рождения *</label>
                                    <div class="date-dropdowns">
                                        <select x-model="form.birth_day" @change="syncBirthDate()" required>
                                            <option value="">День</option>
                                            <template x-for="day in getDaysInMonth()" :key="day">
                                                <option :value="day" x-text="parseInt(day)"></option>
                                            </template>
                                        </select>
                                        <select x-model="form.birth_month" @change="syncBirthDate()" required>
                                            <option value="">Месяц</option>
                                            <option value="01">Январь</option>
                                            <option value="02">Февраль</option>
                                            <option value="03">Март</option>
                                            <option value="04">Апрель</option>
                                            <option value="05">Май</option>
                                            <option value="06">Июнь</option>
                                            <option value="07">Июль</option>
                                            <option value="08">Август</option>
                                            <option value="09">Сентябрь</option>
                                            <option value="10">Октябрь</option>
                                            <option value="11">Ноябрь</option>
                                            <option value="12">Декабрь</option>
                                        </select>
                                        <select x-model="form.birth_year" @change="syncBirthDate()" required>
                                            <option value="">Год</option>
                                            <template x-for="year in getYears()" :key="year">
                                                <option :value="year" x-text="year"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- Время рождения - Dropdowns -->
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Время рождения</label>
                                    <div class="time-dropdowns">
                                        <select x-model="form.birth_hour" @change="syncBirthTime()" :disabled="form.time_unknown" :class="{ 'opacity-50': form.time_unknown }">
                                            <option value="">Час</option>
                                            <template x-for="hour in getHours()" :key="hour">
                                                <option :value="hour" x-text="hour"></option>
                                            </template>
                                        </select>
                                        <select x-model="form.birth_minute" @change="syncBirthTime()" :disabled="form.time_unknown" :class="{ 'opacity-50': form.time_unknown }">
                                            <option value="">Минута</option>
                                            <template x-for="minute in getMinutes()" :key="minute">
                                                <option :value="minute" x-text="minute"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <label class="time-unknown-checkbox">
                                        <input type="checkbox" x-model="form.time_unknown" @change="syncBirthTime()">
                                        <span>Не знаю точное время (будет использовано 12:00)</span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Город рождения *</label>
                                    <div class="city-search-container">
                                        <input type="text"
                                               x-model="cityQuery"
                                               @input.debounce.300ms="searchCities()"
                                               @focus="showCityResults = cityResults.length > 0"
                                               placeholder="Начните вводить город..."
                                               required>
                                        <div class="city-results" x-show="showCityResults && cityResults.length > 0" @click.outside="showCityResults = false">
                                            <template x-for="city in cityResults" :key="city.id">
                                                <div class="city-result-item" @click="selectCity(city)">
                                                    <span x-text="city.name_ru || city.name"></span>
                                                    <span class="city-result-country" x-text="city.country"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                    <p class="city-hint">Можно вводить на русском или латиницей</p>
                                    <p class="city-warning" x-show="cityQuery && !form.partner_city_id">
                                        <i class="fas fa-exclamation-triangle"></i> Выберите город из списка
                                    </p>
                                    <!-- City Details -->
                                    <div class="city-details" x-show="selectedCity" x-cloak>
                                        <span x-text="selectedCity?.name_ru || selectedCity?.name"></span>
                                        <span class="city-details-sep">•</span>
                                        <span x-text="selectedCity?.country"></span>
                                        <span class="city-details-sep">•</span>
                                        <span class="city-details-coords" x-text="selectedCity ? selectedCity.latitude.toFixed(2) + ', ' + selectedCity.longitude.toFixed(2) : ''"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Terms Checkbox -->
                            <div class="form-row">
                                <label class="compat-checkbox">
                                    <input type="checkbox" x-model="form.terms_accepted" required>
                                    <span>Я подтверждаю согласие партнёра на обработку данных и принимаю <a href="/terms" target="_blank">условия</a> и <a href="/privacy" target="_blank">политику конфиденциальности</a></span>
                                </label>
                            </div>

                            <!-- Captcha -->
                            <div class="form-row" style="display: flex; justify-content: center;">
                                <div id="compat-captcha" class="smart-captcha" data-sitekey="{{ config('services.yandex_captcha.site_key') }}"></div>
                            </div>

                            <p x-show="error" x-text="error" style="color: var(--accent-red); font-size: 0.85rem; margin-bottom: 1rem;"></p>
                            <button type="submit" class="compat-submit" :disabled="submitting || !form.terms_accepted">
                                <span x-text="submitting ? 'Отправка...' : 'Отправить приглашение'"></span>
                            </button>
                        </form>

                    </div>
                </div>

                <!-- Has Partners - Show Result -->
                <div x-show="!loading && partners.length > 0 && !showForm && selectedPartner" x-cloak>
                    <!-- Partner Selector + Actions -->
                    <div class="compat-actions-bar">
                        <div class="compat-partner-selector">
                            <label>Партнёр:</label>
                            <select x-model="selectedPartnerId" @change="selectPartnerById($event.target.value)">
                                <template x-for="partner in partners" :key="partner.id">
                                    <option :value="partner.id" x-text="partner.partner_name + ' (' + (partner.overall_score ? partner.overall_score + '%' : 'ожидание') + ')'"></option>
                                </template>
                            </select>
                        </div>
                        <button @click="showForm = true; resetForm()" class="compat-new-partner-btn">
                            <i class="fas fa-plus"></i>
                            Новый партнёр
                        </button>
                    </div>

                    <div class="compat-result">
                    <div class="compat-result-header" :style="`--score: ${selectedPartner?.overall_score || 50}; --score-color: ${selectedPartner?.score_color || '#EAB308'}`">
                        <div class="compat-score-circle">
                            <span class="compat-score-value" :style="`color: ${selectedPartner?.score_color}`" x-text="selectedPartner?.overall_score || '—'"></span>
                        </div>
                        <p class="compat-score-label" x-text="selectedPartner?.score_description || 'Совместимость'"></p>
                        <div class="compat-partner-info">
                            <span>Партнёр: <strong x-text="selectedPartner?.partner_name"></strong></span>
                            <span>&bull;</span>
                            <span x-text="selectedPartner?.partner_birth_date"></span>
                            <span>&bull;</span>
                            <span class="compat-status-badge" :class="selectedPartner?.status === 'completed' ? 'compat-status-completed' : 'compat-status-pending'" x-text="selectedPartner?.status === 'completed' ? 'Подтверждено' : 'Ожидание партнёра'"></span>
                        </div>
                    </div>

                    <div class="compat-result-body">
                        <!-- Pending Message -->
                        <div x-show="selectedPartner?.status === 'pending'" style="text-align: center; padding: 1.5rem; background: rgba(234, 179, 8, 0.1); border-radius: 0.5rem; margin-bottom: 1rem;">
                            <p style="margin: 0 0 1rem; color: var(--accent-gold);">
                                Приглашение отправлено на <strong x-text="selectedPartner?.partner_email"></strong>.<br>
                                Полный результат будет доступен после подтверждения партнёром.
                            </p>
                            <button @click="loaded = false; loadCompatibility()" style="padding: 0.5rem 1rem; background: var(--accent-gold); color: #000; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; font-size: 0.85rem;">
                                <i class="fas fa-sync-alt" style="margin-right: 0.5rem;"></i>Обновить статус
                            </button>
                        </div>

                        <!-- Scores Grid (show if completed) -->
                        <div x-show="selectedPartner?.status === 'completed' && selectedPartner?.scores">
                            <div class="compat-scores-grid">
                                <template x-for="(score, key) in selectedPartner?.scores" :key="key">
                                    <div class="compat-score-item">
                                        <div class="compat-score-header">
                                            <span class="compat-score-name" x-text="score.label"></span>
                                            <span class="compat-score-val" x-text="score.value + '%'"></span>
                                        </div>
                                        <div class="compat-progress">
                                            <div class="compat-progress-fill" :style="`width: ${score.value}%; background: ${score.value >= 70 ? 'var(--accent-green)' : (score.value >= 50 ? 'var(--accent-gold)' : 'var(--accent-red)')}`"></div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- AI Report Loading State -->
                            <div x-show="selectedPartner?.ai_report_status === 'pending' || selectedPartner?.ai_report_status === 'processing'" class="compat-ai-loading" style="text-align: center; padding: 2rem; background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(99, 102, 241, 0.05)); border-radius: 0.75rem; margin: 1.5rem 0;">
                                <div style="display: flex; justify-content: center; margin-bottom: 1rem;">
                                    <div style="position: relative; width: 48px; height: 48px;">
                                        <div style="position: absolute; inset: 0; border: 2px solid rgba(99, 102, 241, 0.2); border-radius: 50%;"></div>
                                        <div style="position: absolute; inset: 0; border: 2px solid transparent; border-top-color: var(--accent-indigo); border-radius: 50%; animation: compat-spin 1s linear infinite;"></div>
                                        <div style="position: absolute; inset: 8px; background: rgba(99, 102, 241, 0.1); border-radius: 50%; animation: compat-pulse 2s ease-in-out infinite;"></div>
                                    </div>
                                </div>
                                <p style="color: var(--text-secondary); margin: 0 0 0.5rem; font-size: 0.95rem;">
                                    ИИ-астролог анализирует вашу совместимость...
                                </p>
                                <p style="color: var(--text-muted); margin: 0; font-size: 0.8rem;">
                                    Это может занять несколько секунд
                                </p>
                            </div>

                            <!-- AI Report Failed State -->
                            <div x-show="selectedPartner?.ai_report_status === 'failed'" style="text-align: center; padding: 1.5rem; background: rgba(239, 68, 68, 0.1); border-radius: 0.75rem; margin: 1.5rem 0; border: 1px solid rgba(239, 68, 68, 0.2);">
                                <p style="color: var(--accent-red); margin: 0 0 0.5rem;">
                                    Не удалось сгенерировать ИИ-анализ
                                </p>
                                <p style="color: var(--text-muted); margin: 0; font-size: 0.85rem;">
                                    Пожалуйста, попробуйте позже
                                </p>
                            </div>

                            <!-- Full Description (under scores) -->
                            <div x-show="selectedPartner?.ai_report?.full_description && selectedPartner?.ai_report_status === 'completed'" class="compat-full-description" style="margin: 1.5rem 0;">
                                <div class="compat-section-title" style="color: var(--accent-indigo); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                    Анализ совместимости от ИИ-астролога
                                </div>
                                <div class="compat-description-text" x-html="formatDescription(selectedPartner?.ai_report?.full_description)" style="color: var(--text-secondary); line-height: 1.8; font-size: 0.95rem; background: var(--bg-tertiary); padding: 1.5rem; border-radius: 0.75rem;"></div>
                            </div>

                            <!-- AI Report Sections -->
                            <template x-if="selectedPartner?.ai_report && selectedPartner?.ai_report_status === 'completed'">
                                <div>
                                    <!-- Strengths -->
                                    <div class="compat-section compat-strengths" x-show="selectedPartner?.ai_report?.strengths && selectedPartner?.ai_report?.strengths.length > 0">
                                        <div class="compat-section-title" style="color: var(--accent-green);">
                                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
                                            Сильные стороны
                                        </div>
                                        <template x-for="s in selectedPartner?.ai_report?.strengths" :key="s">
                                            <div class="compat-list-item">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                <span x-text="s"></span>
                                            </div>
                                        </template>
                                    </div>

                                    <!-- Challenges -->
                                    <div class="compat-section compat-challenges" x-show="selectedPartner?.ai_report?.challenges && selectedPartner?.ai_report?.challenges.length > 0">
                                        <div class="compat-section-title" style="color: var(--accent-red);">
                                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                            Потенциальные вызовы
                                        </div>
                                        <template x-for="c in selectedPartner?.ai_report?.challenges" :key="c">
                                            <div class="compat-list-item">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"/></svg>
                                                <span x-text="c"></span>
                                            </div>
                                        </template>
                                    </div>

                                    <!-- Recommendations -->
                                    <div class="compat-section" x-show="selectedPartner?.ai_report?.recommendations && selectedPartner?.ai_report?.recommendations.length > 0">
                                        <div class="compat-section-title" style="color: var(--accent-gold);">
                                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                                            Рекомендации
                                        </div>
                                        <template x-for="r in selectedPartner?.ai_report?.recommendations" :key="r">
                                            <div class="compat-list-item" style="background: rgba(234, 179, 8, 0.1);">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--accent-gold);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                                <span x-text="r"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
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

        // Send message with queue polling
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

                if (response.ok) {
                    const data = await response.json();
                    // Poll for response
                    await pollChatResponse(data.message_id);
                } else {
                    hideTyping();
                    addChatMessage('Ошибка. Попробуйте позже.', 'assistant');
                }
            } catch (error) {
                hideTyping();
                addChatMessage('Ошибка соединения.', 'assistant');
            }

            chatSend.disabled = false;
        }

        // Poll for chat response
        async function pollChatResponse(messageId) {
            const maxAttempts = 120; // 120 seconds (2 minutes) max
            let attempts = 0;

            while (attempts < maxAttempts) {
                try {
                    const response = await fetch(`/charts/{{ $chart->id }}/chat/${messageId}/status`, {
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });

                    if (response.ok) {
                        const data = await response.json();

                        if (data.status === 'completed' || data.status === 'failed') {
                            hideTyping();
                            addChatMessage(data.content || 'Ошибка при получении ответа.', 'assistant');
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
            addChatMessage('Время ожидания истекло. Попробуйте позже.', 'assistant');
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

        // Compatibility Tab Alpine Component
        function compatibilityTab() {
            return {
                loading: true,
                partners: [],
                selectedPartner: null,
                selectedPartnerId: null,
                showForm: false,
                form: {
                    partner_name: '',
                    partner_email: '',
                    partner_gender: '',
                    partner_birth_date: '',
                    partner_birth_time: '',
                    partner_city_id: null,
                    terms_accepted: false,
                    // Dropdown values
                    birth_day: '',
                    birth_month: '',
                    birth_year: '',
                    birth_hour: '',
                    birth_minute: '',
                    time_unknown: false
                },
                cityQuery: '',
                cityResults: [],
                showCityResults: false,
                selectedCity: null,
                submitting: false,
                error: '',
                loaded: false,
                aiPollInterval: null,

                async loadCompatibility() {
                    if (this.loaded) return;
                    this.loading = true;

                    try {
                        const response = await fetch(`/charts/{{ $chart->id }}/compatibility`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        if (response.ok) {
                            const data = await response.json();
                            this.partners = data.partners || [];
                            // Auto-select first partner if exists
                            if (this.partners.length > 0 && !this.selectedPartner) {
                                this.selectedPartner = this.partners[0];
                                this.selectedPartnerId = this.partners[0].id;
                            }
                            // Start polling for AI report if needed
                            this.startAiReportPolling();
                        }
                    } catch (e) {
                        console.error('Failed to load compatibility:', e);
                    }

                    this.loading = false;
                    this.loaded = true;
                },

                startAiReportPolling() {
                    // Clear any existing interval
                    if (this.aiPollInterval) {
                        clearInterval(this.aiPollInterval);
                    }

                    // Check if any partner needs AI report polling
                    const needsPolling = this.partners.some(p =>
                        p.ai_report_status === 'pending' || p.ai_report_status === 'processing'
                    );

                    if (!needsPolling) return;

                    // Poll every 3 seconds
                    this.aiPollInterval = setInterval(async () => {
                        await this.pollAiReportStatus();
                    }, 3000);
                },

                async pollAiReportStatus() {
                    const partnersNeedingUpdate = this.partners.filter(p =>
                        p.ai_report_status === 'pending' || p.ai_report_status === 'processing'
                    );

                    if (partnersNeedingUpdate.length === 0) {
                        if (this.aiPollInterval) {
                            clearInterval(this.aiPollInterval);
                            this.aiPollInterval = null;
                        }
                        return;
                    }

                    for (const partner of partnersNeedingUpdate) {
                        try {
                            const response = await fetch(`/compatibility/${partner.id}/ai-status`, {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            });

                            if (response.ok) {
                                const data = await response.json();
                                // Update the partner in our list
                                const idx = this.partners.findIndex(p => p.id === partner.id);
                                if (idx !== -1) {
                                    this.partners[idx].ai_report_status = data.ai_report_status;
                                    if (data.ai_report) {
                                        this.partners[idx].ai_report = data.ai_report;
                                    }
                                    // Update selectedPartner if it's the same
                                    if (this.selectedPartner?.id === partner.id) {
                                        this.selectedPartner = {...this.selectedPartner, ...this.partners[idx]};
                                    }
                                }
                            }
                        } catch (e) {
                            console.error('Failed to poll AI status:', e);
                        }
                    }
                },

                selectPartner(partner) {
                    this.selectedPartner = partner;
                    this.selectedPartnerId = partner.id;
                },

                selectPartnerById(id) {
                    const partner = this.partners.find(p => p.id == id);
                    if (partner) {
                        this.selectedPartner = partner;
                    }
                },

                async searchCities() {
                    if (this.cityQuery.length < 2) {
                        this.cityResults = [];
                        return;
                    }

                    try {
                        const response = await fetch(`/cities/search/${encodeURIComponent(this.cityQuery)}`);
                        if (response.ok) {
                            this.cityResults = await response.json();
                            this.showCityResults = true;
                        }
                    } catch (e) {
                        console.error('City search failed:', e);
                    }
                },

                selectCity(city) {
                    this.form.partner_city_id = city.id;
                    this.cityQuery = city.name_ru || city.name;
                    this.selectedCity = city;
                    this.showCityResults = false;
                    this.cityResults = [];
                },

                syncBirthDate() {
                    if (this.form.birth_day && this.form.birth_month && this.form.birth_year) {
                        this.form.partner_birth_date = `${this.form.birth_year}-${this.form.birth_month}-${this.form.birth_day}`;
                    } else {
                        this.form.partner_birth_date = '';
                    }
                },

                syncBirthTime() {
                    if (this.form.time_unknown) {
                        this.form.partner_birth_time = '12:00';
                    } else if (this.form.birth_hour && this.form.birth_minute) {
                        this.form.partner_birth_time = `${this.form.birth_hour}:${this.form.birth_minute}`;
                    } else {
                        this.form.partner_birth_time = '';
                    }
                },

                getDaysInMonth() {
                    const days = [];
                    for (let d = 1; d <= 31; d++) {
                        days.push(String(d).padStart(2, '0'));
                    }
                    return days;
                },

                getYears() {
                    const years = [];
                    const currentYear = new Date().getFullYear();
                    for (let y = currentYear; y >= 1920; y--) {
                        years.push(y);
                    }
                    return years;
                },

                getHours() {
                    const hours = [];
                    for (let h = 0; h <= 23; h++) {
                        hours.push(String(h).padStart(2, '0'));
                    }
                    return hours;
                },

                getMinutes() {
                    const minutes = [];
                    for (let m = 0; m <= 59; m += 5) {
                        minutes.push(String(m).padStart(2, '0'));
                    }
                    return minutes;
                },

                async submitForm() {
                    this.error = '';

                    // Validate
                    if (!this.form.partner_name || !this.form.partner_email || !this.form.partner_gender ||
                        !this.form.partner_birth_date || !this.form.partner_city_id) {
                        this.error = 'Пожалуйста, заполните все обязательные поля';
                        return;
                    }

                    if (!this.form.terms_accepted) {
                        this.error = 'Пожалуйста, подтвердите согласие с условиями';
                        return;
                    }

                    // Get captcha token
                    const captchaInput = document.querySelector('#compat-captcha input[name="smart-token"]');
                    const captchaToken = captchaInput ? captchaInput.value : '';
                    if (!captchaToken) {
                        this.error = 'Пожалуйста, пройдите проверку капчи';
                        return;
                    }

                    this.submitting = true;

                    try {
                        const response = await fetch(`/charts/{{ $chart->id }}/compatibility`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({...this.form, captcha_token: captchaToken})
                        });

                        const data = await response.json();

                        if (data.success) {
                            // Reload partners list and select new partner
                            this.loaded = false;
                            this.resetForm();
                            await this.loadCompatibility();
                            // Select the newly added partner (first in list) and show results
                            if (this.partners.length > 0) {
                                this.selectedPartner = this.partners[0];
                                this.selectedPartnerId = this.partners[0].id;
                            }
                            this.showForm = false;
                        } else {
                            this.error = data.error || 'Произошла ошибка. Попробуйте ещё раз.';
                            if (window.smartCaptcha) window.smartCaptcha.reset();
                        }
                    } catch (e) {
                        this.error = 'Ошибка соединения. Попробуйте ещё раз.';
                        if (window.smartCaptcha) window.smartCaptcha.reset();
                    }

                    this.submitting = false;
                },

                resetForm() {
                    this.form = {
                        partner_name: '',
                        partner_email: '',
                        partner_gender: '',
                        partner_birth_date: '',
                        partner_birth_time: '',
                        partner_city_id: null,
                        terms_accepted: false,
                        birth_day: '',
                        birth_month: '',
                        birth_year: '',
                        birth_hour: '',
                        birth_minute: '',
                        time_unknown: false
                    };
                    this.cityQuery = '';
                    this.selectedCity = null;
                    this.error = '';
                    if (window.smartCaptcha) window.smartCaptcha.reset();
                },

                startNew() {
                    this.selectedPartner = null;
                    this.resetForm();
                },

                formatDescription(text) {
                    if (!text) return '';
                    // Handle both escaped \n and real newlines
                    return text
                        .replace(/\\n\\n/g, '</p><p style="margin: 0 0 1rem 0;">')
                        .replace(/\\n/g, '<br>')
                        .replace(/\n\n/g, '</p><p style="margin: 0 0 1rem 0;">')
                        .replace(/\n/g, '<br>')
                        .replace(/^/, '<p style="margin: 0 0 1rem 0;">')
                        .replace(/$/, '</p>');
                }
            };
        }
    </script>
</body>
</html>
