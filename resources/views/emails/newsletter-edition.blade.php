@component('emails.layout', ['subject' => $edition->title, 'newsletterName' => $edition->newsletter->name, 'unsubscribeUrl' => $unsubscribeUrl])
<p style="margin:0 0 4px; font-size:11px; font-weight:bold; letter-spacing:1.5px; color:#dc2626; text-transform:uppercase;">
    {{ $edition->newsletter->name }}
</p>

<h1 style="margin:8px 0 20px; font-family:Georgia,serif; font-size:24px; line-height:1.3; color:#0a0a0a;">
    {{ $edition->title }}
</h1>

<div style="color:#262626;">
    {!! $edition->body !!}
</div>

<table role="presentation" cellpadding="0" cellspacing="0" style="margin-top:24px;">
    <tr>
        <td style="border:1px solid #dc2626; border-radius:4px;">
            <a href="{{ url('/newsletter/archive/'.$edition->slug) }}" style="display:inline-block; padding:12px 24px; font-family:Arial,sans-serif; font-size:14px; font-weight:bold; color:#dc2626; text-decoration:none;">
                Read Online →
            </a>
        </td>
    </tr>
</table>
@endcomponent