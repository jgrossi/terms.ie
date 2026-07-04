export interface User {
    id: string;
    email: string;
}

export interface Auth {
    user: User | null;
}

export interface Flash {
    toast: string | null;
}

/** Props shared with every page via HandleInertiaRequests::share(). */
export interface SharedProps {
    auth: Auth;
    flash: Flash;
    errors: Record<string, string>;
    [key: string]: unknown;
}

/* ------------------------------------------------------------------ */
/* Domain                                                             */
/* ------------------------------------------------------------------ */

export interface TermListItem {
    id: string;
    name: string;
    variables: string[];
    versions_count: number;
    updated_at: string;
}

export interface TermVersionItem {
    version: number;
    created_at: string;
}

export interface TermDetail {
    id: string;
    name: string;
    body: string;
    updated_at: string;
    version: number;
    has_signatures: boolean;
    user_variables: string[];
    reserved_variables: string[];
}

export interface PendingSignature {
    id: string;
    client_name: string;
    client_email: string;
    term_name: string;
    created_at: string;
}

export interface SignedSignature {
    id: string;
    client_name: string;
    term_name: string;
    signed_name: string;
    signed_at: string;
}

export type SignatureStatus = 'pending' | 'signed';

export interface ClientListItem {
    id: string;
    name: string;
    email: string;
    created_at: string;
}

export interface ClientSignatureRow {
    id: string;
    term_name: string;
    version: number;
    status: SignatureStatus;
    is_expired: boolean;
    created_at: string;
}

export interface TermOption {
    id: string;
    name: string;
    variables: string[];
}

export interface ClientOption {
    id: string;
    name: string;
    email: string;
}

export interface SignatureDetail {
    id: string;
    term_name: string;
    version: number;
    status: SignatureStatus;
    is_pending: boolean;
    is_signed: boolean;
    is_expired: boolean;
    expires_at: string | null;
    client: { id: string; name: string; email: string };
    variables: Record<string, string>;
    body: string;
    signed_name: string | null;
    signed_at: string | null;
    signed_ip: string | null;
    content_hash: string | null;
}

export interface SignDocument {
    id: string;
    term_name: string;
    client_name: string;
    is_signed: boolean;
    is_expired: boolean;
    body: string;
    signed_name: string | null;
    signed_at: string | null;
    signed_ip: string | null;
    content_hash: string | null;
}
