<?php

namespace App\Filters\Atomic;

use Illuminate\Database\Eloquent\Builder;

class ProfileAvailableBookmarksMeFilter
{
    public function __invoke(Builder $query)
    {
        return $query->whereHas('favorites', function ($query) {
            return $query->where('favorited_id', auth()->user()->profile_available->id);
        });
    }
}
