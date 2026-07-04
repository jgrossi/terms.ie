<div {{ $attributes->merge(['class' => 'rounded-box border border-base-200 bg-paper px-8 py-7']) }}>
    <div class="max-w-none font-mono text-[13px] leading-[1.7] text-base-content/80 whitespace-pre-wrap">{{ $slot }}</div>
</div>
