<?php

namespace App\Filters;

use App\Filters\Atomic\ProfileAvailableBookmarksFilter;
use Illuminate\Database\Eloquent\Builder;

class ProfileAvailableFavoritesFilter extends ProfileAvailableDefaultFilter
{
    public function __construct()
    {
        $this->filters = array_merge($this->filters, [
            'favorites' => ProfileAvailableBookmarksFilter::class
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
            'favorites' => null
        ]);
    }
}
