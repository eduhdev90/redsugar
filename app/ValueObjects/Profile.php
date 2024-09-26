<?php

namespace App\ValueObjects;

enum Profile: int
{
    case SUGAR_DADDY_MOMMY = 1;
    case SUGAR_BABY = 2;

    public function label(): string
    {
        return match ($this) {
            static::SUGAR_DADDY_MOMMY => 'Sugar Daddy / Mommy',
            static::SUGAR_BABY => 'Sugar Baby',
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
