<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Установить пароль - AstroChart</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background-color: #0B1120;
            color: #e2e8f0;
            font-family: 'Lato', sans-serif;
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
    </style>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div
            class="w-full sm:max-w-md mt-6 px-6 py-8 bg-[#111827] shadow-md overflow-hidden sm:rounded-lg border border-indigo-900/30">
            <div class="text-center mb-6">
                <h1 class="text-3xl font-serif font-bold text-white tracking-widest">
                    ASTRO<span class="text-gold-400">CHART</span>
                </h1>
                <p class="text-indigo-300 mt-2">Установите пароль для доступа</p>
            </div>

            <form method="POST" action="{{ route('charts.store-password', $natalChart) }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-4">
                    <label for="password" class="block text-sm font-bold text-indigo-300 uppercase mb-2">Пароль</label>
                    <input id="password" type="password" name="password" required
                        class="w-full input-professional rounded-lg px-4 py-3" placeholder="Минимум 8 символов">
                    @error('password')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation"
                        class="block text-sm font-bold text-indigo-300 uppercase mb-2">Подтвердите пароль</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="w-full input-professional rounded-lg px-4 py-3" placeholder="Повторите пароль">
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-500 hover:to-indigo-600 text-white font-bold py-4 rounded-lg shadow-lg transition-all transform hover:scale-[1.01] border border-indigo-500/50">
                    Установить пароль и войти
                </button>

                <p class="text-xs text-center text-indigo-400/70 mt-4">
                    После установки пароля вы получите доступ к вашей натальной карте
                </p>
            </form>
        </div>
    </div>
</body>

</html>