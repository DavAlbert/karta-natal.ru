<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в аккаунт</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f5f5f5; font-family: Arial, Helvetica, sans-serif; -webkit-font-smoothing: antialiased;">
    <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #f5f5f5;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table role="presentation" width="600" border="0" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #1a1a2e; padding: 30px 40px; text-align: center;">
                            <h1 style="color: #d4af37; margin: 0; font-size: 22px; font-weight: normal; letter-spacing: 1px;">Natalnaya-Karta</h1>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 40px 30px 40px; color: #333333;">
                            <p style="margin: 0 0 8px 0; font-size: 16px; line-height: 1.5; color: #1a1a2e; font-weight: bold;">Здравствуйте, {{ $user->name ?? 'Пользователь' }}!</p>
                            <p style="margin: 0 0 24px 0; font-size: 15px; line-height: 1.6; color: #555555;">Мы получили запрос на вход в ваш аккаунт. Нажмите кнопку ниже для авторизации:</p>

                            <!-- Button -->
                            <table role="presentation" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="border-radius: 4px; background-color: #d4af37;">
                                        <a href="{{ route('magic.login.token', $token) }}" style="display: inline-block; padding: 14px 32px; color: #ffffff; text-decoration: none; font-size: 15px; font-weight: bold; border-radius: 4px;">Войти в аккаунт</a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 28px 0 0 0; font-size: 13px; line-height: 1.6; color: #888888;">
                                Ссылка действительна 15 минут.<br>
                                Если вы не запрашивали вход, проигнорируйте это письмо.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #fafafa; padding: 20px 40px; text-align: center; border-top: 1px solid #eeeeee;">
                            <p style="margin: 0; font-size: 12px; color: #999999; line-height: 1.5;">
                                Natalnaya-Karta — Ваша натальная карта онлайн<br>
                                <span style="color: #888888;">&copy; {{ date('Y') }} Все права защищены.</span>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
