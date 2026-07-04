@props([
    'name',
    'label'    => null,
    'required' => false,
    'rows'     => 6,
    'value'    => null,
])

@php $id = 'textarea-' . $name; @endphp

<fieldset class="fieldset w-full">
    @if ($label)
        <legend class="fieldset-legend">
            {{ $label }}
            @if ($required) <span class="text-error ml-0.5">*</span> @endif
        </legend>
    @endif

    <textarea
        id="{{ $id }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'textarea w-full' . ($errors->has($name) ? ' textarea-error' : '')]) }}
    >{{ old($name, $value) }}</textarea>

    @error($name)
        <p class="fieldset-label text-error">{{ $message }}</p>
    @enderror
</fieldset>
