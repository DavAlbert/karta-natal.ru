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
        <h1>Вход в аккаунт ✨</h1>
        <p>Здравствуйте, {{ $user->name ?? 'Звездный Странник' }}!</p>
        <p>Мы получили запрос на вход в ваш аккаунт.</p>

        <p><strong>Нажмите на кнопку ниже, чтобы войти:</strong></p>
        <a href="{{ route('magic.login.token', $token) }}" class="btn">Войти в аккаунт</a>

        <p style="margin-top: 30px; font-size: 12px; color: #94a3b8;">
            Эта ссылка действительна 15 минут.<br>
            Если вы не запрашивали вход, проигнорируйте это письмо.<br><br>
            Если кнопка не работаете, скопируйте ссылку:<br>
            {{ route('magic.login.token', $token) }}
        </p>
    </div>
</body>

</html>
