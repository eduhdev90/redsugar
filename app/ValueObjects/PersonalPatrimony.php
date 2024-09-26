<?php

namespace App\ValueObjects;

enum PersonalPatrimony: int
{
    case UPTO_ONE_HUNDRED_THOUSAND = 1;
    case UPTO_TWO_HUNDRED_FIFTY_THOUSAND = 2;
    case UPTO_FIVE_HUNDRED_THOUSAND = 3;
    case UPTO_SEVEN_HUNDRED_FIFTY_THOUSAND = 4;
    case UPTO_ONE_MILLION = 5;
    case UPTO_TWO_MILLION = 6;
    case UPTO_FIVE_MILLION = 7;
    case UPTO_TEN_MILLION = 8;
    case UPTO_FIFTY_MILLION = 9;
    case UPTO_ONE_HUNDRED_MILLION = 10;
    case OVER_ONE_HUNDRED_MILLION = 11;

    public function label(): string
    {
        return match ($this) {
            static::UPTO_ONE_HUNDRED_THOUSAND => 'Menos de R$ 100 mil',
            static::UPTO_TWO_HUNDRED_FIFTY_THOUSAND => 'R$ 100 mil a R$ 250 mil',
            static::UPTO_FIVE_HUNDRED_THOUSAND => 'R$ 250 mil a R$ 500 mil',
            static::UPTO_SEVEN_HUNDRED_FIFTY_THOUSAND => 'R$ 500 mil a R$ 750 mil',
            static::UPTO_ONE_MILLION => 'R$ 750 mil a R$ 1 milhão',
            static::UPTO_TWO_MILLION => 'R$ 1 milhão a R$ 2 milhões',
            static::UPTO_FIVE_MILLION => 'R$ 2 milhões a R$ 5 milhões',
            static::UPTO_TEN_MILLION => 'R$ 5 milhões a R$ 10 milhões',
            static::UPTO_FIFTY_MILLION => 'R$ 10 milhões a R$ 50 milhões',
            static::UPTO_ONE_HUNDRED_MILLION => 'R$ 50 milhões a R$ 100 milhões',
            static::OVER_ONE_HUNDRED_MILLION => 'Mais de R$ 100 milhões'
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
