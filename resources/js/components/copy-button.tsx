import { useState } from 'react';
import { Check, Clipboard } from 'lucide-react';
import { Button } from '@/components/ui/button';

interface Props {
    text: string;
    label?: string;
}

/** Port of the Alpine copyButton — copies text to the clipboard with a transient confirmation. */
export function CopyButton({ text, label = 'Copy link' }: Props) {
    const [copied, setCopied] = useState(false);

    const copy = async () => {
        try {
            await navigator.clipboard.writeText(text);
        } catch {
            const ta = document.createElement('textarea');
            ta.value = text;
            ta.style.position = 'fixed';
            ta.style.opacity = '0';
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
        }
        setCopied(true);
        setTimeout(() => setCopied(false), 2000);
    };

    return (
        <Button variant="outline" size="sm" onClick={copy}>
            {copied ? <Check className="size-3" /> : <Clipboard className="size-3" />}
            {copied ? 'Copied!' : label}
        </Button>
    );
}
