import { Link } from '@inertiajs/react';
import type { ReactNode } from 'react';
import { cn } from '@/lib/utils';

interface Props {
    label: string;
    value: number;
    caption?: string;
    icon: ReactNode;
    href?: string;
    /** Inverted (near-black) surface — used to draw attention (e.g. pending). */
    invert?: boolean;
}

export function Stat({ label, value, caption, icon, href, invert = false }: Props) {
    const className = cn(
        'group block rounded-xl p-5 transition-colors',
        invert
            ? 'bg-foreground text-background shadow-sm'
            : cn('border bg-card', href && 'hover:border-muted-foreground/30'),
    );

    const content = (
        <>
            <div className="mb-4 flex items-center justify-between">
                <p
                    className={cn(
                        'text-xs font-medium uppercase tracking-wide',
                        invert ? 'text-background/50' : 'text-muted-foreground',
                    )}
                >
                    {label}
                </p>
                <div
                    className={cn(
                        'flex size-7 items-center justify-center rounded-lg',
                        invert ? 'bg-background/10 text-background/70' : 'bg-muted text-muted-foreground',
                    )}
                >
                    {icon}
                </div>
            </div>
            <p className="text-3xl font-medium tabular-nums">{value}</p>
            {caption && (
                <p
                    className={cn(
                        'mt-1 text-xs',
                        invert ? 'text-background/40' : 'text-muted-foreground/60',
                    )}
                >
                    {caption}
                </p>
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
