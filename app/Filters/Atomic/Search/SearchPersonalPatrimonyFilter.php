<?php

namespace App\Filters\Atomic\Search;

use Illuminate\Database\Eloquent\Builder;

class SearchPersonalPatrimonyFilter
{
    public function __invoke(Builder $query, int|string $value)
    {
        return $query->whereIn('personal_patrimony', explode(',', $value));
    }
}
