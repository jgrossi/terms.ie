import type { ReactNode } from 'react';
import { useFlashToast } from '@/hooks/use-flash-toast';

/** Minimal public shell for the client-facing signing page (ports layouts.client). */
export function ClientLayout({ children }: { children: ReactNode }) {
    useFlashToast();

    return (
        <div className="min-h-screen bg-muted/30">
            <nav className="border-b bg-background">
                <div className="mx-auto flex h-14 max-w-3xl items-center px-6">
                    <span className="text-lg font-bold tracking-tight">
                        terms<span className="text-primary">.ie</span>
                    </span>
                </div>
            </nav>

            <main className="mx-auto max-w-3xl px-6 py-10">{children}</main>
        </div>
    );
}
