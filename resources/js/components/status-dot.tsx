import { cn } from '@/lib/utils';
import type { SignatureStatus } from '@/types';

type DisplayStatus = SignatureStatus | 'expired';

const CONFIG: Record<DisplayStatus, { dot: string; label: string }> = {
    signed: { dot: 'bg-success', label: 'Signed' },
    pending: { dot: 'bg-muted-foreground/40', label: 'Pending' },
    expired: { dot: 'bg-warning', label: 'Expired' },
};

export function StatusDot({ status }: { status: DisplayStatus }) {
    const { dot, label } = CONFIG[status];
    return (
        <span className="inline-flex items-center gap-1.5 text-xs font-medium text-foreground/70">
            <span className={cn('size-1.5 rounded-full', dot)} />
            {label}
        </span>
    );
}
