<?php

namespace App\Filters\Atomic;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class ProfileAvailableVisitsMeFilter
{
    public function __invoke(Builder $query)
    {
        return $query->whereHas('visitedme', function ($query) {
            return $query->where('viewable_id', auth()->user()->profile_available->id)
                ->whereDate('created_at', '>=', Carbon::yesterday())
                ->orderBy('created_at', 'DESC');
        })->where('online',1);
    }
}
