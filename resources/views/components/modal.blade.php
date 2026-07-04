@props(['state', 'title' => null])

<div x-data="{{ $state }}"
     x-show="open"
     x-cloak
     @keydown.escape.window="open = false"
     class="fixed inset-0 z-50 flex items-center justify-center p-4"
     x-effect="document.body.style.overflow = open ? 'hidden' : ''">

    <div class="fixed inset-0 bg-black/40" @click="open = false"></div>

    <div class="relative bg-base-100 rounded-2xl shadow-xl w-full max-w-lg"
         @click.stop>

        <div class="flex items-center justify-between p-5 border-b border-base-200">
            @if ($title)
                <h3 class="font-semibold text-lg">{{ $title }}</h3>
            @endif
            <button type="button" @click="open = false" class="btn btn-ghost btn-sm btn-square ml-auto">
                <x-icon name="x" class="w-4 h-4" />
            </button>
        </div>

        <div class="p-5">
            {{ $slot }}
        </div>
    </div>
</div>
