<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $subject ?? 'TOP SOCIETY' }}</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f5; font-family:Georgia,'Times New Roman',serif;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f5; padding:32px 16px;">
    <tr>
        <td align="center">
            <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%; background-color:#ffffff; border-radius:4px; overflow:hidden;">

                {{-- Masthead --}}
                <tr>
                    <td style="background-color:#0a0a0a; padding:28px 32px; text-align:center;">
                        <span style="font-family:Georgia,serif; font-size:26px; font-weight:700; color:#ffffff; letter-spacing:0.5px;">TOP SOCIETY</span>
                        <div style="margin-top:4px; font-family:Arial,sans-serif; font-size:10px; letter-spacing:2px; color:#a3a3a3; text-transform:uppercase;">
                            Nigeria &amp; Global Journal of Authority
                        </div>
                    </td>
                </tr>

                {{-- Content --}}
                <tr>
                    <td style="padding:36px 32px; font-family:Arial,sans-serif; color:#262626; font-size:15px; line-height:1.6;">
                        {{ $slot }}
                    </td>
                </tr>

                {{-- Footer / unsubscribe --}}
                <tr>
                    <td style="background-color:#fafafa; padding:24px 32px; border-top:1px solid #e5e5e5; font-family:Arial,sans-serif; font-size:12px; color:#737373; text-align:center;">
                        <p style="margin:0 0 8px;">
                            You're receiving this because you subscribed to {{ $newsletterName ?? 'a TOP SOCIETY newsletter' }}.
                        </p>
                        @isset($unsubscribeUrl)
                        <p style="margin:0;">
                            <a href="{{ $unsubscribeUrl }}" style="color:#dc2626; text-decoration:underline;">Unsubscribe</a>
                            &nbsp;·&nbsp;
                            <a href="{{ url('/newsletter') }}" style="color:#737373; text-decoration:underline;">Manage subscriptions</a>
                        </p>
                        @endisset
                        <p style="margin:12px 0 0; color:#a3a3a3;">© {{ date('Y') }} TOP SOCIETY. Lagos · Abuja · London · New York.</p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>