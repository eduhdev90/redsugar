<?php

namespace App\ValueObjects;

enum BeardSize: int
{
    case I_DONT_HAVE = 1;
    case SHORT = 2;
    case MEDIUM = 3;
    case LONG = 4;
    case VERY_LONG = 5;

    public function label(): string
    {
        return match ($this) {
            static::I_DONT_HAVE => 'Não possuo',
            static::SHORT => 'Curto',
            static::MEDIUM => 'Médio',
            static::LONG => 'Longo',
            static::VERY_LONG => 'Muito longo'
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
