<?php

namespace App\Mail;

use App\Models\Signature;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SignedDocumentMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Signature $signature) {}

    public function envelope(): Envelope
    {
        $term = $this->signature->termVersion->term->name;

        return new Envelope(subject: "Signed: {$term}");
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.signed-document', with: [
            'clientName' => $this->signature->client->name,
            'termName'   => $this->signature->termVersion->term->name,
        ]);
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromStorageDisk(
                config('filesystems.signature_disk'),
                $this->signature->pdf_path
            )->as('signed-document.pdf')->withMime('application/pdf'),
        ];
    }
}
