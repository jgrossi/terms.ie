import { Head, Link } from '@inertiajs/react';
import {
    CheckCircle2,
    Clock,
    FileText,
    Plus,
    UserPlus,
    Users,
} from 'lucide-react';
import { route } from 'ziggy-js';
import type { ReactNode } from 'react';
import { AppLayout } from '@/layouts/app-layout';
import { PageHeader } from '@/components/page-header';
import { EmptyState } from '@/components/empty-state';
import { Stat } from '@/components/stat';
import { AvatarInitials } from '@/components/avatar-initials';
import { CopyButton } from '@/components/copy-button';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { TablePanel } from '@/components/table-panel';
import {
    Table,
    TableBody,
    TableCell,
    TableRow,
} from '@/components/ui/table';
import { relativeTime } from '@/lib/format';
import { usePage } from '@inertiajs/react';
import type {
    ClientListItem,
    PendingSignature,
    SharedProps,
    SignedSignature,
} from '@/types';

interface Props {
    termCount: number;
    clientCount: number;
    pendingCount: number;
    signedCount: number;
    pendingSignatures: PendingSignature[];
    recentSigned: SignedSignature[];
    recentClients: ClientListItem[];
}

export default function Dashboard({
    termCount,
    clientCount,
    pendingCount,
    signedCount,
    pendingSignatures,
    recentSigned,
    recentClients,
}: Props) {
    const { auth } = usePage<SharedProps>().props;

    return (
        <>
            <Head title="Dashboard" />

            <PageHeader
                title="Overview"
                subtitle={auth.user?.email}
                actions={
                    <>
                        <Button asChild size="sm">
                            <Link href={route('app.terms.create')}>
                                <Plus className="size-3.5" /> New term
                            </Link>
                        </Button>
                        <Button asChild variant="outline" size="sm">
                            <Link href={route('app.clients.create')}>
                                <UserPlus className="size-3.5" /> New client
                            </Link>
                        </Button>
                    </>
                }
            />

            <div className="mb-8 grid grid-cols-2 gap-3 lg:grid-cols-4">
                <Stat
                    label="Terms"
                    value={termCount}
                    caption="total created"
                    icon={<FileText className="size-4" />}
                    href={route('app.terms.index')}
                />
                <Stat
                    label="Clients"
                    value={clientCount}
                    caption="total added"
                    icon={<Users className="size-4" />}
                    href={route('app.clients.index')}
                />
                <Stat
                    label="Awaiting"
                    value={pendingCount}
                    caption={
                        pendingCount > 0
                            ? `${pendingCount} ${pendingCount === 1 ? 'signature' : 'signatures'} pending`
                            : 'all signed'
                    }
                    icon={<Clock className="size-4" />}
                    attention={pendingCount > 0}
                />
                <Stat
                    label="Signed"
                    value={signedCount}
                    caption="total collected"
                    icon={<CheckCircle2 className="size-4" />}
                />
            </div>

            <TablePanel
                className="mb-6"
                title="Awaiting signature"
                action={
                    pendingCount > 0 ? (
                        <Badge className="border-transparent bg-warning/15 text-warning">
                            {pendingCount} pending
                        </Badge>
                    ) : undefined
                }
            >
                {pendingSignatures.length === 0 ? (
                    <div className="flex flex-col items-center justify-center gap-2 py-12">
                        <CheckCircle2 className="size-8 text-muted-foreground/20" />
                        <p className="text-sm text-muted-foreground/50">
                            Nothing pending — all signatures collected.
                        </p>
                    </div>
                ) : (
                    <Table>
                        <TableBody>
                            {pendingSignatures.map((sig) => (
                                <TableRow key={sig.id}>
                                    <TableCell>
                                        <div className="flex min-w-0 items-center gap-3">
                                            <AvatarInitials name={sig.client_name} />
                                            <div className="min-w-0">
                                                <p className="text-sm font-medium leading-tight">
                                                    {sig.client_name}
                                                </p>
                                                <p className="truncate text-xs leading-tight text-muted-foreground">
                                                    {sig.client_email}
                                                </p>
                                            </div>
                                        </div>
                                    </TableCell>
                                    <TableCell className="hidden text-sm text-muted-foreground sm:table-cell">
                                        {sig.term_name}
                                    </TableCell>
                                    <TableCell className="hidden text-xs text-muted-foreground/60 sm:table-cell">
                                        {relativeTime(sig.created_at)}
                                    </TableCell>
                                    <TableCell className="text-right">
                                        <div className="flex items-center justify-end gap-1">
                                            <CopyButton text={route('sign.show', sig.id)} />
                                            <Button asChild variant="ghost" size="sm">
                                                <Link href={route('app.signatures.show', sig.id)}>
                                                    View
                                                </Link>
                                            </Button>
                                        </div>
                                    </TableCell>
                                </TableRow>
                            ))}
                        </TableBody>
                    </Table>
                )}
            </TablePanel>
            {recentClients.length > 0 && (
                <TablePanel
                    className="mb-6"
                    title="Recent clients"
                    action={
                        <Button asChild variant="ghost" size="sm">
                            <Link href={route('app.clients.index')}>
                                View all
                            </Link>
                        </Button>
                    }
                >
                    <Table>
                        <TableBody>
                            {recentClients.map((client) => (
                                <TableRow key={client.id}>
                                    <TableCell>
                                        <div className="flex min-w-0 items-center gap-3">
                                            <AvatarInitials name={client.name} />
                                            <div className="min-w-0">
                                                <p className="text-sm font-medium leading-tight">
                                                    {client.name}
                                                </p>
                                                <p className="truncate text-xs leading-tight text-muted-foreground">
                                                    {client.email}
                                                </p>
                                            </div>
                                        </div>
                                    </TableCell>
                                    <TableCell className="hidden text-xs text-muted-foreground/60 sm:table-cell">
                                        Added {relativeTime(client.created_at)}
                                    </TableCell>
                                    <TableCell className="text-right">
                                        <Button asChild variant="ghost" size="sm">
                                            <Link href={route('app.signatures.create-for-client', client.id)}>
                                                <Plus className="size-3.5" /> New signature
                                            </Link>
                                        </Button>
                                    </TableCell>
                                </TableRow>
                            ))}
                        </TableBody>
                    </Table>
                </TablePanel>
            )}

            {recentSigned.length > 0 && (
                <TablePanel title="Recently signed">
                    <Table>
                        <TableBody>
                            {recentSigned.map((sig) => (
                                <TableRow key={sig.id}>
                                    <TableCell>
                                        <div className="flex min-w-0 items-center gap-3">
                                            <AvatarInitials
                                                name={sig.client_name}
                                                variant="success"
                                            />
                                            <div className="min-w-0">
                                                <p className="text-sm font-medium leading-tight">
                                                    {sig.client_name}
                                                </p>
                                                <p className="text-xs leading-tight text-muted-foreground">
                                                    {sig.term_name}
                                                </p>
                                            </div>
                                        </div>
                                    </TableCell>
                                    <TableCell className="hidden text-xs text-muted-foreground sm:table-cell">
                                        Signed by{' '}
                                        <span className="text-foreground/70">
                                            {sig.signed_name}
                                        </span>
                                    </TableCell>
                                    <TableCell className="hidden text-xs text-muted-foreground/60 sm:table-cell">
                                        {relativeTime(sig.signed_at)}
                                    </TableCell>
                                    <TableCell className="text-right">
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

            {termCount === 0 && clientCount === 0 && (
                <div className="mt-6">
                    <EmptyState
                        icon={<FileText className="size-5" />}
                        title="Welcome to terms.ie"
                        description="Start by creating a term, then assign it to a client to generate a signing link."
                        action={
                            <>
                                <Button asChild size="sm">
                                    <Link href={route('app.terms.create')}>Create a term</Link>
                                </Button>
                                <Button asChild variant="outline" size="sm">
                                    <Link href={route('app.clients.create')}>Add a client</Link>
                                </Button>
                            </>
                        }
                    />
                </div>
            )}
        </>
    );
}

Dashboard.layout = (page: ReactNode) => <AppLayout>{page}</AppLayout>;
