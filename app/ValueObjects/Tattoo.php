<?php

namespace App\ValueObjects;

enum Tattoo: int
{
    case I_DONT_HAVE = 1;
    case HAVE_ONE = 2;
    case HAVE_TWO = 3;
    case HAVE_THREE = 4;
    case HAVE_FOUR_OR_MORE = 5;

    public function label(): string
    {
        return match ($this) {
            static::I_DONT_HAVE => 'Não possuo',
            static::HAVE_ONE => 'Possuo uma',
            static::HAVE_TWO => 'Possuo duas',
            static::HAVE_THREE => 'Possuo três',
            static::HAVE_FOUR_OR_MORE => 'Possuo quatro ou mais'
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
