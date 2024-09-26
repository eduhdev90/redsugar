<?php

namespace App\ValueObjects;

enum MaritalStatus: int
{
    case SINGLE = 1;
    case SEPARATED = 2;
    case DIVORCED = 3;
    case WIDOWED = 4;
    case MARRIED = 5;

    public function label(): string
    {
        return match ($this) {
            static::SINGLE => 'Solteiro (a)',
            static::SEPARATED => 'Separado (a)',
            static::DIVORCED => 'Divorciado (a)',
            static::WIDOWED => 'Viúvo (a)',
            static::MARRIED => 'Casado (a), mas procurando'
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
