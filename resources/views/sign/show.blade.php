@extends('layouts.client')

@section('title', $signature->termVersion->term->name . ' — terms.ie')

@section('content')
@if ($signature->isSigned())

    <x-certificate :signature="$signature" class="mb-6" />

    <x-document class="mb-6">{{ $signature->render() }}</x-document>

    <p class="text-center text-xs text-base-content/30">
        Electronic acceptance is recognised under the Electronic Commerce Act 2000 in Ireland.<br>
        Reference: <span class="font-mono">{{ $signature->id }}</span>
    </p>

@else

    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-base-content tracking-tight leading-tight mb-1">{{ $signature->termVersion->term->name }}</h1>
        <p class="text-sm text-base-content/40">Please read the document below and sign to accept.</p>
    </div>

    <x-document class="mb-6">{{ $signature->render() }}</x-document>

    <x-card>
        <form method="POST" action="{{ route('sign.sign', $signature) }}">
            @csrf
            <x-form.input
                name="signed_name"
                label="Full name"
                placeholder="Type your full name to sign"
                required
                :value="old('signed_name')"
            />
            @if ($errors->any())
                <p class="text-error text-sm mt-2">{{ $errors->first() }}</p>
            @endif
            <div class="mt-5 pt-5 border-t border-base-200">
                <button type="submit" class="btn btn-primary w-full">I accept these terms</button>
                <p class="text-center text-xs text-base-content/30 mt-3">
                    Electronic acceptance is recognised under the Electronic Commerce Act 2000 in Ireland.
                </p>
            </div>
        </form>
    </x-card>

@endif
@endsection
