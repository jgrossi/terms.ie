import { Link, usePage } from '@inertiajs/react';
import { route } from 'ziggy-js';
import type { ReactNode } from 'react';
import { cn } from '@/lib/utils';
import { useFlashToast } from '@/hooks/use-flash-toast';
import type { SharedProps } from '@/types';

function NavLink({
    href,
    active,
    children,
}: {
    href: string;
    active: boolean;
    children: ReactNode;
}) {
    return (
        <Link
            href={href}
            className={cn(
                'rounded-md px-3 py-1.5 text-sm font-medium transition-colors',
                active
                    ? 'bg-muted text-foreground'
                    : 'text-muted-foreground hover:bg-muted hover:text-foreground',
            )}
        >
            {children}
        </Link>
    );
}

export function AppLayout({ children }: { children: ReactNode }) {
    const { auth } = usePage<SharedProps>().props;
    const current = (pattern: string) => Boolean(route().current(pattern));

    useFlashToast();

    return (
        <div className="min-h-screen bg-muted/30">
            <nav className="border-b bg-background">
                <div className="mx-auto flex h-14 max-w-6xl items-center justify-between px-6">
                    <Link
                        href={route('app.dashboard')}
                        className="text-lg font-bold tracking-tight"
                    >
                        terms<span className="text-primary">.ie</span>
                    </Link>

                    <div className="flex flex-1 items-center justify-center gap-1">
                        <NavLink href={route('app.dashboard')} active={current('app.dashboard')}>
                            Dashboard
                        </NavLink>
                        <NavLink href={route('app.terms.index')} active={current('app.terms.*')}>
                            Terms
                        </NavLink>
                        <NavLink href={route('app.clients.index')} active={current('app.clients.*')}>
                            Clients
                        </NavLink>
                    </div>

                    <div className="flex items-center gap-3">
                        <span className="text-sm text-muted-foreground">
                            {auth.user?.email}
                        </span>
                        <Link
                            href={route('logout')}
                            method="post"
                            as="button"
                            className="text-sm text-muted-foreground transition-colors hover:text-foreground"
                        >
                            Sign out
                        </Link>
                    </div>
                </div>
            </nav>

            <main className="mx-auto max-w-6xl px-6 py-8">{children}</main>
        </div>
    );
}
