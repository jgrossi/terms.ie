<?php

namespace App\Models;

use App\Models\Concerns\HasPrefixedUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Term extends Model
{
    use HasFactory, HasPrefixedUlid;

    protected static function ulidPrefix(): string { return 'trm_'; }
    protected $guarded = [];

    const RESERVED = ['CLIENT_NAME', 'CLIENT_EMAIL'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function versions(): HasMany
    {
        return $this->hasMany(TermVersion::class);
    }

    public function latestVersion(): HasOne
    {
        return $this->hasOne(TermVersion::class)->latestOfMany('version');
    }

    public function extractVariables(): array
    {
        preg_match_all('/\{\{([A-Z0-9_]+)\}\}/', $this->body, $matches);

        return array_values(array_unique($matches[1]));
    }

    public function extractUserVariables(): array
    {
        return array_values(array_diff($this->extractVariables(), self::RESERVED));
    }

    public function hasSignatures(): bool
    {
        return $this->versions()->whereHas('signatures')->exists();
    }

}
