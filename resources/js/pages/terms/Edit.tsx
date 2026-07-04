import { Head } from '@inertiajs/react';
import { route } from 'ziggy-js';
import type { ReactNode } from 'react';
import { AppLayout } from '@/layouts/app-layout';
import { PageHeader } from '@/components/page-header';
import { SectionCard } from '@/components/section-card';
import { TermForm } from '@/components/terms/term-form';

interface Props {
    term: { id: string; name: string; body: string };
    reserved: string[];
}

export default function Edit({ term, reserved }: Props) {
    return (
        <>
            <Head title="Edit term" />

            <PageHeader
                title="Edit term"
                crumbs={[
                    { label: 'Terms', href: route('app.terms.index') },
                    { label: term.name, href: route('app.terms.show', term.id) },
                    { label: 'Edit' },
                ]}
            />

            <div className="max-w-2xl">
                <p className="mb-5 rounded-xl bg-muted px-4 py-3 text-sm text-muted-foreground">
                    Changing the body creates a new version. Existing client terms stay on
                    their current version.
                </p>

                <SectionCard>
                    <TermForm term={term} reserved={reserved} />
                </SectionCard>
            </div>
        </>
    );
}

Edit.layout = (page: ReactNode) => <AppLayout>{page}</AppLayout>;
