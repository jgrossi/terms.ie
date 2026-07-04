import { Check } from 'lucide-react';
import { cn } from '@/lib/utils';
import { formatDateTimeUtc } from '@/lib/format';

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
            <div className="flex items-center gap-3 border-b bg-success/5 px-6 py-4">
                <div className="flex size-9 shrink-0 items-center justify-center rounded-full bg-success/10 text-success">
                    <Check className="size-5" />
                </div>
                <div className="min-w-0">
                    <p className="text-[11px] font-medium uppercase tracking-widest text-success">
                        Signed &amp; verified
                    </p>
                    <p className="truncate text-lg font-semibold leading-tight text-foreground">
                        {signature.signed_name}
                    </p>
                </div>
            </div>
            <div className="border-b px-6 py-5">
                <p className="mb-1 text-[11px] uppercase tracking-widest text-muted-foreground">
                    Signature
                </p>
                <p className="font-signature text-4xl leading-none text-foreground">
                    {signature.signed_name}
                </p>
            </div>
            <dl className="divide-y text-sm">
                <div className="flex justify-between gap-4 px-6 py-3">
                    <dt className="text-muted-foreground">Date signed</dt>
                    <dd>{formatDateTimeUtc(signature.signed_at)}</dd>
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
