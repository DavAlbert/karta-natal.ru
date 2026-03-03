@php app()->setLocale($chart->locale ?? 'en') @endphp
<!DOCTYPE html>
<html lang="{{ $chart->locale ?? 'en' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('emails.chart_ready_subject') }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f5f5f5; font-family: Arial, Helvetica, sans-serif; -webkit-font-smoothing: antialiased;">
    <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #f5f5f5;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table role="presentation" width="600" border="0" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #1a1a2e; padding: 30px 40px; text-align: center;">
                            <h1 style="color: #d4af37; margin: 0; font-size: 22px; font-weight: normal; letter-spacing: 1px;">{{ __('emails.chart_ready_heading') }}</h1>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 40px 30px 40px; color: #333333;">
                            <p style="margin: 0 0 8px 0; font-size: 16px; line-height: 1.5; color: #1a1a2e; font-weight: bold;">{{ __('emails.chart_ready_greeting', ['name' => $chart->name ?? '']) }}</p>
                            <p style="margin: 0 0 24px 0; font-size: 15px; line-height: 1.6; color: #555555;">{{ __('emails.chart_ready_text') }}</p>

                            <!-- Button -->
                            <table role="presentation" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="border-radius: 4px; background-color: #d4af37;">
                                        <a href="{{ route('charts.access', $chart->access_token) }}" style="display: inline-block; padding: 14px 32px; color: #ffffff; text-decoration: none; font-size: 15px; font-weight: bold; border-radius: 4px;">{{ __('emails.chart_ready_button') }}</a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 28px 0 0 0; font-size: 13px; line-height: 1.6; color: #888888;">
                                {{ __('emails.link_fallback') }}<br>
                                <a href="{{ route('charts.access', $chart->access_token) }}" style="color: #d4af37; text-decoration: none;">{{ route('charts.access', $chart->access_token) }}</a>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #fafafa; padding: 20px 40px; text-align: center; border-top: 1px solid #eeeeee;">
                            <p style="margin: 0; font-size: 12px; color: #999999; line-height: 1.5;">
                                {{ __('emails.chart_ready_footer') }}<br>
                                <span style="color: #888888;">&copy; {{ date('Y') }} {{ __('emails.copyright') }}</span>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
