import type { ReactNode } from 'react';
import { cn } from '@/lib/utils';

interface Props {
    label?: string;
    className?: string;
    children: ReactNode;
}

/** A bordered card with an optional small uppercase label (ports x-card). */
export function SectionCard({ label, className, children }: Props) {
    return (
        <div className={cn('rounded-xl border bg-card p-6', className)}>
            {label && (
                <h2 className="mb-4 text-xs font-medium uppercase tracking-wide text-muted-foreground">
                    {label}
                </h2>
            )}
            {children}
        </div>
    );
}
