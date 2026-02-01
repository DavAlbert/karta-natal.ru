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

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

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

        .gender-btn input:checked + div {
            border-color: #fbbf24;
            background-color: rgba(251, 191, 36, 0.1);
        }

        .gender-btn input:checked + div i {
            color: #fbbf24;
        }

        /* Custom date/time input styling */
        input[type="date"]::-webkit-calendar-picker-indicator,
        input[type="time"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
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
                                <label class="block text-xs font-bold text-indigo-300 uppercase mb-1">
                                    <i class="fas fa-user mr-1"></i>Ваше имя
                                </label>
                                <input type="text" name="name" id="name" required
                                    class="w-full input-professional rounded-lg px-4 py-3" placeholder="Как вас зовут?">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-indigo-300 uppercase mb-1">
                                    <i class="fas fa-envelope mr-1"></i>Email
                                </label>
                                <input type="email" name="email" id="email" required
                                    class="w-full input-professional rounded-lg px-4 py-3" placeholder="ваш@email.com">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-indigo-300 uppercase mb-1">
                                    <i class="fas fa-venus-mars mr-1"></i>Пол
                                </label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="gender-btn cursor-pointer">
                                        <input type="radio" name="gender" value="male" required class="hidden">
                                        <div class="flex items-center justify-center gap-2 py-3 px-4 rounded-lg border-2 border-indigo-800 bg-indigo-950/30 hover:border-indigo-600 hover:bg-indigo-900/30 transition-all">
                                            <i class="fas fa-mars text-indigo-400"></i>
                                            <span class="text-white text-sm font-medium">Мужской</span>
                                        </div>
                                    </label>
                                    <label class="gender-btn cursor-pointer">
                                        <input type="radio" name="gender" value="female" required class="hidden">
                                        <div class="flex items-center justify-center gap-2 py-3 px-4 rounded-lg border-2 border-indigo-800 bg-indigo-950/30 hover:border-indigo-600 hover:bg-indigo-900/30 transition-all">
                                            <i class="fas fa-venus text-indigo-400"></i>
                                            <span class="text-white text-sm font-medium">Женский</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-indigo-300 uppercase mb-1">
                                    <i class="fas fa-bullseye mr-1"></i>Цель расчета
                                </label>
                                <select name="purpose" id="purpose" required
                                    class="w-full input-professional rounded-lg px-4 py-3">
                                    <option value="">Выберите цель...</option>
                                    <option value="love">Любовь и отношения</option>
                                    <option value="career">Карьера</option>
                                    <option value="health">Здоровье</option>
                                    <option value="finance">Финансы</option>
                                    <option value="personal">Личностный рост</option>
                                    <option value="general">Общий анализ</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-indigo-300 uppercase mb-1">
                                        <i class="far fa-calendar-alt mr-1"></i>Дата рождения
                                    </label>
                                    <input type="date" name="birth_date" id="birth_date" required
                                        class="w-full input-professional rounded-lg px-4 py-3">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-indigo-300 uppercase mb-1">
                                        <i class="far fa-clock mr-1"></i>Время
                                    </label>
                                    <input type="time" name="birth_time" id="birth_time" required
                                        class="w-full input-professional rounded-lg px-4 py-3">
                                </div>
                            </div>


                            <div class="relative">
                                <label class="block text-xs font-bold text-indigo-300 uppercase mb-1">
                                    <i class="fas fa-map-marker-alt mr-1"></i>Место рождения
                                </label>
                                <div class="relative">
                                    <input type="text" id="birth_place_search" autocomplete="off"
                                        class="w-full input-professional rounded-lg px-4 py-3 pr-10"
                                        placeholder="Выберите или найдите город...">
                                    <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-indigo-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                                <input type="hidden" id="city_id" name="city_id" required>

                                <!-- Cities Dropdown -->
                                <div id="cities-dropdown"
                                    class="hidden absolute z-50 w-full mt-1 bg-[#1e293b] border border-indigo-500/30 rounded-lg shadow-xl max-h-60 overflow-y-auto">
                                    @foreach($cities as $city)
                                    <div class="city-option px-4 py-3 hover:bg-indigo-900/30 cursor-pointer border-b border-indigo-900/20 last:border-0 transition-colors"
                                        data-city-id="{{ $city->id }}"
                                        data-city-name="{{ $city->name }}"
                                        data-city-lat="{{ $city->latitude }}"
                                        data-city-lon="{{ $city->longitude }}"
                                        data-city-tz="{{ $city->timezone_gmt }}">
                                        <div class="text-white text-sm">{{ $city->name }}</div>
                                    </div>
                                    @endforeach
                                </div>

                                <!-- City Details Display -->
                                <div id="city-details" class="hidden mt-2 p-3 bg-indigo-900/20 rounded-lg border border-indigo-800/30">
                                    <div class="grid grid-cols-3 gap-2 text-xs">
                                        <div>
                                            <span class="text-indigo-400">Широта:</span>
                                            <span class="text-white font-mono" id="display-latitude">-</span>
                                        </div>
                                        <div>
                                            <span class="text-indigo-400">Долгота:</span>
                                            <span class="text-white font-mono" id="display-longitude">-</span>
                                        </div>
                                        <div>
                                            <span class="text-indigo-400">GMT:</span>
                                            <span class="text-gold-400 font-mono" id="display-timezone">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="mt-4">
                                <label class="flex items-start gap-3 cursor-pointer group">
                                    <input type="checkbox" name="marketing_consent" value="1"
                                        class="mt-1 w-4 h-4 text-indigo-600 bg-indigo-950/30 border-indigo-800 rounded focus:ring-indigo-500 focus:ring-2">
                                    <span class="text-xs text-indigo-300 leading-relaxed group-hover:text-indigo-200 transition-colors">
                                        Я хочу получать персонализированные рекомендации, информацию о новых возможностях платформы и эксклюзивные предложения на основе моей натальной карты
                                    </span>
                                </label>
                            </div>

                            <button type="submit" id="submit-btn" disabled
                                class="w-full mt-4 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-500 hover:to-indigo-600 text-white font-bold py-4 rounded-lg shadow-lg transition-all transform hover:scale-[1.01] border border-indigo-500/50 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
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
                <p class="text-indigo-200 mb-4 leading-relaxed">
                    Мы отправили ссылку на вашу карту вам на почту.
                </p>
                <p class="text-gold-400 font-bold mb-8">
                    Проверьте email и установите пароль для доступа к карте.
                </p>

                <button onclick="document.getElementById('processingModal').classList.add('hidden'); location.reload();"
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
        // Form validation
        const calcForm = document.getElementById('calcForm');
        const submitBtn = document.getElementById('submit-btn');
        const nameInput = document.querySelector('input[name="name"]');
        const emailInput = document.querySelector('input[name="email"]');
        const genderInputs = document.querySelectorAll('input[name="gender"]');
        const purposeInput = document.querySelector('select[name="purpose"]');
        const birthDateInput = document.getElementById('birth_date');
        const birthTimeInput = document.getElementById('birth_time');
        const cityIdInputValidation = document.getElementById('city_id');

        function validateForm() {
            const name = nameInput.value.trim();
            const email = emailInput.value.trim();
            const gender = Array.from(genderInputs).some(input => input.checked);
            const purpose = purposeInput.value;
            const birthDate = birthDateInput.value;
            const birthTime = birthTimeInput.value;
            const cityId = cityIdInputValidation.value;

            const isValid = name && email && gender && purpose && birthDate && birthTime && cityId;
            submitBtn.disabled = !isValid;
        }

        // Add event listeners for all form fields
        nameInput.addEventListener('input', validateForm);
        emailInput.addEventListener('input', validateForm);
        genderInputs.forEach(input => input.addEventListener('change', validateForm));
        purposeInput.addEventListener('change', validateForm);
        birthDateInput.addEventListener('change', validateForm);
        birthTimeInput.addEventListener('change', validateForm);

        // Initial validation on page load (for Moscow default)
        validateForm();

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
                    }, 4000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Произошла ошибка. Пожалуйста, попробуйте еще раз.');
                    modal.classList.add('hidden');
                });
        });

        // City dropdown with client-side search
        const searchInput = document.getElementById('birth_place_search');
        const cityIdInput = document.getElementById('city_id');
        const dropdown = document.getElementById('cities-dropdown');
        const cityDetails = document.getElementById('city-details');
        const displayLatitude = document.getElementById('display-latitude');
        const displayLongitude = document.getElementById('display-longitude');
        const displayTimezone = document.getElementById('display-timezone');
        const allCityOptions = dropdown ? Array.from(dropdown.querySelectorAll('.city-option')) : [];

        // Transliteration map (Latin to Cyrillic)
        const translitMap = {
            'a': 'а', 'b': 'б', 'v': 'в', 'g': 'г', 'd': 'д', 'e': 'е', 'yo': 'ё', 'zh': 'ж',
            'z': 'з', 'i': 'и', 'y': 'й', 'k': 'к', 'l': 'л', 'm': 'м', 'n': 'н', 'o': 'о',
            'p': 'п', 'r': 'р', 's': 'с', 't': 'т', 'u': 'у', 'f': 'ф', 'h': 'х', 'kh': 'х',
            'ts': 'ц', 'ch': 'ч', 'sh': 'ш', 'shch': 'щ', 'shh': 'щ', 'w': 'ш', 'yu': 'ю', 'ya': 'я',
            'j': 'й', 'c': 'ц', 'x': 'кс', 'q': 'к'
        };

        function transliterate(text) {
            let result = text.toLowerCase();
            // Sort by length (longest first) to handle multi-char mappings
            const sortedKeys = Object.keys(translitMap).sort((a, b) => b.length - a.length);
            for (const latin of sortedKeys) {
                result = result.split(latin).join(translitMap[latin]);
            }
            return result;
        }

        function filterCities(query) {
            const searchTerm = query.toLowerCase().trim();
            const translitSearchTerm = transliterate(searchTerm);

            if (searchTerm === '') {
                // Reset to original order
                allCityOptions.forEach(option => {
                    dropdown.appendChild(option);
                });
                return;
            }

            // Sort cities: matching ones first, rest after
            const matchingCities = [];
            const nonMatchingCities = [];

            allCityOptions.forEach(option => {
                const cityName = option.dataset.cityName.toLowerCase();
                // Match both original search and transliterated search
                if (cityName.includes(searchTerm) || cityName.includes(translitSearchTerm)) {
                    matchingCities.push(option);
                } else {
                    nonMatchingCities.push(option);
                }
            });

            // Clear dropdown and re-append in new order
            dropdown.innerHTML = '';

            // Add matching cities first
            matchingCities.forEach(city => dropdown.appendChild(city));

            // Add non-matching cities after
            nonMatchingCities.forEach(city => dropdown.appendChild(city));
        }

        function selectCity(element) {
            // Set values
            searchInput.value = element.dataset.cityName;
            cityIdInput.value = element.dataset.cityId;

            // Show details
            displayLatitude.textContent = element.dataset.cityLat + '°';
            displayLongitude.textContent = element.dataset.cityLon + '°';
            displayTimezone.textContent = 'GMT+' + element.dataset.cityTz;
            cityDetails.classList.remove('hidden');

            dropdown.classList.add('hidden');

            // Validate form
            if (typeof validateForm === 'function') {
                validateForm();
            }
        }

        if (searchInput && dropdown && cityIdInput) {
            // Setup click handlers for all cities
            allCityOptions.forEach(option => {
                option.addEventListener('click', function() {
                    selectCity(this);
                });
            });

            // Open dropdown on focus
            searchInput.addEventListener('focus', function () {
                filterCities(this.value);
                dropdown.classList.remove('hidden');
            });

            // Search as user types (client-side)
            searchInput.addEventListener('input', function () {
                const query = this.value;

                // Clear selection when typing
                if (cityIdInput.value) {
                    cityIdInput.value = '';
                    cityDetails.classList.add('hidden');
                    if (typeof validateForm === 'function') {
                        validateForm();
                    }
                }

                filterCities(query);
                dropdown.classList.remove('hidden');
            });

            // Hide dropdown when clicking outside
            document.addEventListener('click', function (e) {
                if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });

            // Set Moscow as default
            @if(isset($defaultCity) && $defaultCity)
            const defaultCityOption = allCityOptions.find(opt => opt.dataset.cityId === '{{ $defaultCity->id }}');
            if (defaultCityOption) {
                selectCity(defaultCityOption);
            }
            @endif
        }
    </script>
</body>

</html>