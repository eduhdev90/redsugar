<?php

namespace App\Filters\Atomic\Search;

use Illuminate\Database\Eloquent\Builder;

class SearchAcademicBackgroundFilter
{
    public function __invoke(Builder $query, int|string $value)
    {
        return $query->whereIn('academic_background', explode(',', $value));
    }
}
