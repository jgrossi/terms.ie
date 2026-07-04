<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait HasPrefixedUlid
{
    protected static function ulidPrefix(): string
    {
        return '';
    }

    public function usesUniqueIds(): bool
    {
        return true;
    }

    public function getKeyType(): string
    {
        return 'string';
    }

    public function getIncrementing(): bool
    {
        return false;
    }

    public function newUniqueId(): string
    {
        return static::ulidPrefix() . strtolower((string) Str::ulid());
    }

    public function uniqueIds(): array
    {
        return [$this->getKeyName()];
    }
}
