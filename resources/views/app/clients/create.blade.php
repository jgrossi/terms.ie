@extends('layouts.app')

@section('title', 'New client — terms.ie')

@section('content')

<x-page-header title="New client"
    :crumbs="['Clients' => route('app.clients.index'), 'New client' => null]" />

<div class="max-w-lg">
    <x-card>
        <form method="POST" action="{{ route('app.clients.store') }}">
            @csrf
            @include('app.clients._form', ['client' => null])
            <x-form.actions submit="Add client" :cancel="route('app.clients.index')" />
        </form>
    </x-card>
</div>

@endsection
