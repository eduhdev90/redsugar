<?php

namespace App\Filters\Atomic\Search;

use Illuminate\Database\Eloquent\Builder;

class SearchSmokeFilter
{
    public function __invoke(Builder $query, int|string $value)
    {
        return $query->whereIn('smoke', explode(',', $value));
    }
}
