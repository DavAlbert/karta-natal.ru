<!DOCTYPE html>
<html lang="ru" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Условия использования | Karta-Natal.ru</title>
    <meta name="description" content="Условия использования сервиса Karta-Natal.ru для расчёта натальной карты онлайн.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://karta-natal.ru/terms">
    
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
            <h1 class="text-3xl md:text-4xl font-serif font-bold text-white mb-8">Условия использования</h1>

            <div class="prose prose-invert prose-indigo max-w-none space-y-8 text-indigo-200">
                <p class="text-indigo-300">
                    Дата вступления в силу: {{ date('d.m.Y') }}
                </p>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">1. Общие положения</h2>
                    <p>
                        Настоящие Условия использования (далее — «Условия») регулируют отношения между компанией SMART CREATOR AI LLC (далее — «Администрация») и пользователем сервиса Karta-Natal.ru (далее — «Сервис»).
                    </p>
                    <p class="mt-4">
                        Используя Сервис, вы подтверждаете, что ознакомились с настоящими Условиями, понимаете их и соглашаетесь их соблюдать. Если вы не согласны с Условиями, пожалуйста, прекратите использование Сервиса.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">2. Описание Сервиса</h2>
                    <p>
                        Сервис Karta-Natal.ru предоставляет возможность:
                    </p>
                    <ul class="list-disc pl-6 space-y-2 mt-4">
                        <li>Рассчитать натальную карту по дате, времени и месту рождения</li>
                        <li>Получить расшифровку положения планет в знаках зодиака и домах</li>
                        <li>Просмотреть аспекты между планетами</li>
                        <li>Получить персонализированные астрологические интерпретации с использованием технологий искусственного интеллекта</li>
                        <li>Общаться с ИИ-астрологом для получения ответов на вопросы</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">3. Информационный характер услуг</h2>
                    <p>
                        <strong class="text-white">Важно понимать:</strong> Сервис предоставляет информацию исключительно в развлекательных и познавательных целях. Астрология не является наукой, и результаты расчётов не должны рассматриваться как:
                    </p>
                    <ul class="list-disc pl-6 space-y-2 mt-4">
                        <li>Медицинские рекомендации или диагнозы</li>
                        <li>Финансовые или инвестиционные советы</li>
                        <li>Юридические консультации</li>
                        <li>Психологическая помощь или терапия</li>
                        <li>Руководство к принятию важных жизненных решений</li>
                    </ul>
                    <p class="mt-4">
                        Администрация не несёт ответственности за решения, принятые на основе информации, полученной через Сервис.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">4. Регистрация и аккаунт</h2>
                    <p>
                        Для использования некоторых функций Сервиса требуется предоставление адреса электронной почты. Вы обязуетесь:
                    </p>
                    <ul class="list-disc pl-6 space-y-2 mt-4">
                        <li>Предоставлять достоверную информацию</li>
                        <li>Не передавать доступ к своему аккаунту третьим лицам</li>
                        <li>Немедленно уведомлять Администрацию о несанкционированном доступе к вашему аккаунту</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">5. Права интеллектуальной собственности</h2>
                    <p>
                        Все материалы Сервиса, включая дизайн, тексты, графику, программный код, алгоритмы расчётов и интерпретации, являются интеллектуальной собственностью SMART CREATOR AI LLC или используются на законных основаниях.
                    </p>
                    <p class="mt-4">
                        Запрещается копирование, распространение, модификация или использование материалов Сервиса без письменного разрешения Администрации.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">6. Использование ИИ-технологий</h2>
                    <p>
                        Сервис использует технологии искусственного интеллекта для генерации персонализированных интерпретаций и ответов на вопросы пользователей. Вы понимаете и соглашаетесь с тем, что:
                    </p>
                    <ul class="list-disc pl-6 space-y-2 mt-4">
                        <li>ИИ-генерируемый контент создаётся автоматически и может содержать неточности</li>
                        <li>Ответы ИИ-астролога не заменяют консультацию квалифицированного специалиста</li>
                        <li>Администрация не гарантирует точность, полноту или применимость ИИ-генерируемого контента</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">7. Запрещённые действия</h2>
                    <p>При использовании Сервиса запрещается:</p>
                    <ul class="list-disc pl-6 space-y-2 mt-4">
                        <li>Нарушать работу Сервиса или пытаться получить несанкционированный доступ</li>
                        <li>Использовать автоматизированные средства для массового сбора данных</li>
                        <li>Распространять вредоносное программное обеспечение</li>
                        <li>Использовать Сервис в противоправных целях</li>
                        <li>Выдавать себя за другое лицо</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">8. Ограничение ответственности</h2>
                    <p>
                        Сервис предоставляется «как есть» без каких-либо гарантий, явных или подразумеваемых. Администрация не гарантирует:
                    </p>
                    <ul class="list-disc pl-6 space-y-2 mt-4">
                        <li>Бесперебойную работу Сервиса</li>
                        <li>Отсутствие ошибок или неточностей</li>
                        <li>Соответствие результатов вашим ожиданиям</li>
                    </ul>
                    <p class="mt-4">
                        В максимальной степени, допускаемой применимым законодательством, Администрация не несёт ответственности за любые прямые, косвенные, случайные или последующие убытки, связанные с использованием Сервиса.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">9. Изменение условий</h2>
                    <p>
                        Администрация оставляет за собой право изменять настоящие Условия в любое время. Актуальная версия Условий всегда доступна на данной странице. Продолжая использовать Сервис после внесения изменений, вы соглашаетесь с обновлёнными Условиями.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">10. Применимое право</h2>
                    <p>
                        Настоящие Условия регулируются и толкуются в соответствии с законодательством. Все споры, возникающие в связи с использованием Сервиса, подлежат разрешению в соответствии с применимым законодательством.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">11. Контактная информация</h2>
                    <p>
                        По всем вопросам, связанным с использованием Сервиса, вы можете обратиться:
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
                    <a href="/privacy" class="text-indigo-400 hover:text-white transition-colors">Политика конфиденциальности</a>
                    <a href="/terms" class="text-white font-medium">Условия использования</a>
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
