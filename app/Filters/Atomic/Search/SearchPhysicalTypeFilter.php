<?php

namespace App\Filters\Atomic\Search;

use Illuminate\Database\Eloquent\Builder;

class SearchPhysicalTypeFilter
{
    public function __invoke(Builder $query, int|string $value)
    {
        return $query->whereIn('physical_type', explode(',', $value));
    }
}
