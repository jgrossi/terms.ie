@props(['signature'])

<div class="rounded-box border border-base-200 bg-base-100 overflow-hidden shadow-sm">
    <div class="flex items-center gap-3 px-6 py-4 bg-base-content text-base-100">
        <div class="w-9 h-9 rounded-full bg-base-100/10 flex items-center justify-center shrink-0">
            <x-icon name="check" class="w-5 h-5" />
        </div>
        <div class="min-w-0">
            <p class="text-[11px] uppercase tracking-widest text-base-100/50 font-medium">Signed &amp; verified</p>
            <p class="text-lg font-semibold leading-tight truncate">{{ $signature->signed_name }}</p>
        </div>
    </div>
    <dl class="divide-y divide-base-200 text-sm">
        <div class="flex justify-between gap-4 px-6 py-3">
            <dt class="text-base-content/40">Date signed</dt>
            <dd class="text-base-content">{{ $signature->signed_at->format('d M Y \a\t H:i') }}</dd>
        </div>
        <div class="flex justify-between gap-4 px-6 py-3">
            <dt class="text-base-content/40">IP address</dt>
            <dd class="text-base-content/70 font-mono text-xs">{{ $signature->signed_ip }}</dd>
        </div>
        <div class="flex justify-between gap-4 px-6 py-3">
            <dt class="text-base-content/40 shrink-0">Reference</dt>
            <dd class="text-base-content/70 font-mono text-xs truncate">{{ $signature->id }}</dd>
        </div>
        <div class="px-6 py-3">
            <dt class="text-base-content/40 mb-1">Verification hash (SHA-256)</dt>
            <dd class="text-base-content/40 font-mono text-[11px] break-all leading-relaxed">{{ $signature->content_hash }}</dd>
        </div>
    </dl>
</div>
