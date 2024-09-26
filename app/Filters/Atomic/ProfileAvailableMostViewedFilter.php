<?php

namespace App\Filters\Atomic;

use Illuminate\Database\Eloquent\Builder;

class ProfileAvailableMostViewedFilter
{
    public function __invoke(Builder $query)
    {
        return $query->orderBy('total_views', 'DESC');
    }
}
