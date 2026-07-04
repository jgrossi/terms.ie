@foreach ($userVars as $var)
    @php $varLabel = '{{' . $var . '}}'; @endphp
    <x-form.input
        name="variables[{{ $var }}]"
        :label="$varLabel"
        :placeholder="'Value for ' . $varLabel"
        required
        :value="old('variables.' . $var)"
    />
@endforeach
