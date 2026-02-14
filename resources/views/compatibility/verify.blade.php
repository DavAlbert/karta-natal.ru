<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Подтверждение совместимости — Karta-Natal.ru</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
        (function(m,e,t,r,i,k,a){
            m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
            m[i].l=1*new Date();
            for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
            k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)
        })(window, document,'script','https://mc.yandex.ru/metrika/tag.js?id=106818761', 'ym');

        ym(106818761, 'init', {ssr:true, webvisor:true, clickmap:true, ecommerce:"dataLayer", referrer: document.referrer, url: location.href, accurateTrackBounce:true, trackLinks:true});
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/106818761" style="position:absolute; left:-9999px;" alt="" /></div></noscript>

    <style>
        * { box-sizing: border-box; }
        body {
            background: #0B0E14;
            color: #F8FAFC;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            max-width: 480px;
            width: 100%;
            padding: 1.5rem;
        }
        .card {
            background: #11161F;
            border: 1px solid #2A3441;
            border-radius: 1rem;
            padding: 2rem;
            text-align: center;
        }
        .icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #EC4899, #8B5CF6);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 1.75rem;
        }
        h1 {
            font-family: 'Cinzel', serif;
            font-size: 1.5rem;
            margin: 0 0 0.5rem;
            color: #EAB308;
        }
        .subtitle {
            color: #94A3B8;
            margin: 0 0 1.5rem;
            font-size: 0.95rem;
            line-height: 1.6;
        }
        .info-box {
            background: #1A212E;
            border: 1px solid #2A3441;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
            text-align: left;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            font-size: 0.9rem;
            border-bottom: 1px solid #2A3441;
        }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #94A3B8; }
        .info-value { color: #F8FAFC; font-weight: 500; }
        .checkbox-group {
            text-align: left;
            margin-bottom: 1.5rem;
        }
        .checkbox-label {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            cursor: pointer;
            font-size: 0.8rem;
            color: #94A3B8;
            line-height: 1.5;
        }
        .checkbox-label input {
            margin-top: 3px;
            accent-color: #818CF8;
        }
        .checkbox-label a {
            color: #818CF8;
            text-decoration: underline;
        }
        .btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            font-weight: 600;
            font-size: 1rem;
            border: none;
            border-radius: 0.75rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
        .error { color: #EF4444; font-size: 0.875rem; margin-top: 1rem; }
        .features {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #2A3441;
            text-align: left;
        }
        .features-title {
            font-size: 0.85rem;
            color: #94A3B8;
            margin-bottom: 0.75rem;
        }
        .feature {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            color: #F8FAFC;
            padding: 0.35rem 0;
        }
        .feature span:first-child { color: #22C55E; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="icon">💫</div>
            <h1>Привет, {{ $compatibility->partner_name }}!</h1>
            <p class="subtitle">
                <strong>{{ $initiatorName }}</strong> приглашает вас проверить астрологическую совместимость
            </p>

            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">Дата рождения</span>
                    <span class="info-value">{{ $compatibility->partner_birth_date->format('d.m.Y') }}</span>
                </div>
                @if($compatibility->partner_birth_time)
                <div class="info-row">
                    <span class="info-label">Время</span>
                    <span class="info-value">{{ $compatibility->partner_birth_time }}</span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">Место рождения</span>
                    <span class="info-value">{{ $compatibility->partnerCity->name_ru ?? $compatibility->partnerCity->name }}</span>
                </div>
            </div>

            <div class="checkbox-group">
                <label class="checkbox-label">
                    <input type="checkbox" id="marketing" checked>
                    <span>Я хочу получать персональные астрологические рекомендации и новости</span>
                </label>
            </div>

            <div class="checkbox-group">
                <label class="checkbox-label">
                    <input type="checkbox" id="terms" required>
                    <span>Я принимаю <a href="/terms" target="_blank">условия использования</a> и <a href="/privacy" target="_blank">политику конфиденциальности</a></span>
                </label>
            </div>

            <button class="btn" id="confirmBtn" onclick="confirm()">
                Подтвердить и посмотреть результат
            </button>
            <p class="error" id="error" style="display: none;"></p>

            <div class="features">
                <div class="features-title">Вы получите:</div>
                <div class="feature"><span>✓</span> Вашу персональную натальную карту</div>
                <div class="feature"><span>✓</span> Полный анализ совместимости</div>
                <div class="feature"><span>✓</span> Рекомендации от ИИ-астролога</div>
            </div>
        </div>
    </div>

    <script>
        async function confirm() {
            const termsChecked = document.getElementById('terms').checked;
            if (!termsChecked) {
                document.getElementById('error').textContent = 'Пожалуйста, примите условия использования';
                document.getElementById('error').style.display = 'block';
                return;
            }

            const btn = document.getElementById('confirmBtn');
            const error = document.getElementById('error');
            const marketing = document.getElementById('marketing').checked;

            btn.disabled = true;
            btn.textContent = 'Загрузка...';
            error.style.display = 'none';

            try {
                const response = await fetch('{{ route("compatibility.confirm", $compatibility->verification_token) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ marketing_consent: marketing })
                });

                if (response.status === 419) {
                    error.textContent = 'Сессия истекла. Пожалуйста, обновите страницу.';
                    error.style.display = 'block';
                    btn.disabled = false;
                    btn.textContent = 'Подтвердить и посмотреть результат';
                    return;
                }

                const data = await response.json();

                if (data.success && data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    error.textContent = data.message || 'Произошла ошибка';
                    error.style.display = 'block';
                    btn.disabled = false;
                    btn.textContent = 'Подтвердить и посмотреть результат';
                }
            } catch (e) {
                error.textContent = 'Ошибка соединения. Попробуйте ещё раз.';
                error.style.display = 'block';
                btn.disabled = false;
                btn.textContent = 'Подтвердить и посмотреть результат';
            }
        }
    </script>
</body>
</html>
