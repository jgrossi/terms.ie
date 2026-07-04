import { cn } from '@/lib/utils';
import type { SignatureStatus } from '@/types';

export function StatusDot({ status }: { status: SignatureStatus }) {
    const signed = status === 'signed';
    return (
        <span className="inline-flex items-center gap-1.5 text-xs font-medium text-foreground/70">
            <span
                className={cn(
                    'size-1.5 rounded-full',
                    signed ? 'bg-success' : 'bg-muted-foreground/40',
                )}
            />
            {signed ? 'Signed' : 'Pending'}
        </span>
    );
}
