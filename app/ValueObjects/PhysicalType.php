<?php

namespace App\ValueObjects;

enum PhysicalType: int
{
    case THIN = 1;
    case MEDIUM = 2;
    case MUSCULAR = 3;
    case FIT = 4;
    case A_LITTLE_OVERWEIGHT = 5;
    case BIG_AND_LOVABLE = 6;

    public function label(): string
    {
        return match ($this) {
            static::THIN => 'Magro',
            static::MEDIUM => 'Médio',
            static::MUSCULAR => 'Musculoso',
            static::FIT => 'Em forma',
            static::A_LITTLE_OVERWEIGHT => 'Um pouco acima do peso',
            static::BIG_AND_LOVABLE => 'Grande e amoroso'
        };
    }

    public static function forSelect(): array
    {
        $ids = array_column(self::cases(), 'value');
        $values = [];
        foreach (self::cases() as $case) {
            $values[] = $case->label();
        }
        return array_combine($ids, $values);
    }
}
