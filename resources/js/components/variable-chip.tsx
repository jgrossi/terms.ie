import { cn } from '@/lib/utils';

interface Props {
    name: string;
    /** Reserved (auto-filled) variables render in the success colour. */
    reserved?: boolean;
    className?: string;
}

export function VariableChip({ name, reserved = false, className }: Props) {
    return (
        <code
            className={cn(
                'rounded px-1.5 py-0.5 font-mono text-xs',
                reserved
                    ? 'bg-success/10 text-success'
                    : 'bg-primary/10 text-primary',
                className,
            )}
        >
            {`{{${name}}}`}
        </code>
    );
}
