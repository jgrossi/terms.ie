import { usePage } from '@inertiajs/react';
import { useEffect, useRef } from 'react';
import { toast } from 'sonner';
import type { Flash, SharedProps } from '@/types';

/**
 * Bridges Laravel's `->with('toast', ...)` session flash to a Sonner toast.
 * Call once from inside a layout (must run within the Inertia <App> context).
 *
 * Dedupe is keyed on the flash *object* (a fresh instance per Inertia visit),
 * not the message text — so two actions that flash the same message (e.g.
 * "Client created." twice) both toast, while a plain re-render does not.
 */
export function useFlashToast() {
    const flash = usePage<SharedProps>().props.flash;
    const shown = useRef<Flash | null>(null);

    useEffect(() => {
        if (flash && flash !== shown.current && flash.toast) {
            shown.current = flash;
            toast.success(flash.toast);
        }
    }, [flash]);
}
