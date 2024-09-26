<?php

namespace App\Filters\Atomic;

use Illuminate\Database\Eloquent\Builder;

class ProfileAvailableProfileFilter
{
    public function __invoke(Builder $query, int $profile)
    {
        return $query->where('online',1)->where('profile', $profile);
    }
}
