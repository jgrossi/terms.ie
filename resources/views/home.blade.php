@extends('layouts.landing')

@section('content')

{{-- HERO --}}
<section class="bg-base-100 pt-16 pb-24 px-6">
    <div class="max-w-6xl mx-auto">
        <div class="grid lg:grid-cols-2 gap-12 items-center">

            {{-- Left: copy + form --}}
            <div>
                <div class="inline-flex items-center gap-2 bg-base-200 text-base-content/70 text-sm font-medium px-3 py-1 rounded-full mb-6">
                    <span class="w-1.5 h-1.5 bg-base-content rounded-full"></span>
                    Built for Irish small businesses
                </div>

                <h1 class="text-5xl md:text-6xl font-bold text-base-content leading-[1.05] tracking-tight mb-5">
                    Create, send<br>and sign terms<br>online.
                </h1>

                <p class="text-xl text-base-content/60 mb-10 max-w-md leading-relaxed">
                    The easiest way for Irish small businesses to collect signed terms, consent forms and agreements.
                </p>

                <div id="get-started">
                    <div id="hero-form">
                        <form hx-post="{{ route('magic-link.send') }}"
                              hx-target="#hero-form"
                              hx-swap="innerHTML">
                            @csrf
                            <div class="flex gap-3 max-w-md">
                                <input type="email"
                                       name="email"
                                       placeholder="your@email.com"
                                       class="input flex-1 text-base"
                                       required />
                                <button type="submit" class="btn btn-primary px-6">
                                    Start for free →
                                </button>
                            </div>
                        </form>
                        <p class="text-sm text-base-content/40 mt-3">No credit card required · Free plan available</p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-x-6 gap-y-2 mt-8 text-sm text-base-content/50">
                    <span class="flex items-center gap-1.5">
                        <x-icon name="check" class="w-4 h-4 text-success" /> Electronic signature
                    </span>
                    <span class="flex items-center gap-1.5">
                        <x-icon name="check" class="w-4 h-4 text-success" /> Signed PDF download
                    </span>
                    <span class="flex items-center gap-1.5">
                        <x-icon name="check" class="w-4 h-4 text-success" /> Audit trail
                    </span>
                </div>
            </div>

            {{-- Right: mock UI --}}
            <div class="relative hidden lg:flex justify-center items-center">
                {{-- Background card --}}
                <div class="absolute top-4 right-4 w-72 bg-base-100 border border-base-200 rounded-2xl shadow-md p-5 rotate-2 opacity-50">
                    <p class="text-xs text-base-content/40 font-medium uppercase tracking-wide mb-2">Consent Form</p>
                    <x-status :status="\App\Enums\SignatureStatus::Pending" />
                    <div class="flex items-center gap-2 mt-4">
                        <div class="w-8 h-8 rounded-full bg-base-200 flex items-center justify-center text-xs font-semibold text-base-content/50">SM</div>
                        <div>
                            <p class="text-sm text-base-content/50">Sarah Murphy</p>
                            <p class="text-xs text-base-content/30">sarah@example.ie</p>
                        </div>
                    </div>
                </div>

                {{-- Main card --}}
                <div class="relative bg-base-100 border border-base-200 rounded-2xl shadow-2xl w-80 z-10 -rotate-1">
                    <div class="p-5">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <p class="text-xs text-base-content/40 font-medium uppercase tracking-wide mb-0.5">Photography Terms</p>
                                <p class="text-xs text-base-content/30">Version 2 · 1 Jul 2026</p>
                            </div>
                            <span class="badge badge-success badge-sm gap-1">
                                <x-icon name="check" class="w-2.5 h-2.5" />
                                Signed
                            </span>
                        </div>

                        <div class="flex items-center gap-3 py-3 border-t border-b border-base-200">
                            <div class="w-9 h-9 rounded-full bg-primary/10 flex items-center justify-center text-primary text-sm font-bold">JD</div>
                            <div>
                                <p class="text-sm font-medium text-base-content">John Doe</p>
                                <p class="text-xs text-base-content/40">john@example.ie</p>
                            </div>
                        </div>

                        <div class="pt-3 space-y-1">
                            <p class="text-xs text-base-content/40">Signed 1 Jul 2026 at 14:32</p>
                            <p class="text-xs text-base-content/30">IP: 81.24.xxx.xxx</p>
                        </div>

                        <div class="mt-3 bg-base-200 rounded-lg p-2.5">
                            <p class="text-[10px] font-mono text-base-content/30 break-all leading-relaxed">SHA-256: a3f2b1c4d8e9f0a1b2c3...</p>
                        </div>

                        <button class="btn btn-ghost btn-xs w-full mt-3 gap-1 text-base-content/40">
                            <x-icon name="download" class="w-3 h-3" />
                            Download signed PDF
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- HOW IT WORKS --}}
<section class="bg-base-200 py-20 px-6">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-14">
            <h2 class="text-3xl md:text-4xl font-bold text-base-content tracking-tight mb-3">How it works</h2>
            <p class="text-base-content/50 text-lg">Up and running in minutes. No software to install.</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-base-100 rounded-2xl p-6 flex flex-col gap-3">
                <span class="text-4xl font-bold text-base-content/20 tabular-nums">01</span>
                <h3 class="font-semibold text-base-content text-lg">Create a term</h3>
                <p class="text-base-content/50 text-sm leading-relaxed">
                    Write your terms in plain text using variables like
                    <code class="bg-primary/10 text-primary px-1 py-0.5 rounded text-xs font-mono">@{{CLIENT_NAME}}</code>
                    and
                    <code class="bg-primary/10 text-primary px-1 py-0.5 rounded text-xs font-mono">@{{APPOINTMENT_DATE}}</code>.
                </p>
            </div>
            <div class="bg-base-100 rounded-2xl p-6 flex flex-col gap-3">
                <span class="text-4xl font-bold text-base-content/20 tabular-nums">02</span>
                <h3 class="font-semibold text-base-content text-lg">Send to your client</h3>
                <p class="text-base-content/50 text-sm leading-relaxed">Generate a secure link and share it with your client by email or message.</p>
            </div>
            <div class="bg-base-100 rounded-2xl p-6 flex flex-col gap-3">
                <span class="text-4xl font-bold text-base-content/20 tabular-nums">03</span>
                <h3 class="font-semibold text-base-content text-lg">Get it signed</h3>
                <p class="text-base-content/50 text-sm leading-relaxed">Your client reads the terms and accepts by entering their full name — no account needed.</p>
            </div>
            <div class="bg-base-100 rounded-2xl p-6 flex flex-col gap-3">
                <span class="text-4xl font-bold text-base-content/20 tabular-nums">04</span>
                <h3 class="font-semibold text-base-content text-lg">Download the PDF</h3>
                <p class="text-base-content/50 text-sm leading-relaxed">A signed PDF is generated instantly with a timestamp, IP address and verification hash.</p>
            </div>
        </div>
    </div>
</section>

{{-- FEATURES --}}
<section class="bg-base-100 py-20 px-6">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-14">
            <h2 class="text-3xl md:text-4xl font-bold text-base-content tracking-tight mb-3">Everything you need</h2>
            <p class="text-base-content/50 text-lg">Simple by design. Powerful where it counts.</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="border border-base-200 rounded-2xl p-6 flex flex-col gap-3">
                <div class="w-10 h-10 rounded-lg bg-base-200 flex items-center justify-center">
                    <x-icon name="document" class="w-5 h-5 text-base-content/50" />
                </div>
                <h3 class="font-semibold text-base-content">Plain text templates</h3>
                <p class="text-base-content/50 text-sm leading-relaxed">No PDF editors or complex software. Write your terms like you write an email.</p>
            </div>
            <div class="border border-base-200 rounded-2xl p-6 flex flex-col gap-3">
                <div class="w-10 h-10 rounded-lg bg-base-200 flex items-center justify-center">
                    <x-icon name="code" class="w-5 h-5 text-base-content/50" />
                </div>
                <h3 class="font-semibold text-base-content">Variables</h3>
                <p class="text-base-content/50 text-sm leading-relaxed">
                    Automatically personalise each document with placeholders like
                    <code class="bg-primary/10 text-primary px-1 py-0.5 rounded text-xs font-mono">@{{CLIENT_NAME}}</code>
                    and
                    <code class="bg-primary/10 text-primary px-1 py-0.5 rounded text-xs font-mono">@{{AMOUNT}}</code>.
                </p>
            </div>
            <div class="border border-base-200 rounded-2xl p-6 flex flex-col gap-3">
                <div class="w-10 h-10 rounded-lg bg-base-200 flex items-center justify-center">
                    <x-icon name="pen" class="w-5 h-5 text-base-content/50" />
                </div>
                <h3 class="font-semibold text-base-content">Electronic signatures</h3>
                <p class="text-base-content/50 text-sm leading-relaxed">Simple electronic acceptance recognised under the Electronic Commerce Act 2000 in Ireland.</p>
            </div>
            <div class="border border-base-200 rounded-2xl p-6 flex flex-col gap-3">
                <div class="w-10 h-10 rounded-lg bg-base-200 flex items-center justify-center">
                    <x-icon name="shield-check" class="w-5 h-5 text-base-content/50" />
                </div>
                <h3 class="font-semibold text-base-content">Audit trail</h3>
                <p class="text-base-content/50 text-sm leading-relaxed">Every signature is recorded with a timestamp, IP address and full name for your records.</p>
            </div>
            <div class="border border-base-200 rounded-2xl p-6 flex flex-col gap-3">
                <div class="w-10 h-10 rounded-lg bg-base-200 flex items-center justify-center">
                    <x-icon name="download" class="w-5 h-5 text-base-content/50" />
                </div>
                <h3 class="font-semibold text-base-content">Signed PDF</h3>
                <p class="text-base-content/50 text-sm leading-relaxed">A permanent PDF is generated the moment your client signs. Download it anytime.</p>
            </div>
            <div class="border border-base-200 rounded-2xl p-6 flex flex-col gap-3">
                <div class="w-10 h-10 rounded-lg bg-base-200 flex items-center justify-center">
                    <x-icon name="layers" class="w-5 h-5 text-base-content/50" />
                </div>
                <h3 class="font-semibold text-base-content">Version history</h3>
                <p class="text-base-content/50 text-sm leading-relaxed">Old signed documents are never changed. Each version is locked when a client signs.</p>
            </div>
        </div>
    </div>
</section>

{{-- WHO IT'S FOR --}}
<section class="bg-base-200 py-20 px-6">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-14">
            <h2 class="text-3xl md:text-4xl font-bold text-base-content tracking-tight mb-3">Who is it for?</h2>
            <p class="text-base-content/50 text-lg">Any Irish business that needs a signature.</p>
        </div>

        <div class="flex flex-wrap justify-center gap-3">
            @foreach(['Photographers', 'Dentists', 'Doctors', 'Sports clubs', 'Personal trainers', 'Childcare providers', 'Pet services', 'Freelancers'] as $who)
            <span class="bg-base-100 border border-base-200 rounded-full px-4 py-2 text-sm font-medium text-base-content/70">{{ $who }}</span>
            @endforeach
        </div>
    </div>
</section>

{{-- PRICING --}}
<section id="pricing" class="bg-base-100 py-20 px-6">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-14">
            <h2 class="text-3xl md:text-4xl font-bold text-base-content tracking-tight mb-3">Simple pricing</h2>
            <p class="text-base-content/50 text-lg">Start free, upgrade when you need more.</p>
        </div>

        <div class="grid lg:grid-cols-2 gap-6 max-w-3xl mx-auto">

            {{-- Free --}}
            <div class="border border-base-200 rounded-2xl p-8 flex flex-col">
                <div class="mb-6">
                    <p class="font-bold text-lg text-base-content mb-1">Free</p>
                    <div class="flex items-end gap-1">
                        <span class="text-4xl font-bold text-base-content">€0</span>
                        <span class="text-base-content/40 mb-1">/month</span>
                    </div>
                </div>
                <ul class="space-y-3 mb-8 flex-1">
                    @foreach(['1 template', '5 signatures/month', 'Signed PDFs', 'Audit trail', 'Powered by terms.ie'] as $feature)
                    <li class="flex items-center gap-2 text-sm text-base-content/70">
                        <x-icon name="check" class="w-4 h-4 text-success shrink-0" /> {{ $feature }}
                    </li>
                    @endforeach
                </ul>
                <a href="#get-started" class="btn btn-outline btn-block">Get started free</a>
            </div>

            {{-- Pro --}}
            <div class="border-2 border-primary rounded-2xl p-8 flex flex-col relative">
                <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                    <span class="badge badge-primary badge-sm px-3">Most popular</span>
                </div>
                <div class="mb-6">
                    <p class="font-bold text-lg text-base-content mb-1">Pro</p>
                    <div class="flex items-end gap-1">
                        <span class="text-4xl font-bold text-base-content">€5</span>
                        <span class="text-base-content/40 mb-1">/month</span>
                    </div>
                    <p class="text-sm text-base-content/40 mt-1">or €50/year · save 2 months</p>
                </div>
                <ul class="space-y-3 mb-8 flex-1">
                    @foreach([
                        'Unlimited templates',
                        'Up to 100 signatures/month',
                        'Remove terms.ie branding',
                        'Signed PDFs',
                        'Audit trail',
                        '€0.50 per additional signature',
                    ] as $feature)
                    <li class="flex items-center gap-2 text-sm text-base-content/70">
                        <x-icon name="check" class="w-4 h-4 text-success shrink-0" /> {{ $feature }}
                    </li>
                    @endforeach
                    <li class="flex items-center gap-2 text-sm text-base-content/40 line-through">
                        <x-icon name="check" class="w-4 h-4 shrink-0" /> Template library (coming soon)
                    </li>
                </ul>
                <a href="#get-started" class="btn btn-primary btn-block">Get started free</a>
            </div>

        </div>
    </div>
</section>

{{-- FAQ --}}
<section class="bg-base-200 py-20 px-6">
    <div class="max-w-3xl mx-auto">
        <div class="text-center mb-14">
            <h2 class="text-3xl md:text-4xl font-bold text-base-content tracking-tight mb-3">Common questions</h2>
        </div>

        <div class="flex flex-col gap-3">
            @foreach([
                [
                    'q' => 'Is this legally recognised in Ireland?',
                    'a' => 'Electronic acceptance is recognised under the Electronic Commerce Act 2000 in Ireland for many common business agreements and consent forms. We recommend consulting a solicitor for your specific use case.',
                ],
                [
                    'q' => 'Do I need to upload PDFs?',
                    'a' => 'No. Terms are written in plain text directly in the app. We handle the PDF generation automatically when your client signs.',
                ],
                [
                    'q' => 'Can my clients sign on mobile?',
                    'a' => 'Yes. The signing page works on any device — phone, tablet, or desktop. No app download required.',
                ],
                [
                    'q' => 'What happens if I update a term after sending it?',
                    'a' => 'Already-sent terms are locked to the version that was shared. Updating a term creates a new version — existing signed documents are never changed.',
                ],
                [
                    'q' => 'What happens after 100 signatures on the Pro plan?',
                    'a' => 'Additional signatures are charged at €0.50 each. You will only be billed for what you use.',
                ],
            ] as $i => $faq)
            <div class="collapse collapse-arrow bg-base-100 border border-base-200 rounded-xl">
                <input type="radio" name="faq" @if($i === 0) checked @endif />
                <div class="collapse-title font-medium text-base-content pr-8">{{ $faq['q'] }}</div>
                <div class="collapse-content text-base-content/60 text-sm leading-relaxed">
                    <p>{{ $faq['a'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- FINAL CTA --}}
<section class="bg-primary py-20 px-6">
    <div class="max-w-2xl mx-auto text-center">
        <h2 class="text-4xl font-bold text-primary-content tracking-tight mb-4">Stop sending PDFs.<br>Send terms instead.</h2>
        <p class="text-primary-content/70 text-lg mb-10">Create your first term in minutes. Free for one template.</p>

        <div id="footer-form">
            <form hx-post="{{ route('magic-link.send') }}"
                  hx-target="#footer-form"
                  hx-swap="innerHTML"
                  class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
                @csrf
                <input type="email"
                       name="email"
                       placeholder="your@email.com"
                       class="input flex-1 text-base bg-primary-content/10 border-primary-content/30 text-primary-content placeholder:text-primary-content/40 focus:border-primary-content"
                       required />
                <button type="submit" class="btn bg-primary-content text-primary hover:bg-primary-content/90 border-none px-6">
                    Start for free →
                </button>
            </form>
        </div>
    </div>
</section>

{{-- FOOTER --}}
<footer class="bg-base-content py-12 px-6">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
            <div>
                <p class="font-bold text-lg text-base-100 tracking-tight mb-1">
                    terms<span class="text-primary">.ie</span>
                </p>
                <p class="text-base-100/40 text-sm">Made in Ireland 🇮🇪</p>
            </div>

            <nav class="flex flex-wrap gap-x-6 gap-y-2">
                @foreach([
                    ['#pricing',  'Pricing'],
                    ['#',         'Blog'],
                    ['#',         'Privacy Policy'],
                    ['#',         'Terms of Service'],
                    ['#',         'Contact'],
                ] as [$href, $label])
                <a href="{{ $href }}" class="text-sm text-base-100/50 hover:text-base-100 transition-colors">{{ $label }}</a>
                @endforeach
            </nav>
        </div>
    </div>
</footer>

@endsection
