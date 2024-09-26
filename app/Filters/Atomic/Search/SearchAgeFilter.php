<?php

namespace App\Filters\Atomic\Search;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class SearchAgeFilter
{
    public function __invoke(Builder $query, int|string $value)
    {
        // FILTROS CORRETOS
        $age = explode(',', $value);

        if (!isset($age[0]) || !isset($age[1])) {
            return $query->whereDate('birthday', '<=', Carbon::now()->subYear($age[0])->format('Y-m-d'))
                ->whereDate('birthday', '>=', Carbon::now()->subYear($age[1])->format('Y-m-d'));
        }
    }
}
