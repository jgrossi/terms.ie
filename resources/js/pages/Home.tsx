import { Head } from '@inertiajs/react';
import {
    Check,
    Code,
    Download,
    FileText,
    Layers,
    PenLine,
    ShieldCheck,
} from 'lucide-react';
import type { ReactNode } from 'react';
import { LandingLayout } from '@/layouts/landing-layout';
import { MagicLinkForm } from '@/components/magic-link-form';

const STEPS = [
    {
        n: '01',
        title: 'Create a term',
        body: 'Write your terms in plain text using variables like {{CLIENT_NAME}} and {{APPOINTMENT_DATE}}.',
    },
    {
        n: '02',
        title: 'Send to your client',
        body: 'Generate a secure link and share it with your client by email or message.',
    },
    {
        n: '03',
        title: 'Get it signed',
        body: 'Your client reads the terms and accepts by entering their full name — no account needed.',
    },
    {
        n: '04',
        title: 'Download the PDF',
        body: 'A signed PDF is generated instantly with a timestamp, IP address and verification hash.',
    },
];

const FEATURES = [
    { icon: FileText, title: 'Plain text templates', body: 'No PDF editors or complex software. Write your terms like you write an email.' },
    { icon: Code, title: 'Variables', body: 'Automatically personalise each document with placeholders like {{CLIENT_NAME}} and {{AMOUNT}}.' },
    { icon: PenLine, title: 'Electronic signatures', body: 'Simple electronic acceptance recognised under the Electronic Commerce Act 2000 in Ireland.' },
    { icon: ShieldCheck, title: 'Audit trail', body: 'Every signature is recorded with a timestamp, IP address and full name for your records.' },
    { icon: Download, title: 'Signed PDF', body: 'A permanent PDF is generated the moment your client signs. Download it anytime.' },
    { icon: Layers, title: 'Version history', body: 'Old signed documents are never changed. Each version is locked when a client signs.' },
];

const WHO = ['Photographers', 'Dentists', 'Doctors', 'Sports clubs', 'Personal trainers', 'Childcare providers', 'Pet services', 'Freelancers'];

const FREE_FEATURES = ['1 template', '5 signatures/month', 'Signed PDFs', 'Audit trail', 'Powered by terms.ie'];
const PRO_FEATURES = ['Unlimited templates', 'Up to 100 signatures/month', 'Remove terms.ie branding', 'Signed PDFs', 'Audit trail', '€0.50 per additional signature'];

const FAQ = [
    { q: 'Is this legally recognised in Ireland?', a: 'Electronic acceptance is recognised under the Electronic Commerce Act 2000 in Ireland for many common business agreements and consent forms. We recommend consulting a solicitor for your specific use case.' },
    { q: 'Do I need to upload PDFs?', a: 'No. Terms are written in plain text directly in the app. We handle the PDF generation automatically when your client signs.' },
    { q: 'Can my clients sign on mobile?', a: 'Yes. The signing page works on any device — phone, tablet, or desktop. No app download required.' },
    { q: 'What happens if I update a term after sending it?', a: 'Already-sent terms are locked to the version that was shared. Updating a term creates a new version — existing signed documents are never changed.' },
    { q: 'What happens after 100 signatures on the Pro plan?', a: 'Additional signatures are charged at €0.50 each. You will only be billed for what you use.' },
];

function Mono({ children }: { children: ReactNode }) {
    return (
        <code className="rounded bg-primary/10 px-1 py-0.5 font-mono text-xs text-primary">
            {children}
        </code>
    );
}

export default function Home() {
    return (
        <>
            <Head title="Create, send and sign terms online" />

            {/* HERO */}
            <section className="bg-background px-6 pb-24 pt-16">
                <div className="mx-auto grid max-w-6xl items-center gap-12 lg:grid-cols-2">
                    <div>
                        <div className="mb-6 inline-flex items-center gap-2 rounded-full bg-muted px-3 py-1 text-sm font-medium text-muted-foreground">
                            <span className="size-1.5 rounded-full bg-foreground" />
                            Built for Irish small businesses
                        </div>
                        <h1 className="mb-5 text-5xl font-bold leading-[1.05] tracking-tight md:text-6xl">
                            Create, send
                            <br />
                            and sign terms
                            <br />
                            online.
                        </h1>
                        <p className="mb-10 max-w-md text-xl leading-relaxed text-muted-foreground">
                            The easiest way for Irish small businesses to collect signed terms,
                            consent forms and agreements.
                        </p>

                        <div id="get-started">
                            <MagicLinkForm />
                        </div>

                        <div className="mt-8 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-muted-foreground">
                            {['Electronic signature', 'Signed PDF download', 'Audit trail'].map(
                                (t) => (
                                    <span key={t} className="flex items-center gap-1.5">
                                        <Check className="size-4 text-success" /> {t}
                                    </span>
                                ),
                            )}
                        </div>
                    </div>

                    {/* Decorative mock cards */}
                    <div className="relative hidden items-center justify-center lg:flex">
                        <div className="absolute right-4 top-4 w-72 rotate-2 rounded-2xl border bg-card p-5 opacity-50 shadow-md">
                            <p className="mb-2 text-xs font-medium uppercase tracking-wide text-muted-foreground">
                                Consent Form
                            </p>
                            <span className="inline-flex items-center gap-1.5 text-xs font-medium text-foreground/70">
                                <span className="size-1.5 rounded-full bg-muted-foreground/40" />
                                Pending
                            </span>
                            <div className="mt-4 flex items-center gap-2">
                                <div className="flex size-8 items-center justify-center rounded-full bg-muted text-xs font-semibold text-muted-foreground">
                                    SM
                                </div>
                                <div>
                                    <p className="text-sm text-muted-foreground">Sarah Murphy</p>
                                    <p className="text-xs text-muted-foreground/60">
                                        sarah@example.ie
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div className="relative z-10 w-80 -rotate-1 rounded-2xl border bg-card shadow-2xl">
                            <div className="p-5">
                                <div className="mb-4 flex items-start justify-between">
                                    <div>
                                        <p className="mb-0.5 text-xs font-medium uppercase tracking-wide text-muted-foreground">
                                            Photography Terms
                                        </p>
                                        <p className="text-xs text-muted-foreground/60">
                                            Version 2 · 1 Jul 2026
                                        </p>
                                    </div>
                                    <span className="inline-flex items-center gap-1 rounded-full bg-success/10 px-2 py-0.5 text-xs font-medium text-success">
                                        <Check className="size-2.5" /> Signed
                                    </span>
                                </div>
                                <div className="flex items-center gap-3 border-y py-3">
                                    <div className="flex size-9 items-center justify-center rounded-full bg-primary/10 text-sm font-bold text-primary">
                                        JD
                                    </div>
                                    <div>
                                        <p className="text-sm font-medium">John Doe</p>
                                        <p className="text-xs text-muted-foreground">
                                            john@example.ie
                                        </p>
                                    </div>
                                </div>
                                <div className="space-y-1 pt-3">
                                    <p className="text-xs text-muted-foreground">
                                        Signed 1 Jul 2026 at 14:32
                                    </p>
                                    <p className="text-xs text-muted-foreground/60">
                                        IP: 81.24.xxx.xxx
                                    </p>
                                </div>
                                <div className="mt-3 rounded-lg bg-muted p-2.5">
                                    <p className="break-all font-mono text-[10px] leading-relaxed text-muted-foreground/60">
                                        SHA-256: a3f2b1c4d8e9f0a1b2c3...
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* HOW IT WORKS */}
            <section className="bg-muted px-6 py-20">
                <div className="mx-auto max-w-6xl">
                    <div className="mb-14 text-center">
                        <h2 className="mb-3 text-3xl font-bold tracking-tight md:text-4xl">
                            How it works
                        </h2>
                        <p className="text-lg text-muted-foreground">
                            Up and running in minutes. No software to install.
                        </p>
                    </div>
                    <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        {STEPS.map((step) => (
                            <div
                                key={step.n}
                                className="flex flex-col gap-3 rounded-2xl bg-card p-6"
                            >
                                <span className="text-4xl font-bold tabular-nums text-foreground/20">
                                    {step.n}
                                </span>
                                <h3 className="text-lg font-semibold">{step.title}</h3>
                                <p className="text-sm leading-relaxed text-muted-foreground">
                                    {step.body}
                                </p>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* FEATURES */}
            <section className="bg-background px-6 py-20">
                <div className="mx-auto max-w-6xl">
                    <div className="mb-14 text-center">
                        <h2 className="mb-3 text-3xl font-bold tracking-tight md:text-4xl">
                            Everything you need
                        </h2>
                        <p className="text-lg text-muted-foreground">
                            Simple by design. Powerful where it counts.
                        </p>
                    </div>
                    <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        {FEATURES.map(({ icon: Icon, title, body }) => (
                            <div
                                key={title}
                                className="flex flex-col gap-3 rounded-2xl border p-6"
                            >
                                <div className="flex size-10 items-center justify-center rounded-lg bg-muted text-muted-foreground">
                                    <Icon className="size-5" />
                                </div>
                                <h3 className="font-semibold">{title}</h3>
                                <p className="text-sm leading-relaxed text-muted-foreground">
                                    {body}
                                </p>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* WHO IT'S FOR */}
            <section className="bg-muted px-6 py-20">
                <div className="mx-auto max-w-6xl">
                    <div className="mb-14 text-center">
                        <h2 className="mb-3 text-3xl font-bold tracking-tight md:text-4xl">
                            Who is it for?
                        </h2>
                        <p className="text-lg text-muted-foreground">
                            Any Irish business that needs a signature.
                        </p>
                    </div>
                    <div className="flex flex-wrap justify-center gap-3">
                        {WHO.map((who) => (
                            <span
                                key={who}
                                className="rounded-full border bg-card px-4 py-2 text-sm font-medium text-foreground/70"
                            >
                                {who}
                            </span>
                        ))}
                    </div>
                </div>
            </section>

            {/* PRICING */}
            <section id="pricing" className="bg-background px-6 py-20">
                <div className="mx-auto max-w-6xl">
                    <div className="mb-14 text-center">
                        <h2 className="mb-3 text-3xl font-bold tracking-tight md:text-4xl">
                            Simple pricing
                        </h2>
                        <p className="text-lg text-muted-foreground">
                            Start free, upgrade when you need more.
                        </p>
                    </div>
                    <div className="mx-auto grid max-w-3xl gap-6 lg:grid-cols-2">
                        <div className="flex flex-col rounded-2xl border p-8">
                            <div className="mb-6">
                                <p className="mb-1 text-lg font-bold">Free</p>
                                <div className="flex items-end gap-1">
                                    <span className="text-4xl font-bold">€0</span>
                                    <span className="mb-1 text-muted-foreground">/month</span>
                                </div>
                            </div>
                            <ul className="mb-8 flex-1 space-y-3">
                                {FREE_FEATURES.map((f) => (
                                    <li
                                        key={f}
                                        className="flex items-center gap-2 text-sm text-foreground/70"
                                    >
                                        <Check className="size-4 shrink-0 text-success" /> {f}
                                    </li>
                                ))}
                            </ul>
                            <a
                                href="#get-started"
                                className="inline-flex h-10 items-center justify-center rounded-md border font-medium transition-colors hover:bg-muted"
                            >
                                Get started free
                            </a>
                        </div>

                        <div className="relative flex flex-col rounded-2xl border-2 border-primary p-8">
                            <div className="absolute -top-3 left-1/2 -translate-x-1/2">
                                <span className="rounded-full bg-primary px-3 py-0.5 text-xs font-medium text-primary-foreground">
                                    Most popular
                                </span>
                            </div>
                            <div className="mb-6">
                                <p className="mb-1 text-lg font-bold">Pro</p>
                                <div className="flex items-end gap-1">
                                    <span className="text-4xl font-bold">€5</span>
                                    <span className="mb-1 text-muted-foreground">/month</span>
                                </div>
                                <p className="mt-1 text-sm text-muted-foreground">
                                    or €50/year · save 2 months
                                </p>
                            </div>
                            <ul className="mb-8 flex-1 space-y-3">
                                {PRO_FEATURES.map((f) => (
                                    <li
                                        key={f}
                                        className="flex items-center gap-2 text-sm text-foreground/70"
                                    >
                                        <Check className="size-4 shrink-0 text-success" /> {f}
                                    </li>
                                ))}
                                <li className="flex items-center gap-2 text-sm text-muted-foreground line-through">
                                    <Check className="size-4 shrink-0" /> Template library (coming
                                    soon)
                                </li>
                            </ul>
                            <a
                                href="#get-started"
                                className="inline-flex h-10 items-center justify-center rounded-md bg-primary font-medium text-primary-foreground transition-colors hover:bg-primary/90"
                            >
                                Get started free
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            {/* FAQ */}
            <section className="bg-muted px-6 py-20">
                <div className="mx-auto max-w-3xl">
                    <div className="mb-14 text-center">
                        <h2 className="text-3xl font-bold tracking-tight md:text-4xl">
                            Common questions
                        </h2>
                    </div>
                    <div className="flex flex-col gap-3">
                        {FAQ.map((item, i) => (
                            <details
                                key={item.q}
                                open={i === 0}
                                className="group rounded-xl border bg-card px-5 py-4"
                            >
                                <summary className="flex cursor-pointer items-center justify-between font-medium marker:content-['']">
                                    {item.q}
                                    <span className="ml-4 shrink-0 text-muted-foreground transition-transform group-open:rotate-180">
                                        ▾
                                    </span>
                                </summary>
                                <p className="mt-3 text-sm leading-relaxed text-muted-foreground">
                                    {item.a}
                                </p>
                            </details>
                        ))}
                    </div>
                </div>
            </section>

            {/* FINAL CTA */}
            <section className="bg-primary px-6 py-20">
                <div className="mx-auto max-w-2xl text-center">
                    <h2 className="mb-4 text-4xl font-bold tracking-tight text-primary-foreground">
                        Stop sending PDFs.
                        <br />
                        Send terms instead.
                    </h2>
                    <p className="mb-10 text-lg text-primary-foreground/70">
                        Create your first term in minutes. Free for one template.
                    </p>
                    <div className="mx-auto flex max-w-md justify-center">
                        <MagicLinkForm variant="inverted" />
                    </div>
                </div>
            </section>

            {/* FOOTER */}
            <footer className="bg-foreground px-6 py-12 text-background">
                <div className="mx-auto flex max-w-6xl flex-col items-start justify-between gap-6 sm:flex-row sm:items-center">
                    <div>
                        <p className="mb-1 text-lg font-bold tracking-tight">
                            terms<span className="text-primary">.ie</span>
                        </p>
                        <p className="text-sm text-background/40">Made in Ireland 🇮🇪</p>
                    </div>
                    <nav className="flex flex-wrap gap-x-6 gap-y-2">
                        {['Pricing', 'Blog', 'Privacy Policy', 'Terms of Service', 'Contact'].map(
                            (label) => (
                                <a
                                    key={label}
                                    href="#"
                                    className="text-sm text-background/50 transition-colors hover:text-background"
                                >
                                    {label}
                                </a>
                            ),
                        )}
                    </nav>
                </div>
            </footer>
        </>
    );
}

Home.layout = (page: ReactNode) => <LandingLayout>{page}</LandingLayout>;
