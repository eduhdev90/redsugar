<?php

namespace App\ValueObjects;

enum EyeColor: int
{
    case LIGHT_BROWN = 1;
    case BROWN = 2;
    case BLACK = 3;
    case BLUE = 4;
    case GREEN = 5;
    case GRAY = 6;
    case OTHER = 7;

    public function label(): string
    {
        return match ($this) {
            static::LIGHT_BROWN => 'Castanho claro',
            static::BROWN => 'Castanho',
            static::BLACK => 'Preto',
            static::BLUE => 'Azul',
            static::GREEN => 'Verde',
            static::GRAY => 'Cinza',
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
