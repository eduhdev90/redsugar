<?php

namespace App\Filters\Atomic\Search;

use Illuminate\Database\Eloquent\Builder;

class SearchHeightFilter
{
    public function __invoke(Builder $query, int|string $value)
    {
        $height = explode(',', $value);

        if (!isset($height[0]) || !isset($height[1])) return $query;

        return $query->where('height', '>=', $height[0])
            ->where('height', '<=', $height[1]);
    }
}
