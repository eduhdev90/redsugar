<?php

namespace App\ValueObjects;

enum AcademicBackground: int
{
    case SECOND_DEGREE = 1;
    case VOCATIONAL_TECHNICAL = 2;
    case COLLEGE_IN_COURSE = 3;
    case COLLEGE_INCOMPLETE = 4;
    case COLLEGE_COMPLETED = 5;
    case POST_GRADUATED = 6;
    case PHD = 7;
    case SCHOOL_OF_LIFE = 8;

    public function label(): string
    {
        return match ($this) {
            static::SECOND_DEGREE => '2º grau',
            static::VOCATIONAL_TECHNICAL => 'Técnico profissionalizante',
            static::COLLEGE_IN_COURSE => 'Superior cursando',
            static::COLLEGE_INCOMPLETE => 'Superior incompleto',
            static::COLLEGE_COMPLETED => 'Superior completo',
            static::POST_GRADUATED => 'Pós-graduado',
            static::PHD => 'PHD',
            static::SCHOOL_OF_LIFE => 'Escola da vida'
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
