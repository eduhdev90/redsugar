<?php

namespace App\ValueObjects\Plan;

enum ProductType: int
{
    case FREE = 1;
    case PAID = 2;

    public function label(): string
    {
        return match ($this) {
            static::FREE => 'Gratuito',
            static::PAID => 'Pago'
        };
    }
}
