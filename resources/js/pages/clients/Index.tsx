import { Head, Link } from '@inertiajs/react';
import { Plus, Users } from 'lucide-react';
import { route } from 'ziggy-js';
import type { ReactNode } from 'react';
import { AppLayout } from '@/layouts/app-layout';
import { PageHeader } from '@/components/page-header';
import { EmptyState } from '@/components/empty-state';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { relativeTime } from '@/lib/format';
import type { ClientListItem } from '@/types';

export default function Index({ clients }: { clients: ClientListItem[] }) {
    return (
        <>
            <Head title="Clients" />

            <PageHeader
                title="Clients"
                actions={
                    <Button asChild size="sm">
                        <Link href={route('app.clients.create')}>
                            <Plus className="size-4" /> New client
                        </Link>
                    </Button>
                }
            />

            {clients.length === 0 ? (
                <EmptyState
                    icon={<Users className="size-5" />}
                    title="No clients yet"
                    description="Add your first client to start sending terms."
                    action={
                        <Button asChild size="sm">
                            <Link href={route('app.clients.create')}>Add a client</Link>
                        </Button>
                    }
                />
            ) : (
                <Card className="overflow-hidden py-0">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Name</TableHead>
                                <TableHead>Email</TableHead>
                                <TableHead>Added</TableHead>
                                <TableHead />
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            {clients.map((client) => (
                                <TableRow key={client.id}>
                                    <TableCell>
                                        <Link
                                            href={route('app.clients.show', client.id)}
                                            className="font-medium transition-colors hover:text-primary"
                                        >
                                            {client.name}
                                        </Link>
                                    </TableCell>
                                    <TableCell className="text-muted-foreground">
                                        {client.email}
                                    </TableCell>
                                    <TableCell className="text-muted-foreground">
                                        {relativeTime(client.created_at)}
                                    </TableCell>
                                    <TableCell className="text-right">
                                        <Button asChild variant="ghost" size="sm">
                                            <Link href={route('app.clients.edit', client.id)}>
                                                Edit
                                            </Link>
                                        </Button>
                                    </TableCell>
                                </TableRow>
                            ))}
                        </TableBody>
                    </Table>
                </Card>
            )}
        </>
    );
}

Index.layout = (page: ReactNode) => <AppLayout>{page}</AppLayout>;
