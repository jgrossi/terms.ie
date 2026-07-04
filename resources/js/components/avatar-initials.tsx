import { cn } from '@/lib/utils';

interface Props {
    name: string;
    variant?: 'muted' | 'success';
}

function initialsFor(name: string): string {
    const parts = name.trim().split(/\s+/);
    const first = parts[0]?.[0] ?? '';
    const second = parts[1]?.[0] ?? '';
    return (first + second).toUpperCase();
}

export function AvatarInitials({ name, variant = 'muted' }: Props) {
    return (
        <div
            className={cn(
                'flex size-8 shrink-0 select-none items-center justify-center rounded-full text-xs font-semibold',
                variant === 'success'
                    ? 'bg-success/10 text-success/70'
                    : 'bg-muted text-muted-foreground',
            )}
        >
            {initialsFor(name)}
        </div>
    );
}
