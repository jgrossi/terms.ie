import { Link, useForm } from '@inertiajs/react';
import { route } from 'ziggy-js';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

interface Props {
    client?: { id: string; name: string; email: string };
}

export function ClientForm({ client }: Props) {
    const editing = Boolean(client);
    const form = useForm({
        name: client?.name ?? '',
        email: client?.email ?? '',
    });

    const cancelHref = editing
        ? route('app.clients.show', client!.id)
        : route('app.clients.index');

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        if (editing) {
            form.put(route('app.clients.update', client!.id));
        } else {
            form.post(route('app.clients.store'));
        }
    };

    return (
        <form onSubmit={submit} className="flex flex-col gap-5">
            <div className="grid gap-2">
                <Label htmlFor="name">
                    Name <span className="text-destructive">*</span>
                </Label>
                <Input
                    id="name"
                    value={form.data.name}
                    onChange={(e) => form.setData('name', e.target.value)}
                    placeholder="e.g. Jane Smith"
                    aria-invalid={Boolean(form.errors.name)}
                    autoFocus
                />
                {form.errors.name && (
                    <p className="text-sm text-destructive">{form.errors.name}</p>
                )}
            </div>

            <div className="grid gap-2">
                <Label htmlFor="email">
                    Email <span className="text-destructive">*</span>
                </Label>
                <Input
                    id="email"
                    type="email"
                    value={form.data.email}
                    onChange={(e) => form.setData('email', e.target.value)}
                    placeholder="jane@example.ie"
                    aria-invalid={Boolean(form.errors.email)}
                />
                {form.errors.email && (
                    <p className="text-sm text-destructive">{form.errors.email}</p>
                )}
            </div>

            <div className="mt-1 flex items-center gap-3 border-t pt-5">
                <Button type="submit" disabled={form.processing}>
                    {editing ? 'Save changes' : 'Add client'}
                </Button>
                <Button variant="ghost" asChild>
                    <Link href={cancelHref}>Cancel</Link>
                </Button>
            </div>
        </form>
    );
}
