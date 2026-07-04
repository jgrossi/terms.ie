import { Head } from '@inertiajs/react';
import { route } from 'ziggy-js';
import type { ReactNode } from 'react';
import { AppLayout } from '@/layouts/app-layout';
import { PageHeader } from '@/components/page-header';
import { SectionCard } from '@/components/section-card';
import { TermForm } from '@/components/terms/term-form';

export default function Create({ reserved }: { reserved: string[] }) {
    return (
        <>
            <Head title="New term" />

            <PageHeader
                title="New term"
                crumbs={[
                    { label: 'Terms', href: route('app.terms.index') },
                    { label: 'New term' },
                ]}
            />

            <div className="max-w-2xl">
                <SectionCard>
                    <TermForm reserved={reserved} />
                </SectionCard>
            </div>
        </>
    );
}

Create.layout = (page: ReactNode) => <AppLayout>{page}</AppLayout>;
