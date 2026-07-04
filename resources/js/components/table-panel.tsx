import type { ReactNode } from 'react';
import { cn } from '@/lib/utils';

interface Props {
    /** Optional header title shown above a divider. */
    title?: ReactNode;
    /** Optional header action (e.g. a badge), right-aligned. */
    action?: ReactNode;
    className?: string;
    children: ReactNode;
}

/**
 * The single app-wide container for tabular lists: a bordered, `rounded-md`
 * (shadcn default) panel with an optional header. Wrap a <Table> or, for the
 * dashboard lists, table rows. Replaces ad-hoc <Card> table wrappers.
 */
export function TablePanel({ title, action, className, children }: Props) {
    return (
        <div className={cn('overflow-hidden rounded-md border bg-card', className)}>
            {title && (
                <div className="flex items-center justify-between border-b px-4 py-3">
                    <h2 className="text-sm font-semibold">{title}</h2>
                    {action}
                </div>
            )}
            {children}
        </div>
    );
}
