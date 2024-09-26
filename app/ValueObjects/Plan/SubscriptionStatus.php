<?php

namespace App\ValueObjects\Plan;

enum SubscriptionStatus: int
{
    case PENDING = 0;
    case ACTIVE = 1;
    case CANCELED = 2;
    case OVERDUE = 3;

    public function label(): string
    {
        return match ($this) {
            static::PENDING => 'Pendente',
            static::ACTIVE => 'Ativo',
            static::CANCELED => 'Cancelado',
            static::OVERDUE => 'Atrasado'
        };
    }
}
