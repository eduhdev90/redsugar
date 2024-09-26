<?php

namespace App\ValueObjects;

enum Interested: int
{
    case MAN = 1;
    case WOMAN = 2;
    case BOTH = 3;

    public function label(): string
    {
        return match ($this) {
            static::MAN => 'Homem',
            static::WOMAN => 'Mulher',
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
