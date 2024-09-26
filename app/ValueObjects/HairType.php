<?php

namespace App\ValueObjects;

enum HairType: int
{
    case CURLY = 1;
    case BALD = 2;
    case COARSE = 3;
    case STRAIGHT = 4;
    case WAVY = 5;

    public function label(): string
    {
        return match ($this) {
            static::CURLY => 'Cacheado',
            static::BALD => 'Calvo',
            static::COARSE => 'Crespo',
            static::STRAIGHT => 'Liso',
            static::WAVY => 'Ondulado'
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
