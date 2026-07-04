import { Link, usePage } from '@inertiajs/react';
import { route } from 'ziggy-js';
import type { ReactNode } from 'react';
import { Button } from '@/components/ui/button';
import { useFlashToast } from '@/hooks/use-flash-toast';
import type { SharedProps } from '@/types';

/** Public marketing shell with sticky nav + toast (ports layouts.landing). */
export function LandingLayout({ children }: { children: ReactNode }) {
    const { auth } = usePage<SharedProps>().props;
    useFlashToast();

    return (
        <div className="bg-background">
            <nav className="sticky top-0 z-40 border-b bg-background/95 backdrop-blur">
                <div className="mx-auto flex h-16 max-w-6xl items-center justify-between px-6">
                    <Link
                        href={route('home')}
                        className="text-xl font-bold tracking-tight"
                    >
                        terms<span className="text-primary">.ie</span>
                    </Link>
                    <div className="flex items-center gap-2">
                        <Button asChild variant="ghost" size="sm">
                            <a href="#pricing">Pricing</a>
                        </Button>
                        {auth.user ? (
                            <Button asChild size="sm">
                                <Link href={route('app.dashboard')}>Go to app →</Link>
                            </Button>
                        ) : (
                            <Button asChild size="sm">
                                <a href="#get-started">Get started</a>
                            </Button>
                        )}
                    </div>
                </div>
            </nav>

            {children}
        </div>
    );
}
