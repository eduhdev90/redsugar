<?php

namespace App\Filters\Atomic;

use Illuminate\Database\Eloquent\Builder;

class ProfileAvailableGenderFilter
{
    public function __invoke(Builder $query, int $gender)
    {
        return $query->where('gender', $gender);
    }
}
