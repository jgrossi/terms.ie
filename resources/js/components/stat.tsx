import { Link } from '@inertiajs/react';
import type { ReactNode } from 'react';
import { cn } from '@/lib/utils';

interface Props {
    label: string;
    value: number;
    caption?: string;
    icon: ReactNode;
    href?: string;
    /** Marks the card as needing attention with a small amber dot (e.g. pending work). */
    attention?: boolean;
}

export function Stat({ label, value, caption, icon, href, attention = false }: Props) {
    const className = cn(
        'group block rounded-xl border bg-card p-4 transition-colors',
        href && 'hover:border-muted-foreground/30',
    );

    const content = (
        <>
            <div className="flex items-center justify-between">
                <div className="flex items-center gap-1.5">
                    <p className="text-xs font-medium uppercase tracking-wide text-muted-foreground">
                        {label}
                    </p>
                    {attention && (
                        <span className="size-1.5 rounded-full bg-warning" aria-hidden />
                    )}
                </div>
                <span className="text-muted-foreground/50">{icon}</span>
            </div>
            <p className="mt-3 text-2xl font-semibold tabular-nums">{value}</p>
            {caption && (
                <p className="mt-0.5 text-xs text-muted-foreground/60">{caption}</p>
            )}
        </>
    );

    if (href) {
        return (
            <Link href={href} className={className}>
                {content}
            </Link>
        );
    }
    return <div className={className}>{content}</div>;
}
