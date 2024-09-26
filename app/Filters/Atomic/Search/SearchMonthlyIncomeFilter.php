<?php

namespace App\Filters\Atomic\Search;

use Illuminate\Database\Eloquent\Builder;

class SearchMonthlyIncomeFilter
{
    public function __invoke(Builder $query, int|string $value)
    {
        return $query->whereIn('monthly_income', explode(',', $value));
    }
}
