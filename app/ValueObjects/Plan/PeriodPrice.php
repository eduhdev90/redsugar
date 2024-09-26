<?php

namespace App\ValueObjects\Plan;

enum PeriodPrice: int
{
    case MONTH = 1;
    case QUARTER = 2;
    case YEAR = 3;

    public function label(): string
    {
        return match ($this) {
            static::MONTH => 'Mensal',
            static::QUARTER => 'Trimestral',
            static::YEAR => 'Anual'
        };
    }
}
