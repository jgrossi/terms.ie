@props(['label' => null, 'padding' => 'p-6', 'flush' => false])

<div {{ $attributes->merge(['class' => 'bg-base-100 rounded-box border border-base-200' . ($flush ? ' overflow-hidden' : ' ' . $padding)]) }}>
    @if ($label)
        <h2 class="text-xs font-medium text-base-content/50 uppercase tracking-wide mb-4">{{ $label }}</h2>
    @endif
    {{ $slot }}
</div>
