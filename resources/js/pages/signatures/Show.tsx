import { Head, Link, router } from '@inertiajs/react';
import { Trash2 } from 'lucide-react';
import { route } from 'ziggy-js';
import type { ReactNode } from 'react';
import { AppLayout } from '@/layouts/app-layout';
import { PageHeader } from '@/components/page-header';
import { SectionCard } from '@/components/section-card';
import { StatusDot } from '@/components/status-dot';
import { Certificate } from '@/components/certificate';
import { DocumentSurface } from '@/components/document-surface';
import { CopyButton } from '@/components/copy-button';
import { ConfirmDialog } from '@/components/confirm-dialog';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import type { SignatureDetail } from '@/types';

export default function Show({ signature }: { signature: SignatureDetail }) {
    const signUrl = route('sign.show', signature.id);
    const variableEntries = Object.entries(signature.variables ?? {});

    return (
        <>
            <Head title={signature.term_name} />

            <PageHeader
                title={signature.term_name}
                crumbs={[
                    { label: 'Clients', href: route('app.clients.index') },
                    {
                        label: signature.client.name,
                        href: route('app.clients.show', signature.client.id),
                    },
                    { label: signature.term_name },
                ]}
                actions={
                    <>
                        <StatusDot status={signature.status} />
                        {signature.is_pending && (
                            <ConfirmDialog
                                title="Revoke this signing request?"
                                description="The link will stop working."
                                confirmLabel="Revoke"
                                onConfirm={() =>
                                    router.delete(route('app.signatures.destroy', signature.id))
                                }
                                trigger={
                                    <Button
                                        variant="ghost"
                                        size="sm"
                                        className="text-destructive hover:bg-destructive/10 hover:text-destructive"
                                    >
                                        <Trash2 className="size-3.5" /> Revoke
                                    </Button>
                                }
                            />
                        )}
                    </>
                }
            />

            <div className="grid gap-6 lg:grid-cols-3">
                <div className="space-y-6 lg:col-span-2">
                    {signature.is_pending && (
                        <SectionCard label="Signing link">
                            <p className="mb-3 text-xs text-muted-foreground">
                                Send this link to {signature.client.name} to sign the document.
                            </p>
                            <div className="flex gap-2">
                                <Input
                                    readOnly
                                    value={signUrl}
                                    onClick={(e) => e.currentTarget.select()}
                                    className="font-mono text-xs"
                                />
                                <CopyButton text={signUrl} label="Copy" />
                            </div>
                        </SectionCard>
                    )}

                    {signature.is_signed &&
                        signature.signed_name &&
                        signature.signed_at && (
                            <Certificate
                                signature={{
                                    id: signature.id,
                                    signed_name: signature.signed_name,
                                    signed_at: signature.signed_at,
                                    signed_ip: signature.signed_ip,
                                    content_hash: signature.content_hash,
                                }}
                            />
                        )}

                    <DocumentSurface body={signature.body} />
                </div>

                <div className="space-y-4">
                    <SectionCard label="Client">
                        <p className="font-medium">{signature.client.name}</p>
                        <p className="text-sm text-muted-foreground">
                            {signature.client.email}
                        </p>
                    </SectionCard>

                    {variableEntries.length > 0 && (
                        <SectionCard label="Variables">
                            <dl className="space-y-2">
                                {variableEntries.map(([key, value]) => (
                                    <div key={key}>
                                        <dt className="font-mono text-xs text-muted-foreground">
                                            {`{{${key}}}`}
                                        </dt>
                                        <dd className="text-sm">{value}</dd>
                                    </div>
                                ))}
                            </dl>
                        </SectionCard>
                    )}
                </div>
            </div>
        </>
    );
}

Show.layout = (page: ReactNode) => <AppLayout>{page}</AppLayout>;
