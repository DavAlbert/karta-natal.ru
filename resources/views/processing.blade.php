<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Выполнение расчета...</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        slate: { 850: '#1e293b', 900: '#0f172a' },
                        gold: { 500: '#d4af37' }
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-slate-900 text-white h-screen flex flex-col items-center justify-center font-sans">

    <div class="max-w-md w-full px-6 text-center">
        <!-- Spinner/Animation -->
        <div class="relative w-20 h-20 mx-auto mb-8">
            <div class="absolute inset-0 border-4 border-slate-700 rounded-full"></div>
            <div class="absolute inset-0 border-t-4 border-gold-500 rounded-full animate-spin"></div>
        </div>

        <h2 class="text-2xl font-serif font-bold mb-2">Анализ положения планет</h2>
        <p class="text-slate-400 text-sm mb-8" id="status-text">Инициализация эфемерид...</p>

        <!-- Progress Bar -->
        <div class="w-full bg-slate-800 rounded-full h-1.5 mb-2 overflow-hidden">
            <div class="bg-gold-500 h-1.5 rounded-full transition-all duration-300 ease-out" style="width: 0%"
                id="progress-bar"></div>
        </div>
        <div class="text-right text-xs text-slate-500 font-mono" id="percentage">0%</div>

    </div>

    <script>
        const steps = [
            { pct: 15, text: "Расчет координат Солнца..." },
            { pct: 30, text: "Определение лунных узлов..." },
            { pct: 55, text: "Вычисление системы домов (Placidus)..." },
            { pct: 75, text: "Анализ мажорных аспектов..." },
            { pct: 90, text: "Формирование отчета..." },
            { pct: 100, text: "Готово!" }
        ];

        let currentStep = 0;
        const progressBar = document.getElementById('progress-bar');
        const statusText = document.getElementById('status-text');
        const percentageText = document.getElementById('percentage');

        function nextStep() {
            if (currentStep >= steps.length) {
                window.location.href = "{{ route('register') }}";
                return;
            }

            const step = steps[currentStep];
            progressBar.style.width = step.pct + '%';
            statusText.innerText = step.text;
            percentageText.innerText = step.pct + '%';

            currentStep++;

            // Random delay between 500ms and 1500ms for realism
            const delay = Math.random() * 1000 + 500;
            setTimeout(nextStep, delay);
        }

        setTimeout(nextStep, 500);
    </script>
</body>

</html>