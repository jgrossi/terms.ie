import { Head } from '@inertiajs/react';
import { route } from 'ziggy-js';
import type { ReactNode } from 'react';
import { AppLayout } from '@/layouts/app-layout';
import { PageHeader } from '@/components/page-header';
import { SectionCard } from '@/components/section-card';
import { ClientForm } from '@/components/clients/client-form';

interface Props {
    client: { id: string; name: string; email: string };
}

export default function Edit({ client }: Props) {
    return (
        <>
            <Head title="Edit client" />

            <PageHeader
                title="Edit client"
                crumbs={[
                    { label: 'Clients', href: route('app.clients.index') },
                    { label: client.name, href: route('app.clients.show', client.id) },
                    { label: 'Edit' },
                ]}
            />

            <div className="max-w-lg">
                <SectionCard>
                    <ClientForm client={client} />
                </SectionCard>
            </div>
        </>
    );
}

Edit.layout = (page: ReactNode) => <AppLayout>{page}</AppLayout>;
