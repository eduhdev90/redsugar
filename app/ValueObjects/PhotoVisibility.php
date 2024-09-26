<?php

namespace App\ValueObjects;

enum PhotoVisibility: int
{
    case PUBLIC = 1;
    case PRIVATE = 2;

    public function label(): string
    {
        return match ($this) {
            static::PUBLIC => 'Pública',
            static::PRIVATE => 'Privada',
        };
    }
}
