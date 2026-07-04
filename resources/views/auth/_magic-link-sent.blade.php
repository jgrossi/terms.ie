<div class="flex flex-col gap-2 max-w-md">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-success/15 flex items-center justify-center shrink-0">
            <x-icon name="mail" class="w-5 h-5 text-success" />
        </div>
        <div>
            <p class="font-semibold text-base-content">Check your inbox</p>
            <p class="text-sm text-base-content/50">We sent a sign-in link to <strong>{{ $email }}</strong></p>
        </div>
    </div>
    <p class="text-xs text-base-content/40 pl-13">The link expires in 15 minutes. Check your spam folder if you don't see it.</p>
</div>
