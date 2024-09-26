<?php

namespace App\Filters\Atomic\Search;

use Illuminate\Database\Eloquent\Builder;

class SearchBeardColorFilter
{
    public function __invoke(Builder $query, int|string $value)
    {
        return $query->whereIn('beard_color', explode(',', $value));
    }
}
