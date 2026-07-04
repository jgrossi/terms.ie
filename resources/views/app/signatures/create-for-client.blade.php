@extends('layouts.app')

@section('title', 'Assign term — terms.ie')

@section('content')

<x-page-header :title="'Assign term to ' . $client->name"
    :crumbs="['Clients' => route('app.clients.index'), $client->name => route('app.clients.show', $client), 'Assign term' => null]" />

<div class="max-w-lg">
    @if ($terms->isEmpty())
        <x-empty-state icon="document" title="No terms yet"
            description="You haven't created any terms yet.">
            <x-slot:action>
                <a href="{{ route('app.terms.create') }}" class="btn btn-primary btn-sm">Create a term</a>
            </x-slot:action>
        </x-empty-state>
    @else
        <x-card>
            <form method="POST" action="{{ route('app.signatures.store-for-client', $client) }}">
                @csrf
                <div class="flex flex-col gap-5">

                    <x-form.select name="term_id" label="Term" :required="true"
                        @change="$el.value && htmx.ajax('GET', '{{ url('app/terms') }}/' + $el.value + '/signature-fields', {target: '#variable-fields', swap: 'innerHTML'})">
                        <option value="" disabled {{ !old('term_id') ? 'selected' : '' }}>Select a term…</option>
                        @foreach ($terms as $term)
                            <option value="{{ $term->id }}" {{ old('term_id') === $term->id ? 'selected' : '' }}>
                                {{ $term->name }}
                            </option>
                        @endforeach
                    </x-form.select>

                    <div id="variable-fields"
                         class="flex flex-col gap-5"
                         @if(old('term_id'))
                         hx-get="{{ url('app/terms/' . old('term_id') . '/signature-fields') }}"
                         hx-trigger="load"
                         hx-swap="innerHTML"
                         @endif>
                    </div>

                </div>

                <x-form.actions submit="Generate signing link" :cancel="route('app.clients.show', $client)" />
            </form>
        </x-card>
    @endif
</div>

@endsection
