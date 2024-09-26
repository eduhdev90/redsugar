<?php

namespace App\Filters\Atomic\Search;

use Illuminate\Database\Eloquent\Builder;

class SearchDrinkFilter
{
    public function __invoke(Builder $query, int|string $value)
    {
        return $query->whereIn('drink', explode(',', $value));
    }
}
