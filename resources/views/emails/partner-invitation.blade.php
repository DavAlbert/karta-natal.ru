@php app()->setLocale($locale ?? 'en') @endphp
<!DOCTYPE html>
<html lang="{{ $locale ?? 'en' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('emails.partner_invite_heading') }}</title>
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
                                💫 {{ __('emails.partner_invite_heading') }}
                            </h1>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px;">
                            <p style="color: #F8FAFC; font-size: 18px; margin: 0 0 24px; line-height: 1.6;">
                                {{ __('emails.partner_invite_greeting', ['name' => $compatibility->partner_name]) }}
                            </p>

                            <p style="color: #94A3B8; font-size: 16px; margin: 0 0 24px; line-height: 1.6;">
                                {{ __('emails.partner_invite_text', ['name' => $initiatorName]) }}
                            </p>

                            <p style="color: #94A3B8; font-size: 16px; margin: 0 0 32px; line-height: 1.6;">
                                {{ __('emails.partner_invite_description') }}
                            </p>

                            <!-- Button -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center" style="padding: 0 0 32px;">
                                        <a href="{{ $verifyUrl }}" style="display: inline-block; background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #ffffff; text-decoration: none; padding: 16px 32px; border-radius: 8px; font-size: 16px; font-weight: 600;">
                                            {{ __('emails.partner_invite_button') }}
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="color: #94A3B8; font-size: 15px; margin: 0 0 16px; line-height: 1.6;">
                                {{ __('emails.partner_invite_after_confirm') }}
                            </p>

                            <ul style="color: #94A3B8; font-size: 15px; margin: 0 0 24px; padding-left: 20px; line-height: 1.8;">
                                <li>{{ __('emails.partner_invite_feature_1') }}</li>
                                <li>{{ __('emails.partner_invite_feature_2') }}</li>
                                <li>{{ __('emails.partner_invite_feature_3') }}</li>
                                <li>{{ __('emails.partner_invite_feature_4') }}</li>
                                <li>{{ __('emails.partner_invite_feature_5') }}</li>
                            </ul>

                            <p style="color: #64748b; font-size: 14px; margin: 0; padding: 16px; background: rgba(99, 102, 241, 0.1); border-radius: 8px; border-left: 3px solid #6366f1;">
                                <strong>{{ __('emails.partner_invite_expiry') }}</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 24px 40px; border-top: 1px solid #1e293b; text-align: center;">
                            <p style="color: #64748b; font-size: 14px; margin: 0 0 8px;">
                                {{ __('emails.partner_invite_footer') }}
                            </p>
                            <p style="color: #475569; font-size: 12px; margin: 0;">
                                {{ __('emails.partner_invite_ignore') }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
