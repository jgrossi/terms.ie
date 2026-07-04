import type { ReactNode } from 'react';
import { LandingLayout } from '@/layouts/landing-layout';
import { LegalPage } from '@/components/legal-page';

export default function Terms() {
    return (
        <LegalPage title="Terms of Service" updated="4 July 2026">
            <p>
                These Terms of Service ("Terms") govern your use of terms.ie ("the Service"),
                operated from Ireland. By creating an account or using the Service you agree to
                these Terms.
            </p>

            <h2>1. The Service</h2>
            <p>
                terms.ie lets businesses create text-based agreements, send them to their own
                clients, and collect electronic signatures. You are responsible for the content
                of the documents you create and for ensuring they are appropriate and lawful for
                your use.
            </p>

            <h2>2. Accounts</h2>
            <p>
                You sign in with a magic link sent to your email address. You are responsible for
                keeping access to that inbox secure and for all activity under your account.
            </p>

            <h2>3. Electronic signatures</h2>
            <p>
                Electronic acceptance collected through the Service is recognised under the
                Electronic Commerce Act 2000 in Ireland for many common agreements. We provide
                the tooling and an audit record (name, timestamp, IP address and a verification
                hash); we do not warrant that a given document is legally enforceable for your
                specific purpose. Seek legal advice where that matters.
            </p>

            <h2>4. Acceptable use</h2>
            <p>
                You may not use the Service for unlawful, fraudulent, or abusive purposes, to
                send unsolicited messages, or to impersonate others. We may suspend accounts that
                do so.
            </p>

            <h2>5. Plans and payment</h2>
            <p>
                Paid plans, where offered, are billed in advance and described on our pricing
                page. Fees are non-refundable except where required by law.
            </p>

            <h2>6. Availability and liability</h2>
            <p>
                The Service is provided "as is". To the maximum extent permitted by law, we are
                not liable for indirect or consequential loss, and our total liability is limited
                to the fees you paid in the previous twelve months.
            </p>

            <h2>7. Changes and termination</h2>
            <p>
                We may update these Terms and will post the revised version here. You may stop
                using the Service at any time. We may suspend or terminate access for breach of
                these Terms.
            </p>

            <h2>8. Contact</h2>
            <p>
                Questions about these Terms: <a href="mailto:hello@terms.ie">hello@terms.ie</a>.
            </p>
        </LegalPage>
    );
}

Terms.layout = (page: ReactNode) => <LandingLayout>{page}</LandingLayout>;
