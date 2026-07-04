import { Head, useForm } from '@inertiajs/react';
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
                    className="mb-6"
                    signature={{
                        id: signature.id,
                        signed_name: signature.signed_name,
                        signed_at: signature.signed_at,
                        signed_ip: signature.signed_ip,
                        content_hash: signature.content_hash,
                    }}
                />
                <DocumentSurface className="mb-6" body={signature.body} />
                <p className="text-center text-xs text-muted-foreground/60">
                    {LEGAL}
                    <br />
                    Reference: <span className="font-mono">{signature.id}</span>
                </p>
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
                    <div className="grid gap-2">
                        <Label htmlFor="signed_name">
                            Full name <span className="text-destructive">*</span>
                        </Label>
                        <Input
                            id="signed_name"
                            value={form.data.signed_name}
                            onChange={(e) => form.setData('signed_name', e.target.value)}
                            placeholder="Type your full name to sign"
                            aria-invalid={Boolean(form.errors.signed_name)}
                        />
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
