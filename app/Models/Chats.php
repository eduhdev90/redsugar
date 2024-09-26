<?php

namespace App\Models;

use App\Models\Views\ProfileAvailableView;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Chats extends Model{

    use HasFactory;

    protected $fillable = [
        'user_profile1_id',
        'user_profile2_id',
        'messages',
        'active',
    ];

    protected $casts = [
        'messages' => 'array',
    ];

    public function user1(): BelongsTo{
        return $this->belongsTo(UserProfile::class, 'user_profile1_id', 'id');
    }
    public function user2(): BelongsTo{
        return $this->belongsTo(UserProfile::class, 'user_profile2_id', 'id');
    }
}
