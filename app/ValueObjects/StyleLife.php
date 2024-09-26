<?php

namespace App\ValueObjects;

enum StyleLife: int
{
    case CASUAL = 1;
    case LASTING = 2;
    case BOTH = 3;

    public function label(): string
    {
        return match ($this) {
            static::CASUAL => 'Casual',
            static::LASTING => 'Duradouro',
            static::BOTH => 'Ambos',
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
