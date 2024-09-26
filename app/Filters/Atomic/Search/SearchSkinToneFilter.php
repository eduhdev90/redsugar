<?php

namespace App\Filters\Atomic\Search;

use Illuminate\Database\Eloquent\Builder;

class SearchSkinToneFilter
{
    public function __invoke(Builder $query, int|string $value)
    {
        return $query->whereIn('skin_tone', explode(',', $value));
    }
}
