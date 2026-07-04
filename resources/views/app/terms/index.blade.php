@extends('layouts.app')

@section('title', 'Terms — terms.ie')

@section('content')

<x-page-header title="Terms">
    <x-slot:actions>
        <a href="{{ route('app.terms.create') }}" class="btn btn-primary btn-sm">
            <x-icon name="plus" class="w-4 h-4" /> New term
        </a>
    </x-slot:actions>
</x-page-header>

@if ($terms->isEmpty())
    <x-empty-state icon="document" title="No terms yet"
        description="Create your first term template to get started.">
        <x-slot:action>
            <a href="{{ route('app.terms.create') }}" class="btn btn-primary btn-sm">Create a term</a>
        </x-slot:action>
    </x-empty-state>
@else
    <x-card :flush="true">
        <table class="table">
            <thead>
                <tr class="border-base-200">
                    <th class="text-base-content/50 font-medium">Name</th>
                    <th class="text-base-content/50 font-medium">Variables</th>
                    <th class="text-base-content/50 font-medium">Versions</th>
                    <th class="text-base-content/50 font-medium">Updated</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($terms as $term)
                <tr class="border-base-200 hover:bg-base-50">
                    <td>
                        <a href="{{ route('app.terms.show', $term) }}" class="font-medium text-base-content hover:text-primary">
                            {{ $term->name }}
                        </a>
                    </td>
                    <td class="text-sm text-base-content/50">
                        @php $vars = $term->extractUserVariables(); @endphp
                        @if ($vars)
                            <div class="flex flex-wrap gap-1">
                                @foreach ($vars as $var)
                                    @php $label = '{{' . $var . '}}'; @endphp
                                    <code class="bg-primary/10 text-primary text-xs px-1.5 py-0.5 rounded font-mono">{{ $label }}</code>
                                @endforeach
                            </div>
                        @else
                            <span class="text-base-content/30">—</span>
                        @endif
                    </td>
                    <td class="text-sm text-base-content/50">v{{ $term->versions_count }}</td>
                    <td class="text-sm text-base-content/50">{{ $term->updated_at->diffForHumans() }}</td>
                    <td class="text-right">
                        <a href="{{ route('app.terms.edit', $term) }}" class="btn btn-ghost btn-xs">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </x-card>
@endif

@endsection
