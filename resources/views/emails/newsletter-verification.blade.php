@component('emails.layout', ['subject' => 'Confirm your subscription', 'newsletterName' => $subscription->newsletter->name])
<p style="margin:0 0 4px; font-size:11px; font-weight:bold; letter-spacing:1.5px; color:#dc2626; text-transform:uppercase;">
    One More Step
</p>

<h1 style="margin:8px 0 16px; font-family:Georgia,serif; font-size:24px; line-height:1.3; color:#0a0a0a;">
    Confirm your subscription to {{ $subscription->newsletter->name }}
</h1>

<p style="margin:0 0 24px; color:#525252;">
    {{ $subscription->newsletter->description }}
</p>

<p style="margin:0 0 24px; color:#525252;">
    Click below to start receiving it. If you didn't request this, you can safely ignore this email — you won't be subscribed unless you confirm.
</p>

<table role="presentation" cellpadding="0" cellspacing="0">
    <tr>
        <td style="background-color:#dc2626; border-radius:4px;">
            <a href="{{ $verifyUrl }}" style="display:inline-block; padding:12px 24px; font-family:Arial,sans-serif; font-size:14px; font-weight:bold; color:#ffffff; text-decoration:none;">
                Confirm Subscription →
            </a>
        </td>
    </tr>
</table>

<p style="margin:24px 0 0; font-size:12px; color:#a3a3a3;">
    Or paste this link into your browser: {{ $verifyUrl }}
</p>
@endcomponent