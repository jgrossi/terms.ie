@extends('layouts.app')

@section('title', 'Signature — terms.ie')

@section('content')

<x-page-header :title="$signature->termVersion->term->name"
    :crumbs="['Clients' => route('app.clients.index'), $signature->client->name => route('app.clients.show', $signature->client), $signature->termVersion->term->name => null]">
    <x-slot:actions>
        <x-status :status="$signature->status" />
        @if ($signature->isPending())
            <form method="POST" action="{{ route('app.signatures.destroy', $signature) }}"
                  onsubmit="return confirm('Revoke this signing request? The link will stop working.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-ghost btn-sm text-error hover:bg-error/10">
                    <x-icon name="trash" class="w-3.5 h-3.5" /> Revoke
                </button>
            </form>
        @endif
    </x-slot:actions>
</x-page-header>

<div class="grid lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2 space-y-6">

        {{-- Signing link --}}
        @if ($signature->isPending())
            <x-card label="Signing link">
                <p class="text-xs text-base-content/40 mb-3">Send this link to {{ $signature->client->name }} to sign the document.</p>
                <div class="flex gap-2" x-data="copyButton('{{ route('sign.show', $signature) }}')">
                    <input type="text" readonly
                           value="{{ route('sign.show', $signature) }}"
                           class="input input-sm w-full font-mono text-xs"
                           @click="$el.select()" />
                    <button class="btn btn-sm btn-outline shrink-0" @click="copy()">
                        <span x-text="copied ? 'Copied!' : 'Copy'">Copy</span>
                    </button>
                </div>
            </x-card>
        @endif

        {{-- Certificate (signed state) --}}
        @if ($signature->isSigned())
            <x-certificate :signature="$signature" />
        @endif

        {{-- Document content --}}
        <x-document>{{ $signature->render() }}</x-document>

    </div>

    <div class="space-y-4">

        <x-card label="Client">
            <p class="font-medium text-base-content">{{ $signature->client->name }}</p>
            <p class="text-sm text-base-content/50">{{ $signature->client->email }}</p>
        </x-card>

        @if ($signature->variables)
            <x-card label="Variables">
                <dl class="space-y-2">
                    @foreach ($signature->variables as $key => $value)
                        <div>
                            <dt class="text-xs text-base-content/40 font-mono">@php echo e('{{' . $key . '}}') @endphp</dt>
                            <dd class="text-sm text-base-content">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
            </x-card>
        @endif

    </div>
</div>

@endsection
