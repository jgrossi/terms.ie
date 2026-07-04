@props(['status'])   {{-- App\Enums\SignatureStatus --}}

<span class="inline-flex items-center gap-1.5 text-xs font-medium text-base-content/70">
    <span class="w-1.5 h-1.5 rounded-full {{ $status->dotClass() }}"></span>
    {{ $status->label() }}
</span>
