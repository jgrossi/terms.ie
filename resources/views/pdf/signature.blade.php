<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @font-face {
            font-family: 'Signature';
            font-style: normal;
            font-weight: 400;
            src: url("{{ storage_path('fonts/Sacramento-Regular.ttf') }}") format('truetype');
        }

        @page { margin: 40px 48px; }

        /* Base-14 Helvetica/Courier are never embedded — keeps the PDF tiny (~50KB). */
        body { font-family: 'Helvetica', sans-serif; color: #18181b; font-size: 12px; }

        .header { border-bottom: 1px solid #e4e4e7; padding-bottom: 10px; margin-bottom: 18px; }
        .brand { font-size: 15px; font-weight: bold; }
        .brand .ie { color: #2563EB; }
        .term-name { font-size: 13px; color: #52525b; margin-top: 3px; }

        .contract {
            font-family: 'Courier', monospace;
            font-size: 11px;
            line-height: 1.6;
            white-space: pre-wrap;
            color: #27272a;
        }

        .sig-block { margin-top: 30px; border-top: 1px solid #e4e4e7; padding-top: 16px; }
        .sig-label { font-size: 9px; text-transform: uppercase; letter-spacing: 1px; color: #16a34a; }
        .sig-name { font-family: 'Signature'; font-size: 32px; color: #18181b; margin: 2px 0; }
        .sig-rule { border-bottom: 1px solid #a1a1aa; width: 260px; margin-bottom: 8px; }
        .sig-meta { font-size: 10px; color: #52525b; }

        .verify { margin-top: 16px; font-size: 9px; color: #71717a; }
        .verify .hash { font-family: 'DejaVu Sans Mono', monospace; word-break: break-all; }
        .legal { margin-top: 20px; font-size: 8px; color: #a1a1aa; }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">terms<span class="ie">.ie</span></div>
        <div class="term-name">{{ $termName }}</div>
    </div>

    <div class="contract">{{ $body }}</div>

    <div class="sig-block">
        <div class="sig-label">Signed &amp; verified</div>
        <div class="sig-name">{{ $signedName }}</div>
        <div class="sig-rule"></div>
        <div class="sig-meta">{{ $signedName }} &middot; signed {{ $signedAtUtc }} &middot; IP {{ $signedIp }}</div>

        <div class="verify">
            Reference: {{ $reference }}<br>
            Verification hash (SHA-256): <span class="hash">{{ $contentHash }}</span>
        </div>

        <div class="legal">
            Electronic acceptance is recognised under the Electronic Commerce Act 2000 in Ireland.
        </div>
    </div>
</body>
</html>
