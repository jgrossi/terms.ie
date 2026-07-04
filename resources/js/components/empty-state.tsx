import type { ReactNode } from 'react';

interface Props {
    icon?: ReactNode;
    title: string;
    description?: string;
    action?: ReactNode;
}

export function EmptyState({ icon, title, description, action }: Props) {
    return (
        <div className="rounded-xl border bg-card px-6 py-16 text-center">
            {icon && (
                <div className="mx-auto mb-4 flex size-12 items-center justify-center rounded-full bg-muted text-muted-foreground/60">
                    {icon}
                </div>
            )}
            <h3 className="mb-1.5 text-lg font-semibold">{title}</h3>
            {description && (
                <p className="mx-auto mb-6 max-w-sm text-sm text-muted-foreground">
                    {description}
                </p>
            )}
            {action && <div className="flex justify-center gap-3">{action}</div>}
        </div>
    );
}
