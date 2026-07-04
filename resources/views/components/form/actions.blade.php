@props(['submit' => 'Save', 'cancel' => null])

<div class="flex gap-3 mt-6 pt-5 border-t border-base-200">
    <button type="submit" class="btn btn-primary">{{ $submit }}</button>
    @if ($cancel)
        <a href="{{ $cancel }}" class="btn btn-ghost">Cancel</a>
    @endif
    {{ $slot }}
</div>
