import { useForm } from '@inertiajs/react';
import { useState } from 'react';
import { Mail } from 'lucide-react';
import { route } from 'ziggy-js';
import { cn } from '@/lib/utils';

interface Props {
    variant?: 'default' | 'inverted';
}

/** Email → magic-link request with an inline "check your inbox" success state. */
export function MagicLinkForm({ variant = 'default' }: Props) {
    const [sent, setSent] = useState(false);
    const [sentTo, setSentTo] = useState('');
    const form = useForm({ email: '' });
    const inverted = variant === 'inverted';

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        form.post(route('magic-link.send'), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                setSentTo(form.data.email);
                setSent(true);
                form.reset('email');
            },
        });
    };

    if (sent) {
        return (
            <div className="flex max-w-md flex-col gap-2">
                <div className="flex items-center gap-3">
                    <div className="flex size-10 shrink-0 items-center justify-center rounded-full bg-success/15">
                        <Mail className="size-5 text-success" />
                    </div>
                    <div>
                        <p className={cn('font-semibold', inverted && 'text-primary-foreground')}>
                            Check your inbox
                        </p>
                        <p
                            className={cn(
                                'text-sm',
                                inverted ? 'text-primary-foreground/70' : 'text-muted-foreground',
                            )}
                        >
                            We sent a sign-in link to <strong>{sentTo}</strong>
                        </p>
                    </div>
                </div>
                <p
                    className={cn(
                        'pl-13 text-xs',
                        inverted ? 'text-primary-foreground/50' : 'text-muted-foreground/70',
                    )}
                >
                    The link expires in 15 minutes. Check your spam folder if you don't see it.
                </p>
            </div>
        );
    }

    return (
        <form onSubmit={submit} className="max-w-md">
            <div className="flex flex-col gap-3 sm:flex-row">
                <input
                    type="email"
                    value={form.data.email}
                    onChange={(e) => form.setData('email', e.target.value)}
                    placeholder="your@email.com"
                    required
                    className={cn(
                        'h-10 flex-1 rounded-md border px-3 text-base outline-none',
                        inverted
                            ? 'border-primary-foreground/30 bg-primary-foreground/10 text-primary-foreground placeholder:text-primary-foreground/40 focus:border-primary-foreground'
                            : 'border-input bg-background focus:border-ring focus:ring-2 focus:ring-ring/40',
                    )}
                />
                <button
                    type="submit"
                    disabled={form.processing}
                    className={cn(
                        'h-10 shrink-0 rounded-md px-6 text-sm font-medium transition-colors disabled:opacity-60',
                        inverted
                            ? 'bg-primary-foreground text-primary hover:bg-primary-foreground/90'
                            : 'bg-primary text-primary-foreground hover:bg-primary/90',
                    )}
                >
                    Start for free →
                </button>
            </div>
            {form.errors.email && (
                <p
                    className={cn(
                        'mt-2 text-sm',
                        inverted ? 'text-primary-foreground' : 'text-destructive',
                    )}
                >
                    {form.errors.email}
                </p>
            )}
            {!inverted && (
                <p className="mt-3 text-sm text-muted-foreground/70">
                    No credit card required · Free plan available
                </p>
            )}
        </form>
    );
}
