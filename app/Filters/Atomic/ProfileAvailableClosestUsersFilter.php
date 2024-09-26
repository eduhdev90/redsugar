<?php

namespace App\Filters\Atomic;

use Illuminate\Database\Eloquent\Builder;

class ProfileAvailableClosestUsersFilter
{
    public function __invoke(Builder $query, float $latitude, float $longitude)
    {
        return $query->where('online',1)->orderByRaw("(POW((longitude -($longitude)),2) + POW((latitude -($latitude)),2))");
    }
}
