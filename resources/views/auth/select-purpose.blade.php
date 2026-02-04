<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Выберите цель - AstroChart</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700&family=Lato:wght@300;400;700&display=swap"
        rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.1.0/js/all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.1.0/css/fontawesome.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background-color: #0B1120;
            color: #e2e8f0;
            font-family: 'Lato', sans-serif;
        }

        .purpose-card {
            transition: all 0.3s ease;
        }

        .purpose-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(99, 102, 241, 0.25);
        }

        .purpose-card input:checked + div {
            border-color: #fbbf24;
            background-color: rgba(251, 191, 36, 0.08);
        }

        .purpose-card input:checked + div .purpose-icon {
            color: #fbbf24;
        }

        .purpose-card input:checked + div .purpose-check {
            opacity: 1;
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4 py-8">
        <div class="w-full max-w-4xl">
            <!-- Header -->
            <div class="text-center mb-10">
                <h1 class="text-2xl sm:text-3xl font-serif font-bold text-white tracking-wider">
                    ASTRO<span style="color: #fbbf24;">CHART</span>
                </h1>
                <p class="text-indigo-300 mt-4 text-lg">Select Your Purpose</p>
                <p class="text-indigo-400/60 mt-1 text-sm">Choose the main focus for your chart analysis</p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('charts.store-purpose', $chart) }}" id="purposeForm">
                @csrf

                <!-- Cards Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                    <!-- Love -->
                    <label class="purpose-card cursor-pointer">
                        <input type="radio" name="purpose" value="love" class="hidden" required>
                        <div class="relative flex flex-col items-center justify-center p-6 rounded-xl border-2 border-indigo-800/50 bg-indigo-950/20 hover:border-indigo-600/50">
                            <div class="purpose-check absolute top-3 right-3 opacity-0 transition-opacity">
                                <i class="fas fa-check-circle" style="color: #fbbf24;"></i>
                            </div>
                            <i class="fas fa-heart purpose-icon text-2xl text-pink-400 mb-3"></i>
                            <span class="text-white font-medium text-center">Love & Relationships</span>
                            <span class="text-indigo-400/60 text-xs text-center mt-1">Partnership dynamics, romantic indicators</span>
                        </div>
                    </label>

                    <!-- Career -->
                    <label class="purpose-card cursor-pointer">
                        <input type="radio" name="purpose" value="career" class="hidden" required>
                        <div class="relative flex flex-col items-center justify-center p-6 rounded-xl border-2 border-indigo-800/50 bg-indigo-950/20 hover:border-indigo-600/50">
                            <div class="purpose-check absolute top-3 right-3 opacity-0 transition-opacity">
                                <i class="fas fa-check-circle" style="color: #fbbf24;"></i>
                            </div>
                            <i class="fas fa-briefcase purpose-icon text-2xl text-blue-400 mb-3"></i>
                            <span class="text-white font-medium text-center">Career</span>
                            <span class="text-indigo-400/60 text-xs text-center mt-1">Professional path, ambitions</span>
                        </div>
                    </label>

                    <!-- Health -->
                    <label class="purpose-card cursor-pointer">
                        <input type="radio" name="purpose" value="health" class="hidden" required>
                        <div class="relative flex flex-col items-center justify-center p-6 rounded-xl border-2 border-indigo-800/50 bg-indigo-950/20 hover:border-indigo-600/50">
                            <div class="purpose-check absolute top-3 right-3 opacity-0 transition-opacity">
                                <i class="fas fa-check-circle" style="color: #fbbf24;"></i>
                            </div>
                            <i class="fas fa-heart-pulse purpose-icon text-2xl text-emerald-400 mb-3"></i>
                            <span class="text-white font-medium text-center">Health</span>
                            <span class="text-indigo-400/60 text-xs text-center mt-1">Vitality, wellness patterns</span>
                        </div>
                    </label>

                    <!-- Finance -->
                    <label class="purpose-card cursor-pointer">
                        <input type="radio" name="purpose" value="finance" class="hidden" required>
                        <div class="relative flex flex-col items-center justify-center p-6 rounded-xl border-2 border-indigo-800/50 bg-indigo-950/20 hover:border-indigo-600/50">
                            <div class="purpose-check absolute top-3 right-3 opacity-0 transition-opacity">
                                <i class="fas fa-check-circle" style="color: #fbbf24;"></i>
                            </div>
                            <i class="fas fa-coins purpose-icon text-2xl text-amber-400 mb-3"></i>
                            <span class="text-white font-medium text-center">Finance</span>
                            <span class="text-indigo-400/60 text-xs text-center mt-1">Resources, material security</span>
                        </div>
                    </label>

                    <!-- Personal Growth -->
                    <label class="purpose-card cursor-pointer">
                        <input type="radio" name="purpose" value="personal" class="hidden" required>
                        <div class="relative flex flex-col items-center justify-center p-6 rounded-xl border-2 border-indigo-800/50 bg-indigo-950/20 hover:border-indigo-600/50">
                            <div class="purpose-check absolute top-3 right-3 opacity-0 transition-opacity">
                                <i class="fas fa-check-circle" style="color: #fbbf24;"></i>
                            </div>
                            <i class="fas fa-leaf purpose-icon text-2xl text-green-400 mb-3"></i>
                            <span class="text-white font-medium text-center">Personal Growth</span>
                            <span class="text-indigo-400/60 text-xs text-center mt-1">Self-development, evolution</span>
                        </div>
                    </label>

                    <!-- General -->
                    <label class="purpose-card cursor-pointer">
                        <input type="radio" name="purpose" value="general" class="hidden" required>
                        <div class="relative flex flex-col items-center justify-center p-6 rounded-xl border-2 border-indigo-800/50 bg-indigo-950/20 hover:border-indigo-600/50">
                            <div class="purpose-check absolute top-3 right-3 opacity-0 transition-opacity">
                                <i class="fas fa-check-circle" style="color: #fbbf24;"></i>
                            </div>
                            <i class="fas fa-star purpose-icon text-2xl text-purple-400 mb-3"></i>
                            <span class="text-white font-medium text-center">General Analysis</span>
                            <span class="text-indigo-400/60 text-xs text-center mt-1">Complete natal overview</span>
                        </div>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-center">
                    <button type="submit" id="submitBtn" disabled
                        class="px-12 py-3.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-500 hover:to-indigo-600 text-white font-semibold rounded-lg shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none border border-indigo-500/30">
                        Continue
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const purposeInputs = document.querySelectorAll('input[name="purpose"]');
        const submitBtn = document.getElementById('submitBtn');

        purposeInputs.forEach(input => {
            input.addEventListener('change', function() {
                submitBtn.disabled = false;
            });
        });
    </script>
</body>

</html>
