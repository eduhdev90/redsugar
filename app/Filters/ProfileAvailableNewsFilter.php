<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class ProfileAvailableNewsFilter extends ProfileAvailableDefaultFilter
{
    public function apply(Builder $query): Builder
    {
        $query = parent::apply($query);

        $query = $query->where('online',1)->orderBy('created_at', 'DESC');

        return $query;
    }
}
