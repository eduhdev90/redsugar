<?php

namespace App\ValueObjects;

enum Children: int
{
    case ONE = 1;
    case TWO = 2;
    case THREE = 3;
    case FOUR = 4;
    case FIVE = 5;
    case I_DONT_HAVE = 6;
    case I_PREFER_NOT_TO_INFORM = 7;

    public function label(): string
    {
        return match ($this) {
            static::ONE => '1',
            static::TWO => '2',
            static::THREE => '3',
            static::FOUR => '4',
            static::FIVE => '5 ou mais',
            static::I_DONT_HAVE => 'Não',
            static::I_PREFER_NOT_TO_INFORM => 'Prefiro não informar'
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
