<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <title>Натальная карта онлайн — Узнай свою судьбу (AstroChart)</title>
    <meta name="description"
        content="Бесплатный расчет натальной карты с расшифровкой. Узнайте свое предназначение, совместимость и прогнозы на будущее.">

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
                        },
                        backgroundImage: {
                            'stars': "radial-gradient(white, rgba(255,255,255,.15) 1px, transparent 2px)",
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
    </style>
</head>

<body class="font-sans antialiased star-bg">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 border-b border-indigo-900/30 bg-[#0B1120]/95 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-3">
                    <span class="text-2xl font-serif font-bold text-white tracking-widest">
                        ASTRO<span class="text-gold">CHART</span>
                    </span>
                </div>

                <div class="hidden md:flex items-center gap-8">
                    <a href="#features"
                        class="flex items-center gap-2 text-indigo-200 hover:text-white font-medium transition-colors text-sm uppercase tracking-wider group">
                        <svg class="w-4 h-4 text-indigo-400 group-hover:text-gold-400 transition-colors" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                        Гороскоп
                    </a>
                    <a href="#compatibility"
                        class="flex items-center gap-2 text-indigo-200 hover:text-white font-medium transition-colors text-sm uppercase tracking-wider group">
                        <svg class="w-4 h-4 text-indigo-400 group-hover:text-gold-400 transition-colors" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        Совместимость
                    </a>
                </div>

                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="text-indigo-300 font-semibold hover:text-white transition-colors">Кабинет</a>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-indigo-300 font-medium hover:text-white transition-colors">Войти</a>
                        <a href="{{ route('register') }}"
                            class="border border-indigo-500 text-indigo-300 px-5 py-2 rounded-full hover:bg-indigo-900/50 transition-colors text-sm uppercase tracking-wider">
                            Регистрация
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden bg-cover bg-center"
        style="background-image: url('/images/hero-bg.png');">

        <!-- Overlay for readability -->
        <div class="absolute inset-0 bg-[#0B1120]/80"></div>

        <!-- Minimal Ambient Light (Reduced Glow) -->
        <div
            class="absolute top-0 right-0 w-[500px] h-[500px] bg-indigo-900/20 rounded-full blur-3xl pointer-events-none -translate-y-1/2 translate-x-1/2">
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Text Content -->
                <div class="text-center lg:text-left">
                    <div
                        class="inline-block px-4 py-1.5 rounded-full border border-indigo-800 bg-indigo-950/50 text-indigo-300 text-xs font-bold uppercase tracking-widest mb-6">
                        ✨ Раскрой тайны звезд
                    </div>
                    <h1 class="text-5xl md:text-6xl lg:text-7xl font-serif font-bold text-white leading-tight mb-6">
                        Ваша судьба <br> записана в <span class="text-gold">звездах</span>
                    </h1>
                    <p class="text-lg text-indigo-200 mb-10 leading-relaxed max-w-lg mx-auto lg:mx-0">
                        Получите подробную расшифровку вашей натальной карты. Узнайте свои сильные стороны,
                        предназначение и совместимость с партнером.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <div class="flex items-center gap-2 text-indigo-300 text-sm">
                            <svg class="w-5 h-5 text-gold-500" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <span>450k+ расчетов</span>
                        </div>
                        <div class="flex items-center gap-2 text-indigo-300 text-sm">
                            <svg class="w-5 h-5 text-gold-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Точность Swiss Ephemeris</span>
                        </div>
                    </div>
                </div>

                <!-- Hero Form -->
                <div class="relative">
                    <div class="bg-[#111827] rounded-xl border border-indigo-900/50 p-8 shadow-2xl">
                        <h3 class="text-2xl font-serif font-bold text-white mb-2 text-center">Рассчитать карту</h3>
                        <p class="text-indigo-300 text-center text-sm mb-6">Введите данные рождения</p>

                        <form id="calcForm" action="{{ route('calculate') }}" method="POST" class="space-y-4">
                            @csrf

                            <div>
                                <label class="block text-xs font-bold text-indigo-300 uppercase mb-1">Ваше имя</label>
                                <input type="text" name="name" required
                                    class="w-full input-professional rounded-lg px-4 py-3" placeholder="Как вас зовут?">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-indigo-300 uppercase mb-1">Email</label>
                                <input type="email" name="email" required
                                    class="w-full input-professional rounded-lg px-4 py-3" placeholder="ваш@email.com">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-indigo-300 uppercase mb-1">Дата</label>
                                    <input type="date" name="birth_date" required
                                        class="w-full input-professional rounded-lg px-4 py-3">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-indigo-300 uppercase mb-1">Время</label>
                                    <input type="time" name="birth_time"
                                        class="w-full input-professional rounded-lg px-4 py-3">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-indigo-300 uppercase mb-1">Место
                                    рождения</label>
                                <input type="text" name="birth_place" required
                                    class="w-full input-professional rounded-lg px-4 py-3" placeholder="Москва, Россия">
                            </div>

                            <button type="submit"
                                class="w-full mt-4 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-500 hover:to-indigo-600 text-white font-bold py-4 rounded-lg shadow-lg transition-all transform hover:scale-[1.01] border border-indigo-500/50">
                                Рассчитать сейчас
                            </button>

                            <p class="text-xs text-center text-indigo-400/50 mt-4">
                                * Нажимая кнопку, вы даете согласие на обработку персональных данных
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-24 bg-[#0B1120] border-t border-indigo-900/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl md:text-4xl font-serif font-bold text-white mb-4">Что расскажет ваша карта?</h2>
                <div class="h-1 w-20 bg-gold-500 mx-auto rounded-full"></div>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div
                    class="p-8 rounded-xl bg-[#111827] border border-indigo-900/30 hover:border-gold-500/30 transition-colors">
                    <div
                        class="w-12 h-12 bg-indigo-900/30 rounded-full flex items-center justify-center mb-6 text-2xl border border-indigo-800 text-gold-500">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Личность (Солнце)</h3>
                    <p class="text-indigo-300 text-sm leading-relaxed">
                        Ваше истинное "Я", жизненная энергия и основные черты характера. То, кем вы являетесь на самом
                        деле.
                    </p>
                </div>

                <div
                    class="p-8 rounded-xl bg-[#111827] border border-indigo-900/30 hover:border-gold-500/30 transition-colors">
                    <div
                        class="w-12 h-12 bg-indigo-900/30 rounded-full flex items-center justify-center mb-6 text-2xl border border-indigo-800 text-indigo-300">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Эмоции (Луна)</h3>
                    <p class="text-indigo-300 text-sm leading-relaxed">
                        Ваш внутренний мир, подсознание и эмоциональные потребности. Как вы любите и чувствуете.
                    </p>
                </div>

                <div
                    class="p-8 rounded-xl bg-[#111827] border border-indigo-900/30 hover:border-gold-500/30 transition-colors">
                    <div
                        class="w-12 h-12 bg-indigo-900/30 rounded-full flex items-center justify-center mb-6 text-2xl border border-indigo-800 text-purple-400">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Маска (Асцендент)</h3>
                    <p class="text-indigo-300 text-sm leading-relaxed">
                        Первое впечатление, которое вы производите на окружающих. Ваш социальный "интерфейс".
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#050914] border-t border-indigo-900/20 py-12">
        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <span class="text-xl font-serif font-bold text-white tracking-widest opacity-50">
                ASTRO<span class="text-gold">CHART</span>
            </span>
            <div class="text-indigo-400 text-sm">
                &copy; {{ date('Y') }} AstroChart. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Processing Modal -->
    <div id="processingModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-[#0B1120]/95 backdrop-blur-md"></div>

        <!-- Content -->
        <div class="relative z-10 max-w-md w-full px-6 text-center">

            <!-- 1. LOADING STATE -->
            <div id="loadingState">
                <!-- Zodiac Circle Loader -->
                <div class="relative w-64 h-64 mx-auto mb-10 flex items-center justify-center">
                    <!-- Central Pulse -->
                    <div class="absolute w-32 h-32 bg-indigo-900/30 rounded-full blur-2xl animate-pulse"></div>

                    <!-- Calculates positions for 12 zodiacs -->
                    @php
                        $zodiacs = ['aries', 'taurus', 'gemini', 'cancer', 'leo', 'virgo', 'libra', 'scorpio', 'sagittarius', 'capricorn', 'aquarius', 'pisces'];
                    @endphp

                    <div class="relative w-full h-full animate-[spin_20s_linear_infinite]">
                        @foreach($zodiacs as $index => $sign)
                            @php
                                $angle = ($index * 30); // 360 / 12
                                $rad = deg2rad($angle);
                                // Radius = 45% to keep inside container
                                $x = 50 + (45 * cos($rad));
                                $y = 50 + (45 * sin($rad));
                            @endphp
                            <div class="absolute w-8 h-8 -ml-4 -mt-4 transition-all duration-500"
                                style="left: {{ $x }}%; top: {{ $y }}%; transform: rotate({{ $angle + 90 }}deg)">
                                <img src="/images/zodiac/{{ $sign }}.png" class="w-full h-full object-contain opacity-60">
                            </div>
                        @endforeach
                    </div>
                </div>

                <h2 class="text-3xl font-serif font-bold text-white mb-2 tracking-widest">CALCULATING</h2>
                <p class="text-indigo-300 text-sm mb-8 font-mono tracking-widest uppercase" id="statusText">CONNECTING
                    TO SWISS EPHEMERIS...</p>

                <!-- Progress Bar -->
                <div class="relative w-full h-1 bg-indigo-900/50 rounded-full overflow-hidden">
                    <div class="bg-gold-500 h-full transition-all duration-300 ease-out shadow-[0_0_10px_rgba(251,191,36,0.8)]"
                        style="width: 0%" id="progressBar"></div>
                </div>
                <div class="flex justify-between mt-2 text-[10px] text-indigo-500 font-mono uppercase tracking-widest">
                    <span>Progress</span>
                    <span id="percentage">0%</span>
                </div>
            </div>

            <!-- 2. SUCCESS STATE (Hidden initially) -->
            <div id="successState" class="hidden animate-fade-in-up">
                <div
                    class="w-20 h-20 bg-emerald-500/10 rounded-full flex items-center justify-center mx-auto mb-6 border border-emerald-500/30">
                    <svg class="w-10 h-10 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>

                <h2 class="text-3xl font-serif font-bold text-white mb-4">Готово!</h2>
                <p class="text-indigo-200 mb-8 leading-relaxed">
                    Мы отправили ссылку на вашу карту вам на почту, чтобы вы не потеряли её.
                </p>

                <button id="viewChartBtn"
                    class="w-full bg-gold-500 hover:bg-gold-400 text-black font-bold py-4 rounded-lg shadow-[0_0_20px_rgba(245,158,11,0.4)] transition-all transform hover:scale-[1.02] mb-4">
                    ОТКРЫТЬ МОЮ КАРТУ
                </button>

                <button onclick="document.getElementById('processingModal').classList.add('hidden')"
                    class="text-sm text-indigo-400 hover:text-white transition-colors">
                    Закрыть
                </button>
            </div>

        </div>
    </div>

    <!-- Styles for custom animations -->
    <style>
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <!-- AJAX Script -->
    <script>
        document.getElementById('calcForm').addEventListener('submit', function (e) {
            e.preventDefault();

            // Reset states
            document.getElementById('loadingState').classList.remove('hidden');
            document.getElementById('successState').classList.add('hidden');

            // Show Modal
            const modal = document.getElementById('processingModal');
            modal.classList.remove('hidden');

            // Animation Steps
            const steps = [
                { pct: 15, text: "Расчет координат Солнца..." },
                { pct: 30, text: "Определение лунных узлов..." },
                { pct: 55, text: "Вычисление системы домов..." },
                { pct: 75, text: "Анализ мажорных аспектов..." },
                { pct: 90, text: "Формирование отчета..." },
                { pct: 100, text: "Готово!" }
            ];

            let currentStep = 0;
            const progressBar = document.getElementById('progressBar');
            const statusText = document.getElementById('statusText');
            const percentageText = document.getElementById('percentage');

            function nextAnimationStep() {
                if (currentStep >= steps.length) return;

                const step = steps[currentStep];
                progressBar.style.width = step.pct + '%';
                statusText.innerText = step.text;
                percentageText.innerText = step.pct + '%';
                currentStep++;

                if (currentStep < steps.length) {
                    setTimeout(nextAnimationStep, 800); // Animation delay
                }
            }

            // Start Animation
            nextAnimationStep();

            // Submit Data via AJAX
            const formData = new FormData(this);

            fetch("{{ route('calculate') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    // Wait for animation to likely finish (min 3s total)
                    setTimeout(() => {
                        // Switch to Success State
                        document.getElementById('loadingState').classList.add('hidden');
                        document.getElementById('successState').classList.remove('hidden');

                        // Set the button link
                        const btn = document.getElementById('viewChartBtn');
                        btn.onclick = function () {
                            window.location.href = data.redirect_url;
                        };

                    }, 4000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Произошла ошибка. Пожалуйста, попробуйте еще раз.');
                    modal.classList.add('hidden');
                });
        });
    </script>
</body>

</html>