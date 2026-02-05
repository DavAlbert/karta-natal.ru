<!DOCTYPE html>
<html lang="ru" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Политика конфиденциальности | Karta-Natal.ru</title>
    <meta name="description" content="Политика конфиденциальности сервиса Karta-Natal.ru. Узнайте, как мы собираем, используем и защищаем ваши персональные данные.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://karta-natal.ru/privacy">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html, body {
            background-color: #0B1120;
            color: #e2e8f0;
            overflow-x: hidden;
            max-width: 100%;
        }
        .text-gold { color: #fbbf24; }
    </style>
</head>

<body class="font-sans antialiased">
    <!-- Navbar -->
    <nav class="fixed w-full z-50 border-b border-indigo-900/30 bg-[#0B1120]/95 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="/" class="flex items-center gap-3">
                    <span class="text-2xl font-serif font-bold text-white tracking-widest">
                        КАРТА<span class="text-gold">НАТАЛ</span>
                    </span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main class="pt-32 pb-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl md:text-4xl font-serif font-bold text-white mb-8">Политика конфиденциальности</h1>

            <div class="prose prose-invert prose-indigo max-w-none space-y-8 text-indigo-200">
                <p class="text-indigo-300">
                    Дата вступления в силу: {{ date('d.m.Y') }}
                </p>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">1. Общие положения</h2>
                    <p>
                        Настоящая Политика конфиденциальности определяет порядок обработки персональных данных пользователей сервиса Karta-Natal.ru (далее — «Сервис»), принадлежащего компании SMART CREATOR AI LLC (далее — «Оператор»).
                    </p>
                    <p>
                        Используя Сервис, вы соглашаетесь с условиями настоящей Политики конфиденциальности. Если вы не согласны с этими условиями, пожалуйста, не используйте Сервис.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">2. Какие данные мы собираем</h2>
                    <p>Для предоставления услуг по расчёту натальной карты мы собираем следующие данные:</p>
                    <ul class="list-disc pl-6 space-y-2 mt-4">
                        <li><strong class="text-white">Имя</strong> — для персонализации результатов расчёта</li>
                        <li><strong class="text-white">Адрес электронной почты</strong> — для отправки результатов и авторизации</li>
                        <li><strong class="text-white">Дата рождения</strong> — необходима для астрологических расчётов</li>
                        <li><strong class="text-white">Время рождения</strong> — необходимо для точного расчёта асцендента и домов</li>
                        <li><strong class="text-white">Место рождения</strong> — необходимо для расчёта положения планет</li>
                        <li><strong class="text-white">Пол</strong> — для персонализации интерпретаций</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">3. Как мы используем данные</h2>
                    <p>Собранные данные используются исключительно для:</p>
                    <ul class="list-disc pl-6 space-y-2 mt-4">
                        <li>Расчёта вашей натальной карты</li>
                        <li>Генерации персонализированных астрологических интерпретаций</li>
                        <li>Отправки результатов расчёта на вашу электронную почту</li>
                        <li>Авторизации и доступа к вашему аккаунту</li>
                        <li>Отправки информационных сообщений (при вашем согласии)</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">4. Хранение и защита данных</h2>
                    <p>
                        Мы принимаем все необходимые организационные и технические меры для защиты персональных данных от неправомерного доступа, уничтожения, изменения, блокирования, копирования и распространения.
                    </p>
                    <p class="mt-4">
                        Данные хранятся на защищённых серверах. Доступ к персональным данным имеют только уполномоченные сотрудники, которые обязаны соблюдать конфиденциальность.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">5. Передача данных третьим лицам</h2>
                    <p>
                        Мы не продаём, не обмениваем и не передаём ваши персональные данные третьим лицам без вашего согласия, за исключением случаев:
                    </p>
                    <ul class="list-disc pl-6 space-y-2 mt-4">
                        <li>Требований действующего законодательства</li>
                        <li>Защиты наших законных прав и интересов</li>
                        <li>Использования сервисов обработки данных (например, почтовые сервисы), при условии соблюдения ими конфиденциальности</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">6. Файлы cookie</h2>
                    <p>
                        Сервис использует файлы cookie для обеспечения корректной работы, авторизации пользователей и улучшения качества обслуживания. Вы можете отключить cookie в настройках браузера, однако это может повлиять на функциональность Сервиса.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">7. Ваши права</h2>
                    <p>Вы имеете право:</p>
                    <ul class="list-disc pl-6 space-y-2 mt-4">
                        <li>Получить информацию о ваших персональных данных, которые мы обрабатываем</li>
                        <li>Потребовать исправления неточных данных</li>
                        <li>Потребовать удаления ваших данных</li>
                        <li>Отозвать согласие на обработку данных</li>
                    </ul>
                    <p class="mt-4">
                        Для реализации своих прав свяжитесь с нами по электронной почте.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">8. Изменения политики</h2>
                    <p>
                        Мы оставляем за собой право вносить изменения в настоящую Политику конфиденциальности. Актуальная версия всегда доступна на данной странице. Продолжая использовать Сервис после внесения изменений, вы соглашаетесь с обновлённой Политикой.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">9. Контактная информация</h2>
                    <p>
                        По всем вопросам, связанным с обработкой персональных данных, вы можете обратиться:
                    </p>
                    <p class="mt-4">
                        <strong class="text-white">SMART CREATOR AI LLC</strong><br>
                        Сайт: <a href="https://karta-natal.ru" class="text-indigo-400 hover:text-white">karta-natal.ru</a>
                    </p>
                </section>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-[#050914] border-t border-indigo-900/20 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-8">
                <a href="/" class="text-xl font-serif font-bold text-white tracking-widest opacity-50">
                    КАРТА<span class="text-gold">НАТАЛ</span>
                </a>
                <nav class="flex flex-wrap justify-center gap-6 text-sm">
                    <a href="/privacy" class="text-white font-medium">Политика конфиденциальности</a>
                    <a href="/terms" class="text-indigo-400 hover:text-white transition-colors">Условия использования</a>
                </nav>
            </div>
            <div class="border-t border-indigo-900/20 pt-6 text-center">
                <p class="text-indigo-600 text-xs">
                    &copy; {{ date('Y') }} SMART CREATOR AI LLC. Все права защищены.
                </p>
            </div>
        </div>
    </footer>
</body>

</html>
