<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: sans-serif;
            background-color: #0f172a;
            color: #e2e8f0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #1e293b;
            padding: 30px;
            border-radius: 8px;
            border: 1px solid #334155;
        }

        .btn {
            display: inline-block;
            background-color: #d4af37;
            color: #fff;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin-top: 20px;
        }

        h1 {
            color: #d4af37;
            margin-bottom: 20px;
        }

        p {
            line-height: 1.6;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Ваша карта готова! ✨</h1>
        <p>Здравствуйте, {{ $chart->user->name ?? 'Звездный Странник' }}!</p>
        <p>Расчет вашей натальной карты для <strong>{{ $chart->name }}</strong> успешно завершен.</p>
        <p>Мы проанализировали положение планет в момент вашего рождения.</p>

        <a href="{{ route('charts.show', $chart) }}" class="btn">Посмотреть Расшифровку</a>

        <p style="margin-top: 30px; font-size: 12px; color: #94a3b8;">
            Если кнопка не работает, скопируйте ссылку:<br>
            {{ route('charts.show', $chart) }}
        </p>
    </div>
</body>

</html>