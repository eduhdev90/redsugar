<?php

namespace App\ValueObjects;

enum HairColor: int
{
    case BLACK = 1;
    case BROWN = 2;
    case LIGHT_BROWN = 3;
    case BLONDE = 4;
    case RED = 5;
    case GRAY = 6;
    case OTHER = 7;

    public function label(): string
    {
        return match ($this) {
            static::BLACK => 'Preto',
            static::BROWN => 'Castanho',
            static::LIGHT_BROWN => 'Castanho claro',
            static::BLONDE => 'Loiro',
            static::RED => 'Ruivo',
            static::GRAY => 'Grisalho',
            static::OTHER => 'Outro'
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
