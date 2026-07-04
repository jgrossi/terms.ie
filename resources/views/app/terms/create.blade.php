@extends('layouts.app')

@section('title', 'New term — terms.ie')

@section('content')

<x-page-header title="New term"
    :crumbs="['Terms' => route('app.terms.index'), 'New term' => null]" />

<div class="max-w-2xl">
    <x-card>
        <form method="POST" action="{{ route('app.terms.store') }}">
            @csrf
            @include('app.terms._form', ['term' => null])
            <x-form.actions submit="Create term" :cancel="route('app.terms.index')" />
        </form>
    </x-card>
</div>

@endsection
