<?php

namespace App\Filters\Atomic\Search;

use Illuminate\Database\Eloquent\Builder;

class SearchHairTypeFilter
{
    public function __invoke(Builder $query, int|string $value)
    {
        return $query->whereIn('hair_type', explode(',', $value));
    }
}
