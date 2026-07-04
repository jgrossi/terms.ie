import { Link } from '@inertiajs/react';
import type { ReactNode } from 'react';

export interface Crumb {
    label: string;
    href?: string;
}

interface Props {
    title: string;
    subtitle?: ReactNode;
    crumbs?: Crumb[];
    actions?: ReactNode;
}

export function PageHeader({ title, subtitle, crumbs, actions }: Props) {
    return (
        <div className="mb-8">
            {crumbs && crumbs.length > 0 && (
                <nav className="mb-3 flex items-center gap-2 text-sm">
                    {crumbs.map((crumb, i) => (
                        <span key={i} className="flex items-center gap-2">
                            {i > 0 && <span className="text-muted-foreground/40">/</span>}
                            {crumb.href ? (
                                <Link
                                    href={crumb.href}
                                    className="text-muted-foreground transition-colors hover:text-foreground"
                                >
                                    {crumb.label}
                                </Link>
                            ) : (
                                <span className="text-foreground/60">{crumb.label}</span>
                            )}
                        </span>
                    ))}
                </nav>
            )}

            <div className="flex items-start justify-between gap-4">
                <div className="min-w-0">
                    <h1 className="text-2xl font-semibold leading-tight tracking-tight">
                        {title}
                    </h1>
                    {subtitle && (
                        <div className="mt-1.5 text-sm text-muted-foreground">{subtitle}</div>
                    )}
                </div>
                {actions && (
                    <div className="flex shrink-0 items-center gap-2">{actions}</div>
                )}
            </div>
        </div>
    );
}
