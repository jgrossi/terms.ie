import type { ReactNode } from 'react';
import { cn } from '@/lib/utils';

const RESERVED = ['CLIENT_NAME', 'CLIENT_EMAIL'];
const VARIABLE = /\{\{([A-Z0-9_]+)\}\}/g;

/** Renders a term body with {{VARIABLE}} placeholders highlighted (ports _body.blade). */
export function TermBody({ body }: { body: string }) {
    const text = body.trim();
    const nodes: ReactNode[] = [];
    let lastIndex = 0;
    let key = 0;
    let match: RegExpExecArray | null;

    VARIABLE.lastIndex = 0;
    while ((match = VARIABLE.exec(text)) !== null) {
        if (match.index > lastIndex) {
            nodes.push(<span key={key++}>{text.slice(lastIndex, match.index)}</span>);
        }
        const reserved = RESERVED.includes(match[1]);
        nodes.push(
            <mark
                key={key++}
                className={cn(
                    'rounded px-1 py-0.5 font-mono text-sm not-italic',
                    reserved ? 'bg-success/15 text-success' : 'bg-primary/15 text-primary',
                )}
            >
                {`{{${match[1]}}}`}
            </mark>,
        );
        lastIndex = VARIABLE.lastIndex;
    }
    if (lastIndex < text.length) {
        nodes.push(<span key={key++}>{text.slice(lastIndex)}</span>);
    }

    return (
        <div className="max-w-none whitespace-pre-wrap font-mono text-sm leading-relaxed text-foreground/80">
            {nodes}
        </div>
    );
}
