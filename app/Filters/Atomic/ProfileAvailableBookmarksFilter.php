<?php

namespace App\Filters\Atomic;

use Illuminate\Database\Eloquent\Builder;

class ProfileAvailableBookmarksFilter
{
    public function __invoke(Builder $query)
    {
        return $query->whereHas('favoritedme', function ($query) {
            return $query->where('user_profile_id', auth()->user()->profile_available->id);
        });
    }
}
