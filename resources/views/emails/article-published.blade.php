@component('emails.layout', ['subject' => $article->title, 'newsletterName' => $newsletter->name, 'unsubscribeUrl' => $unsubscribeUrl])
<p style="margin:0 0 4px; font-size:11px; font-weight:bold; letter-spacing:1.5px; color:#dc2626; text-transform:uppercase;">
    New from {{ $newsletter->name }}
</p>

<h1 style="margin:8px 0 16px; font-family:Georgia,serif; font-size:24px; line-height:1.3; color:#0a0a0a;">
    {{ $article->title }}
</h1>

@if ($article->excerpt)
<p style="margin:0 0 24px; color:#525252;">{{ $article->excerpt }}</p>
@endif

<table role="presentation" cellpadding="0" cellspacing="0">
    <tr>
        <td style="background-color:#dc2626; border-radius:4px;">
            <a href="{{ url('/articles/'.$article->slug) }}" style="display:inline-block; padding:12px 24px; font-family:Arial,sans-serif; font-size:14px; font-weight:bold; color:#ffffff; text-decoration:none;">
                Read the Full Story →
            </a>
        </td>
    </tr>
</table>
@endcomponent