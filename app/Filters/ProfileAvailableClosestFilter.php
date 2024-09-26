<?php

namespace App\Filters;

use App\Filters\Atomic\ProfileAvailableClosestUsersFilter;
use Illuminate\Database\Eloquent\Builder;

class ProfileAvailableClosestFilter extends ProfileAvailableDefaultFilter
{
    public function __construct()
    {
        $this->filters = array_merge($this->filters, [
            'closest_users' => ProfileAvailableClosestUsersFilter::class
        ]);
    }

    public function apply(Builder $query): Builder
    {
        $query = parent::apply($query);
        return $query;
    }

    public function receivedFilters(): array
    {
        $this->data = parent::receivedFilters();
        return array_merge($this->data, [
            'closest_users' => [(float) $this->profile->latitude, (float) $this->profile->longitude]
        ]);
    }
}
