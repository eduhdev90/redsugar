<?php

namespace App\Models;

use App\Models\Views\ProfileAvailableView;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ads extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'ads',
        'country',
        'state',
        'city',
        'latitude',
        'longitude',
        'active',
        'valid_until',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
