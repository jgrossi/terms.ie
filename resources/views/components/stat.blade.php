@props(['label', 'value', 'caption' => null, 'icon' => 'document', 'href' => null, 'invert' => false])

@php
    $tag  = $href ? 'a' : 'div';
    $base = $invert
        ? 'bg-base-content text-base-100 shadow-sm'
        : 'bg-base-100 border border-base-200' . ($href ? ' hover:border-base-300' : '');
@endphp

<{{ $tag }} @if($href) href="{{ $href }}" @endif
    class="group rounded-box p-5 transition-colors {{ $base }}">
    <div class="flex items-center justify-between mb-4">
        <p class="text-xs font-medium uppercase tracking-wide {{ $invert ? 'text-base-100/50' : 'text-base-content/50' }}">{{ $label }}</p>
        <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ $invert ? 'bg-base-100/10' : 'bg-base-200' }}">
            <x-icon :name="$icon" class="w-3.5 h-3.5 {{ $invert ? 'text-base-100/70' : 'text-base-content/50' }}" />
        </div>
    </div>
    <p class="text-3xl font-medium tabular-nums {{ $invert ? 'text-base-100' : 'text-base-content' }}">{{ $value }}</p>
    @if ($caption)
        <p class="text-xs mt-1 {{ $invert ? 'text-base-100/40' : 'text-base-content/30' }}">{{ $caption }}</p>
    @endif
</{{ $tag }}>
