@extends('layouts.app')

@section('title', 'Assign term — terms.ie')

@section('content')

<x-page-header title="Assign to client"
    :crumbs="['Terms' => route('app.terms.index'), $term->name => route('app.terms.show', $term), 'Assign to client' => null]" />

<div class="max-w-lg">
    @if ($clients->isEmpty())
        <x-empty-state icon="users" title="No clients yet"
            description="You need to add a client before assigning a term.">
            <x-slot:action>
                <a href="{{ route('app.clients.create') }}" class="btn btn-primary btn-sm">Add a client</a>
            </x-slot:action>
        </x-empty-state>
    @else
        <x-card>
            <form method="POST" action="{{ route('app.signatures.store', $term) }}">
                @csrf
                <div class="flex flex-col gap-5">

                    <x-form.select name="client_id" label="Client" :required="true">
                        <option value="" disabled selected>Select a client…</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id') === $client->id ? 'selected' : '' }}>
                                {{ $client->name }} — {{ $client->email }}
                            </option>
                        @endforeach
                    </x-form.select>

                    @foreach ($userVars as $var)
                        @php $varLabel = '{{' . $var . '}}'; @endphp
                        <x-form.input
                            name="variables[{{ $var }}]"
                            :label="$varLabel"
                            :placeholder="'Value for ' . $varLabel"
                            required
                            :value="old('variables.' . $var)"
                        />
                    @endforeach

                </div>

                <x-form.actions submit="Generate signing link" :cancel="route('app.terms.show', $term)" />
            </form>
        </x-card>
    @endif
</div>

@endsection
