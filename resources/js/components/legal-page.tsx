import { Head, Link } from '@inertiajs/react';
import type { ReactNode } from 'react';
import { route } from 'ziggy-js';

interface Props {
    title: string;
    updated: string;
    children: ReactNode;
}

/** Shared shell for the static legal pages (Terms, Privacy). */
export function LegalPage({ title, updated, children }: Props) {
    return (
        <>
            <Head title={title} />
            <div className="mx-auto max-w-3xl px-6 py-16">
                <Link
                    href={route('home')}
                    className="text-sm text-muted-foreground transition-colors hover:text-foreground"
                >
                    ← Back to terms.ie
                </Link>
                <h1 className="mt-6 text-3xl font-bold tracking-tight">{title}</h1>
                <p className="mt-2 text-sm text-muted-foreground">Last updated {updated}</p>

                <div className="mt-8 space-y-6 text-sm leading-relaxed text-foreground/80 [&_h2]:mt-8 [&_h2]:text-base [&_h2]:font-semibold [&_h2]:text-foreground [&_a]:text-primary [&_a]:underline">
                    {children}
                </div>

                <p className="mt-12 rounded-lg border bg-muted/40 px-4 py-3 text-xs text-muted-foreground">
                    This is a general template and not legal advice. Have it reviewed by a
                    solicitor before relying on it.
                </p>
            </div>
        </>
    );
}
