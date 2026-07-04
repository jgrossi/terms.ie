<?php

namespace App\Models;

use App\Models\Concerns\HasPrefixedUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TermVersion extends Model
{
    use HasFactory, HasPrefixedUlid;

    protected static function ulidPrefix(): string { return 'tmv_'; }
    protected $guarded = [];

    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }

    public function signatures(): HasMany
    {
        return $this->hasMany(Signature::class);
    }
}
