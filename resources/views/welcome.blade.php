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


                <div class="flex items-center gap-4">
                    @auth
                        @php $chart = Auth::user()->natalCharts()->first(); @endphp
                        @if($chart)
                            <a href="{{ route('charts.show', $chart) }}" class="flex items-center gap-2 text-indigo-300 font-semibold hover:text-white transition-colors">
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
                        <button onclick="document.getElementById('loginModal').classList.remove('hidden')" class="flex items-center gap-2 text-indigo-300 font-medium hover:text-white transition-colors">
                            <i class="fas fa-user text-sm"></i>
                            Войти
                        </button>

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
                        @auth
                            @php $chart = Auth::user()->natalCharts()->first(); @endphp
                            @if($chart)
                                <div class="relative">
                                    <!-- Blurred Form -->
                                    <div class="filter blur-[2px] opacity-50 pointer-events-none">
                                        <form id="calcForm" action="{{ route('calculate') }}" method="POST">
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
                                                <input type="email" name="email" id="email" required autocomplete="email"
                                                    class="w-full input-professional rounded-lg px-4 py-3" placeholder="ваш@email.com">
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold text-indigo-300 uppercase mb-1">
                                                    <i class="fas fa-venus-mars mr-1"></i>Пол
                                                </label>
                                                <div class="grid grid-cols-2 gap-3">
                                                    <label class="gender-btn cursor-pointer">
                                                        <input type="radio" name="gender" value="male" required class="hidden" checked>
                                                        <div class="flex items-center justify-center gap-2 py-3 px-4 rounded-lg border-2 border-indigo-800 bg-indigo-950/30">
                                                            <i class="fas fa-mars text-indigo-400"></i>
                                                            <span class="text-white text-sm font-medium">Мужской</span>
                                                        </div>
                                                    </label>
                                                    <label class="gender-btn cursor-pointer">
                                                        <input type="radio" name="gender" value="female" required class="hidden">
                                                        <div class="flex items-center justify-center gap-2 py-3 px-4 rounded-lg border-2 border-indigo-800 bg-indigo-950/30">
                                                            <i class="fas fa-venus text-indigo-400"></i>
                                                            <span class="text-white text-sm font-medium">Женский</span>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>

                                            <input type="hidden" name="purpose" value="general">

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

                                            <div>
                                                <label class="block text-xs font-bold text-indigo-300 uppercase mb-1">
                                                    <i class="fas fa-map-marker-alt mr-1"></i>Место рождения
                                                </label>
                                                <input type="text" id="birth_place_search" autocomplete="off"
                                                    class="w-full input-professional rounded-lg px-4 py-3" placeholder="Начните вводить город...">
                                                <input type="hidden" name="city_id" id="city_id" required>
                                                <div id="city-details" class="hidden mt-2 p-3 bg-indigo-900/20 rounded-lg border border-indigo-800">
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-indigo-300" id="display-country"></span>
                                                        <span class="text-white" id="display-city"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="cities-dropdown" class="hidden absolute z-50 mt-1 w-full bg-[#1e293b] border border-indigo-700 rounded-lg shadow-xl max-h-60 overflow-y-auto">
                                            </div>

                                            <button type="submit" id="submit-btn" disabled
                                                class="w-full mt-4 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-bold py-4 rounded-lg border border-indigo-500/50 opacity-50">
                                                Рассчитать сейчас
                                            </button>

                                            <p class="text-xs text-center text-indigo-400/50 mt-4">
                                                * Нажимая кнопку, вы даете согласие на обработку персональных данных
                                            </p>
                                        </form>
                                    </div>

                                    <!-- Overlay Message -->
                                    <div class="absolute inset-0 flex flex-col items-center justify-center z-10">
                                        <div class="bg-[#111827]/95 backdrop-blur-sm rounded-xl border border-indigo-500/30 p-6 text-center shadow-xl">
                                            <div class="w-16 h-16 bg-emerald-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                                <i class="fas fa-check text-2xl text-emerald-400"></i>
                                            </div>
                                            <h3 class="text-xl font-serif font-bold text-white mb-2">Ваша карта уже рассчитана!</h3>
                                            <p class="text-indigo-300 text-sm mb-4">Вы можете просмотреть результаты</p>
                                            <a href="{{ route('charts.show', $chart) }}" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-500 hover:to-indigo-600 text-white font-bold rounded-xl shadow-lg transition-all">
                                                <i class="fas fa-star"></i>
                                                Открыть карту
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <form id="calcForm" action="{{ route('calculate') }}" method="POST">
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
                                        <input type="email" name="email" id="email" required autocomplete="email"
                                            class="w-full input-professional rounded-lg px-4 py-3" placeholder="ваш@email.com">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-indigo-300 uppercase mb-1">
                                            <i class="fas fa-venus-mars mr-1"></i>Пол
                                        </label>
                                        <div class="grid grid-cols-2 gap-3">
                                            <label class="gender-btn cursor-pointer">
                                                <input type="radio" name="gender" value="male" required class="hidden" checked>
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

                                    <input type="hidden" name="purpose" value="general">

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

                                    <div>
                                        <label class="block text-xs font-bold text-indigo-300 uppercase mb-1">
                                            <i class="fas fa-map-marker-alt mr-1"></i>Место рождения
                                        </label>
                                        <input type="text" id="birth_place_search" autocomplete="off"
                                            class="w-full input-professional rounded-lg px-4 py-3" placeholder="Начните вводить город...">
                                        <input type="hidden" name="city_id" id="city_id" required>
                                        <div id="city-details" class="hidden mt-2 p-3 bg-indigo-900/20 rounded-lg border border-indigo-800">
                                            <div class="flex items-center gap-2">
                                                <span class="text-indigo-300" id="display-country"></span>
                                                <span class="text-white" id="display-city"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="cities-dropdown" class="hidden absolute z-50 mt-1 w-full bg-[#1e293b] border border-indigo-700 rounded-lg shadow-xl max-h-60 overflow-y-auto">
                                    </div>

                                    <button type="submit" id="submit-btn" disabled
                                        class="w-full mt-4 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-500 hover:to-indigo-600 text-white font-bold py-4 rounded-lg shadow-lg transition-all transform hover:scale-[1.01] border border-indigo-500/50 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                                        Рассчитать сейчас
                                    </button>

                                    <p class="text-xs text-center text-indigo-400/50 mt-4">
                                        * Нажимая кнопку, вы даете согласие на обработку персональных данных
                                    </p>
                                </form>
                            @endif
                        @else
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
                                    <input type="email" name="email" id="email" required autocomplete="email"
                                        class="w-full input-professional rounded-lg px-4 py-3" placeholder="ваш@email.com">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-indigo-300 uppercase mb-1">
                                        <i class="fas fa-venus-mars mr-1"></i>Пол
                                    </label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <label class="gender-btn cursor-pointer">
                                            <input type="radio" name="gender" value="male" required class="hidden" checked>
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

                                <input type="hidden" name="purpose" value="general">

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

                                <div>
                                    <label class="block text-xs font-bold text-indigo-300 uppercase mb-1">
                                        <i class="fas fa-city mr-1"></i>Город рождения
                                    </label>
                                    <div class="relative">
                                        <input type="text" id="birth_place_search" autocomplete="off"
                                            class="w-full input-professional rounded-lg px-4 py-3 pr-10"
                                            placeholder="Начните вводить название города...">
                                        <div id="search-spinner" class="hidden absolute right-3 top-1/2 -translate-y-1/2">
                                            <svg class="animate-spin h-5 w-5 text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>
                                        <svg id="search-icon" class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-indigo-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                        <div id="cities-dropdown"
                                            class="hidden absolute z-50 mt-1 bg-[#1e293b] border border-indigo-500/30 rounded-lg shadow-xl max-h-60 overflow-y-auto w-full left-0">
                                        </div>
                                    </div>
                                    <p class="text-xs text-indigo-400/60 mt-1">Можно вводить на русском или латиницей</p>
                                </div>
                                <input type="hidden" id="city_id" name="city_id" required>

                                <div id="city-details" class="hidden mt-2 p-3 bg-indigo-900/20 rounded-lg border border-indigo-800/30 text-xs">
                                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1">
                                        <span class="text-white" id="display-city">-</span>
                                        <span class="text-indigo-500">•</span>
                                        <span class="text-indigo-300" id="display-country">-</span>
                                        <span class="text-indigo-500">•</span>
                                        <span class="text-indigo-400 font-mono"><span id="display-latitude">-</span>, <span id="display-longitude">-</span></span>
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
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Zodiac Signs Section -->
    <section class="py-20 bg-[#080d15] border-t border-indigo-900/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="inline-block px-3 py-1 rounded-full bg-indigo-900/50 text-indigo-300 text-xs font-semibold uppercase tracking-wider mb-4">Знаки зодиака</span>
                <h2 class="text-3xl md:text-4xl font-serif font-bold text-white mb-4">Узнайте свой знак</h2>
                <p class="text-indigo-300/80">Каждый знак обладает уникальными качествами и талантами</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                @php
                    $zodiacSigns = [
                        ['file' => 'aries', 'name' => 'Овен', 'date' => '21 мар — 19 апр', 'traits' => 'Смелость, энергия, лидерство', 'element' => 'fire'],
                        ['file' => 'taurus', 'name' => 'Телец', 'date' => '20 апр — 20 май', 'traits' => 'Надёжность, терпение, верность', 'element' => 'earth'],
                        ['file' => 'gemini', 'name' => 'Близнецы', 'date' => '21 май — 20 июн', 'traits' => 'Общительность, ум, гибкость', 'element' => 'air'],
                        ['file' => 'cancer', 'name' => 'Рак', 'date' => '21 июн — 22 июл', 'traits' => 'Забота, интуиция, преданность', 'element' => 'water'],
                        ['file' => 'leo', 'name' => 'Лев', 'date' => '23 июл — 22 авг', 'traits' => 'Харизма, щедрость, творчество', 'element' => 'fire'],
                        ['file' => 'virgo', 'name' => 'Дева', 'date' => '23 авг — 22 сен', 'traits' => 'Аналитика, трудолюбие, забота', 'element' => 'earth'],
                        ['file' => 'libra', 'name' => 'Весы', 'date' => '23 сен — 22 окт', 'traits' => 'Гармония, дипломатия, эстетика', 'element' => 'air'],
                        ['file' => 'scorpio', 'name' => 'Скорпион', 'date' => '23 окт — 21 ноя', 'traits' => 'Страсть, глубина, решимость', 'element' => 'water'],
                        ['file' => 'sagittarius', 'name' => 'Стрелец', 'date' => '22 ноя — 21 дек', 'traits' => 'Оптимизм, честность, свобода', 'element' => 'fire'],
                        ['file' => 'capricorn', 'name' => 'Козерог', 'date' => '22 дек — 19 янв', 'traits' => 'Амбиции, дисциплина, мудрость', 'element' => 'earth'],
                        ['file' => 'aquarius', 'name' => 'Водолей', 'date' => '20 янв — 18 фев', 'traits' => 'Оригинальность, гуманизм, интеллект', 'element' => 'air'],
                        ['file' => 'pisces', 'name' => 'Рыбы', 'date' => '19 фев — 20 мар', 'traits' => 'Эмпатия, творчество, мечтательность', 'element' => 'water'],
                    ];
                    $elementStyles = [
                        'fire' => ['border' => 'hover:border-red-500/50', 'bg' => 'from-red-500/5', 'text' => 'text-red-400'],
                        'earth' => ['border' => 'hover:border-amber-500/50', 'bg' => 'from-amber-500/5', 'text' => 'text-amber-400'],
                        'air' => ['border' => 'hover:border-cyan-500/50', 'bg' => 'from-cyan-500/5', 'text' => 'text-cyan-400'],
                        'water' => ['border' => 'hover:border-blue-500/50', 'bg' => 'from-blue-500/5', 'text' => 'text-blue-400'],
                    ];
                @endphp
                @foreach($zodiacSigns as $sign)
                @php $style = $elementStyles[$sign['element']]; @endphp
                <div class="group relative p-5 rounded-2xl bg-gradient-to-b {{ $style['bg'] }} to-[#111827] border border-indigo-900/30 {{ $style['border'] }} transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-indigo-900/20">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-14 h-14 md:w-16 md:h-16">
                            <img src="/images/zodiac/{{ $sign['file'] }}.png" alt="{{ $sign['name'] }}" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300">
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-white font-bold text-lg mb-0.5">{{ $sign['name'] }}</h3>
                            <p class="text-indigo-400/60 text-xs mb-2">{{ $sign['date'] }}</p>
                            <p class="{{ $style['text'] }} text-xs leading-relaxed">{{ $sign['traits'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- What You Get Section -->
    <section class="py-20 bg-[#0B1120] border-t border-indigo-900/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="inline-block px-3 py-1 rounded-full bg-gold-500/10 text-gold-400 text-xs font-semibold uppercase tracking-wider mb-4">Что вы получите</span>
                <h2 class="text-3xl md:text-4xl font-serif font-bold text-white mb-4">Полный анализ вашей карты</h2>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="p-6 rounded-2xl bg-[#111827] border border-indigo-900/30 hover:border-indigo-500/30 transition-all">
                    <div class="w-12 h-12 rounded-xl bg-indigo-500/10 flex items-center justify-center mb-4">
                        <img src="/images/planets/sun.png" alt="" class="w-8 h-8">
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">Позиции планет</h3>
                    <p class="text-indigo-300/70 text-sm leading-relaxed">10 планет в знаках зодиака с точными градусами и расшифровкой значений</p>
                </div>

                <div class="p-6 rounded-2xl bg-[#111827] border border-indigo-900/30 hover:border-indigo-500/30 transition-all">
                    <div class="w-12 h-12 rounded-xl bg-purple-500/10 flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">12 домов</h3>
                    <p class="text-indigo-300/70 text-sm leading-relaxed">Сферы жизни от личности до карьеры — где проявляется энергия планет</p>
                </div>

                <div class="p-6 rounded-2xl bg-[#111827] border border-indigo-900/30 hover:border-indigo-500/30 transition-all">
                    <div class="w-12 h-12 rounded-xl bg-pink-500/10 flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">Аспекты</h3>
                    <p class="text-indigo-300/70 text-sm leading-relaxed">Связи между планетами — гармоничные трины и напряжённые квадраты</p>
                </div>

                <div class="p-6 rounded-2xl bg-[#111827] border border-indigo-900/30 hover:border-indigo-500/30 transition-all">
                    <div class="w-12 h-12 rounded-xl bg-gold-500/10 flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-gold-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">ИИ-астролог</h3>
                    <p class="text-indigo-300/70 text-sm leading-relaxed">Задавайте вопросы о любви, карьере, здоровье — получайте ответы</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Elements Section -->
    <section class="py-20 bg-[#0B1120] border-t border-indigo-900/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="inline-block px-3 py-1 rounded-full bg-indigo-900/50 text-indigo-300 text-xs font-semibold uppercase tracking-wider mb-4">Стихии</span>
                <h2 class="text-3xl md:text-4xl font-serif font-bold text-white mb-4">Четыре стихии зодиака</h2>
                <p class="text-indigo-300/80">Стихия вашего знака определяет базовый темперамент и способ взаимодействия с миром</p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Fire -->
                <div class="group p-6 rounded-2xl bg-gradient-to-b from-red-900/20 to-[#111827] border border-red-500/20 hover:border-red-500/40 transition-all hover:-translate-y-1">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-red-500/10 flex items-center justify-center">
                            <span class="text-2xl">🔥</span>
                        </div>
                        <h3 class="text-xl font-bold text-red-400">Огонь</h3>
                    </div>
                    <p class="text-indigo-300/70 text-sm mb-4 leading-relaxed">Энергия, страсть и стремление к действию. Огненные знаки — прирождённые лидеры.</p>
                    <div class="flex items-center gap-2 pt-4 border-t border-indigo-900/30">
                        <img src="/images/zodiac/aries.png" alt="Овен" class="w-7 h-7 opacity-80 hover:opacity-100 transition-opacity">
                        <img src="/images/zodiac/leo.png" alt="Лев" class="w-7 h-7 opacity-80 hover:opacity-100 transition-opacity">
                        <img src="/images/zodiac/sagittarius.png" alt="Стрелец" class="w-7 h-7 opacity-80 hover:opacity-100 transition-opacity">
                    </div>
                </div>

                <!-- Earth -->
                <div class="group p-6 rounded-2xl bg-gradient-to-b from-amber-900/20 to-[#111827] border border-amber-500/20 hover:border-amber-500/40 transition-all hover:-translate-y-1">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center">
                            <span class="text-2xl">🌍</span>
                        </div>
                        <h3 class="text-xl font-bold text-amber-400">Земля</h3>
                    </div>
                    <p class="text-indigo-300/70 text-sm mb-4 leading-relaxed">Стабильность, практичность и надёжность. Земные знаки строят прочный фундамент.</p>
                    <div class="flex items-center gap-2 pt-4 border-t border-indigo-900/30">
                        <img src="/images/zodiac/taurus.png" alt="Телец" class="w-7 h-7 opacity-80 hover:opacity-100 transition-opacity">
                        <img src="/images/zodiac/virgo.png" alt="Дева" class="w-7 h-7 opacity-80 hover:opacity-100 transition-opacity">
                        <img src="/images/zodiac/capricorn.png" alt="Козерог" class="w-7 h-7 opacity-80 hover:opacity-100 transition-opacity">
                    </div>
                </div>

                <!-- Air -->
                <div class="group p-6 rounded-2xl bg-gradient-to-b from-cyan-900/20 to-[#111827] border border-cyan-500/20 hover:border-cyan-500/40 transition-all hover:-translate-y-1">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-cyan-500/10 flex items-center justify-center">
                            <span class="text-2xl">💨</span>
                        </div>
                        <h3 class="text-xl font-bold text-cyan-400">Воздух</h3>
                    </div>
                    <p class="text-indigo-300/70 text-sm mb-4 leading-relaxed">Интеллект, общение и новые идеи. Воздушные знаки соединяют людей и концепции.</p>
                    <div class="flex items-center gap-2 pt-4 border-t border-indigo-900/30">
                        <img src="/images/zodiac/gemini.png" alt="Близнецы" class="w-7 h-7 opacity-80 hover:opacity-100 transition-opacity">
                        <img src="/images/zodiac/libra.png" alt="Весы" class="w-7 h-7 opacity-80 hover:opacity-100 transition-opacity">
                        <img src="/images/zodiac/aquarius.png" alt="Водолей" class="w-7 h-7 opacity-80 hover:opacity-100 transition-opacity">
                    </div>
                </div>

                <!-- Water -->
                <div class="group p-6 rounded-2xl bg-gradient-to-b from-blue-900/20 to-[#111827] border border-blue-500/20 hover:border-blue-500/40 transition-all hover:-translate-y-1">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center">
                            <span class="text-2xl">💧</span>
                        </div>
                        <h3 class="text-xl font-bold text-blue-400">Вода</h3>
                    </div>
                    <p class="text-indigo-300/70 text-sm mb-4 leading-relaxed">Эмоции, интуиция и глубина чувств. Водные знаки понимают то, что скрыто.</p>
                    <div class="flex items-center gap-2 pt-4 border-t border-indigo-900/30">
                        <img src="/images/zodiac/cancer.png" alt="Рак" class="w-7 h-7 opacity-80 hover:opacity-100 transition-opacity">
                        <img src="/images/zodiac/scorpio.png" alt="Скорпион" class="w-7 h-7 opacity-80 hover:opacity-100 transition-opacity">
                        <img src="/images/zodiac/pisces.png" alt="Рыбы" class="w-7 h-7 opacity-80 hover:opacity-100 transition-opacity">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-20 bg-[#080d15] border-t border-indigo-900/20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-2xl md:text-3xl font-serif font-bold text-white">Как получить карту?</h2>
            </div>

            <div class="flex flex-col md:flex-row items-center justify-center gap-4 md:gap-8">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-indigo-500/20 border border-indigo-500/30 flex items-center justify-center text-indigo-400 font-bold">1</div>
                    <span class="text-indigo-200 text-sm">Введите дату и место рождения</span>
                </div>
                <svg class="hidden md:block w-8 h-8 text-indigo-800" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-purple-500/20 border border-purple-500/30 flex items-center justify-center text-purple-400 font-bold">2</div>
                    <span class="text-indigo-200 text-sm">Получите ссылку на email</span>
                </div>
                <svg class="hidden md:block w-8 h-8 text-indigo-800" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gold-500/20 border border-gold-500/30 flex items-center justify-center text-gold-400 font-bold">3</div>
                    <span class="text-indigo-200 text-sm">Изучите карту и чат с ИИ</span>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-[#0B1120] border-t border-indigo-900/20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-serif font-bold text-white mb-4">Узнайте, что говорят звёзды</h2>
            <p class="text-indigo-300/80 mb-8">Бесплатный расчёт натальной карты с персональной расшифровкой</p>
            <a href="#calcForm" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-500 hover:to-indigo-600 text-white font-bold rounded-xl shadow-lg transition-all transform hover:scale-105 border border-indigo-500/50">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
                Рассчитать бесплатно
            </a>
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
        <div class="absolute inset-0 bg-[#0B1120]/98 backdrop-blur-sm"></div>

        <!-- Content -->
        <div class="relative z-10 max-w-sm w-full px-6">

            <!-- 1. LOADING STATE -->
            <div id="loadingState">
                <div class="bg-[#111827] rounded-2xl border border-indigo-900/50 p-8 shadow-2xl">
                    <!-- Loader Animation -->
                    <div class="flex justify-center mb-8">
                        <div class="relative">
                            <!-- Outer ring -->
                            <div class="w-20 h-20 rounded-full border-2 border-indigo-900/50"></div>
                            <!-- Spinning arc -->
                            <div class="absolute inset-0 w-20 h-20 rounded-full border-2 border-transparent border-t-indigo-500 animate-spin"></div>
                            <!-- Inner glow -->
                            <div class="absolute inset-3 w-14 h-14 rounded-full bg-indigo-500/10 animate-pulse"></div>
                            <!-- Center icon -->
                            <div class="absolute inset-0 flex items-center justify-center">
                                <svg class="w-8 h-8 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Status Text -->
                    <h3 class="text-xl font-semibold text-white text-center mb-2">Рассчитываем карту</h3>
                    <p class="text-indigo-400 text-sm text-center mb-6" id="statusText">Подключение к эфемеридам...</p>

                    <!-- Progress Bar -->
                    <div class="relative w-full h-2 bg-indigo-900/30 rounded-full overflow-hidden mb-2">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-500 h-full transition-all duration-500 ease-out rounded-full"
                            style="width: 0%" id="progressBar"></div>
                    </div>
                    <p class="text-indigo-500 text-xs text-right" id="percentage">0%</p>
                </div>
            </div>

        </div>
    </div>

    <!-- Email Sent Fullscreen Message -->
    <div id="emailSentModal" class="fixed inset-0 z-[100] hidden">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-[#0B1120]"></div>

        <!-- Content -->
        <div class="relative z-10 flex flex-col items-center justify-center min-h-screen px-4">
            <div class="w-24 h-24 bg-emerald-500/10 rounded-full flex items-center justify-center mb-8">
                <svg class="w-12 h-12 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>

            <h2 class="text-3xl font-bold text-white mb-4">Проверьте почту!</h2>
            <p class="text-indigo-300 text-center max-w-md mb-2">
                Мы отправили вам письмо со ссылкой на вашу натальную карту.
            </p>
            <p class="text-indigo-500 text-sm text-center mb-8">
                Не забудьте проверить папку <span class="text-indigo-400">Спам</span>, если письмо не пришло.
            </p>

            <button onclick="document.getElementById('emailSentModal').classList.add('hidden'); resetForm();"
                class="px-8 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-medium rounded-lg transition-colors">
                Понятно
            </button>
        </div>
    </div>

    <!-- Styles -->
    <style>
        .animate-fade-in-up {
            animation: fadeInUp 0.3s ease-out forwards;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
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
        const purposeInput = document.querySelector('input[name="purpose"]');
        const birthDateInput = document.getElementById('birth_date');
        const birthTimeInput = document.getElementById('birth_time');
        const cityIdInputValidation = document.getElementById('city_id');

        function validateForm() {
            const name = nameInput.value.trim();
            const email = emailInput.value.trim();
            const gender = Array.from(genderInputs).some(input => input.checked);
            const purpose = purposeInput ? purposeInput.value : 'general';
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
        birthDateInput.addEventListener('change', validateForm);
        birthTimeInput.addEventListener('change', validateForm);

        // Initial validation on page load (for Moscow default)
        validateForm();

        if (calcForm) {
        calcForm.addEventListener('submit', function (e) {
            e.preventDefault();

            // Reset states
            document.getElementById('loadingState')?.classList.remove('hidden');
            document.getElementById('successState')?.classList.add('hidden');

            // Show Modal
            const modal = document.getElementById('processingModal');
            if (modal) modal.classList.remove('hidden');

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
                if (progressBar) progressBar.style.width = step.pct + '%';
                if (statusText) statusText.innerText = step.text;
                if (percentageText) percentageText.innerText = step.pct + '%';
                currentStep++;

                if (currentStep < steps.length) {
                    setTimeout(nextAnimationStep, 800);
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
                        // If user is logged in, redirect to chart
                        if (data.redirect && data.redirect.includes('charts/show')) {
                            window.location.href = data.redirect;
                        } else {
                            // Show email sent modal
                            document.getElementById('processingModal').classList.add('hidden');
                            document.getElementById('emailSentModal').classList.remove('hidden');
                        }
                    }, 4000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Произошла ошибка. Пожалуйста, попробуйте еще раз.');
                    const modal = document.getElementById('processingModal');
                    if (modal) modal.classList.add('hidden');
                });
            });
        }
        // Reset form function
        function resetForm() {
            if (calcForm) calcForm.reset();
            document.getElementById('city_id').value = '';
            document.getElementById('city-details').classList.add('hidden');
            validateForm();
        }

        // Close processing modal
        function closeProcessingModal() {
            document.getElementById('processingModal').classList.add('hidden');
        }

        // City search with backend API
        const searchInput = document.getElementById('birth_place_search');
        const cityIdInput = document.getElementById('city_id');
        const dropdown = document.getElementById('cities-dropdown');
        const cityDetails = document.getElementById('city-details');
        const displayCountry = document.getElementById('display-country');
        const displayCity = document.getElementById('display-city');
        const displayLatitude = document.getElementById('display-latitude');
        const displayLongitude = document.getElementById('display-longitude');
        const searchSpinner = document.getElementById('search-spinner');
        const searchIcon = document.getElementById('search-icon');
        let searchTimeout = null;
        let currentQuery = '';

        // Country code to Russian name mapping
        const countryNames = {
            'AD': 'Андорра', 'AE': 'ОАЭ', 'AL': 'Албания', 'AM': 'Армения', 'AT': 'Австрия',
            'AU': 'Австралия', 'AX': 'Аландские о-ва', 'AZ': 'Азербайджан',
            'BA': 'Босния и Герцеговина', 'BE': 'Бельгия', 'BG': 'Болгария', 'BR': 'Бразилия', 'BY': 'Беларусь',
            'CA': 'Канада', 'CH': 'Швейцария', 'CN': 'Китай', 'CO': 'Колумбия',
            'CU': 'Куба', 'CY': 'Кипр', 'CZ': 'Чехия', 'DE': 'Германия',
            'DK': 'Дания', 'EE': 'Эстония', 'EG': 'Египет', 'ES': 'Испания',
            'FI': 'Финляндия', 'FJ': 'Фиджи', 'FR': 'Франция', 'GB': 'Великобритания',
            'GE': 'Грузия', 'GG': 'Гернси', 'GI': 'Гибралтар', 'GR': 'Греция',
            'HK': 'Гонконг', 'HR': 'Хорватия', 'HU': 'Венгрия', 'IE': 'Ирландия',
            'IL': 'Израиль', 'IM': 'Остров Мэн', 'IN': 'Индия', 'IR': 'Иран',
            'IT': 'Италия', 'JE': 'Джерси', 'JP': 'Япония', 'KE': 'Кения',
            'KG': 'Киргизия', 'KR': 'Южная Корея', 'KZ': 'Казахстан',
            'LI': 'Лихтенштейн', 'LT': 'Литва', 'LU': 'Люксембург',
            'LV': 'Латвия', 'MC': 'Монако', 'MD': 'Молдова', 'ME': 'Черногория',
            'MK': 'Северная Македония', 'MT': 'Мальта', 'MX': 'Мексика', 'NG': 'Нигерия',
            'NL': 'Нидерланды', 'NO': 'Норвегия', 'NP': 'Непал', 'NZ': 'Новая Зеландия',
            'OM': 'Оман', 'PE': 'Перу', 'PL': 'Польша', 'PS': 'Палестина',
            'PT': 'Португалия', 'RO': 'Румыния', 'RS': 'Сербия', 'RU': 'Россия',
            'SA': 'Саудовская Аравия', 'SE': 'Швеция', 'SG': 'Сингапур', 'SI': 'Словения',
            'SK': 'Словакия', 'SM': 'Сан-Марино', 'TH': 'Таиланд', 'TJ': 'Таджикистан',
            'TM': 'Туркменистан', 'TR': 'Турция', 'TW': 'Тайвань',
            'UA': 'Украина', 'US': 'США', 'UZ': 'Узбекистан', 'VA': 'Ватикан',
            'VN': 'Вьетнам', 'XK': 'Косово', 'ZA': 'ЮАР',
        };

        function getCountryName(code) {
            return countryNames[code] || code;
        }

        function showSpinner() {
            searchSpinner.classList.remove('hidden');
            searchIcon.classList.add('hidden');
        }

        function hideSpinner() {
            searchSpinner.classList.add('hidden');
            searchIcon.classList.remove('hidden');
        }

        function renderCities(cities, query) {
            dropdown.innerHTML = '';

            if (cities.length === 0) {
                dropdown.innerHTML = `
                    <div class="px-4 py-3 text-indigo-400 text-sm">
                        <i class="fas fa-search mr-2"></i>Город не найден. Попробуйте другое написание.
                    </div>`;
                dropdown.classList.remove('hidden');
                return;
            }

            cities.forEach(city => {
                const div = document.createElement('div');
                div.className = 'city-option px-4 py-3 hover:bg-indigo-800 cursor-pointer border-b border-gray-700 last:border-0 transition-colors active:bg-indigo-800';
                div.dataset.cityId = city.id;
                div.dataset.cityName = city.name_ru || city.name;
                div.dataset.cityNameLatin = city.name;
                div.dataset.cityCountry = city.country;
                div.dataset.cityLat = city.latitude;
                div.dataset.cityLon = city.longitude;
                div.dataset.cityTz = city.timezone_gmt;

                // Show both Russian and Latin names if different
                const displayName = city.name_ru || city.name;
                const secondaryName = city.name_ru && city.name_ru !== city.name ? city.name : null;
                const countryName = getCountryName(city.country);

                div.innerHTML = `
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-white text-sm font-medium">${displayName}</span>
                            ${secondaryName ? `<span class="text-indigo-400 text-xs ml-2">(${secondaryName})</span>` : ''}
                        </div>
                        <span class="text-indigo-500 text-xs">${countryName}</span>
                    </div>`;

                div.addEventListener('click', function() {
                    selectCity(this);
                });

                dropdown.appendChild(div);
            });

            dropdown.classList.remove('hidden');
        }

        async function searchCities(query) {
            query = query.trim();

            if (query.length < 2) {
                dropdown.classList.add('hidden');
                hideSpinner();
                return;
            }

            currentQuery = query;
            showSpinner();

            try {
                const response = await fetch(`/cities/search/${encodeURIComponent(query)}`);
                const cities = await response.json();

                // Only render if this is still the current query
                if (query === currentQuery) {
                    renderCities(cities, query);
                    hideSpinner();
                }
            } catch (error) {
                console.error('Failed to search cities:', error);
                hideSpinner();
                dropdown.innerHTML = `
                    <div class="px-4 py-3 text-red-400 text-sm">
                        <i class="fas fa-exclamation-circle mr-2"></i>Ошибка поиска. Попробуйте позже.
                    </div>`;
                dropdown.classList.remove('hidden');
            }
        }

        function selectCity(element) {
            // Set values - display Russian name, store ID
            const displayName = element.dataset.cityName;
            searchInput.value = displayName;
            cityIdInput.value = element.dataset.cityId;

            // Show details with Russian country name
            const countryCode = element.dataset.cityCountry || '-';
            displayCountry.textContent = getCountryName(countryCode);
            displayCity.textContent = displayName;
            displayLatitude.textContent = parseFloat(element.dataset.cityLat).toFixed(2) + '°';
            displayLongitude.textContent = parseFloat(element.dataset.cityLon).toFixed(2) + '°';
            cityDetails.classList.remove('hidden');

            dropdown.classList.add('hidden');

            // Validate form
            if (typeof validateForm === 'function') {
                validateForm();
            }
        }

        if (searchInput && dropdown && cityIdInput) {
            // Search as user types with debounce
            searchInput.addEventListener('input', function () {
                // Clear selection when typing
                if (cityIdInput.value) {
                    cityIdInput.value = '';
                    cityDetails.classList.add('hidden');
                    validateForm();
                }

                // Debounce search
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    searchCities(this.value);
                }, 300);
            });

            // Show dropdown on focus if there's text
            searchInput.addEventListener('focus', function () {
                if (this.value.trim().length >= 2) {
                    searchCities(this.value);
                }
            });

            // Hide dropdown when clicking outside
            document.addEventListener('click', function (e) {
                if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.add('hidden');
                }
            });

            // Keyboard navigation
            searchInput.addEventListener('keydown', function(e) {
                const options = dropdown.querySelectorAll('.city-option');
                const activeOption = dropdown.querySelector('.city-option.active');
                let currentIndex = Array.from(options).indexOf(activeOption);

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (activeOption) activeOption.classList.remove('active');
                    currentIndex = (currentIndex + 1) % options.length;
                    options[currentIndex]?.classList.add('active');
                    options[currentIndex]?.scrollIntoView({ block: 'nearest' });
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (activeOption) activeOption.classList.remove('active');
                    currentIndex = currentIndex <= 0 ? options.length - 1 : currentIndex - 1;
                    options[currentIndex]?.classList.add('active');
                    options[currentIndex]?.scrollIntoView({ block: 'nearest' });
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    if (activeOption) {
                        selectCity(activeOption);
                    } else if (options.length === 1) {
                        selectCity(options[0]);
                    }
                } else if (e.key === 'Escape') {
                    dropdown.classList.add('hidden');
                }
            });
        }
    </script>

    <!-- Login Modal -->
    <div id="loginModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-[#0B1120]/98 backdrop-blur-sm" onclick="closeLoginModal()"></div>

        <!-- Content -->
        <div class="relative z-10 max-w-md w-full px-6">
            <div class="bg-[#111827] rounded-2xl border border-indigo-900/50 p-8 shadow-2xl">
                <!-- Success State -->
                <div id="loginSuccessState" class="hidden">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-emerald-500/10 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Ссылка отправлена!</h3>
                        <p class="text-indigo-300 text-sm mb-6">Проверьте вашу почту и перейдите по ссылке для входа.</p>
                    </div>
                </div>

                <!-- Login Form -->
                <div id="loginFormState">
                    <div class="text-center mb-6">
                        <h3 class="text-xl font-bold text-white">Войти в аккаунт</h3>
                        <p class="text-indigo-300 text-sm mt-1">Введите email, мы отправим ссылку для входа</p>
                    </div>

                    <form id="loginForm" action="/login/send" method="POST">
                        @csrf
                        <div class="mb-4">
                            <input type="email" name="email" id="loginEmail" required autocomplete="email"
                                class="w-full bg-[#0f172a] border border-indigo-800 rounded-lg px-4 py-3 text-white placeholder-indigo-500 focus:border-indigo-500 focus:outline-none text-center"
                                placeholder="ваш@email.com" autofocus>
                            <p id="loginError" class="text-red-400 text-sm mt-2 text-center hidden"></p>
                        </div>

                        <button type="submit" id="loginSubmitBtn"
                            class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-500 text-white font-medium rounded-lg transition-colors">
                            Получить ссылку
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Login Modal Script -->
    <script>
        // Close modal function
        function closeLoginModal() {
            document.getElementById('loginModal').classList.add('hidden');
        }

        // Login Form
        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const submitBtn = document.getElementById('loginSubmitBtn');
                const errorMsg = document.getElementById('loginError');

                submitBtn.disabled = true;
                submitBtn.textContent = 'Отправка...';

                errorMsg.classList.add('hidden');

                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.errors && data.errors.email) {
                        errorMsg.textContent = data.errors.email[0];
                        errorMsg.classList.remove('hidden');
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Получить ссылку';
                    } else {
                        document.getElementById('loginFormState').classList.add('hidden');
                        document.getElementById('loginSuccessState').classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    errorMsg.textContent = 'Ошибка. Попробуйте еще раз.';
                    errorMsg.classList.remove('hidden');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Получить ссылку';
                });
            });
        }

        // Clear error when typing
        const loginEmail = document.getElementById('loginEmail');
        if (loginEmail) {
            loginEmail.addEventListener('input', function() {
                document.getElementById('loginError').classList.add('hidden');
            });
        }

        // Auto-open login modal if ?login=true in URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('login') === 'true') {
            document.getElementById('loginModal').classList.remove('hidden');
            const emailParam = urlParams.get('email');
            if (emailParam && loginEmail) {
                loginEmail.value = emailParam;
            }
            window.history.replaceState({}, document.title, window.location.pathname);
        }

        // Show chart created banner if ?chart_created=1
        if (urlParams.get('chart_created') === '1') {
            // Remove query param from URL
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    </script>
</body>

</html>