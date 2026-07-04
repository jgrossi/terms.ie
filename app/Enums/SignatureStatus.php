<?php

namespace App\Enums;

enum SignatureStatus: string
{
    case Pending = 'pending';
    case Signed  = 'signed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Signed  => 'Signed',
        };
    }

    public function badge(): string
    {
        return match ($this) {
            self::Pending => 'badge-warning',
            self::Signed  => 'badge-success',
        };
    }

    public function dotClass(): string
    {
        return match ($this) {
            self::Pending => 'bg-base-content/40',
            self::Signed  => 'bg-success',
        };
    }
}
