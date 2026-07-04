<?php

namespace App\Mail;

use App\Models\Signature;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SignatureLinkMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Signature $signature) {}

    public function envelope(): Envelope
    {
        $term = $this->signature->termVersion->term->name;

        return new Envelope(subject: "Please sign: {$term}");
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.signature-link', with: [
            'clientName' => $this->signature->client->name,
            'termName'   => $this->signature->termVersion->term->name,
            'url'        => route('sign.show', $this->signature),
            'expiresAt'  => $this->signature->expires_at?->format('d M Y'),
        ]);
    }
}
