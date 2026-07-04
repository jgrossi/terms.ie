@extends('layouts.app')

@section('title', $client->name . ' — terms.ie')

@section('content')

<x-page-header :title="$client->name" :subtitle="$client->email"
    :crumbs="['Clients' => route('app.clients.index'), $client->name => null]">
    <x-slot:actions>
        <a href="{{ route('app.signatures.create-for-client', $client) }}" class="btn btn-primary btn-sm">
            Assign term
        </a>
        <a href="{{ route('app.clients.edit', $client) }}" class="btn btn-outline btn-sm">
            <x-icon name="pencil" class="w-3.5 h-3.5" /> Edit
        </a>
        @unless ($client->signatures()->exists())
            <form method="POST" action="{{ route('app.clients.destroy', $client) }}"
                  onsubmit="return confirm('Delete {{ $client->name }}? This cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-ghost btn-sm text-error hover:bg-error/10">
                    <x-icon name="trash" class="w-3.5 h-3.5" /> Delete
                </button>
            </form>
        @endunless
    </x-slot:actions>
</x-page-header>

@php $signatures = $client->signatures()->with('termVersion.term')->latest()->get(); @endphp

@if ($signatures->isEmpty())
    <x-empty-state icon="document" title="No terms assigned yet"
        description="Assign a term to this client to generate a signing link.">
        <x-slot:action>
            <a href="{{ route('app.signatures.create-for-client', $client) }}" class="btn btn-primary btn-sm">Assign term</a>
        </x-slot:action>
    </x-empty-state>
@else
    <x-card :flush="true">
        <table class="table">
            <thead>
                <tr class="border-base-200">
                    <th class="text-base-content/50 font-medium">Term</th>
                    <th class="text-base-content/50 font-medium">Version</th>
                    <th class="text-base-content/50 font-medium">Status</th>
                    <th class="text-base-content/50 font-medium">Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($signatures as $signature)
                <tr class="border-base-200 hover:bg-base-50">
                    <td class="font-medium text-base-content">{{ $signature->termVersion->term->name }}</td>
                    <td class="text-sm text-base-content/50">v{{ $signature->termVersion->version }}</td>
                    <td><x-status :status="$signature->status" /></td>
                    <td class="text-sm text-base-content/50">{{ $signature->created_at->diffForHumans() }}</td>
                    <td class="text-right">
                        <a href="{{ route('app.signatures.show', $signature) }}" class="btn btn-ghost btn-xs">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </x-card>
@endif

@endsection
