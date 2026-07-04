import { Head, Link, router } from '@inertiajs/react';
import { FileText, Pencil, Trash2 } from 'lucide-react';
import { route } from 'ziggy-js';
import type { ReactNode } from 'react';
import { AppLayout } from '@/layouts/app-layout';
import { PageHeader } from '@/components/page-header';
import { EmptyState } from '@/components/empty-state';
import { StatusDot } from '@/components/status-dot';
import { ConfirmDialog } from '@/components/confirm-dialog';
import { Button } from '@/components/ui/button';
import { TablePanel } from '@/components/table-panel';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { relativeTime } from '@/lib/format';
import type { ClientSignatureRow } from '@/types';

interface Props {
    client: { id: string; name: string; email: string; has_signatures: boolean };
    signatures: ClientSignatureRow[];
}

export default function Show({ client, signatures }: Props) {
    return (
        <>
            <Head title={client.name} />

            <PageHeader
                title={client.name}
                subtitle={client.email}
                crumbs={[
                    { label: 'Clients', href: route('app.clients.index') },
                    { label: client.name },
                ]}
                actions={
                    <>
                        <Button asChild size="sm">
                            <Link href={route('app.signatures.create-for-client', client.id)}>
                                Assign term
                            </Link>
                        </Button>
                        <Button asChild variant="outline" size="sm">
                            <Link href={route('app.clients.edit', client.id)}>
                                <Pencil className="size-3.5" /> Edit
                            </Link>
                        </Button>
                        {!client.has_signatures && (
                            <ConfirmDialog
                                title={`Delete ${client.name}?`}
                                description="This cannot be undone."
                                confirmLabel="Delete"
                                onConfirm={() =>
                                    router.delete(route('app.clients.destroy', client.id))
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

            {signatures.length === 0 ? (
                <EmptyState
                    icon={<FileText className="size-5" />}
                    title="No terms assigned yet"
                    description="Assign a term to this client to generate a signing link."
                    action={
                        <Button asChild size="sm">
                            <Link href={route('app.signatures.create-for-client', client.id)}>
                                Assign term
                            </Link>
                        </Button>
                    }
                />
            ) : (
                <TablePanel>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Term</TableHead>
                                <TableHead>Version</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead>Date</TableHead>
                                <TableHead />
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            {signatures.map((sig) => (
                                <TableRow key={sig.id}>
                                    <TableCell className="font-medium">
                                        {sig.term_name}
                                    </TableCell>
                                    <TableCell className="text-muted-foreground">
                                        v{sig.version}
                                    </TableCell>
                                    <TableCell>
                                        <StatusDot
                                            status={sig.is_expired ? 'expired' : sig.status}
                                        />
                                    </TableCell>
                                    <TableCell className="text-muted-foreground">
                                        {relativeTime(sig.created_at)}
                                    </TableCell>
                                    <TableCell className="text-right">
                                        {sig.is_expired && (
                                            <Button
                                                variant="ghost"
                                                size="sm"
                                                onClick={() =>
                                                    router.post(
                                                        route('app.signatures.extend', sig.id),
                                                        {},
                                                        { preserveScroll: true },
                                                    )
                                                }
                                            >
                                                Extend
                                            </Button>
                                        )}
                                        <Button asChild variant="ghost" size="sm">
                                            <Link href={route('app.signatures.show', sig.id)}>
                                                View
                                            </Link>
                                        </Button>
                                    </TableCell>
                                </TableRow>
                            ))}
                        </TableBody>
                    </Table>
                </TablePanel>
            )}
        </>
    );
}

Show.layout = (page: ReactNode) => <AppLayout>{page}</AppLayout>;
