<div class="flex flex-col gap-5">
    <x-form.input name="name" label="Name" placeholder="e.g. Photography Terms" required :value="$term?->name" />

    <fieldset class="fieldset w-full">
        <legend class="fieldset-legend">
            Body <span class="text-error ml-0.5">*</span>
            <span class="font-normal text-base-content/40 ml-2">Use <code class="bg-base-200 px-1 rounded font-mono text-xs">@{{VARIABLE_NAME}}</code> for placeholders</span>
        </legend>
        <textarea id="textarea-body"
                  name="body"
                  rows="16"
                  required
                  placeholder="Write your terms here...&#10;&#10;Hello @{{CLIENT_NAME}},&#10;&#10;These are the terms for your appointment on @{{DATE}}."
                  class="textarea w-full font-mono text-sm leading-relaxed {{ $errors->has('body') ? 'textarea-error' : '' }}">{{ old('body', $term?->body) }}</textarea>
        @error('body')
            <p class="fieldset-label text-error">{{ $message }}</p>
        @enderror
    </fieldset>

    <div class="bg-base-200 rounded-xl p-4 text-sm text-base-content/60 space-y-1">
        <p class="font-medium text-base-content/80">Reserved variables — filled automatically from the client:</p>
        <div class="flex gap-2 flex-wrap mt-2">
            @foreach (\App\Models\Term::RESERVED as $var)
                @php $label = '{{' . $var . '}}'; @endphp
                <code class="bg-success/10 text-success text-xs px-2 py-1 rounded font-mono">{{ $label }}</code>
            @endforeach
        </div>
    </div>
</div>
