import type { ReactNode } from 'react';
import { LandingLayout } from '@/layouts/landing-layout';
import { LegalPage } from '@/components/legal-page';

export default function Privacy() {
    return (
        <LegalPage title="Privacy Policy" updated="4 July 2026">
            <p>
                This Privacy Policy explains how terms.ie ("we") handles personal data. We act as
                a data controller for account data, and as a data processor for the client data
                you enter to send and collect signatures. We comply with the GDPR and the Irish
                Data Protection Act 2018.
            </p>

            <h2>1. What we collect</h2>
            <p>
                <strong>Account data:</strong> your email address and sign-in activity.{' '}
                <strong>Content you create:</strong> your terms/templates and the client details
                you add (name, email) to send them. <strong>Signature records:</strong> the
                signer's name, the date and time, IP address, and a verification hash — kept as
                proof the document was accepted.
            </p>

            <h2>2. How we use it</h2>
            <p>
                To provide the Service: authenticating you, generating and delivering documents,
                collecting signatures, and producing the signed PDF and audit trail. We do not
                sell your data or use it for advertising.
            </p>

            <h2>3. Processors we use</h2>
            <p>
                We share data only with the providers needed to run the Service: email delivery
                (Resend, EU region), file storage (Cloudflare R2), and hosting infrastructure.
                Each processes data on our instructions under a data-processing agreement.
            </p>

            <h2>4. Retention</h2>
            <p>
                We keep signed documents and their audit records for as long as your account is
                active, so they remain available to you and the signer. You can delete templates
                and client records at any time; deleting your account removes associated data,
                subject to any legal retention obligations.
            </p>

            <h2>5. Your rights</h2>
            <p>
                Under the GDPR you can request access to, correction of, or deletion of your
                personal data, and object to or restrict certain processing. To exercise these
                rights, contact us. You may also complain to the Irish Data Protection Commission
                (dataprotection.ie).
            </p>

            <h2>6. Security</h2>
            <p>
                Data is transmitted over HTTPS and stored with access controls. Signed PDFs in
                storage are private and served only through short-lived signed links.
            </p>

            <h2>7. Contact</h2>
            <p>
                Privacy questions or requests:{' '}
                <a href="mailto:hello@terms.ie">hello@terms.ie</a>.
            </p>
        </LegalPage>
    );
}

Privacy.layout = (page: ReactNode) => <LandingLayout>{page}</LandingLayout>;
