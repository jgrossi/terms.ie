<?php

namespace App\Models;

use App\Enums\SignatureStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Signature extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    /** Pending signing links are valid for this many days after assignment. */
    const EXPIRY_DAYS = 7;

    protected function casts(): array
    {
        return [
            'status'     => SignatureStatus::class,
            'variables'  => 'array',
            'expires_at' => 'datetime',
            'signed_at'  => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Signature $signature) {
            $signature->status ??= SignatureStatus::Pending;
            $signature->expires_at ??= now()->addDays(self::EXPIRY_DAYS);
        });
    }

    public function isPending(): bool
    {
        return $this->status === SignatureStatus::Pending;
    }

    public function isExpired(): bool
    {
        return $this->isPending() && $this->expires_at?->isPast();
    }

    public function isSigned(): bool
    {
        return $this->status === SignatureStatus::Signed;
    }

    public function termVersion(): BelongsTo
    {
        return $this->belongsTo(TermVersion::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function render(): string
    {
        $replacements = array_merge($this->variables ?? [], [
            'CLIENT_NAME'  => $this->client->name,
            'CLIENT_EMAIL' => $this->client->email,
        ]);

        $body = $this->termVersion->body;
        foreach ($replacements as $key => $value) {
            $body = str_replace('{{'.$key.'}}', (string) $value, $body);
        }

        return $body;
    }

    public function fingerprint(string $signedName, \DateTimeInterface $signedAt): string
    {
        return hash('sha256', implode("\n", [
            $this->id,
            $this->termVersion->term->name,
            'v'.$this->termVersion->version,
            $this->render(),
            $signedName,
            $signedAt->format(\DateTimeInterface::ATOM),
        ]));
    }
}
