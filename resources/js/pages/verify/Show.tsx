import { Head, Link, useForm } from '@inertiajs/react';
import { FileUp, RotateCcw, ShieldAlert, ShieldCheck } from 'lucide-react';
import { useState } from 'react';
import type { ReactNode } from 'react';
import { route } from 'ziggy-js';
import { ClientLayout } from '@/layouts/client-layout';
import { Certificate } from '@/components/certificate';
import { SectionCard } from '@/components/section-card';
import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';
import type { VerifyPage, VerifiedResult } from '@/types';

const LEGAL =
    'Every signed PDF carries a unique SHA-256 fingerprint. Upload the file to confirm it matches the original record held by terms.ie.';

export default function Show({ reference, termName, verification }: VerifyPage) {
    if (verification?.status === 'verified') {
        return <Verified reference={reference} result={verification} />;
    }

    return (
        <>
            <Head title="Verify a signed document" />

            <div className="mb-8 text-center">
                <div className="mx-auto mb-4 flex size-12 items-center justify-center rounded-full bg-primary/10 text-primary">
                    <ShieldCheck className="size-6" />
                </div>
                <h1 className="mb-1.5 text-2xl font-semibold leading-tight tracking-tight">
                    Verify a signed document
                </h1>
                <p className="mx-auto max-w-md text-sm text-muted-foreground">
                    Upload a signed PDF and we'll check its fingerprint against our records.
                </p>
            </div>

            {verification?.status === 'failed' && (
                <div className="mb-6 overflow-hidden rounded-xl border border-destructive/30 bg-destructive/5">
                    <div className="flex items-center gap-3 px-6 py-4">
                        <div className="flex size-9 shrink-0 items-center justify-center rounded-full bg-destructive/10 text-destructive">
                            <ShieldAlert className="size-5" />
                        </div>
                        <div className="min-w-0">
                            <p className="font-semibold leading-tight text-foreground">
                                We couldn't verify this document
                            </p>
                            <p className="mt-0.5 text-sm text-muted-foreground">
                                The file doesn't match the signed record for this reference.
                                It may have been altered or belong to a different document.
                            </p>
                        </div>
                    </div>
                </div>
            )}

            <UploadForm reference={reference} />

            <div className="mt-6 rounded-lg border bg-muted/40 px-4 py-3 text-center">
                <p className="text-xs text-muted-foreground">
                    Verifying{' '}
                    <span className="font-medium text-foreground/80">{termName}</span>{' '}
                    · reference <span className="font-mono">{reference}</span>
                </p>
            </div>

            <p className="mx-auto mt-6 max-w-sm text-center text-xs leading-relaxed text-muted-foreground/60">
                {LEGAL}
            </p>
        </>
    );
}

function Verified({ reference, result }: { reference: string; result: VerifiedResult }) {
    return (
        <>
            <Head title="Document verified" />

            <div className="mb-3 overflow-hidden rounded-xl border bg-success/5">
                <div className="flex flex-col items-center px-6 py-8 text-center">
                    <div className="relative mb-4">
                        <div className="absolute inset-0 animate-ping rounded-full bg-success/20" />
                        <div className="relative flex size-14 items-center justify-center rounded-full bg-success/15 text-success">
                            <ShieldCheck className="size-7" />
                        </div>
                    </div>
                    <h1 className="text-xl font-semibold leading-tight tracking-tight">
                        This document is authentic
                    </h1>
                    <p className="mt-1.5 max-w-md text-sm text-muted-foreground">
                        The file is an unaltered copy of the signed record held by terms.ie.
                        The SHA-256 fingerprint matches exactly.
                    </p>
                </div>
            </div>

            <p className="mb-3 text-center text-sm text-muted-foreground">
                {result.term_name} &middot; signed by {result.client_name}
            </p>

            <Certificate
                className="mb-6"
                signature={{
                    id: result.id,
                    signed_name: result.signed_name,
                    signed_at: result.signed_at ?? '',
                    signed_ip: result.signed_ip,
                    content_hash: result.pdf_hash,
                }}
            />

            <div className="flex justify-center">
                <Button asChild variant="outline">
                    <Link href={route('verify.show', reference)}>
                        <RotateCcw className="size-4" /> Verify another document
                    </Link>
                </Button>
            </div>
        </>
    );
}

function UploadForm({ reference }: { reference: string }) {
    const form = useForm<{ document: File | null }>({ document: null });
    const [dragging, setDragging] = useState(false);

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        form.post(route('verify.verify', reference), {
            forceFormData: true,
            preserveScroll: true,
        });
    };

    const pick = (file: File | null) => form.setData('document', file);

    return (
        <SectionCard>
            <form onSubmit={submit}>
                <label
                    htmlFor="document"
                    onDragOver={(e) => {
                        e.preventDefault();
                        setDragging(true);
                    }}
                    onDragLeave={() => setDragging(false)}
                    onDrop={(e) => {
                        e.preventDefault();
                        setDragging(false);
                        pick(e.dataTransfer.files[0] ?? null);
                    }}
                    className={cn(
                        'flex cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed px-6 py-10 text-center transition-colors',
                        dragging
                            ? 'border-primary bg-primary/5'
                            : 'border-input hover:border-primary/50 hover:bg-accent/40',
                    )}
                >
                    <div className="mb-3 flex size-11 items-center justify-center rounded-full bg-muted text-muted-foreground">
                        <FileUp className="size-5" />
                    </div>
                    {form.data.document ? (
                        <>
                            <p className="font-medium text-foreground">
                                {form.data.document.name}
                            </p>
                            <p className="mt-0.5 text-xs text-muted-foreground">
                                {(form.data.document.size / 1024).toFixed(0)} KB · click to replace
                            </p>
                        </>
                    ) : (
                        <>
                            <p className="font-medium text-foreground">
                                Click to upload or drag &amp; drop
                            </p>
                            <p className="mt-0.5 text-xs text-muted-foreground">
                                The signed PDF file
                            </p>
                        </>
                    )}
                    <input
                        id="document"
                        type="file"
                        accept="application/pdf"
                        className="sr-only"
                        onChange={(e) => pick(e.target.files?.[0] ?? null)}
                    />
                </label>

                {form.errors.document && (
                    <p className="mt-3 text-sm text-destructive">{form.errors.document}</p>
                )}

                <div className="mt-5 border-t pt-5">
                    <Button
                        type="submit"
                        className="w-full"
                        disabled={!form.data.document || form.processing}
                    >
                        Verify document
                    </Button>
                </div>
            </form>
        </SectionCard>
    );
}

Show.layout = (page: ReactNode) => <ClientLayout>{page}</ClientLayout>;
