@extends('layouts.app')

@section('title', 'Edit client — terms.ie')

@section('content')

<x-page-header title="Edit client"
    :crumbs="['Clients' => route('app.clients.index'), $client->name => route('app.clients.show', $client), 'Edit' => null]" />

<div class="max-w-lg">
    <x-card>
        <form method="POST" action="{{ route('app.clients.update', $client) }}">
            @csrf
            @method('PUT')
            @include('app.clients._form', compact('client'))
            <x-form.actions submit="Save changes" :cancel="route('app.clients.show', $client)" />
        </form>
    </x-card>
</div>

@endsection
