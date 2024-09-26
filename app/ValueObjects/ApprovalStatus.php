<?php

namespace App\ValueObjects;

enum ApprovalStatus: int
{
    case PENDING = 0;
    case APPROVED = 1;
    case REPROVED = 2;

    public function label(): string
    {
        return match ($this) {
            static::PENDING => 'Pendente',
            static::APPROVED => 'Aprovado',
            static::REPROVED => 'Reprovado'
        };
    }
}
