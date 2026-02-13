<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Приглашение на проверку совместимости</title>
</head>
<body style="margin: 0; padding: 0; background-color: #0B1120; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #0B1120;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="background-color: #111827; border-radius: 16px; overflow: hidden; max-width: 100%;">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, rgba(236, 72, 153, 0.2), rgba(139, 92, 246, 0.15)); padding: 32px 40px; text-align: center;">
                            <h1 style="margin: 0; color: #F8FAFC; font-size: 24px; font-weight: 600;">
                                💫 Приглашение на проверку совместимости
                            </h1>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px;">
                            <p style="color: #F8FAFC; font-size: 18px; margin: 0 0 24px; line-height: 1.6;">
                                Здравствуйте, <strong>{{ $compatibility->partner_name }}</strong>!
                            </p>

                            <p style="color: #94A3B8; font-size: 16px; margin: 0 0 24px; line-height: 1.6;">
                                <strong style="color: #EC4899;">{{ $initiatorName }}</strong> приглашает вас проверить вашу астрологическую совместимость.
                            </p>

                            <p style="color: #94A3B8; font-size: 16px; margin: 0 0 32px; line-height: 1.6;">
                                По вашим данным уже рассчитана натальная карта. Нажмите на кнопку ниже, чтобы посмотреть свою карту и подтвердить участие:
                            </p>

                            <!-- Button -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center" style="padding: 0 0 32px;">
                                        <a href="{{ $verifyUrl }}" style="display: inline-block; background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #ffffff; text-decoration: none; padding: 16px 32px; border-radius: 8px; font-size: 16px; font-weight: 600;">
                                            Посмотреть мою натальную карту
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="color: #94A3B8; font-size: 15px; margin: 0 0 16px; line-height: 1.6;">
                                После подтверждения вы оба получите доступ к полному отчёту:
                            </p>

                            <ul style="color: #94A3B8; font-size: 15px; margin: 0 0 24px; padding-left: 20px; line-height: 1.8;">
                                <li>Ваша персональная натальная карта с расшифровкой</li>
                                <li>Общий балл совместимости</li>
                                <li>16 категорий анализа отношений</li>
                                <li>Сильные стороны и потенциальные вызовы</li>
                                <li>Рекомендации от ИИ-астролога</li>
                            </ul>

                            <p style="color: #64748b; font-size: 14px; margin: 0; padding: 16px; background: rgba(99, 102, 241, 0.1); border-radius: 8px; border-left: 3px solid #6366f1;">
                                <strong>Важно:</strong> Ссылка действительна 7 дней.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 24px 40px; border-top: 1px solid #1e293b; text-align: center;">
                            <p style="color: #64748b; font-size: 14px; margin: 0 0 8px;">
                                С уважением, команда Karta-Natal.ru
                            </p>
                            <p style="color: #475569; font-size: 12px; margin: 0;">
                                Если вы не ожидали это письмо, просто проигнорируйте его.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
