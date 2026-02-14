<!DOCTYPE html>
<html lang="ru" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Совместимость — Karta-Natal.ru</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- FontAwesome -->
    <script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.1.0/js/all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.1.0/css/fontawesome.min.css" rel="stylesheet">

    <style>
        :root {
            --bg-primary: #0B1120;
            --bg-secondary: #111827;
            --bg-tertiary: #1e293b;
            --border: #334155;
            --border-light: #475569;
            --text-primary: #F8FAFC;
            --text-secondary: #94A3B8;
            --text-muted: #64748b;
            --accent-gold: #fbbf24;
            --accent-green: #22C55E;
            --accent-red: #EF4444;
            --accent-orange: #F97316;
            --accent-indigo: #818CF8;
            --accent-pink: #EC4899;
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

        .text-gold { color: var(--accent-gold); }

        .container { max-width: 900px; margin: 0 auto; padding: 1rem; }

        /* Navbar */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
            border-bottom: 1px solid rgba(99, 102, 241, 0.2);
            background: rgba(11, 17, 32, 0.95);
            backdrop-filter: blur(8px);
        }
        .navbar-inner {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 4rem;
        }
        .logo {
            font-family: 'Cinzel', serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            letter-spacing: 0.1em;
        }
        .logo span { color: var(--accent-gold); }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.875rem;
            transition: color 0.2s;
        }
        .nav-link:hover { color: var(--text-primary); }

        /* Main content */
        .main-content { padding-top: 5rem; }

        /* Score Hero Section */
        .score-hero {
            text-align: center;
            padding: 2.5rem 1rem;
            background: linear-gradient(180deg, rgba(99, 102, 241, 0.1) 0%, transparent 100%);
            border-radius: 1rem;
            margin-bottom: 1.5rem;
        }

        .score-circle {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: conic-gradient(var(--score-color) calc(var(--score) * 3.6deg), rgba(255,255,255,0.1) 0);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            position: relative;
            margin-bottom: 1rem;
        }
        .score-circle::before {
            content: '';
            position: absolute;
            inset: 10px;
            background: var(--bg-primary);
            border-radius: 50%;
        }
        .score-value {
            position: relative;
            font-size: 3rem;
            font-weight: 700;
            color: var(--score-color);
        }
        .score-label {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-family: 'Cinzel', serif;
        }

        /* Cards */
        .card {
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            margin-bottom: 1rem;
            overflow: hidden;
        }
        .card-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 1rem;
            border-bottom: 1px solid var(--border);
            background: rgba(255,255,255,0.02);
        }
        .card-header i { font-size: 1rem; }
        .card-header h2 {
            font-size: 0.9rem;
            font-weight: 600;
            margin: 0;
        }
        .card-body { padding: 1rem; }

        /* Progress Bars */
        .progress-item { margin-bottom: 1rem; }
        .progress-item:last-child { margin-bottom: 0; }
        .progress-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.35rem;
        }
        .progress-label { font-size: 0.8rem; color: var(--text-secondary); }
        .progress-value { font-size: 0.8rem; font-weight: 600; color: var(--accent-gold); }
        .progress-bar {
            height: 8px;
            background: var(--bg-tertiary);
            border-radius: 4px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 1s ease-out;
        }

        /* Lists */
        .list-item {
            padding: 0.625rem 0.875rem;
            margin-bottom: 0.4rem;
            border-radius: 0.5rem;
            font-size: 0.8rem;
            color: var(--text-secondary);
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            line-height: 1.5;
        }
        .list-item:last-child { margin-bottom: 0; }
        .list-item i { margin-top: 0.15rem; flex-shrink: 0; font-size: 0.75rem; }
        .strengths .list-item {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.2);
        }
        .strengths .list-item i { color: var(--accent-green); }
        .challenges .list-item {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
        .challenges .list-item i { color: var(--accent-red); }
        .recommendations .list-item {
            background: rgba(251, 191, 36, 0.1);
            border: 1px solid rgba(251, 191, 36, 0.2);
        }
        .recommendations .list-item i { color: var(--accent-gold); }

        /* CTA Section */
        .cta-section {
            text-align: center;
            padding: 2rem 1.5rem;
            background: linear-gradient(135deg, rgba(124, 58, 237, 0.15), rgba(236, 72, 153, 0.1));
            border-radius: 1rem;
            border: 1px solid rgba(139, 92, 246, 0.2);
            margin-top: 1.5rem;
        }
        .cta-section h3 {
            margin: 0 0 0.5rem;
            font-size: 1.1rem;
            font-family: 'Cinzel', serif;
        }
        .cta-section p {
            margin: 0 0 1.25rem;
            color: var(--text-secondary);
            font-size: 0.875rem;
        }
        .cta-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 1.75rem;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            font-weight: 600;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: all 0.2s;
        }
        .cta-btn:hover { opacity: 0.9; transform: translateY(-1px); }

        /* Footer */
        .footer {
            text-align: center;
            padding: 2rem 0;
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .card { animation: fadeInUp 0.3s ease-out; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="{{ route('welcome') }}" class="logo">КАРТА<span>НАТАЛ</span></a>
            <div class="nav-links">
                <a href="{{ route('welcome') }}" class="nav-link">
                    <i class="fas fa-star"></i>
                    <span>Натальная карта</span>
                </a>
            </div>
        </div>
    </nav>

    <main class="main-content">
        <div class="container">
            @php
                $score = $result->score;
                $scoreColor = $score >= 70 ? 'var(--accent-green)' : ($score >= 50 ? 'var(--accent-orange)' : 'var(--accent-red)');
            @endphp

            <!-- Score Hero -->
            <div class="score-hero" style="--score: {{ $score }}; --score-color: {{ $scoreColor }}">
                <div class="score-circle">
                    <span class="score-value">{{ $score }}</span>
                </div>
                <p class="score-label">{{ $result->score_description }}</p>
            </div>

            @if($report = $result->ai_report)
            <!-- Scores -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-chart-bar" style="color: var(--accent-gold);"></i>
                    <h2>Оценка совместимости</h2>
                </div>
                <div class="card-body">
                    @php $scores = [
                        ['label' => 'Общая', 'value' => $report['overall_score'] ?? 5],
                        ['label' => 'Эмоции', 'value' => $report['emotional_score'] ?? 5],
                        ['label' => 'Общение', 'value' => $report['communication_score'] ?? 5],
                        ['label' => 'Романтика', 'value' => $report['romantic_score'] ?? 5],
                        ['label' => 'Ценности', 'value' => $report['values_score'] ?? 5],
                        ['label' => 'Рост', 'value' => $report['growth_score'] ?? 5],
                    ]; @endphp

                    @foreach($scores as $item)
                    <div class="progress-item">
                        <div class="progress-header">
                            <span class="progress-label">{{ $item['label'] }}</span>
                            <span class="progress-value">{{ $item['value'] }}/10</span>
                        </div>
                        <div class="progress-bar">
                            @php $c = $item['value'] >= 7 ? 'var(--accent-green)' : ($item['value'] >= 4 ? 'var(--accent-orange)' : 'var(--accent-red)'); @endphp
                            <div class="progress-fill" style="width: {{ $item['value'] * 10 }}%; background: {{ $c }};"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Strengths -->
            @if(!empty($report['strengths']))
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-star" style="color: var(--accent-green);"></i>
                    <h2>Сильные стороны</h2>
                </div>
                <div class="card-body">
                    <div class="strengths">
                        @foreach($report['strengths'] as $s)
                        <div class="list-item">
                            <i class="fas fa-check"></i>
                            <span>{{ $s }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Challenges -->
            @if(!empty($report['challenges']))
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-exclamation-triangle" style="color: var(--accent-red);"></i>
                    <h2>Вызовы</h2>
                </div>
                <div class="card-body">
                    <div class="challenges">
                        @foreach($report['challenges'] as $c)
                        <div class="list-item">
                            <i class="fas fa-exclamation"></i>
                            <span>{{ $c }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Recommendations -->
            @if(!empty($report['recommendations']))
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-lightbulb" style="color: var(--accent-gold);"></i>
                    <h2>Рекомендации</h2>
                </div>
                <div class="card-body">
                    <div class="recommendations">
                        @foreach($report['recommendations'] as $r)
                        <div class="list-item">
                            <i class="fas fa-lightbulb"></i>
                            <span>{{ $r }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
            @endif

            <!-- CTA -->
            <div class="cta-section">
                <h3>Хотите полный анализ?</h3>
                <p>Создайте свою натальную карту и получите детальный анализ от ИИ-астролога</p>
                <a href="{{ route('welcome') }}" class="cta-btn">
                    <i class="fas fa-star"></i>
                    Создать натальную карту
                </a>
            </div>

            <!-- Footer -->
            <footer class="footer">
                <p>&copy; {{ date('Y') }} Karta-Natal.ru — Натальная карта онлайн бесплатно</p>
            </footer>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.progress-fill').forEach(bar => {
                const w = bar.style.width;
                bar.style.width = '0';
                setTimeout(() => bar.style.width = w, 300);
            });
        });
    </script>
</body>
</html>
