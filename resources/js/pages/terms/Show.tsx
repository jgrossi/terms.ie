import { Head, Link, router } from '@inertiajs/react';
import { Pencil, Trash2 } from 'lucide-react';
import { route } from 'ziggy-js';
import type { ReactNode } from 'react';
import { AppLayout } from '@/layouts/app-layout';
import { PageHeader } from '@/components/page-header';
import { SectionCard } from '@/components/section-card';
import { TermBody } from '@/components/term-body';
import { VariableChip } from '@/components/variable-chip';
import { ConfirmDialog } from '@/components/confirm-dialog';
import { Button } from '@/components/ui/button';
import { formatDate, relativeTime } from '@/lib/format';
import type { TermDetail, TermVersionItem } from '@/types';

interface Props {
    term: TermDetail;
    versions: TermVersionItem[];
}

export default function Show({ term, versions }: Props) {
    return (
        <>
            <Head title={term.name} />

            <PageHeader
                title={term.name}
                subtitle={
                    <>
                        Last updated {relativeTime(term.updated_at)}
                        <span className="ml-1 font-mono text-xs text-muted-foreground/50">
                            v{term.version}
                        </span>
                    </>
                }
                crumbs={[
                    { label: 'Terms', href: route('app.terms.index') },
                    { label: term.name },
                ]}
                actions={
                    <>
                        <Button asChild variant="outline" size="sm">
                            <Link href={route('app.terms.edit', term.id)}>
                                <Pencil className="size-3.5" /> Edit
                            </Link>
                        </Button>
                        {!term.has_signatures && (
                            <ConfirmDialog
                                title="Delete this term?"
                                description="This cannot be undone."
                                confirmLabel="Delete"
                                onConfirm={() =>
                                    router.delete(route('app.terms.destroy', term.id))
                                }
                                trigger={
                                    <Button
                                        variant="ghost"
                                        size="sm"
                                        className="text-destructive hover:bg-destructive/10 hover:text-destructive"
                                    >
                                        <Trash2 className="size-3.5" /> Delete
                                    </Button>
                                }
                            />
                        )}
                    </>
                }
            />

            <div className="grid gap-6 lg:grid-cols-3">
                <div className="space-y-6 lg:col-span-2">
                    <SectionCard label="Content">
                        <TermBody body={term.body} />
                    </SectionCard>
                </div>

                <div className="space-y-4">
                    <SectionCard label="Variables">
                        {term.user_variables.length === 0 &&
                        term.reserved_variables.length === 0 ? (
                            <p className="text-sm text-muted-foreground/50">
                                No variables found.
                            </p>
                        ) : (
                            <div className="space-y-3">
                                {term.user_variables.length > 0 && (
                                    <div>
                                        <p className="mb-1.5 text-xs text-muted-foreground">
                                            Filled when assigning to a client
                                        </p>
                                        <div className="flex flex-wrap gap-1.5">
                                            {term.user_variables.map((v) => (
                                                <VariableChip key={v} name={v} />
                                            ))}
                                        </div>
                                    </div>
                                )}
                                {term.reserved_variables.length > 0 && (
                                    <div>
                                        <p className="mb-1.5 text-xs text-muted-foreground">
                                            Auto-filled from client
                                        </p>
                                        <div className="flex flex-wrap gap-1.5">
                                            {term.reserved_variables.map((v) => (
                                                <VariableChip key={v} name={v} reserved />
                                            ))}
                                        </div>
                                    </div>
                                )}
                            </div>
                        )}
                    </SectionCard>

                    <SectionCard label="Version history">
                        <div className="space-y-2">
                            {versions.map((version) => (
                                <div
                                    key={version.version}
                                    className="flex items-center justify-between text-sm"
                                >
                                    <span className="font-mono text-foreground/70">
                                        v{version.version}
                                    </span>
                                    <span className="text-xs text-muted-foreground/60">
                                        {formatDate(version.created_at)}
                                    </span>
                                </div>
                            ))}
                        </div>
                    </SectionCard>

                    <SectionCard label="Send to client">
                        <p className="mb-3 text-xs text-muted-foreground">
                            Assign this term to a client to generate a signing link.
                        </p>
                        <Button asChild size="sm" className="w-full">
                            <Link href={route('app.signatures.create', term.id)}>
                                Assign to client
                            </Link>
                        </Button>
                    </SectionCard>
                </div>
            </div>
        </>
    );
}

Show.layout = (page: ReactNode) => <AppLayout>{page}</AppLayout>;
