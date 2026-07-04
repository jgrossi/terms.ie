@extends('layouts.app')

@section('title', 'Edit term — terms.ie')

@section('content')

@php $currentVersion = $term->versions()->max('version'); @endphp

<x-page-header title="Edit term"
    :crumbs="['Terms' => route('app.terms.index'), $term->name => route('app.terms.show', $term), 'Edit' => null]" />

<div class="max-w-2xl">

    <p class="mb-5 text-sm text-base-content/60 bg-base-200 rounded-box px-4 py-3">
        Changing the body creates a new version. Existing client terms stay on their current version.
    </p>

    <x-card>
        <form method="POST" action="{{ route('app.terms.update', $term) }}">
            @csrf
            @method('PUT')
            @include('app.terms._form', ['term' => $term])
            <x-form.actions submit="Save changes" :cancel="route('app.terms.show', $term)" />
        </form>
    </x-card>
</div>

@endsection
