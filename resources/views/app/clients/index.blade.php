@extends('layouts.app')

@section('title', 'Clients — terms.ie')

@section('content')

<x-page-header title="Clients">
    <x-slot:actions>
        <a href="{{ route('app.clients.create') }}" class="btn btn-primary btn-sm">
            <x-icon name="plus" class="w-4 h-4" /> New client
        </a>
    </x-slot:actions>
</x-page-header>

@if ($clients->isEmpty())
    <x-empty-state icon="users" title="No clients yet"
        description="Add your first client to start sending terms.">
        <x-slot:action>
            <a href="{{ route('app.clients.create') }}" class="btn btn-primary btn-sm">Add a client</a>
        </x-slot:action>
    </x-empty-state>
@else
    <x-card :flush="true">
        <table class="table">
            <thead>
                <tr class="border-base-200">
                    <th class="text-base-content/50 font-medium">Name</th>
                    <th class="text-base-content/50 font-medium">Email</th>
                    <th class="text-base-content/50 font-medium">Added</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clients as $client)
                <tr class="border-base-200 hover:bg-base-50">
                    <td>
                        <a href="{{ route('app.clients.show', $client) }}" class="font-medium text-base-content hover:text-primary">
                            {{ $client->name }}
                        </a>
                    </td>
                    <td class="text-sm text-base-content/50">{{ $client->email }}</td>
                    <td class="text-sm text-base-content/50">{{ $client->created_at->diffForHumans() }}</td>
                    <td class="text-right">
                        <a href="{{ route('app.clients.edit', $client) }}" class="btn btn-ghost btn-xs">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </x-card>
@endif

@endsection
