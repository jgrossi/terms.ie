import { cn } from '@/lib/utils';

/** The "paper" contract surface — monospace, whitespace-preserved (ports x-document). */
export function DocumentSurface({
    body,
    className,
}: {
    body: string;
    className?: string;
}) {
    return (
        <div className={cn('rounded-xl border bg-paper px-8 py-7', className)}>
            <div className="max-w-none whitespace-pre-wrap font-mono text-[13px] leading-[1.7] text-foreground/80">
                {body}
            </div>
        </div>
    );
}
