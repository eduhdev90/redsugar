<?php

namespace App\Filters\Atomic;

use Illuminate\Database\Eloquent\Builder;

class ProfileAvailableHighLightFilter
{
    public function __invoke(Builder $query)
    {
        return $query->orderBy('highlight', 'DESC');
    }
}
