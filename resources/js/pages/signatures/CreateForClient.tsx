import { Head, Link, useForm } from '@inertiajs/react';
import { FileText } from 'lucide-react';
import { route } from 'ziggy-js';
import type { ReactNode } from 'react';
import { AppLayout } from '@/layouts/app-layout';
import { PageHeader } from '@/components/page-header';
import { SectionCard } from '@/components/section-card';
import { EmptyState } from '@/components/empty-state';
import { VariableInputs } from '@/components/signatures/variable-inputs';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import type { TermOption } from '@/types';

interface Props {
    client: { id: string; name: string };
    terms: TermOption[];
}

export default function CreateForClient({ client, terms }: Props) {
    const form = useForm<{ term_id: string; variables: Record<string, string> }>({
        term_id: '',
        variables: {},
    });

    const selectedVars =
        terms.find((t) => t.id === form.data.term_id)?.variables ?? [];

    const onTermChange = (id: string) => {
        const term = terms.find((t) => t.id === id);
        form.setData('term_id', id);
        form.setData(
            'variables',
            Object.fromEntries((term?.variables ?? []).map((v) => [v, ''])),
        );
    };

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        form.post(route('app.signatures.store-for-client', client.id));
    };

    return (
        <>
            <Head title="Assign term" />

            <PageHeader
                title={`Assign term to ${client.name}`}
                crumbs={[
                    { label: 'Clients', href: route('app.clients.index') },
                    { label: client.name, href: route('app.clients.show', client.id) },
                    { label: 'Assign term' },
                ]}
            />

            <div className="max-w-lg">
                {terms.length === 0 ? (
                    <EmptyState
                        icon={<FileText className="size-5" />}
                        title="No terms yet"
                        description="You haven't created any terms yet."
                        action={
                            <Button asChild size="sm">
                                <Link href={route('app.terms.create')}>Create a term</Link>
                            </Button>
                        }
                    />
                ) : (
                    <SectionCard>
                        <form onSubmit={submit} className="flex flex-col gap-5">
                            <div className="grid gap-2">
                                <Label>
                                    Term <span className="text-destructive">*</span>
                                </Label>
                                <Select value={form.data.term_id} onValueChange={onTermChange}>
                                    <SelectTrigger aria-invalid={Boolean(form.errors.term_id)}>
                                        <SelectValue placeholder="Select a term…" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {terms.map((term) => (
                                            <SelectItem key={term.id} value={term.id}>
                                                {term.name}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                {form.errors.term_id && (
                                    <p className="text-sm text-destructive">
                                        {form.errors.term_id}
                                    </p>
                                )}
                            </div>

                            <VariableInputs
                                vars={selectedVars}
                                values={form.data.variables}
                                errors={form.errors as Record<string, string>}
                                onChange={(name, value) =>
                                    form.setData('variables', {
                                        ...form.data.variables,
                                        [name]: value,
                                    })
                                }
                            />

                            <div className="mt-1 flex items-center gap-3 border-t pt-5">
                                <Button type="submit" disabled={form.processing}>
                                    Generate signing link
                                </Button>
                                <Button variant="ghost" asChild>
                                    <Link href={route('app.clients.show', client.id)}>
                                        Cancel
                                    </Link>
                                </Button>
                            </div>
                        </form>
                    </SectionCard>
                )}
            </div>
        </>
    );
}

CreateForClient.layout = (page: ReactNode) => <AppLayout>{page}</AppLayout>;
