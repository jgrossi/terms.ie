const RELATIVE_UNITS: [Intl.RelativeTimeFormatUnit, number][] = [
    ['year', 31_536_000_000],
    ['month', 2_592_000_000],
    ['week', 604_800_000],
    ['day', 86_400_000],
    ['hour', 3_600_000],
    ['minute', 60_000],
    ['second', 1_000],
];

/** "2 days ago" style relative time — mirrors Carbon's diffForHumans(). */
export function relativeTime(iso: string): string {
    const diff = new Date(iso).getTime() - Date.now();
    const abs = Math.abs(diff);
    const rtf = new Intl.RelativeTimeFormat('en', { numeric: 'auto' });

    for (const [unit, ms] of RELATIVE_UNITS) {
        if (abs >= ms || unit === 'second') {
            return rtf.format(Math.round(diff / ms), unit);
        }
    }
    return '';
}

/** "03 Jul 2026" */
export function formatDate(iso: string): string {
    return new Date(iso).toLocaleDateString('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
}

/** "03 Jul 2026 at 14:32" */
export function formatDateTime(iso: string): string {
    const date = new Date(iso);
    const day = date.toLocaleDateString('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
    const time = date.toLocaleTimeString('en-GB', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: false,
    });
    return `${day} at ${time}`;
}
