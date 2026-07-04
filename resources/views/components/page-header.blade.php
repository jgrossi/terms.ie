@props(['title', 'subtitle' => null, 'crumbs' => []])

<div class="mb-8">
    @if (!empty($crumbs))
        <nav class="flex items-center gap-2 mb-3 text-sm">
            @foreach ($crumbs as $label => $url)
                @unless ($loop->first)<span class="text-base-content/25">/</span>@endunless
                @if ($url)
                    <a href="{{ $url }}" class="text-base-content/40 hover:text-base-content transition-colors">{{ $label }}</a>
                @else
                    <span class="text-base-content/60">{{ $label }}</span>
                @endif
            @endforeach
        </nav>
    @endif

    <div class="flex items-start justify-between gap-4">
        <div class="min-w-0">
            <h1 class="text-2xl font-semibold text-base-content tracking-tight leading-tight">{{ $title }}</h1>
            @if ($subtitle)
                <p class="text-sm text-base-content/50 mt-1.5">{!! $subtitle !!}</p>
            @endif
        </div>
        @isset($actions)
            <div class="flex items-center gap-2 shrink-0">{{ $actions }}</div>
        @endisset
    </div>
</div>
