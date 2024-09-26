<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_profile_id',
        'favorited_id',
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(UserProfile::class);
    }

    public function favorited(): BelongsTo
    {
        return $this->belongsTo(UserProfile::class, ownerKey: 'favorited_id');
    }
}
