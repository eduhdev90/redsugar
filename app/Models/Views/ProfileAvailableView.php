<?php

namespace App\Models\Views;

use App\Models\Favorite;
use App\Models\ProfileView;
use App\Models\UserPhoto;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ProfileAvailableView extends Model
{
    protected $table = 'profile_available_view';

    public function age()
    {
        return Carbon::parse($this->attributes['birthday'])->age;
    }

    public function photos(): HasMany
    {
        return $this->hasMany(UserPhoto::class, 'user_profile_id', 'id');
    }

    public function views()
    {
        return $this->morphMany(ProfileView::class, 'viewable');
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class, 'user_profile_id', 'id');
    }

    public function favoritedme(): HasMany
    {
        return $this->hasMany(Favorite::class, 'favorited_id', 'id');
    }

    public function visitedme(): MorphMany
    {
        return $this->morphMany(ProfileView::class, 'viewable', id: 'visitor_id');
    }

    public function scopeFilter(Builder $query, $filters)
    {
        return $filters->apply($query);
    }
}
