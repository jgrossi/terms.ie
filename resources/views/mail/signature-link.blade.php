<x-mail::message>
# {{ $termName }}

Hi {{ $clientName }},

You've been asked to review and sign the document below. It only takes a minute — no account needed.

<x-mail::button :url="$url">
Review &amp; sign
</x-mail::button>

@if ($expiresAt)
This link expires on **{{ $expiresAt }}**.
@endif

Thanks,<br>
terms.ie
</x-mail::message>
