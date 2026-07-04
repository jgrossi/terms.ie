@extends('layouts.app')

@section('title', 'Dashboard — terms.ie')

@section('content')

<x-page-header title="Overview" subtitle="{{ auth()->user()->email }}">
    <x-slot:actions>
        <a href="{{ route('app.terms.create') }}" class="btn btn-primary btn-sm">
            <x-icon name="plus" class="w-3.5 h-3.5" /> New term
        </a>
    </x-slot:actions>
</x-page-header>

{{-- Stat cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <x-stat label="Terms" :value="$termCount" caption="total created" icon="document" :href="route('app.terms.index')" />
    <x-stat label="Clients" :value="$clientCount" caption="total added" icon="users" :href="route('app.clients.index')" />
    <x-stat label="Awaiting" :value="$pendingCount"
        :caption="$pendingCount > 0 ? Str::plural('signature', $pendingCount) . ' pending' : 'all signed'"
        icon="clock"
        :invert="$pendingCount > 0" />
    <x-stat label="Signed" :value="$signedCount" caption="total collected" icon="check-circle" />
</div>

{{-- Awaiting signature --}}
<x-card :flush="true" class="mb-6">
    <div class="flex items-center justify-between px-6 py-4 border-b border-base-200">
        <h2 class="text-sm font-semibold text-base-content">Awaiting signature</h2>
        @if ($pendingCount > 0)
            <span class="badge badge-sm bg-base-content text-base-100 border-0">{{ $pendingCount }} pending</span>
        @endif
    </div>

    @if ($pendingSignatures->isEmpty())
        <div class="flex flex-col items-center justify-center py-12 gap-2">
            <x-icon name="check-circle" class="w-8 h-8 text-base-content/15" />
            <p class="text-sm text-base-content/30">Nothing pending — all signatures collected.</p>
        </div>
    @else
        <table class="table">
            <thead>
                <tr class="border-base-200 text-xs">
                    <th class="text-base-content/40 font-medium">Client</th>
                    <th class="text-base-content/40 font-medium">Term</th>
                    <th class="text-base-content/40 font-medium hidden sm:table-cell">Assigned</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pendingSignatures as $sig)
                <tr class="border-base-200 hover:bg-base-50">
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-base-200 flex items-center justify-center text-xs font-semibold text-base-content/50 shrink-0 select-none">
                                {{ strtoupper(substr($sig->client->name, 0, 1)) }}{{ strtoupper(substr(strstr($sig->client->name, ' ') ?: ' ', 1, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="font-medium text-sm text-base-content leading-tight">{{ $sig->client->name }}</p>
                                <p class="text-xs text-base-content/40 leading-tight truncate">{{ $sig->client->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="text-sm text-base-content/60">{{ $sig->termVersion->term->name }}</td>
                    <td class="text-xs text-base-content/40 hidden sm:table-cell">{{ $sig->created_at->diffForHumans() }}</td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-1" x-data="copyButton('{{ route('sign.show', $sig) }}')">
                            <button @click="copy()" class="btn btn-xs btn-outline gap-1">
                                <x-icon name="clipboard" class="w-3 h-3" />
                                <span x-text="copied ? 'Copied!' : 'Copy link'">Copy link</span>
                            </button>
                            <a href="{{ route('app.signatures.show', $sig) }}" class="btn btn-xs btn-ghost">View</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</x-card>

{{-- Recently signed --}}
@if ($recentSigned->isNotEmpty())
<x-card :flush="true">
    <div class="px-6 py-4 border-b border-base-200">
        <h2 class="text-sm font-semibold text-base-content">Recently signed</h2>
    </div>
    <table class="table">
        <tbody>
            @foreach ($recentSigned as $sig)
            <tr class="border-base-200 hover:bg-base-50">
                <td>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-success/10 flex items-center justify-center text-xs font-semibold text-success/70 shrink-0 select-none">
                            {{ strtoupper(substr($sig->client->name, 0, 1)) }}{{ strtoupper(substr(strstr($sig->client->name, ' ') ?: ' ', 1, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-medium text-sm text-base-content leading-tight">{{ $sig->client->name }}</p>
                            <p class="text-xs text-base-content/40 leading-tight">{{ $sig->termVersion->term->name }}</p>
                        </div>
                    </div>
                </td>
                <td class="text-xs text-base-content/40 hidden sm:table-cell">
                    Signed by <span class="text-base-content/60">{{ $sig->signed_name }}</span>
                </td>
                <td class="text-xs text-base-content/40 text-right hidden sm:table-cell">{{ $sig->signed_at->diffForHumans() }}</td>
                <td class="text-right">
                    <a href="{{ route('app.signatures.show', $sig) }}" class="btn btn-xs btn-ghost">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</x-card>
@endif

{{-- Empty state (no data at all) --}}
@if ($termCount === 0 && $clientCount === 0)
<x-empty-state icon="document" title="Welcome to terms.ie"
    description="Start by creating a term, then assign it to a client to generate a signing link."
    class="mt-6">
    <x-slot:action>
        <a href="{{ route('app.terms.create') }}" class="btn btn-primary btn-sm">Create a term</a>
        <a href="{{ route('app.clients.create') }}" class="btn btn-outline btn-sm">Add a client</a>
    </x-slot:action>
</x-empty-state>
@endif

@endsection
