<!DOCTYPE html>
<html lang="ru" class="scroll-smooth" style="overflow-x: hidden;">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <title>Совместимость по дате рождения онлайн — Анализ натальных карт | Karta-Natal.ru</title>
    <meta name="description" content="Рассчитайте совместимость по дате рождения онлайн бесплатно. Два натальных карты, синастрия, оценка совместимости от 1 до 10. Подробный анализ эмоциональной, романтической и духовной совместимости с ИИ.">
    <meta name="keywords" content="совместимость по дате рождения, совместимость натальных карт, синастрия онлайн, гороскоп совместимости, совместимость знаков зодиака, совместимость мужчины и женщины, астрологическая совместимость">
    <meta name="author" content="SMART CREATOR AI LLC">
    <meta name="robots" content="index, follow, max-image-preview:large">
    <link rel="canonical" href="{{ route('compatibility') }}">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('compatibility') }}">
    <meta property="og:title" content="Совместимость по дате рождения — Анализ натальных карт">
    <meta property="og:description" content="Узнайте вашу совместимость с партнёром. Два натальных карты, синастрия, подробный анализ и рекомендации от ИИ-астролога.">
    <meta property="og:image" content="{{ asset('images/compatibility-og.png') }}">
    <meta property="og:locale" content="ru_RU">
    <meta property="og:site_name" content="Karta-Natal.ru">

    <!-- Structured Data -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "WebApplication",
        "name": "Совместимость по дате рождения",
        "url": "https://karta-natal.ru/compatibility",
        "description": "Бесплатный онлайн сервис для расчёта совместимости по натальным картам с подробным анализом синастрии",
        "applicationCategory": "LifestyleApplication",
        "operatingSystem": "Web",
        "offers": {
            "@@type": "Offer",
            "price": "0",
            "priceCurrency": "RUB"
        }
    }
    </script>

    <script src="https://js.hcaptcha.com/1/api.js" async defer></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.1.0/js/all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.1.0/css/fontawesome.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html, body {
            background-color: #0B1120;
            color: #e2e8f0;
            overflow-x: hidden;
            max-width: 100%;
        }

        .text-gold { color: #fbbf24; }
        .bg-gold { background-color: #fbbf24; }
        .border-gold { border-color: #fbbf24; }

        .input-professional {
            background-color: #1e293b;
            border: 1px solid #334155;
            color: white;
            transition: all 0.2s;
        }

        .input-professional:focus {
            border-color: #fbbf24;
            outline: none;
            box-shadow: 0 0 0 1px #fbbf24;
        }

        .star-bg {
            background-image: radial-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 30px 30px;
        }

        .gender-btn input:checked + div {
            border-color: #fbbf24;
            background-color: rgba(251, 191, 36, 0.1);
        }

        .gender-btn input:checked + div i {
            color: #fbbf24;
        }

        /* hCaptcha */
        .h-captcha iframe {
            max-width: 100% !important;
        }

        /* Custom date/time input styling */
        input[type="date"]::-webkit-calendar-picker-indicator,
        input[type="time"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
        }

        /* Prevent iOS zoom on input focus */
        @media (max-width: 640px) {
            .input-professional {
                font-size: 16px !important;
            }
            input[type="date"],
            input[type="time"] {
                font-size: 16px !important;
            }
        }

        .city-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 0.5rem;
            max-height: 200px;
            overflow-y: auto;
            z-index: 50;
            display: none;
        }
        .city-results.show { display: block; }
        .city-result-item {
            padding: 0.75rem 1rem;
            cursor: pointer;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .city-result-item:hover { background: rgba(251, 191, 36, 0.1); }

        .loading-overlay {
            position: fixed;
            inset: 0;
            background: rgba(11, 17, 32, 0.95);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .loading-overlay.show { display: flex; }
    </style>
</head>

<body class="font-sans antialiased star-bg">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 border-b border-indigo-900/30 bg-[#0B1120]/95 backdrop-blur-sm overflow-x-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('welcome') }}" class="flex items-center gap-3">
                    <span class="text-2xl font-serif font-bold text-white tracking-widest">
                        КАРТА<span class="text-gold">НАТАЛ</span>
                    </span>
                </a>

                <div class="flex items-center gap-4">
                    <a href="{{ route('compatibility') }}" class="flex items-center gap-2 text-white font-semibold transition-colors">
                        <i class="fas fa-heart text-gold"></i>
                        Совместимость
                    </a>
                    @auth
                        @php $chart = Auth::user()->natalCharts()->first(); @endphp
                        @if($chart)
                            <a href="{{ route('charts.show', $chart) }}" class="flex items-center gap-2 text-indigo-300 hover:text-white transition-colors">
                                <i class="fas fa-user text-sm"></i>
                                Моя карта
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 text-indigo-400 hover:text-white transition-colors">
                                <i class="fas fa-sign-out-alt text-sm"></i>
                                Выйти
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="flex items-center gap-2 text-indigo-300 hover:text-white transition-colors">
                            <i class="fas fa-user text-sm"></i>
                            Войти
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-40 lg:pb-28 overflow-hidden bg-cover bg-center"
        style="background-image: url('/images/hero-bg.png');">
        <div class="absolute inset-0 bg-[#0B1120]/50"></div>
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-indigo-900/20 rounded-full blur-3xl pointer-events-none -translate-y-1/2 translate-x-1/2"></div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-10">
                <div class="inline-block px-4 py-1.5 rounded-full border border-indigo-800 bg-indigo-950/50 text-indigo-300 text-xs font-bold uppercase tracking-widest mb-6">
                    Бесплатный анализ с ИИ
                </div>
                <h1 class="text-3xl md:text-5xl font-serif font-bold text-white leading-tight mb-6">
                    Совместимость <span class="text-gold">по дате рождения</span>
                </h1>
                <p class="text-lg text-indigo-200 mb-8 leading-relaxed max-w-2xl mx-auto">
                    Узнайте вашу совместимость с партнёром на основе двух натальных карт. Подробный анализ синастрии, оценка совместимости от 1 до 10 и персональные рекомендации от ИИ-астролога.
                </p>
            </div>

            <!-- Form -->
            <div class="bg-[#111827] rounded-xl border border-indigo-900/50 p-6 lg:p-8 shadow-2xl">
                <form id="compatibility-form" method="POST" action="{{ route('compatibility.calculate') }}">
                    @csrf

                    <!-- Email field at top -->
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-indigo-300 uppercase mb-1">
                            <i class="fas fa-envelope mr-1"></i>Ваш Email (для получения результатов)
                        </label>
                        <input type="email" name="person1_email" required
                            class="w-full input-professional rounded-lg px-4 py-3" placeholder="ваш@email.com">
                    </div>

                    <!-- Two persons side by side -->
                    <div class="grid lg:grid-cols-2 gap-6 lg:gap-8">
                        <!-- Person 1 -->
                        <div class="bg-indigo-950/30 rounded-xl p-5 border border-indigo-800/50">
                            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                                <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-sm text-white"></i>
                                </div>
                                Вы
                            </h3>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-indigo-300 uppercase mb-1">
                                        <i class="fas fa-user mr-1"></i>Имя
                                    </label>
                                    <input type="text" name="person1_name" required
                                        class="w-full input-professional rounded-lg px-4 py-3" placeholder="Ваше имя">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-indigo-300 uppercase mb-1">
                                        <i class="fas fa-venus-mars mr-1"></i>Пол
                                    </label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <label class="gender-btn cursor-pointer">
                                            <input type="radio" name="person1_gender" value="male" required class="hidden">
                                            <div class="flex items-center justify-center gap-2 py-2.5 px-3 rounded-lg border-2 border-indigo-800 bg-indigo-950/50 hover:border-indigo-600 transition-all">
                                                <i class="fas fa-mars text-indigo-400"></i>
                                                <span class="text-white text-sm">Муж</span>
                                            </div>
                                        </label>
                                        <label class="gender-btn cursor-pointer">
                                            <input type="radio" name="person1_gender" value="female" required class="hidden">
                                            <div class="flex items-center justify-center gap-2 py-2.5 px-3 rounded-lg border-2 border-indigo-800 bg-indigo-950/50 hover:border-indigo-600 transition-all">
                                                <i class="fas fa-venus text-indigo-400"></i>
                                                <span class="text-white text-sm">Жен</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-bold text-indigo-300 uppercase mb-1">
                                            <i class="fas fa-calendar mr-1"></i>Дата
                                        </label>
                                        <input type="date" name="person1_birth_date" required
                                            class="w-full input-professional rounded-lg px-3 py-3">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-indigo-300 uppercase mb-1">
                                            <i class="fas fa-clock mr-1"></i>Время
                                        </label>
                                        <input type="time" name="person1_birth_time" value="12:00"
                                            class="w-full input-professional rounded-lg px-3 py-3">
                                    </div>
                                </div>

                                <div class="relative">
                                    <label class="block text-xs font-bold text-indigo-300 uppercase mb-1">
                                        <i class="fas fa-map-marker-alt mr-1"></i>Город
                                    </label>
                                    <input type="text" id="city1-input" placeholder="Начните вводить..."
                                        class="w-full input-professional rounded-lg px-4 py-3" autocomplete="off">
                                    <input type="hidden" name="person1_city_id" id="person1_city_id" required>
                                    <div class="city-results" id="city1-results"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Heart divider for mobile -->
                        <div class="lg:hidden flex justify-center -my-2">
                            <div class="w-12 h-12 bg-gradient-to-r from-pink-500 to-rose-500 rounded-full flex items-center justify-center shadow-lg">
                                <i class="fas fa-heart text-white text-lg"></i>
                            </div>
                        </div>

                        <!-- Person 2 -->
                        <div class="bg-rose-950/20 rounded-xl p-5 border border-rose-800/30">
                            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                                <div class="w-8 h-8 bg-gradient-to-r from-pink-500 to-rose-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-heart text-sm text-white"></i>
                                </div>
                                Партнёр
                            </h3>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-indigo-300 uppercase mb-1">
                                        <i class="fas fa-user mr-1"></i>Имя
                                    </label>
                                    <input type="text" name="person2_name" placeholder="Имя партнёра"
                                        class="w-full input-professional rounded-lg px-4 py-3">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-indigo-300 uppercase mb-1">
                                        <i class="fas fa-venus-mars mr-1"></i>Пол
                                    </label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <label class="gender-btn cursor-pointer">
                                            <input type="radio" name="person2_gender" value="male" required class="hidden">
                                            <div class="flex items-center justify-center gap-2 py-2.5 px-3 rounded-lg border-2 border-indigo-800 bg-indigo-950/50 hover:border-indigo-600 transition-all">
                                                <i class="fas fa-mars text-indigo-400"></i>
                                                <span class="text-white text-sm">Муж</span>
                                            </div>
                                        </label>
                                        <label class="gender-btn cursor-pointer">
                                            <input type="radio" name="person2_gender" value="female" required class="hidden">
                                            <div class="flex items-center justify-center gap-2 py-2.5 px-3 rounded-lg border-2 border-indigo-800 bg-indigo-950/50 hover:border-indigo-600 transition-all">
                                                <i class="fas fa-venus text-indigo-400"></i>
                                                <span class="text-white text-sm">Жен</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-bold text-indigo-300 uppercase mb-1">
                                            <i class="fas fa-calendar mr-1"></i>Дата
                                        </label>
                                        <input type="date" name="person2_birth_date" required
                                            class="w-full input-professional rounded-lg px-3 py-3">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-indigo-300 uppercase mb-1">
                                            <i class="fas fa-clock mr-1"></i>Время
                                        </label>
                                        <input type="time" name="person2_birth_time" value="12:00"
                                            class="w-full input-professional rounded-lg px-3 py-3">
                                    </div>
                                </div>

                                <div class="relative">
                                    <label class="block text-xs font-bold text-indigo-300 uppercase mb-1">
                                        <i class="fas fa-map-marker-alt mr-1"></i>Город
                                    </label>
                                    <input type="text" id="city2-input" placeholder="Начните вводить..."
                                        class="w-full input-professional rounded-lg px-4 py-3" autocomplete="off">
                                    <input type="hidden" name="person2_city_id" id="person2_city_id" required>
                                    <div class="city-results" id="city2-results"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- hCaptcha -->
                    <div class="flex justify-center my-6">
                        <div class="h-captcha" data-sitekey="{{ config('services.hcaptcha.site_key') }}"></div>
                    </div>

                    <!-- Submit -->
                    <button type="submit" id="submit-btn"
                        class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold py-4 px-6 rounded-lg transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-heart mr-2"></i>
                        Рассчитать совместимость
                    </button>

                    <p class="text-center text-indigo-400 text-sm mt-4">
                        Нажимая кнопку, вы соглашаетесь с <a href="{{ route('privacy') }}" class="text-gold hover:underline">политикой конфиденциальности</a>
                    </p>
                </form>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="py-16 bg-[#0B1120]">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto bg-indigo-900/30 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-chart-pie text-2xl text-gold"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">6 показателей совместимости</h3>
                    <p class="text-indigo-300 text-sm">Оценка от 1 до 10: эмоции, коммуникация, романтика, ценности и рост</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto bg-indigo-900/30 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-brain text-2xl text-gold"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">AI-анализ синастрии</h3>
                    <p class="text-indigo-300 text-sm">Две полные натальные карты + детальный разбор аспектов между ними</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto bg-indigo-900/30 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-lightbulb text-2xl text-gold"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Персональные рекомендации</h3>
                    <p class="text-indigo-300 text-sm">Практические советы для укрепления отношений</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loading">
        <div class="text-center">
            <i class="fas fa-star fa-spin text-4xl text-gold mb-4"></i>
            <h2 class="text-2xl font-serif text-white mb-2">Анализируем совместимость...</h2>
            <p class="text-indigo-300">Рассчитываем две натальные карты</p>
        </div>
    </div>

    <!-- Footer -->
    <footer class="py-8 border-t border-indigo-900/30 bg-[#0B1120]">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-indigo-400 text-sm">
                &copy; {{ date('Y') }} Karta-Natal.ru — Натальная карта онлайн бесплатно
            </p>
        </div>
    </footer>

    <script>
        // City search
        function setupCitySearch(inputId, hiddenId, resultsId) {
            const input = document.getElementById(inputId);
            const hidden = document.getElementById(hiddenId);
            const results = document.getElementById(resultsId);
            let debounceTimer;

            input.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                const query = this.value;

                if (query.length < 2) {
                    results.classList.remove('show');
                    return;
                }

                debounceTimer = setTimeout(() => {
                    fetch(`/cities/search/${encodeURIComponent(query)}`)
                        .then(r => r.json())
                        .then(data => {
                            results.innerHTML = '';
                            if (data.length === 0) {
                                results.innerHTML = '<div class="city-result-item">Город не найден</div>';
                            } else {
                                data.forEach(city => {
                                    const div = document.createElement('div');
                                    div.className = 'city-result-item';
                                    div.textContent = `${city.name_ru || city.name}, ${city.country}`;
                                    div.onclick = () => {
                                        input.value = `${city.name_ru || city.name}, ${city.country}`;
                                        hidden.value = city.id;
                                        results.classList.remove('show');
                                    };
                                    results.appendChild(div);
                                });
                            }
                            results.classList.add('show');
                        });
                }, 300);
            });

            document.addEventListener('click', (e) => {
                if (!input.contains(e.target) && !results.contains(e.target)) {
                    results.classList.remove('show');
                }
            });
        }

        setupCitySearch('city1-input', 'person1_city_id', 'city1-results');
        setupCitySearch('city2-input', 'person2_city_id', 'city2-results');

        // Form submission
        document.getElementById('compatibility-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const btn = document.getElementById('submit-btn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Рассчитываем...';

            const hcaptchaToken = await hcaptcha.execute(this.querySelector('.h-captcha'), { async: true })
                .catch(() => null);

            const formData = new FormData(this);
            formData.append('hcaptcha_token', hcaptchaToken?.response || '');

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                const data = await response.json();

                if (data.success) {
                    document.getElementById('loading').classList.add('show');
                    setTimeout(() => {
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else if (data.email_sent) {
                            alert('Результат отправлен на ваш email!');
                            window.location.reload();
                        }
                    }, 2000);
                } else {
                    alert('Ошибка: ' + (data.error || 'Попробуйте снова'));
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-calculator mr-2"></i>Рассчитать совместимость';
                }
            } catch (err) {
                alert('Произошла ошибка. Попробуйте ещё раз.');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-calculator mr-2"></i>Рассчитать совместимость';
            }
        });
    </script>
</body>
</html>
