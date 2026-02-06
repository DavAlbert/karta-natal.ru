<!DOCTYPE html>
<html lang="ru" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Совместимость: {{ $result->person1['name'] }} & Партнёр — Karta-Natal.ru</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

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

        .container { max-width: 1000px; margin: 0 auto; padding: 1rem; }

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
        .nav-link.active { color: var(--accent-gold); }

        /* Main content */
        .main-content { padding-top: 5rem; }

        /* Hero Score Section */
        .score-hero {
            text-align: center;
            padding: 3rem 1rem;
            background: linear-gradient(180deg, rgba(99, 102, 241, 0.1) 0%, transparent 100%);
            border-radius: 1rem;
            margin-bottom: 2rem;
        }
        .score-names {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        .person-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 2rem;
        }
        .person-badge i { font-size: 1rem; }
        .person-badge.person1 i { color: var(--accent-indigo); }
        .person-badge.person2 i { color: var(--accent-pink); }
        .heart-divider {
            width: 3rem;
            height: 3rem;
            background: linear-gradient(135deg, var(--accent-pink), var(--accent-red));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        .score-circle {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: conic-gradient(var(--score-color) calc(var(--score) * 3.6deg), rgba(255,255,255,0.1) 0);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            position: relative;
            margin-bottom: 1.5rem;
        }
        .score-circle::before {
            content: '';
            position: absolute;
            inset: 12px;
            background: var(--bg-primary);
            border-radius: 50%;
        }
        .score-value {
            position: relative;
            font-size: 3.5rem;
            font-weight: 700;
            color: var(--score-color);
        }
        .score-label {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-family: 'Cinzel', serif;
        }
        .score-sublabel {
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        /* Cards */
        .card {
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
            overflow: hidden;
        }
        .card-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
            background: rgba(255,255,255,0.02);
        }
        .card-header i { font-size: 1.1rem; }
        .card-header h2 {
            font-size: 1rem;
            font-weight: 600;
            margin: 0;
        }
        .card-body { padding: 1.25rem; }

        /* Progress Bars */
        .progress-item { margin-bottom: 1.25rem; }
        .progress-item:last-child { margin-bottom: 0; }
        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        .progress-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .progress-label i { font-size: 0.875rem; opacity: 0.7; }
        .progress-value {
            font-size: 0.875rem;
            font-weight: 600;
        }
        .progress-bar {
            height: 10px;
            background: var(--bg-tertiary);
            border-radius: 5px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            border-radius: 5px;
            transition: width 1s ease-out;
        }

        /* Analysis Sections */
        .analysis-section { margin-bottom: 1.5rem; }
        .analysis-section:last-child { margin-bottom: 0; }
        .analysis-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--accent-gold);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .analysis-text {
            font-size: 0.9rem;
            color: var(--text-secondary);
            line-height: 1.8;
        }

        /* Lists */
        .list-item {
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            line-height: 1.6;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }
        .list-item:last-child { margin-bottom: 0; }
        .list-item i { margin-top: 0.2rem; flex-shrink: 0; }
        .strengths .list-item {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.2);
            color: var(--text-secondary);
        }
        .strengths .list-item i { color: var(--accent-green); }
        .challenges .list-item {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: var(--text-secondary);
        }
        .challenges .list-item i { color: var(--accent-red); }
        .recommendations .list-item {
            background: rgba(251, 191, 36, 0.1);
            border: 1px solid rgba(251, 191, 36, 0.2);
            color: var(--text-secondary);
        }
        .recommendations .list-item i { color: var(--accent-gold); }

        /* Charts Grid */
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        @media (max-width: 640px) { .charts-grid { grid-template-columns: 1fr; } }
        .chart-box {
            background: var(--bg-tertiary);
            border-radius: 0.75rem;
            padding: 1rem;
            border: 1px solid var(--border);
        }
        .chart-title {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .chart-title.person1 { color: var(--accent-indigo); }
        .chart-title.person2 { color: var(--accent-pink); }
        .planet-row {
            display: flex;
            justify-content: space-between;
            padding: 0.4rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            font-size: 0.8rem;
        }
        .planet-row:last-child { border-bottom: none; }
        .planet-name { color: var(--text-muted); }
        .planet-value { font-weight: 500; }

        /* Synastry Aspects */
        .aspects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 0.75rem;
        }
        .aspect-item {
            padding: 0.75rem;
            background: var(--bg-tertiary);
            border-radius: 0.5rem;
            font-size: 0.8rem;
            border-left: 3px solid var(--border);
        }
        .aspect-item.harmony { border-left-color: var(--accent-green); }
        .aspect-item.tension { border-left-color: var(--accent-red); }
        .aspect-planets { font-weight: 600; margin-bottom: 0.25rem; }
        .aspect-type { color: var(--text-muted); font-size: 0.75rem; }

        /* Actions */
        .actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin: 2rem 0;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: var(--bg-tertiary);
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            color: var(--text-secondary);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn:hover {
            background: var(--bg-secondary);
            border-color: var(--border-light);
            color: var(--text-primary);
        }
        .btn-primary {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-color: transparent;
            color: white;
        }
        .btn-primary:hover {
            opacity: 0.9;
            color: white;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 2rem 0;
            border-top: 1px solid var(--border);
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .card { animation: fadeInUp 0.4s ease-out; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="{{ route('welcome') }}" class="logo">КАРТА<span>НАТАЛ</span></a>
            <div class="nav-links">
                <a href="{{ route('compatibility') }}" class="nav-link active">
                    <i class="fas fa-heart"></i>
                    <span>Совместимость</span>
                </a>
                <a href="{{ route('welcome') }}" class="nav-link">
                    <i class="fas fa-home"></i>
                    <span>Главная</span>
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
                <div class="score-names">
                    <div class="person-badge person1">
                        <i class="fas fa-user"></i>
                        <span>{{ $result->person1['name'] }}</span>
                    </div>
                    <div class="heart-divider">
                        <i class="fas fa-heart"></i>
                    </div>
                    <div class="person-badge person2">
                        <i class="fas fa-heart"></i>
                        <span>Партнёр</span>
                    </div>
                </div>

                <div class="score-circle">
                    <span class="score-value">{{ $score }}</span>
                </div>
                <p class="score-label">{{ $result->score_description }}</p>
                <p class="score-sublabel">из 100 баллов совместимости</p>
            </div>

            @if($report = $result->ai_report)
            <!-- Detailed Scores -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-chart-bar" style="color: var(--accent-gold);"></i>
                    <h2>Детальная оценка</h2>
                </div>
                <div class="card-body">
                    @php
                        $scores = [
                            ['label' => 'Общая совместимость', 'icon' => 'fa-heart', 'value' => $report['overall_score'] ?? 5],
                            ['label' => 'Эмоциональная связь', 'icon' => 'fa-water', 'value' => $report['emotional_score'] ?? 5],
                            ['label' => 'Коммуникация', 'icon' => 'fa-comments', 'value' => $report['communication_score'] ?? 5],
                            ['label' => 'Романтика и страсть', 'icon' => 'fa-fire', 'value' => $report['romantic_score'] ?? 5],
                            ['label' => 'Ценности и цели', 'icon' => 'fa-bullseye', 'value' => $report['values_score'] ?? 5],
                            ['label' => 'Потенциал роста', 'icon' => 'fa-seedling', 'value' => $report['growth_score'] ?? 5],
                        ];
                    @endphp

                    @foreach($scores as $item)
                    <div class="progress-item">
                        <div class="progress-header">
                            <span class="progress-label">
                                <i class="fas {{ $item['icon'] }}"></i>
                                {{ $item['label'] }}
                            </span>
                            <span class="progress-value" style="color: {{ $item['value'] >= 7 ? 'var(--accent-green)' : ($item['value'] >= 4 ? 'var(--accent-orange)' : 'var(--accent-red)') }}">
                                {{ $item['value'] }}/10
                            </span>
                        </div>
                        <div class="progress-bar">
                            @php
                                $fillColor = $item['value'] >= 7 ? 'var(--accent-green)' : ($item['value'] >= 4 ? 'var(--accent-orange)' : 'var(--accent-red)');
                            @endphp
                            <div class="progress-fill" style="width: {{ $item['value'] * 10 }}%; background: {{ $fillColor }};"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Detailed Analysis -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-scroll" style="color: var(--accent-gold);"></i>
                    <h2>Подробный анализ</h2>
                </div>
                <div class="card-body">
                    @if(!empty($report['overall_analysis']))
                    <div class="analysis-section">
                        <p class="analysis-title"><i class="fas fa-heart"></i> Общая совместимость</p>
                        <p class="analysis-text">{{ $report['overall_analysis'] }}</p>
                    </div>
                    @endif

                    @if(!empty($report['emotional_analysis']))
                    <div class="analysis-section">
                        <p class="analysis-title"><i class="fas fa-water"></i> Эмоциональная связь</p>
                        <p class="analysis-text">{{ $report['emotional_analysis'] }}</p>
                    </div>
                    @endif

                    @if(!empty($report['communication_analysis']))
                    <div class="analysis-section">
                        <p class="analysis-title"><i class="fas fa-comments"></i> Коммуникация</p>
                        <p class="analysis-text">{{ $report['communication_analysis'] }}</p>
                    </div>
                    @endif

                    @if(!empty($report['romantic_analysis']))
                    <div class="analysis-section">
                        <p class="analysis-title"><i class="fas fa-fire"></i> Романтика и страсть</p>
                        <p class="analysis-text">{{ $report['romantic_analysis'] }}</p>
                    </div>
                    @endif

                    @if(!empty($report['values_analysis']))
                    <div class="analysis-section">
                        <p class="analysis-title"><i class="fas fa-bullseye"></i> Ценности и цели</p>
                        <p class="analysis-text">{{ $report['values_analysis'] }}</p>
                    </div>
                    @endif

                    @if(!empty($report['growth_analysis']))
                    <div class="analysis-section">
                        <p class="analysis-title"><i class="fas fa-seedling"></i> Потенциал роста</p>
                        <p class="analysis-text">{{ $report['growth_analysis'] }}</p>
                    </div>
                    @endif
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
                        @foreach($report['strengths'] as $strength)
                        <div class="list-item">
                            <i class="fas fa-check-circle"></i>
                            <span>{{ $strength }}</span>
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
                        @foreach($report['challenges'] as $challenge)
                        <div class="list-item">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $challenge }}</span>
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
                        @foreach($report['recommendations'] as $rec)
                        <div class="list-item">
                            <i class="fas fa-lightbulb"></i>
                            <span>{{ $rec }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
            @endif

            <!-- Synastry Aspects -->
            @if($synastry = $result->synastry)
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-link" style="color: var(--accent-gold);"></i>
                    <h2>Синастрия: ключевые аспекты</h2>
                </div>
                <div class="card-body">
                    <div class="aspects-grid">
                        @foreach($synastry['aspects'] ?? [] as $aspect)
                        <div class="aspect-item {{ $aspect['nature'] }}">
                            <div class="aspect-planets">{{ $aspect['planet1'] }} → {{ $aspect['planet2'] }}</div>
                            <div class="aspect-type">{{ $aspect['type'] }} ({{ $aspect['orb'] }}°)</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Charts Summary -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-globe" style="color: var(--accent-gold);"></i>
                    <h2>Натальные карты</h2>
                </div>
                <div class="card-body">
                    <div class="charts-grid">
                        <div class="chart-box">
                            <p class="chart-title person1"><i class="fas fa-user"></i> {{ $result->person1['name'] }}</p>
                            @php $chart1 = $result->chart1; @endphp
                            <div class="planet-row"><span class="planet-name">Солнце</span><span class="planet-value">{{ $chart1['sun']['sign'] ?? '' }} {{ floor($chart1['sun']['degree'] ?? 0) }}°</span></div>
                            <div class="planet-row"><span class="planet-name">Луна</span><span class="planet-value">{{ $chart1['moon']['sign'] ?? '' }} {{ floor($chart1['moon']['degree'] ?? 0) }}°</span></div>
                            <div class="planet-row"><span class="planet-name">Меркурий</span><span class="planet-value">{{ $chart1['mercury']['sign'] ?? '' }} {{ floor($chart1['mercury']['degree'] ?? 0) }}°</span></div>
                            <div class="planet-row"><span class="planet-name">Венера</span><span class="planet-value">{{ $chart1['venus']['sign'] ?? '' }} {{ floor($chart1['venus']['degree'] ?? 0) }}°</span></div>
                            <div class="planet-row"><span class="planet-name">Марс</span><span class="planet-value">{{ $chart1['mars']['sign'] ?? '' }} {{ floor($chart1['mars']['degree'] ?? 0) }}°</span></div>
                            <div class="planet-row"><span class="planet-name">Асцендент</span><span class="planet-value">{{ $chart1['ascendant']['sign'] ?? '' }} {{ floor($chart1['ascendant']['degree'] ?? 0) }}°</span></div>
                        </div>

                        <div class="chart-box">
                            <p class="chart-title person2"><i class="fas fa-heart"></i> Партнёр</p>
                            @php $chart2 = $result->chart2; @endphp
                            <div class="planet-row"><span class="planet-name">Солнце</span><span class="planet-value">{{ $chart2['sun']['sign'] ?? '' }} {{ floor($chart2['sun']['degree'] ?? 0) }}°</span></div>
                            <div class="planet-row"><span class="planet-name">Луна</span><span class="planet-value">{{ $chart2['moon']['sign'] ?? '' }} {{ floor($chart2['moon']['degree'] ?? 0) }}°</span></div>
                            <div class="planet-row"><span class="planet-name">Меркурий</span><span class="planet-value">{{ $chart2['mercury']['sign'] ?? '' }} {{ floor($chart2['mercury']['degree'] ?? 0) }}°</span></div>
                            <div class="planet-row"><span class="planet-name">Венера</span><span class="planet-value">{{ $chart2['venus']['sign'] ?? '' }} {{ floor($chart2['venus']['degree'] ?? 0) }}°</span></div>
                            <div class="planet-row"><span class="planet-name">Марс</span><span class="planet-value">{{ $chart2['mars']['sign'] ?? '' }} {{ floor($chart2['mars']['degree'] ?? 0) }}°</span></div>
                            <div class="planet-row"><span class="planet-name">Асцендент</span><span class="planet-value">{{ $chart2['ascendant']['sign'] ?? '' }} {{ floor($chart2['ascendant']['degree'] ?? 0) }}°</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="actions">
                <a href="{{ route('compatibility') }}" class="btn btn-primary">
                    <i class="fas fa-heart"></i>
                    Новый расчёт
                </a>
                <a href="{{ route('welcome') }}" class="btn">
                    <i class="fas fa-home"></i>
                    На главную
                </a>
            </div>

            <!-- Footer -->
            <footer class="footer">
                <p>&copy; {{ date('Y') }} Karta-Natal.ru — Натальная карта онлайн бесплатно</p>
            </footer>
        </div>
    </main>

    <script>
        // Animate progress bars
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.progress-fill').forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0';
                setTimeout(() => {
                    bar.style.width = width;
                }, 300);
            });
        });
    </script>
</body>
</html>
