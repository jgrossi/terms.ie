import { Head, Link } from '@inertiajs/react';
import { FileText, Plus } from 'lucide-react';
import { route } from 'ziggy-js';
import type { ReactNode } from 'react';
import { AppLayout } from '@/layouts/app-layout';
import { PageHeader } from '@/components/page-header';
import { EmptyState } from '@/components/empty-state';
import { VariableChip } from '@/components/variable-chip';
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
import type { TermListItem } from '@/types';

export default function Index({ terms }: { terms: TermListItem[] }) {
    return (
        <>
            <Head title="Terms" />

            <PageHeader
                title="Terms"
                actions={
                    <Button asChild size="sm">
                        <Link href={route('app.terms.create')}>
                            <Plus className="size-4" /> New term
                        </Link>
                    </Button>
                }
            />

            {terms.length === 0 ? (
                <EmptyState
                    icon={<FileText className="size-5" />}
                    title="No terms yet"
                    description="Create your first term template to get started."
                    action={
                        <Button asChild size="sm">
                            <Link href={route('app.terms.create')}>Create a term</Link>
                        </Button>
                    }
                />
            ) : (
                <TablePanel>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Name</TableHead>
                                <TableHead>Variables</TableHead>
                                <TableHead>Versions</TableHead>
                                <TableHead>Updated</TableHead>
                                <TableHead />
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            {terms.map((term) => (
                                <TableRow key={term.id}>
                                    <TableCell>
                                        <Link
                                            href={route('app.terms.show', term.id)}
                                            className="font-medium transition-colors hover:text-primary"
                                        >
                                            {term.name}
                                        </Link>
                                    </TableCell>
                                    <TableCell>
                                        {term.variables.length > 0 ? (
                                            <div className="flex flex-wrap gap-1">
                                                {term.variables.map((v) => (
                                                    <VariableChip key={v} name={v} />
                                                ))}
                                            </div>
                                        ) : (
                                            <span className="text-muted-foreground/40">—</span>
                                        )}
                                    </TableCell>
                                    <TableCell className="text-muted-foreground">
                                        v{term.versions_count}
                                    </TableCell>
                                    <TableCell className="text-muted-foreground">
                                        {relativeTime(term.updated_at)}
                                    </TableCell>
                                    <TableCell className="text-right">
                                        <Button asChild variant="ghost" size="sm">
                                            <Link href={route('app.terms.edit', term.id)}>
                                                Edit
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

Index.layout = (page: ReactNode) => <AppLayout>{page}</AppLayout>;
