<?php

namespace App\Models;

use App\Models\Views\ProfileAvailableView;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class UsersBlock extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_profile_id',
        'user_profile_id_blocked',
    ];

    // Defina o nome correto da tabela
    protected $table = 'users_block';

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserProfile::class, 'user_profile_id', 'id');
    }

    public function blocked(): BelongsTo
    {
        return $this->belongsTo(UserProfile::class, 'user_profile_id_blocked', 'id');
    }
}
