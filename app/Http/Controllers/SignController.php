<?php

namespace App\Http\Controllers;

use App\Enums\SignatureStatus;
use App\Models\Signature;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SignController extends Controller
{
    public function show(Signature $signature): View
    {
        return view('sign.show', compact('signature'));
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
