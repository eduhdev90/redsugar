<?php

namespace App\Filters\Atomic\Search;

use Illuminate\Database\Eloquent\Builder;

class SearchTattooFilter
{
    public function __invoke(Builder $query, int|string $value)
    {
        return $query->whereIn('tattoo', explode(',', $value));
    }
}
