@php
    app()->setLocale($chart->locale ?? 'en');
    $chartUrl = !empty($chart->access_token)
        ? route('charts.access', ['token' => $chart->access_token])
        : locale_route('charts.show', ['natalChart' => $chart->id]);
@endphp
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="{{ $chart->locale ?? 'en' }}">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="x-apple-disable-message-reformatting" />
    <meta name="color-scheme" content="light" />
    <meta name="supported-color-schemes" content="light" />
    <title>{{ __('emails.chart_ready_subject') }}</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <style>
        table { border-collapse: collapse; }
        td, th { font-family: Arial, Helvetica, sans-serif; }
        a { text-decoration: none; }
    </style>
    <![endif]-->
    <style type="text/css">
        /* Reset */
        body, table, td, p, a, li, blockquote { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; }
        body { margin: 0; padding: 0; width: 100% !important; height: 100% !important; }
        /* Mobile */
        @media only screen and (max-width: 620px) {
            .email-container { width: 100% !important; max-width: 100% !important; }
            .email-content { padding: 24px 20px !important; }
            .email-header { padding: 24px 20px !important; }
            .email-footer { padding: 16px 20px !important; }
            .btn-td { padding: 13px 24px !important; }
            .star-decoration { font-size: 14px !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #0f0f23; font-family: Arial, Helvetica, sans-serif; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale;">
    {{-- Preheader (hidden preview text) --}}
    <div style="display: none; max-height: 0; overflow: hidden; mso-hide: all;">
        {{ __('emails.chart_ready_text') }}&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;
    </div>

    {{-- Background wrapper --}}
    <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #0f0f23;">
        <tr>
            <td align="center" style="padding: 32px 16px;">
                <!--[if mso]>
                <table role="presentation" align="center" border="0" cellspacing="0" cellpadding="0" width="560">
                <tr><td>
                <![endif]-->
                <table role="presentation" class="email-container" width="560" border="0" cellspacing="0" cellpadding="0" style="max-width: 560px; width: 100%; margin: 0 auto;">

                    {{-- Top decorative stars --}}
                    <tr>
                        <td align="center" style="padding: 0 0 16px 0;">
                            <span class="star-decoration" style="font-size: 16px; color: #d4af37; letter-spacing: 8px;">&#10022; &#10022; &#10022;</span>
                        </td>
                    </tr>

                    {{-- Main card --}}
                    <tr>
                        <td style="background-color: #1a1a3e; border-radius: 12px; border: 1px solid #2a2a5e; overflow: hidden;">
                            <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0">

                                {{-- Header --}}
                                <tr>
                                    <td class="email-header" align="center" style="padding: 32px 40px 24px 40px; border-bottom: 1px solid #2a2a5e;">
                                        <table role="presentation" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td align="center">
                                                    <span style="font-size: 28px; line-height: 1;">&#9734;</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="center" style="padding-top: 12px;">
                                                    <h1 style="margin: 0; font-size: 20px; font-weight: 700; color: #d4af37; letter-spacing: 2px; text-transform: uppercase;">NatalScope</h1>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                {{-- Body --}}
                                <tr>
                                    <td class="email-content" style="padding: 32px 40px 36px 40px;">
                                        <p style="margin: 0 0 6px 0; font-size: 17px; line-height: 1.5; color: #e8e8f0; font-weight: 700;">{{ __('emails.chart_ready_greeting', ['name' => $chart->name ?? '']) }}</p>
                                        <p style="margin: 0 0 28px 0; font-size: 15px; line-height: 1.7; color: #a8a8c8;">{{ __('emails.chart_ready_text') }}</p>

                                        {{-- CTA Button --}}
                                        <table role="presentation" border="0" cellspacing="0" cellpadding="0" width="100%">
                                            <tr>
                                                <td align="center">
                                                    <!--[if mso]>
                                                    <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ $chartUrl }}" style="height:48px;v-text-anchor:middle;width:220px;" arcsize="10%" strokecolor="#d4af37" fillcolor="#d4af37">
                                                    <w:anchorlock/>
                                                    <center style="color:#1a1a2e;font-family:Arial,Helvetica,sans-serif;font-size:15px;font-weight:bold;">{{ __('emails.chart_ready_button') }}</center>
                                                    </v:roundrect>
                                                    <![endif]-->
                                                    <!--[if !mso]><!-->
                                                    <table role="presentation" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td class="btn-td" style="border-radius: 6px; background-color: #d4af37;">
                                                                <a href="{{ $chartUrl }}" target="_blank" style="display: inline-block; padding: 14px 36px; color: #1a1a2e; text-decoration: none; font-size: 15px; font-weight: 700; font-family: Arial, Helvetica, sans-serif; border-radius: 6px; mso-padding-alt: 0;">{{ __('emails.chart_ready_button') }}</a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <!--<![endif]-->
                                                </td>
                                            </tr>
                                        </table>

                                        {{-- Fallback link --}}
                                        <table role="presentation" border="0" cellspacing="0" cellpadding="0" width="100%" style="margin-top: 28px;">
                                            <tr>
                                                <td style="border-top: 1px solid #2a2a5e; padding-top: 20px;">
                                                    <p style="margin: 0 0 6px 0; font-size: 13px; line-height: 1.6; color: #6a6a8a;">{{ __('emails.link_fallback') }}</p>
                                                    <p style="margin: 0; font-size: 13px; line-height: 1.6; word-break: break-all;"><a href="{{ $chartUrl }}" style="color: #d4af37; text-decoration: underline;">{{ $chartUrl }}</a></p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                {{-- Footer --}}
                                <tr>
                                    <td class="email-footer" align="center" style="padding: 20px 40px; background-color: #151533; border-top: 1px solid #2a2a5e;">
                                        <p style="margin: 0 0 4px 0; font-size: 12px; color: #6a6a8a; line-height: 1.5;">{{ __('emails.chart_ready_footer') }}</p>
                                        <p style="margin: 0; font-size: 12px; color: #4a4a6a; line-height: 1.5;">&copy; {{ date('Y') }} {{ __('emails.copyright') }}</p>
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>

                    {{-- Bottom decorative stars --}}
                    <tr>
                        <td align="center" style="padding: 16px 0 0 0;">
                            <span class="star-decoration" style="font-size: 16px; color: #d4af37; letter-spacing: 8px;">&#10022; &#10022; &#10022;</span>
                        </td>
                    </tr>

                </table>
                <!--[if mso]>
                </td></tr>
                </table>
                <![endif]-->
            </td>
        </tr>
    </table>
</body>
</html>
