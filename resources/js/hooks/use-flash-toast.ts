import { usePage } from '@inertiajs/react';
import { useEffect, useRef } from 'react';
import { toast } from 'sonner';
import type { SharedProps } from '@/types';

/**
 * Bridges Laravel's `->with('toast', ...)` session flash to a Sonner toast.
 * Call once from inside a layout (must run within the Inertia <App> context).
 */
export function useFlashToast() {
    const { flash } = usePage<SharedProps>().props;
    const last = useRef<string | null>(null);

    useEffect(() => {
        const message = flash?.toast ?? null;
        if (message && message !== last.current) {
            last.current = message;
            toast.success(message);
        }
    }, [flash]);
}
