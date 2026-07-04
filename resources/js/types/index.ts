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
