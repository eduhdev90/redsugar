<?php

namespace App\ValueObjects;

enum MonthlyIncome: int
{
    case UPTO_TEN_THOUSAND = 1;
    case UPTO_THIRTY_THOUSAND = 2;
    case UPTO_FIFTY_THOUSAND = 3;
    case UPTO_SEVENTY_THOUSAND = 4;
    case UPTO_ONE_HUNDRED_THOUSAND = 5;
    case UPTO_ONE_HUNDRED_FIFTY_THOUSAND = 6;
    case UPTO_FIVE_HUNDRED_THOUSAND = 7;
    case IAM_VERY_RICH = 8;

    public function label(): string
    {
        return match ($this) {
            static::UPTO_TEN_THOUSAND => 'Até R$ 10 mil',
            static::UPTO_THIRTY_THOUSAND => 'R$ 10 mil a R$ 30 mil',
            static::UPTO_FIFTY_THOUSAND => 'R$ 30 mil a R$ 50 mil',
            static::UPTO_SEVENTY_THOUSAND => 'R$ 50 mil a R$ 70 mil',
            static::UPTO_ONE_HUNDRED_THOUSAND => 'R$ 70 mil a R$ 100 mil',
            static::UPTO_ONE_HUNDRED_FIFTY_THOUSAND => 'R$ 100 mil a R$ 150 mil',
            static::UPTO_FIVE_HUNDRED_THOUSAND => 'R$ 150 mil a R$ 500 mil',
            static::IAM_VERY_RICH => 'Ok, eu sou muito rico'
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
