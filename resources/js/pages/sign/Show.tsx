import { Head, Link, useForm } from '@inertiajs/react';
import { Download, Clock, ShieldCheck } from 'lucide-react';
import type { ReactNode } from 'react';
import { route } from 'ziggy-js';
import { ClientLayout } from '@/layouts/client-layout';
import { Certificate } from '@/components/certificate';
import { DocumentSurface } from '@/components/document-surface';
import { SectionCard } from '@/components/section-card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { SignDocument } from '@/types';

const LEGAL =
    'Electronic acceptance is recognised under the Electronic Commerce Act 2000 in Ireland.';

export default function Show({ signature }: { signature: SignDocument }) {
    const form = useForm({ signed_name: '' });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        form.post(route('sign.sign', signature.id));
    };

    if (signature.is_signed && signature.signed_name && signature.signed_at) {
        return (
            <>
                <Head title={signature.term_name} />
                <Certificate
                    className="mb-4"
                    signature={{
                        id: signature.id,
                        signed_name: signature.signed_name,
                        signed_at: signature.signed_at,
                        signed_ip: signature.signed_ip,
                        content_hash: signature.content_hash,
                    }}
                />
                <div className="mb-6 flex flex-wrap justify-center gap-3">
                    <Button asChild variant="outline">
                        <a href={route('sign.pdf', signature.id)}>
                            <Download className="size-4" /> Download PDF
                        </a>
                    </Button>
                    <Button asChild variant="outline">
                        <Link href={route('verify.show', signature.id)}>
                            <ShieldCheck className="size-4" /> Verify document
                        </Link>
                    </Button>
                </div>
                <DocumentSurface className="mb-6" body={signature.body} />
                <p className="text-center text-xs text-muted-foreground/60">
                    {LEGAL}
                    <br />
                    Reference: <span className="font-mono">{signature.id}</span>
                </p>
            </>
        );
    }

    if (signature.is_expired) {
        return (
            <>
                <Head title={signature.term_name} />
                <SectionCard className="text-center">
                    <div className="mx-auto mb-4 flex size-12 items-center justify-center rounded-full bg-muted text-muted-foreground">
                        <Clock className="size-6" />
                    </div>
                    <h1 className="mb-1.5 text-lg font-semibold">
                        This signing link has expired
                    </h1>
                    <p className="mx-auto max-w-sm text-sm text-muted-foreground">
                        Please contact the sender to request a new link for “
                        {signature.term_name}”.
                    </p>
                </SectionCard>
            </>
        );
    }

    return (
        <>
            <Head title={signature.term_name} />

            <div className="mb-8">
                <h1 className="mb-1 text-2xl font-semibold leading-tight tracking-tight">
                    {signature.term_name}
                </h1>
                <p className="text-sm text-muted-foreground">
                    Please read the document below and sign to accept.
                </p>
            </div>

            <DocumentSurface className="mb-6" body={signature.body} />

            <SectionCard>
                <form onSubmit={submit}>
                    <div className="mb-4 rounded-lg border bg-muted/40 px-4 py-3">
                        <p className="text-xs uppercase tracking-wide text-muted-foreground">
                            Signing as
                        </p>
                        <p className="font-medium">{signature.client_name}</p>
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="signed_name">
                            Full name <span className="text-destructive">*</span>
                        </Label>
                        <Input
                            id="signed_name"
                            value={form.data.signed_name}
                            onChange={(e) => form.setData('signed_name', e.target.value)}
                            placeholder={signature.client_name}
                            aria-invalid={Boolean(form.errors.signed_name)}
                            autoFocus
                        />
                        <p className="text-xs text-muted-foreground">
                            Type your name exactly as shown above to sign.
                        </p>
                        {form.errors.signed_name && (
                            <p className="text-sm text-destructive">
                                {form.errors.signed_name}
                            </p>
                        )}
                    </div>

                    <div className="mt-5 border-t pt-5">
                        <Button type="submit" className="w-full" disabled={form.processing}>
                            I accept these terms
                        </Button>
                        <p className="mt-3 text-center text-xs text-muted-foreground/60">
                            {LEGAL}
                        </p>
                    </div>
                </form>
            </SectionCard>
        </>
    );
}

Show.layout = (page: ReactNode) => <ClientLayout>{page}</ClientLayout>;
