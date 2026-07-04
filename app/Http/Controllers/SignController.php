<?php

namespace App\Http\Controllers;

use App\Enums\SignatureStatus;
use App\Models\Signature;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SignController extends Controller
{
    public function show(Signature $signature): Response
    {
        return Inertia::render('sign/Show', [
            'signature' => [
                'id'           => $signature->id,
                'term_name'    => $signature->termVersion->term->name,
                'is_signed'    => $signature->isSigned(),
                'body'         => $signature->render(),
                'signed_name'  => $signature->signed_name,
                'signed_at'    => $signature->signed_at?->toIso8601String(),
                'signed_ip'    => $signature->signed_ip,
                'content_hash' => $signature->content_hash,
            ],
        ]);
    }

    public function sign(Request $request, Signature $signature): RedirectResponse
    {
        abort_unless($signature->isPending(), 403, 'This document has already been signed.');

        $data = $request->validate([
            'signed_name' => ['required', 'string', 'max:255'],
        ]);

        $signedAt = now();

        $signature->update([
            'status'       => SignatureStatus::Signed,
            'signed_name'  => $data['signed_name'],
            'signed_at'    => $signedAt,
            'signed_ip'    => $request->ip(),
            'content_hash' => $signature->fingerprint($data['signed_name'], $signedAt),
        ]);

        return redirect()->route('sign.show', $signature)->with('toast', 'Document signed successfully.');
    }
}
