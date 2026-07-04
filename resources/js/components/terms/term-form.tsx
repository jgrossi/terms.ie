import { Link, useForm } from '@inertiajs/react';
import { route } from 'ziggy-js';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { VariableChip } from '@/components/variable-chip';
import { cn } from '@/lib/utils';

interface Props {
    term?: { id: string; name: string; body: string };
    reserved: string[];
}

const BODY_PLACEHOLDER = `Write your terms here...

Hello {{CLIENT_NAME}},

These are the terms for your appointment on {{DATE}}.`;

export function TermForm({ term, reserved }: Props) {
    const editing = Boolean(term);
    const form = useForm({
        name: term?.name ?? '',
        body: term?.body ?? '',
    });

    const cancelHref = editing
        ? route('app.terms.show', term!.id)
        : route('app.terms.index');

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        if (editing) {
            form.put(route('app.terms.update', term!.id));
        } else {
            form.post(route('app.terms.store'));
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
                    placeholder="e.g. Photography Terms"
                    aria-invalid={Boolean(form.errors.name)}
                    autoFocus
                />
                {form.errors.name && (
                    <p className="text-sm text-destructive">{form.errors.name}</p>
                )}
            </div>

            <div className="grid gap-2">
                <Label htmlFor="body">
                    Body <span className="text-destructive">*</span>
                    <span className="ml-2 font-normal text-muted-foreground">
                        Use{' '}
                        <code className="rounded bg-muted px-1 font-mono text-xs">
                            {'{{VARIABLE_NAME}}'}
                        </code>{' '}
                        for placeholders
                    </span>
                </Label>
                <Textarea
                    id="body"
                    rows={28}
                    value={form.data.body}
                    onChange={(e) => form.setData('body', e.target.value)}
                    placeholder={BODY_PLACEHOLDER}
                    aria-invalid={Boolean(form.errors.body)}
                    className={cn('min-h-[28rem] resize-y font-mono text-sm leading-relaxed')}
                />
                {form.errors.body && (
                    <p className="text-sm text-destructive">{form.errors.body}</p>
                )}
            </div>

            <div className="space-y-2 rounded-xl bg-muted p-4 text-sm text-muted-foreground">
                <p className="font-medium text-foreground/80">
                    Reserved variables — filled automatically from the client:
                </p>
                <div className="flex flex-wrap gap-2">
                    {reserved.map((name) => (
                        <VariableChip key={name} name={name} reserved />
                    ))}
                </div>
            </div>

            <div className="mt-1 flex items-center gap-3 border-t pt-5">
                <Button type="submit" disabled={form.processing}>
                    {editing ? 'Save changes' : 'Create term'}
                </Button>
                <Button variant="ghost" asChild>
                    <Link href={cancelHref}>Cancel</Link>
                </Button>
            </div>
        </form>
    );
}
