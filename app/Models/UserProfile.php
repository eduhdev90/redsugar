<?php

namespace App\Models;

use App\Models\Scopes\NotRepprovedStatusScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'external_id',
        'gender',
        'interested',
        'profile',
        'birthday',
        'country',
        'state',
        'city',
        'latitude',
        'longitude',
        'style_life',
        'height',
        'physical_type',
        'skin_tone',
        'eye_color',
        'drink',
        'smoke',
        'hair_color',
        'hair_type',
        'marital_status',
        'beard_size',
        'beard_color',
        'children',
        'tattoo',
        'academic_background',
        'occupation',
        'monthly_income',
        'personal_patrimony',
        'hobbies',
        'first_impression',
        'about',
        'profile_photo_id',
        'profile_image',
        'online',
        'current_step',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new NotRepprovedStatusScope);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(UserPhoto::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function views()
    {
        return $this->morphMany(ProfileView::class, 'viewable');
    }
}
