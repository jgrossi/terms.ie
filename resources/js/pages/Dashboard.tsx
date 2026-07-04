import { Head, Link } from '@inertiajs/react';
import {
    CheckCircle2,
    Clock,
    FileText,
    Plus,
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
import { Card } from '@/components/ui/card';
import { relativeTime } from '@/lib/format';
import { usePage } from '@inertiajs/react';
import type {
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
}

export default function Dashboard({
    termCount,
    clientCount,
    pendingCount,
    signedCount,
    pendingSignatures,
    recentSigned,
}: Props) {
    const { auth } = usePage<SharedProps>().props;

    return (
        <>
            <Head title="Dashboard" />

            <PageHeader
                title="Overview"
                subtitle={auth.user?.email}
                actions={
                    <Button asChild size="sm">
                        <Link href={route('app.terms.create')}>
                            <Plus className="size-3.5" /> New term
                        </Link>
                    </Button>
                }
            />

            <div className="mb-8 grid grid-cols-2 gap-4 lg:grid-cols-4">
                <Stat
                    label="Terms"
                    value={termCount}
                    caption="total created"
                    icon={<FileText className="size-3.5" />}
                    href={route('app.terms.index')}
                />
                <Stat
                    label="Clients"
                    value={clientCount}
                    caption="total added"
                    icon={<Users className="size-3.5" />}
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
                    icon={<Clock className="size-3.5" />}
                    invert={pendingCount > 0}
                />
                <Stat
                    label="Signed"
                    value={signedCount}
                    caption="total collected"
                    icon={<CheckCircle2 className="size-3.5" />}
                />
            </div>

            <Card className="mb-6 overflow-hidden py-0">
                <div className="flex items-center justify-between border-b px-6 py-4">
                    <h2 className="text-sm font-semibold">Awaiting signature</h2>
                    {pendingCount > 0 && (
                        <Badge className="bg-foreground text-background">
                            {pendingCount} pending
                        </Badge>
                    )}
                </div>

                {pendingSignatures.length === 0 ? (
                    <div className="flex flex-col items-center justify-center gap-2 py-12">
                        <CheckCircle2 className="size-8 text-muted-foreground/20" />
                        <p className="text-sm text-muted-foreground/50">
                            Nothing pending — all signatures collected.
                        </p>
                    </div>
                ) : (
                    <div className="divide-y">
                        {pendingSignatures.map((sig) => (
                            <div
                                key={sig.id}
                                className="flex items-center justify-between gap-4 px-6 py-3"
                            >
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
                                <div className="hidden flex-1 text-sm text-muted-foreground sm:block">
                                    {sig.term_name}
                                </div>
                                <div className="hidden text-xs text-muted-foreground/60 sm:block">
                                    {relativeTime(sig.created_at)}
                                </div>
                                <div className="flex items-center gap-1">
                                    <CopyButton text={route('sign.show', sig.id)} />
                                    <Button asChild variant="ghost" size="sm">
                                        <Link href={route('app.signatures.show', sig.id)}>
                                            View
                                        </Link>
                                    </Button>
                                </div>
                            </div>
                        ))}
                    </div>
                )}
            </Card>

            {recentSigned.length > 0 && (
                <Card className="overflow-hidden py-0">
                    <div className="border-b px-6 py-4">
                        <h2 className="text-sm font-semibold">Recently signed</h2>
                    </div>
                    <div className="divide-y">
                        {recentSigned.map((sig) => (
                            <div
                                key={sig.id}
                                className="flex items-center justify-between gap-4 px-6 py-3"
                            >
                                <div className="flex min-w-0 items-center gap-3">
                                    <AvatarInitials name={sig.client_name} variant="success" />
                                    <div className="min-w-0">
                                        <p className="text-sm font-medium leading-tight">
                                            {sig.client_name}
                                        </p>
                                        <p className="text-xs leading-tight text-muted-foreground">
                                            {sig.term_name}
                                        </p>
                                    </div>
                                </div>
                                <div className="hidden text-xs text-muted-foreground sm:block">
                                    Signed by{' '}
                                    <span className="text-foreground/70">{sig.signed_name}</span>
                                </div>
                                <div className="hidden text-xs text-muted-foreground/60 sm:block">
                                    {relativeTime(sig.signed_at)}
                                </div>
                                <Button asChild variant="ghost" size="sm">
                                    <Link href={route('app.signatures.show', sig.id)}>View</Link>
                                </Button>
                            </div>
                        ))}
                    </div>
                </Card>
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
