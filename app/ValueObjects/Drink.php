<?php

namespace App\ValueObjects;

enum Drink: int
{
    case NEVER = 1;
    case RARELY = 2;
    case SOCIALLY = 3;
    case REGULARLY = 4;
    case FREQUENTLY = 5;
    case TRYING_TO_STOP = 6;
    case STOPPED = 7;

    public function label(): string
    {
        return match ($this) {
            static::NEVER => 'Nunca',
            static::RARELY => 'Raramente',
            static::SOCIALLY => 'Socialmente',
            static::REGULARLY => 'Regularmente',
            static::FREQUENTLY => 'Frequentemente',
            static::TRYING_TO_STOP => 'Tentando parar',
            static::STOPPED => 'Parei'
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
