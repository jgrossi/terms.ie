@props(['name', 'label' => null, 'required' => false])

<fieldset class="fieldset w-full">
    @if ($label)
        <legend class="fieldset-legend">
            {{ $label }}
            @if ($required) <span class="text-error ml-0.5">*</span> @endif
        </legend>
    @endif

    <select
        name="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'select w-full' . ($errors->has($name) ? ' select-error' : '')]) }}
    >
        {{ $slot }}
    </select>

    @error($name)
        <p class="fieldset-label text-error">{{ $message }}</p>
    @enderror
</fieldset>
