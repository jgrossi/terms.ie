@extends('layouts.app')

@section('title', $term->name . ' — terms.ie')

@section('content')

<x-page-header :title="$term->name"
    :subtitle="'Last updated ' . $term->updated_at->diffForHumans() . ' <span class=\'text-base-content/30 font-mono text-xs ml-1\'>v' . ($versions->first()?->version ?? 1) . '</span>'"
    :crumbs="['Terms' => route('app.terms.index'), $term->name => null]">
    <x-slot:actions>
        <a href="{{ route('app.terms.edit', $term) }}" class="btn btn-outline btn-sm">
            <x-icon name="pencil" class="w-3.5 h-3.5" /> Edit
        </a>
        @unless ($term->hasSignatures())
            <form method="POST" action="{{ route('app.terms.destroy', $term) }}"
                  onsubmit="return confirm('Delete this term? This cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-ghost btn-sm text-error hover:bg-error/10">
                    <x-icon name="trash" class="w-3.5 h-3.5" /> Delete
                </button>
            </form>
        @endunless
    </x-slot:actions>
</x-page-header>

<div class="grid lg:grid-cols-3 gap-6">

    {{-- Body --}}
    <div class="lg:col-span-2 space-y-6">
        <x-card label="Content">
            @include('app.terms._body', compact('term'))
        </x-card>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-4">

        <x-card label="Variables">
            @php
                $userVars     = $term->extractUserVariables();
                $reservedVars = array_intersect($term->extractVariables(), \App\Models\Term::RESERVED);
            @endphp

            @if (empty($userVars) && empty($reservedVars))
                <p class="text-sm text-base-content/30">No variables found.</p>
            @else
                <div class="space-y-3">
                    @if ($userVars)
                        <div>
                            <p class="text-xs text-base-content/40 mb-1.5">Filled when assigning to a client</p>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach ($userVars as $var)
                                    @php $label = '{{' . $var . '}}'; @endphp
                                    <code class="bg-primary/10 text-primary text-xs px-1.5 py-0.5 rounded font-mono">{{ $label }}</code>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if ($reservedVars)
                        <div>
                            <p class="text-xs text-base-content/40 mb-1.5">Auto-filled from client</p>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach ($reservedVars as $var)
                                    @php $label = '{{' . $var . '}}'; @endphp
                                    <code class="bg-success/10 text-success text-xs px-1.5 py-0.5 rounded font-mono">{{ $label }}</code>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </x-card>

        <x-card label="Version history">
            <div class="space-y-2">
                @foreach ($versions as $version)
                <div class="flex items-center justify-between text-sm">
                    <span class="font-mono text-base-content/70">v{{ $version->version }}</span>
                    <span class="text-base-content/40 text-xs">{{ $version->created_at->format('d M Y') }}</span>
                </div>
                @endforeach
            </div>
        </x-card>

        <x-card label="Send to client">
            <p class="text-xs text-base-content/40 mb-3">Assign this term to a client to generate a signing link.</p>
            <a href="{{ route('app.signatures.create', $term) }}" class="btn btn-primary btn-sm btn-block">Assign to client</a>
        </x-card>

    </div>
</div>

@endsection
