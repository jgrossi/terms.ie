<div class="flex flex-col gap-5">
    <x-form.input name="name" label="Name" placeholder="e.g. Jane Smith" required :value="$client?->name" />
    <x-form.input name="email" label="Email" type="email" placeholder="jane@example.ie" required :value="$client?->email" />
</div>
