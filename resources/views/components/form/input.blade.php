@props([
    'name',
    'label'    => null,
    'type'     => 'text',
    'required' => false,
    'value'    => null,
])

@php $id = 'input-' . $name; @endphp

<fieldset class="fieldset w-full">
    @if ($label)
        <legend class="fieldset-legend">
            {{ $label }}
            @if ($required) <span class="text-error ml-0.5">*</span> @endif
        </legend>
    @endif

    <input
        id="{{ $id }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name, $value) }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'input w-full' . ($errors->has($name) ? ' input-error' : '')]) }}
    />

    @error($name)
        <p class="fieldset-label text-error">{{ $message }}</p>
    @enderror
</fieldset>
