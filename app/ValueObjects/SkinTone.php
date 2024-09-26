<?php

namespace App\ValueObjects;

enum SkinTone: int
{
    case WHITE_CAUCASIAN = 1;
    case MIDDLE_EAST = 2;
    case OTHERS = 3;
    case BROWN_MULATTO = 4;
    case BLACK_AFRODESCENDANT = 5;
    case LATINO_HISPANIC = 6;
    case ASIAN_JAPANESE = 7;
    case ASIAN_CHINESE = 8;
    case ASIAN_KOREAN = 9;
    case ASIAN_OTHERS = 10;
    case INDIAN = 11;

    public function label(): string
    {
        return match ($this) {
            static::WHITE_CAUCASIAN => 'Branco / Caucasiano',
            static::MIDDLE_EAST => 'Oriente Médio',
            static::OTHERS => 'Outros',
            static::BROWN_MULATTO => 'Pardo / Mulato',
            static::BLACK_AFRODESCENDANT => 'Negro / Afrodescendente',
            static::LATINO_HISPANIC => 'Latino / Hispânico',
            static::ASIAN_JAPANESE => 'Asiático japonês',
            static::ASIAN_CHINESE => 'Asiático chinês',
            static::ASIAN_KOREAN => 'Asiático coreano',
            static::ASIAN_OTHERS => 'Asiático outros',
            static::INDIAN => 'Indiano'
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
