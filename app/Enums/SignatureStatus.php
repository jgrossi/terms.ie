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
}
