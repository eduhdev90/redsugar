<?php

namespace App\Filters\Atomic\Search;

use Illuminate\Database\Eloquent\Builder;

class SearchChildrenFilter
{
    public function __invoke(Builder $query, int|string $value)
    {
        return $query->whereIn('children', explode(',', $value));
    }
}