<?php

namespace App\Filters\Atomic\Search;

use Illuminate\Database\Eloquent\Builder;

class SearchStyleLifeFilter
{
    public function __invoke(Builder $query, int|string $value)
    {
        return $query->whereIn('style_life', explode(',', $value));
    }
}
