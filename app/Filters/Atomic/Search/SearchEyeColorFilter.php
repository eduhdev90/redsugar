<?php

namespace App\Filters\Atomic\Search;

use Illuminate\Database\Eloquent\Builder;

class SearchEyeColorFilter
{
    public function __invoke(Builder $query, int|string $value)
    {
        return $query->whereIn('eye_color', explode(',', $value));
    }
}
