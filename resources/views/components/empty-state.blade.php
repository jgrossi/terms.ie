@props(['icon' => 'document', 'title', 'description' => null])

<div class="bg-base-100 rounded-box border border-base-200 px-6 py-16 text-center">
    <div class="w-12 h-12 rounded-full bg-base-200 flex items-center justify-center mx-auto mb-4">
        <x-icon :name="$icon" class="w-5 h-5 text-base-content/40" />
    </div>
    <h3 class="text-lg font-semibold text-base-content mb-1.5">{{ $title }}</h3>
    @if ($description)
        <p class="text-sm text-base-content/50 max-w-sm mx-auto mb-6">{{ $description }}</p>
    @endif
    @isset($action)
        <div class="flex gap-3 justify-center">{{ $action }}</div>
    @endisset
</div>
