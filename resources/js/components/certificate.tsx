import { Check } from 'lucide-react';
import { cn } from '@/lib/utils';
import { formatDateTime } from '@/lib/format';

export interface CertificateData {
    id: string;
    signed_name: string;
    signed_at: string;
    signed_ip: string | null;
    content_hash: string | null;
}

export function Certificate({
    signature,
    className,
}: {
    signature: CertificateData;
    className?: string;
}) {
    return (
        <div className={cn('overflow-hidden rounded-xl border bg-card shadow-sm', className)}>
            <div className="flex items-center gap-3 bg-foreground px-6 py-4 text-background">
                <div className="flex size-9 shrink-0 items-center justify-center rounded-full bg-background/10">
                    <Check className="size-5" />
                </div>
                <div className="min-w-0">
                    <p className="text-[11px] font-medium uppercase tracking-widest text-background/50">
                        Signed &amp; verified
                    </p>
                    <p className="truncate text-lg font-semibold leading-tight">
                        {signature.signed_name}
                    </p>
                </div>
            </div>
            <dl className="divide-y text-sm">
                <div className="flex justify-between gap-4 px-6 py-3">
                    <dt className="text-muted-foreground">Date signed</dt>
                    <dd>{formatDateTime(signature.signed_at)}</dd>
                </div>
                <div className="flex justify-between gap-4 px-6 py-3">
                    <dt className="text-muted-foreground">IP address</dt>
                    <dd className="font-mono text-xs text-foreground/70">
                        {signature.signed_ip}
                    </dd>
                </div>
                <div className="flex justify-between gap-4 px-6 py-3">
                    <dt className="shrink-0 text-muted-foreground">Reference</dt>
                    <dd className="truncate font-mono text-xs text-foreground/70">
                        {signature.id}
                    </dd>
                </div>
                <div className="px-6 py-3">
                    <dt className="mb-1 text-muted-foreground">
                        Verification hash (SHA-256)
                    </dt>
                    <dd className="break-all font-mono text-[11px] leading-relaxed text-muted-foreground">
                        {signature.content_hash}
                    </dd>
                </div>
            </dl>
        </div>
    );
}
