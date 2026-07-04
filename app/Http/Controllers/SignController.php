<?php

namespace App\Http\Controllers;

use App\Enums\SignatureStatus;
use App\Mail\SignedDocumentMail;
use App\Models\Signature;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SignController extends Controller
{
    public function show(Signature $signature): Response
    {
        return Inertia::render('sign/Show', [
            'signature' => [
                'id'           => $signature->id,
                'term_name'    => $signature->termVersion->term->name,
                'client_name'  => $signature->client->name,
                'is_signed'    => $signature->isSigned(),
                'is_expired'   => $signature->isExpired(),
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
        abort_if($signature->isExpired(), 403, 'This signing link has expired.');

        $clientName = $signature->client->name;

        $request->validate([
            'signed_name' => [
                'required', 'string', 'max:255',
                function (string $attribute, mixed $value, \Closure $fail) use ($clientName) {
                    if (mb_strtolower(trim((string) $value)) !== mb_strtolower(trim($clientName))) {
                        $fail("Please type your name exactly as shown: {$clientName}");
                    }
                },
            ],
        ]);

        $signedAt = now();

        // Store the canonical client name rather than whatever casing was typed.
        $signature->update([
            'status'       => SignatureStatus::Signed,
            'signed_name'  => $clientName,
            'signed_at'    => $signedAt,
            'signed_ip'    => $request->ip(),
            'content_hash' => $signature->fingerprint($clientName, $signedAt),
        ]);

        $signature->update(['pdf_path' => $this->generatePdf($signature)]);

        // Email the client a copy of the signed PDF for their records.
        Mail::to($signature->client->email)->queue(new SignedDocumentMail($signature));

        return redirect()->route('sign.show', $signature)->with('toast', 'Document signed successfully.');
    }

    public function pdf(Signature $signature): StreamedResponse|RedirectResponse
    {
        abort_unless($signature->isSigned(), 403, 'This document has not been signed yet.');

        $disk = Storage::disk(config('filesystems.signature_disk'));

        if (! $signature->pdf_path || ! $disk->exists($signature->pdf_path)) {
            $signature->update(['pdf_path' => $this->generatePdf($signature)]);
        }

        $filename = 'terms-'.$signature->id.'.pdf';

        // Private R2 (S3) buckets: redirect to a short-lived signed URL. Local: stream.
        if (config('filesystems.disks.'.config('filesystems.signature_disk').'.driver') === 's3') {
            return redirect()->away($disk->temporaryUrl(
                $signature->pdf_path,
                now()->addMinutes(5),
                ['ResponseContentDisposition' => 'attachment; filename="'.$filename.'"']
            ));
        }

        return $disk->download($signature->pdf_path, $filename);
    }

    /**
     * Render the signed document to a PDF, store it on the signature disk,
     * and return the relative path.
     */
    private function generatePdf(Signature $signature): string
    {
        $signature->loadMissing('termVersion.term', 'client');

        $pdf = Pdf::loadView('pdf.signature', [
            'termName'    => $signature->termVersion->term->name,
            'body'        => $signature->render(),
            'signedName'  => $signature->signed_name,
            'signedAtUtc' => $signature->signed_at?->utc()->format('d M Y \a\t H:i').' UTC',
            'signedIp'    => $signature->signed_ip,
            'reference'   => $signature->id,
            'contentHash' => $signature->content_hash,
        ]);

        $path = "signatures/{$signature->id}.pdf";
        Storage::disk(config('filesystems.signature_disk'))->put($path, $pdf->output());

        return $path;
    }
}
