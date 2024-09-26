<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SubscriptionActiveView extends Model
{
    protected $table = 'subscriptions_active_view';

    public function scopeFilter(Builder $query, $filters)
    {
        return $filters->apply($query);
    }
}
