<?php

namespace App\Http\Controllers;

use App\Models\Signature;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class VerifyController extends Controller
{
    public function show(Signature $signature): Response
    {
        return Inertia::render('verify/Show', [
            'reference'    => $signature->id,
            'termName'     => $signature->termVersion->term->name,
            'verification' => null,
        ]);
    }

    public function verify(Request $request, Signature $signature): Response
    {
        $request->validate([
            'document' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $hash = hash('sha256', file_get_contents($request->file('document')->getRealPath()));

        // Look up by the file's hash, then confirm it belongs to this reference.
        $match = Signature::where('pdf_hash', $hash)->first();
        $verified = $match !== null && $match->id === $signature->id;

        $verification = ['status' => 'failed'];

        if ($verified) {
            $match->loadMissing('termVersion.term', 'client');
            $verification = [
                'status'      => 'verified',
                'term_name'   => $match->termVersion->term->name,
                'client_name' => $match->client->name,
                'id'          => $match->id,
                'signed_name' => $match->signed_name,
                'signed_at'   => $match->signed_at?->toIso8601String(),
                'signed_ip'   => $match->signed_ip,
                'pdf_hash'    => $match->pdf_hash,
            ];
        }

        return Inertia::render('verify/Show', [
            'reference'    => $signature->id,
            'termName'     => $signature->termVersion->term->name,
            'verification' => $verification,
        ]);
    }
}
