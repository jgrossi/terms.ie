import { Head } from '@inertiajs/react';
import { route } from 'ziggy-js';
import type { ReactNode } from 'react';
import { AppLayout } from '@/layouts/app-layout';
import { PageHeader } from '@/components/page-header';
import { SectionCard } from '@/components/section-card';
import { ClientForm } from '@/components/clients/client-form';

export default function Create() {
    return (
        <>
            <Head title="New client" />

            <PageHeader
                title="New client"
                crumbs={[
                    { label: 'Clients', href: route('app.clients.index') },
                    { label: 'New client' },
                ]}
            />

            <div className="max-w-lg">
                <SectionCard>
                    <ClientForm />
                </SectionCard>
            </div>
        </>
    );
}

Create.layout = (page: ReactNode) => <AppLayout>{page}</AppLayout>;
