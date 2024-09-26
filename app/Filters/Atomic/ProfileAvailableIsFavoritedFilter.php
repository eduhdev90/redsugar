<?php

namespace App\Filters\Atomic;

use Illuminate\Database\Eloquent\Builder;

class ProfileAvailableIsFavoritedFilter
{
    public function __invoke(Builder $query)
    {
        return $query->withCount(['favoritedme' => function (Builder $query) {
            $query->where('user_profile_id', '=', auth()->user()->profile->id);
        }]);
    }
}
