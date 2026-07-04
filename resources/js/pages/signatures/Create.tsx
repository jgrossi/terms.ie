import { Head, Link, useForm } from '@inertiajs/react';
import { Users } from 'lucide-react';
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
import type { ClientOption } from '@/types';

interface Props {
    term: { id: string; name: string };
    clients: ClientOption[];
    userVars: string[];
}

export default function Create({ term, clients, userVars }: Props) {
    const form = useForm<{ client_id: string; variables: Record<string, string> }>({
        client_id: '',
        variables: Object.fromEntries(userVars.map((v) => [v, ''])),
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        form.post(route('app.signatures.store', term.id));
    };

    return (
        <>
            <Head title="Assign term" />

            <PageHeader
                title="Assign to client"
                crumbs={[
                    { label: 'Terms', href: route('app.terms.index') },
                    { label: term.name, href: route('app.terms.show', term.id) },
                    { label: 'Assign to client' },
                ]}
            />

            <div className="max-w-lg">
                {clients.length === 0 ? (
                    <EmptyState
                        icon={<Users className="size-5" />}
                        title="No clients yet"
                        description="You need to add a client before assigning a term."
                        action={
                            <Button asChild size="sm">
                                <Link href={route('app.clients.create')}>Add a client</Link>
                            </Button>
                        }
                    />
                ) : (
                    <SectionCard>
                        <form onSubmit={submit} className="flex flex-col gap-5">
                            <div className="grid gap-2">
                                <Label>
                                    Client <span className="text-destructive">*</span>
                                </Label>
                                <Select
                                    value={form.data.client_id}
                                    onValueChange={(v) => form.setData('client_id', v)}
                                >
                                    <SelectTrigger aria-invalid={Boolean(form.errors.client_id)}>
                                        <SelectValue placeholder="Select a client…" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {clients.map((client) => (
                                            <SelectItem key={client.id} value={client.id}>
                                                {client.name} — {client.email}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                {form.errors.client_id && (
                                    <p className="text-sm text-destructive">
                                        {form.errors.client_id}
                                    </p>
                                )}
                            </div>

                            <VariableInputs
                                vars={userVars}
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
                                    <Link href={route('app.terms.show', term.id)}>Cancel</Link>
                                </Button>
                            </div>
                        </form>
                    </SectionCard>
                )}
            </div>
        </>
    );
}

Create.layout = (page: ReactNode) => <AppLayout>{page}</AppLayout>;
