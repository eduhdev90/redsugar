<?php

namespace App\Filters;

use App\Filters\Atomic\ProfileAvailableBookmarksMeFilter;
use Illuminate\Database\Eloquent\Builder;

class ProfileAvailableFavoritedMeFilter extends ProfileAvailableDefaultFilter
{
    public function __construct()
    {
        $this->filters = array_merge($this->filters, [
            'favoritedme' => ProfileAvailableBookmarksMeFilter::class
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
            'favoritedme' => null
        ]);
    }
}
